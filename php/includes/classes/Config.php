<?php

class Config
{
    // MYSQL settings
    protected $host = '[host]';
    protected $db = '[data_base]';
    protected $db_user = '[data_base_user]';
    protected $db_password = '[data_base_password]';
    
    // MYSQL tables
    protected $table_dialogs = 't_dialogs';
    protected $table_chats = 't_chats';
    protected $table_answers = 't_answers';
    protected $table_answers_buttons = 't_answers_buttons';
    protected $table_answers_buttons_arguments = 't_answers_buttons_arguments';
    protected $table_answers_keyboards = 't_answers_keyboards';

    // KEY
    protected $secret = '123qwe123';

    // Telegram
    public $telegram_bot_token = '[bot_token]';
    public $telegram_bot_name = '[bot_name]';
    public $telegram_send_url = 'https://api.telegram.org/bot';
    public $telegram_file_url = 'https://api.telegram.org/file/';
}