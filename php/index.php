<?
include('includes/classes/Telegram.php');
$Telegram = new Telegram();

if (isset($_GET['e'])) {
    $decode_obj = $Telegram->decipher_url($_GET['e']);
    if ($decode_obj->action == 'authorization') {
        if ($Telegram->authorization($decode_obj->chat_id)) {
            $Telegram->telegram_send($decode_obj->chat_id, $Telegram->get_bot_answer($Telegram->get_branch($decode_obj->chat_id), $Telegram->get_step($decode_obj->chat_id)));
            header("Location: https://t.me/" . $Telegram->telegram_bot_name);
            exit;
        }
    }
}