<?php

require_once 'api.php'; // всякие функции отправки сообщений, обработки и так далее + задание констант типа API_TOKEN и коннект к бд

# сеттим вебхук, если скрипт выполняется из оболочки системы
if(php_sapi_name() == 'cli')
	{
		setWebhook(WEBHOOK);	
	}
# процессим входящую ебалу

$content = file_get_contents("php://input"); // всё, что пришло на вебхук ПОСТом - идет в $content
$update = @json_decode($content, true); // декодим из джсона в ассоциативный массив

if(!$update)
	{
		// кривой JSON, значит левый запрос или что-то такое
		sendMessage(@$_CHAT['id'], 'Я не пони(');
		die;
	}
else
	{
		# делаем псевдоглобальные переменные
		$_MESS = $update['message']; // массив с содержанием самого сообщения (полезная информация то есть)
		$_TEXT = mb_strtolower($_MESS['text'], 'utf-8'); // для нерегистрозависимости сразу текст в нижнее подчеркивание
		$_CHAT = $_MESS['chat']; // информация о том, какой это чат (если это личка, части переменных не будет)
		$_USER = $_MESS['from']; // информация о юзере-отправителе
		$_USER['username'] = empty($_USER['username']) ? $_USER['first_name'].' '.$_USER['last_name'] : $_USER['username'];
		

		$_CHAT['title'] = empty($_CHAT['title']) ? 'ЛС' : $_CHAT['title'];

		// пишем в базу
		mysql_query("INSERT INTO `messages`(`id_chat`, `id_message`, `id_user`, `time`, `message`, `user_nick`, `chat_name`) VALUES ('".$_CHAT['id']."', '".$_MESS['message_id']."', '".$_USER['id']."', '".time()."', '".$_MESS['text']."', '".$_USER['username']."', '".$_CHAT['title']."')");

		// собираем базу юзеров
		$q_u = mysql_query("SELECT * FROM `tg_users` WHERE `id_user` = '".$_USER['id']."'");
		if(mysql_num_rows($q_u) < 1)
			{
				mysql_query("INSERT INTO `tg_users`(`id_user`, `nick`) VALUES ('".$_USER['id']."', '".$_USER['username']."')");
			}
		
		// проверяем, чят или личка, если чят - пишем чят
		if($_USER['id'] != $_CHAT['id'])
			{
				$q_c = mysql_query("SELECT * FROM `tg_chats` WHERE `id_chat` = '".$_CHAT['id']."'");
				if(mysql_num_rows($q_c) < 1)
					{
						mysql_query("INSERT INTO `tg_chats`(`id_chat`, `title`) VALUES ('".$_CHAT['id']."', '".$_CHAT['title']."')");
					}
			}

		// тут require всяких скриптов-обработчиков
		$h = opendir('scripts');
		
		while(false !== ($file = readdir($h)))
			{
				$___tmp = explode('.', $file);
				$ext = end($___tmp);
				if($ext == 'php')
					{
						require_once 'scripts/'.$file;
					}
			}
		closedir($h);
				
	}