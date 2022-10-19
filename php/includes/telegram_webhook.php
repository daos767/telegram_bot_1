<?php

include ('../includes/classes/Telegram.php');
$Telegram = new Telegram();

$get_data = file_get_contents('php://input');

$get_data = utf8_encode($get_data);
$data = json_decode($get_data);
$data_json = json_encode($data, JSON_UNESCAPED_UNICODE);
$data = (array)json_decode($data_json, true);

$chat_id = $data['message']['chat']['id'];
$message_text = $data['message']['text'];
if (@$data['message']['entities'][0]['type'] == 'bot_command') {
    $Telegram->telegram_get_command($chat_id, $data['message']['text']);
} else {
    if (isset($data['message']['text'])) {
        $Telegram->telegram_get_message($chat_id,  $data['message']['text']);
        $Telegram->save_data($data_json, 'text', '');
    } elseif (isset($data['message']['photo'])) {
        $dir = '../upload/' . $chat_id . '/photo/';
        if (!file_exists($dir)) mkdir($dir, 0777);
        $Telegram->telegram_get_photo($chat_id, $dir, $data['message']['photo']);
        $Telegram->save_data($data_json, 'photo', $dir);
    } elseif (isset($data['message']['video'])) {
        $dir = '../upload/' . $chat_id . '/video/';
        if (!file_exists($dir)) mkdir($dir, 0777);
        $Telegram->telegram_get_video($chat_id, $dir, $data['message']['video']);
        $Telegram->save_data($data_json, 'video', $dir);
    }
}