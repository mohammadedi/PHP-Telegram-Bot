Бот для Telegram на PHP

Часть кода основана на "официальной" библиотеке PHP от телеграмма.

Алгоритм работы бота:

1) Поступает запрос на вебхук process.php
2) Подключаются все php-файлы из папки scripts (все скрипты складывать туда)


Требуется БД (ведется логгирование сообщений, пользователей и чатов, но можно убрать самостоятельно при необходимости). Коннект в api.php 

Соответственно, в самих скриптах никакие дополнительные переменные объявлять не надо, т.к. она подкачивается в основной process.php 
Примеры псевдо-"глобальных" переменных ($_USER, $_CHAT и так далее) можно найти в самом process.php. Описание функций (sendMessage, sendImage и так далее) - в api.php

В scripts есть несколько скриптов, показывающих как все работает

В силу ряда изменений (отказ от устаревшей библиотеки MySQL и изменение специфики работы cURL с отправкой файлов) часть функций не работает на PHP7.

При возникновении вопросов пишите в телеграмм - @D13410N3