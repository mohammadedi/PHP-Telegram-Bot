<?php
# технический скрипт для того, чтобы узнать id юзера / id чата
if($_TEXT == '/whoami')
	{
		$mess = '';
		$mess .= $_USER['id'].': '.$_USER['username'];
		
		if($_CHAT['id'] != $_USER['id'])
			{
				$mess .= PHP_EOL.$_CHAT['id'].': '.$_CHAT['title'];
			}
		
		sendMessage($_CHAT['id'], $mess);
	}