<?php
# основные настройки бота
define('BOT_TOKEN', '123'); // токен бота
define('API_URL', 'https://api.telegram.org/bot'.BOT_TOKEN.'/'); // адрес API. Без нужды не трогать!
define('WEBHOOK', 'https://test.ru/bot/process.php'); // адрес вебхука, поменять на свой (строго https!!!)
define('ADMIN', 'D13410N3'); // ник админа, используется проверка "админ ли?"
define('R', '/var/html/host/bot'); // рутовая директория, т.е. где лежит этот файл, например

# доп. сервисы
define('SPEECHKIT_TOKEN', '123'); // token Yandex.SpeechKit для распознавания голоса
define('LASTFM', '1234567890'); // API key Last.FM для просмотра NowPlaying

# настройки подключения к БД
@mysql_connect(':/var/run/mysqld/mysqld.sock','root','DBPASS') or die(mysql_error()); // укажите тут данные для коннекта к серверу БД
@mysql_select_db('DBNAME') or die(mysql_error()); // укажите имя базы
mysql_set_charset('utf8'); // по умолчанию все на utf-8