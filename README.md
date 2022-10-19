<h1 align="center">telegram_Bot</h1>
<h3 align="left">Языки и инструменты:</h3>
<p align="left"> 
    <a href="https://www.php.net" target="_blank" rel="noreferrer">
        <img src="https://raw.githubusercontent.com/devicons/devicon/master/icons/php/php-original.svg" alt="php" width="40" height="40"/>
    </a>
    <a href="https://www.mysql.com/" target="_blank" rel="noreferrer">
        <img src="https://raw.githubusercontent.com/devicons/devicon/master/icons/mysql/mysql-original-wordmark.svg" alt="mysql" width="40" height="40"/>
    </a>
</p>
<h3 align="left">Ссылка на бота:</h3>
<p>
    <a href="https://t.me/testDs1_bot" target="_blank" rel="noreferrer">єВорог_testBot</a>
</p>
<h3 align="left">Установка:</h3>
<p>
    <ol>
        <li>Выполнить дамп mysql из папки /db</li>
        <li>
            Внести настройки в файл /php/includes/classes/Config.php
            <ul>
                <li>host - Имя/адрес сервера mysql</li>
                <li>data_base - Имя базы данных</li>
                <li>data_base_user - Пользователь базы данных</li>
                <li>data_base_password - Пароль пользователя базы данных</li>
                <li>bot_token - Токен telegram бота. Получить токен нужно у <a href="https://t.me/BotFather" target="_blank" rel="noreferrer">BotFather</a></li>
                <li>bot_name - Имя бота которого вы создали у <a href="https://t.me/BotFather" target="_blank" rel="noreferrer">BotFather</a></li>
            </ul>
        </li>
        <li>Скопировать содержимое папки /php на сервер</li>
    </ol>
</p>
<h3 align="left">Описание таблиц mysql:</h3>
<p>
    <ul>
        <li>t_answers - Текст который отправляет бот</li>
        <li>t_answers_buttons - Кнопки <a href="https://core.telegram.org/bots/api#inlinekeyboardbutton" target="_blank" rel="noreferrer">InlineKeyboardButton</a></li>
        <li>t_answers_buttons_arguments - Аргументы кнопок <a href="https://core.telegram.org/bots/api#inlinekeyboardbutton" target="_blank" rel="noreferrer">InlineKeyboardButton</a></li>
        <li>t_answers_keyboards - Кнопки <a href="https://core.telegram.org/bots/api#replykeyboardmarkup" target="_blank" rel="noreferrer">ReplyKeyboardMarkup</a></li>
        <li>t_chats - Список чатов</li>
        <li>t_dialogs - Диалоги с ботом</li>
    </ul>
</p>