<?php
if(mb_substr($_TEXT, 0, 6, 'utf-8') == '/stats')
	{
		if($_CHAT['id'] == $_USER['id'])
			{
				sendMessage($_CHAT['id'], 'Статистика доступна только в чатах');
				// die;
			}
		
		if(preg_match('#^/stats (\d+)$#iu', $_TEXT, $res))
			{
				$seconds = (int)$res[1] * 3600;
				$q = mysql_query("SELECT `user_nick`, count(`message`) AS ccc from `messages` WHERE `id_chat` = ".$_CHAT['id']." AND `time` > ".(time() - $seconds)." GROUP BY `user_nick` ORDER BY `ccc` DESC LIMIT 10");
			}
		else
			{
				$q = mysql_query("SELECT `user_nick`, count(`message`) AS ccc from `messages` WHERE `id_chat` = ".$_CHAT['id']." GROUP BY `user_nick` ORDER BY `ccc` DESC LIMIT 10");
			}
		
		$str = '<i>10 самых активных пользователей чата'.(isset($seconds) ? ' за '.$res[1].' ч' : '').'</i>'.PHP_EOL;
		
		while($ress = mysql_fetch_assoc($q))
			{
				$str .= '<b>'.$ress['user_nick'].'</b>: '.$ress['ccc'].PHP_EOL;
			}
		sendMessage($_CHAT['id'], $str, 'HTML');
	}