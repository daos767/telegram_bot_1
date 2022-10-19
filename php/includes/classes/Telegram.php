<?php

include('Config.php');
class Telegram extends Config
{
    public function __construct()
    {
        $this->mysqli = $this->mysqliConnect();
    }

    public function mysqliConnect()
    {
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        $mysqli = new mysqli($this->host, $this->db_user, $this->db_password, $this->db);
        mysqli_set_charset($mysqli, 'utf8mb4');
        return $mysqli;
    }

    public function cURL($url, $ref, $headers, $parameters, $user_agent)
    {
        $user_agent = ($user_agent) ? $user_agent : $_SERVER['HTTP_USER_AGENT'];
        $ch =  curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);
        curl_setopt($ch, CURLOPT_REFERER, $ref);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        if ($headers) curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        if ($parameters) {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $parameters);
        }
        $result = curl_exec($ch);
        if ($result) return $result;
        else return curl_error($ch);
        curl_close($ch);
    }

    public function telegram_get_command($chat_id, $command)
    {
        switch ($command) {
            case '/start':
                if (!$this->mysqli->query("SELECT * FROM " . $this->table_chats . " WHERE chat_id=" . $chat_id)->num_rows) {
                    $branch = 0;
                    $step = 0;
                    $this->save_chat_id($chat_id, $branch, $step);
                    if ($this->set_branch($chat_id, $branch) && $this->set_step($chat_id, $step)) {
                        $this->telegram_send($chat_id, $this->get_bot_answer($branch, $step));
                    }
                } else {
                    $branch = 1;
                    $step = 1;
                    if ($this->set_branch($chat_id, $branch) && $this->set_step($chat_id, $step)) {
                        $this->telegram_send($chat_id, $this->get_bot_answer($this->get_branch($chat_id), $this->get_step($chat_id)));
                    }
                }
                break;
            default:
                $branch = -1;
                $step = -1;
                if ($this->set_branch($chat_id, $branch) && $this->set_step($chat_id, $step)) {
                    $this->telegram_send($chat_id, $this->get_bot_answer($this->get_branch($chat_id), $this->get_step($chat_id)));
                }
                break;
        }
    }

    public function telegram_get_message($chat_id, $message)
    {
        $md5_message = md5($message);
        $sql_answers_keyboards = "SELECT * FROM `" . $this->table_answers_keyboards . "` WHERE `md5` LIKE '%" . $md5_message . "%'";
        if ($result = $this->mysqli->query($sql_answers_keyboards)) {
            if ($result->num_rows) {
                $obj = $result->fetch_object();
                if ($obj->type == 3) {
                    $sql_chats = "SELECT * FROM `" . $this->table_chats . "` WHERE chat_id=" . $chat_id;
                    if ($result = $this->mysqli->query($sql_chats)) {
                        $obj_chats = $result->fetch_object();
                        $sql_answers = "SELECT * FROM `" . $this->table_answers . "` WHERE branch=" . $obj_chats->branch . " AND type=3";
                        if ($result = $this->mysqli->query($sql_answers)) {
                            if ($result->num_rows) {
                                $obj_answers = $result->fetch_object();
                                $sql_answers_keyboards = "SELECT * FROM `" . $this->table_answers_keyboards . "` WHERE answer_id=" . $obj_answers->id . " AND type=3";
                                if ($result = $this->mysqli->query($sql_answers_keyboards)) {
                                    $obj_keyboards = $result->fetch_object();
                                    $go_to = json_decode($obj_keyboards->go_to);
                                }
                            }
                        }
                    }
                } else {
                    $go_to = json_decode($obj->go_to);
                }
                $return[$obj_chats->branch][$obj_chats->step] = $message;
                if ($this->set_branch($chat_id, $go_to->branch) && $this->set_step($chat_id, $go_to->step)) {
                    $this->telegram_send($chat_id, $this->get_bot_answer($go_to->branch, $go_to->step));
                    return $return;
                }
            } else {
                $sql = "SELECT * FROM `" . $this->table_chats . "` WHERE chat_id=" . $chat_id;
                if ($result = $this->mysqli->query($sql)) {
                    $obj_chats = $result->fetch_object();
                    $return[$obj_chats->branch][$obj_chats->step] = $message;
                    $sql = "SELECT * FROM `" . $this->table_answers . "` WHERE branch=" . $obj_chats->branch . " AND type=5";
                    if ($result = $this->mysqli->query($sql)) {
                        if ($result->num_rows) {
                            $obj_answers = $result->fetch_object();
                            $go_to = json_decode($obj_answers->go_to);
                            if ($this->set_branch($chat_id, $go_to->branch) && $this->set_step($chat_id, $go_to->step)) {
                                $this->telegram_send($chat_id, $this->get_bot_answer($go_to->branch, $go_to->step));
                            }
                        } else {
                            if ($this->set_branch($chat_id, $obj_chats->branch) && $this->set_step($chat_id, $obj_chats->step)) {
                                $this->telegram_send($chat_id, $this->get_bot_answer($obj_chats->branch, $obj_chats->step));
                            }
                        }
                    }
                    return $return;
                }
            }
        }
    }

    public function telegram_get_photo($chat_id, $dir, $files_array)
    {
        $flag = 0;
        foreach ($files_array as $file_array) {
            $file_path = $this->get_photo_path($file_array['file_id']);
            if ($this->save_get_photo($dir, $file_path, $file_array['file_id'])) {
                $flag = 1;
            }
        }
        if ($flag) {
            $go_to = json_decode($this->get_goto($chat_id), true);
            $this->telegram_send($chat_id, $this->get_bot_answer($go_to['branch'], $go_to['step']));
        }
    }

    public function telegram_get_video($chat_id, $dir, $file_array)
    {
        if ($this->save_get_video($dir, $this->get_video_path($file_array['file_id']), $file_array['file_id'])) {
            $go_to = json_decode($this->get_goto($chat_id), true);
            $this->telegram_send($chat_id, $this->get_bot_answer($go_to['branch'], $go_to['step']));
        }
    }

    public function get_bot_answer($branch, $step)
    {
        $sql = "SELECT * FROM " . $this->table_answers . " WHERE branch=" . $branch . " AND step=" . $step . " ORDER BY id";
        if ($result = $this->mysqli->query($sql)) {
            while ($obj = $result->fetch_object()) {
                $answers_arr[] = $obj;
            }
            return $answers_arr;
        }
    }

    public function get_bot_buttons($answer_id)
    {
        $sql = "SELECT * FROM " . $this->table_answers_buttons . " WHERE answer_id=" . $answer_id . " ORDER BY id";
        if ($result = $this->mysqli->query($sql)) {
            while ($obj = $result->fetch_object()) {
                if ($obj->type == 1) {
                    $obj->arguments = $this->get_bot_buttons_arguments($obj->id);
                }
                $answers_buttons[] = $obj;
            }
            return $answers_buttons;
        }
    }

    public function get_bot_buttons_arguments($button_id)
    {
        $sql = "SELECT * FROM " . $this->table_answers_buttons_arguments . " WHERE button_id=" . $button_id . " ORDER BY id";
        if ($result = $this->mysqli->query($sql)) {
            while ($obj = $result->fetch_object()) {
                $arguments[] = $obj;
            }
            return $arguments;
        }
    }

    public function get_bot_keyboards($answer_id)
    {
        $sql = "SELECT * FROM " . $this->table_answers_keyboards . " WHERE answer_id=" . $answer_id . " ORDER BY id";
        if ($result = $this->mysqli->query($sql)) {
            while ($obj = $result->fetch_object()) {
                if ($obj->type == 2 || $obj->type == 3) {
                    $answers_keyboards[] = $obj;
                }
            }
            return $answers_keyboards;
        }
    }

    public function telegram_send($chat_id, $data)
    {
        foreach ($data as $obj) {
            if ($obj->type == 1) $this->telegram_send_message_text($chat_id, $obj->text);
            elseif ($obj->type == 2) {
                $buttons = $this->get_bot_buttons($obj->id);
                $this->telegram_send_message_button($chat_id, $obj->text, $buttons);
            } elseif ($obj->type == 3 || $obj->type == 5) {
                $keyboards = $this->get_bot_keyboards($obj->id);
                $this->telegram_send_message_keyboards($chat_id, $obj->text, $keyboards);
            } elseif ($obj->type == 4) {
                $this->telegram_send_message_video($chat_id, $obj->text, $obj->url);
            } elseif ($obj->type == 6) {
                $this->telegram_send_message_text($chat_id, $obj->text);
                $go_to = json_decode($obj->go_to);
                if ($this->set_branch($chat_id, $go_to->branch) && $this->set_step($chat_id, $go_to->step)) {
                    $this->telegram_send($chat_id, $this->get_bot_answer($go_to->branch, $go_to->step));
                }
            }
        }
    }

    public function telegram_send_message_video($chat_id, $caption, $url)
    {
        $parameters = array(
            'chat_id' => $chat_id,
            'video' => $url,
            'caption' => $caption
        );
        $parameters = http_build_query($parameters);
        $this->cURL($this->telegram_send_url . $this->telegram_bot_token . '/sendVideo', '', '', $parameters, '');
    }

    public function telegram_send_message_keyboards($chat_id, $message_answer, $keyboards)
    {
        $c = 0;
        $row = '';
        foreach ($keyboards as $keyboards_obj) {
            if ($row != $keyboards_obj->row) {
                $row = $keyboards_obj->row;
                $c = 0;
            }
            $replyMarkup['keyboard'][$keyboards_obj->row][$c]['text'] = $keyboards_obj->text;
            if ($keyboards_obj->callback_data) {
                $replyMarkup['keyboard'][$keyboards_obj->row][$c]['callback_data'] = $keyboards_obj->callback_data;
            }
            $c++;
        }
        $replyMarkup['resize_keyboard'] = true;
        $encodedMarkup = json_encode($replyMarkup, JSON_UNESCAPED_UNICODE);
        $parameters = [
            'chat_id' => $chat_id,
            'parse_mode' => 'HTML',
            'text' => $message_answer,
            'reply_markup' => $encodedMarkup
        ];

        $parameters = http_build_query($parameters);
        $this->cURL($this->telegram_send_url . $this->telegram_bot_token . '/sendMessage', '', '', $parameters, '');
    }

    public function telegram_send_message_button($chat_id, $message_answer, $buttons)
    {
        $static_arguments = [
            'chat_id' => $chat_id
        ];
        $c = 0;
        $row = '';
        foreach ($buttons as $key => $butons_obj) {
            if ($row != $butons_obj->row) {
                $row = $butons_obj->row;
                $c = 0;
            }
            $url = (isset($butons_obj->arguments)) ? $butons_obj->url . '?' . $this->create_button_url($butons_obj->arguments, $static_arguments) : $butons_obj->url;
            $butons_array['inline_keyboard'][$butons_obj->row][$c]['text'] = $butons_obj->text;
            $butons_array['inline_keyboard'][$butons_obj->row][$c]['url'] = $url;
            $c++;
        }
        $keyboard = json_encode($butons_array);
        $parameters = [
            'chat_id' => $chat_id,
            'parse_mode' => 'HTML',
            'text' => $message_answer,
            'reply_markup' => $keyboard
        ];
        $parameters = http_build_query($parameters);
        $this->cURL($this->telegram_send_url . $this->telegram_bot_token . '/sendMessage', '', '', $parameters, '');
    }

    public function telegram_send_message_text($chat_id, $message_answer)
    {
        $parameters = array(
            'chat_id' => $chat_id,
            'text' => $message_answer
        );
        $parameters = http_build_query($parameters);
        $this->cURL($this->telegram_send_url . $this->telegram_bot_token . '/sendMessage', '', '', $parameters, '');
    }

    public function get_photo_path($file_id)
    {
        $parameters = [
            'file_id' => $file_id
        ];
        $parameters = http_build_query($parameters);
        $result_json = $this->cURL($this->telegram_send_url . $this->telegram_bot_token . '/getFile', '', '', $parameters, '');
        $result = json_decode($result_json);
        return $result->result->file_path;
    }

    public function get_video_path($file_id)
    {
        $parameters = [
            'file_id' => $file_id
        ];
        $parameters = http_build_query($parameters);
        $result_json = $this->cURL($this->telegram_send_url . $this->telegram_bot_token . '/getFile', '', '', $parameters, '');
        $result = json_decode($result_json);
        return $result->result->file_path;
    }

    public function save_get_photo($dir, $file_path, $file_id)
    {
        $file_name = $file_id . '.' . explode('.', explode('/', $file_path)[1])[1];
        $url = $this->telegram_file_url . '/bot' . $this->telegram_bot_token . '/' . $file_path;
        if (file_put_contents($dir . $file_name, file_get_contents($url))) return true;
    }

    public function save_get_video($dir, $file_path, $file_id)
    {
        $file_name = $file_id . '.' . explode('.', explode('/', $file_path)[1])[1];
        $url = $this->telegram_file_url . '/bot' . $this->telegram_bot_token . '/' . $file_path;
        if (file_put_contents($dir . $file_name, file_get_contents($url))) return true;
    }

    public function save_chat_id($chat_id, $branch, $step)
    {
        $token = md5($chat_id);
        $dir = '../upload/' . $chat_id . '/';
        if (!file_exists($dir)) mkdir($dir, 0777);
        $sql = "INSERT INTO " . $this->table_chats . " SET chat_id=" . $chat_id . ", token='" . $token . "', branch=" . $branch . ", step=" . $step;
        if ($this->mysqli->query($sql)) return true;
    }

    public function authorization($chat_id)
    {
        $sql = "UPDATE " . $this->table_chats . " SET is_authorization=1, branch=1, step=1 WHERE chat_id=" . $chat_id;
        if ($this->mysqli->query($sql)) {
            return true;
        }
    }

    public function create_button_url($arguments, $static_arguments)
    {
        for ($i = 0; $i < count($arguments); $i++) {
            $arr[$arguments[$i]->argument] = ($arguments[$i]->value) ? $arguments[$i]->value : $static_arguments[$arguments[$i]->argument];
        }
        return 'e=' . $this->encode(json_encode($arr));
    }

    public function encode($unencoded)
    {
        $key = $this->secret;
        $string = base64_encode($unencoded);
        $arr = array();
        $x = 0;
        $newstr = '';
        while ($x++ < strlen($string)) {
            $arr[$x - 1] = md5(md5($key . $string[$x - 1]) . $key);
            @$newstr = $newstr . $arr[$x - 1][3] . $arr[$x - 1][6] . $arr[$x - 1][1] . $arr[$x - 1][2];
        }
        return $newstr;
    }

    public function decode($encoded)
    {
        $key = $this->secret;
        $strofsym = "qwertyuiopasdfghjklzxcvbnm1234567890QWERTYUIOPASDFGHJKLZXCVBNM=";
        $x = 0;
        while ($x++ <= strlen($strofsym)) {
            @$tmp = md5(md5($key . $strofsym[$x - 1]) . $key);
            @$encoded = str_replace($tmp[3] . $tmp[6] . $tmp[1] . $tmp[2], $strofsym[$x - 1], $encoded);
        }
        return base64_decode($encoded);
    }

    public function decipher_url($encoded)
    {
        return json_decode($this->decode($encoded));
    }

    public function get_branch($chat_id)
    {
        $sql = "SELECT * FROM " . $this->table_chats . " WHERE chat_id=" . $chat_id;
        if ($result = $this->mysqli->query($sql)) {
            return $result->fetch_object()->branch;
        }
    }

    public function get_step($chat_id)
    {
        $sql = "SELECT * FROM " . $this->table_chats . " WHERE chat_id=" . $chat_id;
        if ($result = $this->mysqli->query($sql)) {
            return $result->fetch_object()->step;
        }
    }

    public function set_branch($chat_id, $branch)
    {
        $sql = "UPDATE " . $this->table_chats . " SET branch=" . $branch . " WHERE chat_id=" . $chat_id;
        if ($this->mysqli->query($sql)) {
            return true;
        }
    }

    public function set_step($chat_id, $step)
    {
        $sql = "UPDATE " . $this->table_chats . " SET step=" . $step . " WHERE chat_id=" . $chat_id;
        if ($this->mysqli->query($sql)) {
            return true;
        }
    }

    public function get_goto($chat_id)
    {
        $sql = "SELECT * FROM `" . $this->table_chats . "` WHERE chat_id=" . $chat_id;
        if ($result = $this->mysqli->query($sql)) {
            $obj_chats = $result->fetch_object();
            $sql = "SELECT * FROM `" . $this->table_answers . "` WHERE branch=" . $obj_chats->branch;
            if ($result = $this->mysqli->query($sql)) {
                while ($obj_answers = $result->fetch_object()) {
                    if ($obj_answers->go_to) {
                        return $obj_answers->go_to;
                    }
                }
            }
        }
    }

    public function save_data($data_json, $type, $dir)
    {
        $data = json_decode($data_json);
        if ($type == 'text') {
            $text = $data->message->text;
        } elseif ($type == 'photo') {
            $file_data = end($data->message->$type);
            $found_file = glob($dir . $file_data->file_id . '.*');
            $text = ($_SERVER['HTTPS']) ? 'https://' . $_SERVER['HTTP_HOST'] : 'http://' . $_SERVER['HTTP_HOST'];
            $text .= substr($found_file[0], 2);
        } elseif ($type == 'video') {
            $found_file = glob($dir . $data->message->$type->file_id . '.*');
            $text = ($_SERVER['HTTPS']) ? 'https://' . $_SERVER['HTTP_HOST'] : 'http://' . $_SERVER['HTTP_HOST'];
            $text .= substr($found_file[0], 2);
        }
        $sql = "INSERT INTO " . $this->table_dialogs . " SET 
            data_json='" . $data_json . "', 
            message_id=" . $data->message->message_id . ",
            first_name='" . $data->message->from->first_name . "',
            username='" . $data->message->from->username . "',
            language_code='" . $data->message->from->language_code . "',
            chat_id=" . $data->message->chat->id . ",
            type='" . $type . "',
            date=" . $data->message->date . ",
            text='" . $text . "'
        ";
        $this->mysqli->query($sql);
    }
}
