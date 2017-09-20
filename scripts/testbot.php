<?php

# админ может посмотреть статистику бота

if($_USER['username'] == ADMIN)
	{
		if($_MESS['text'] == '/testbot')
			{
				$c_mess = mysql_num_rows(mysql_query("SELECT * FROM `messages`"));
				$c_users = mysql_num_rows(mysql_query("SELECT * FROM `tg_users`"));
				$c_chats = mysql_num_rows(mysql_query("SELECT * FROM `tg_chats`"));
				sendMessage($_CHAT['id'], 'Я работаю. В базе *'.$c_mess.'* сообщений, *'.$c_users.'* пользователей и *'.$c_chats.'* чатов', 'Markdown', $_MESS['id']);
			}
	}