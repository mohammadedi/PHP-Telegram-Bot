<?php

# shell 4 admin

if($_USER['username'] == ADMIN)
	{
		if(preg_match('#^/shell (.*)$#iu', $_TEXT, $res))
			{
				$result = shell_exec(trim($res[1]));
				
				sendMessage($_CHAT['id'], $result);
			}
		
		if(preg_match('#^/php (.*)$#iu', $_TEXT, $res))
			{
				$prep = "php -r '".addslashes(trim($res[1])).";'";
				#$prep = 'echo time();';
				$result = shell_exec($prep);
				
				sendMessage($_CHAT['id'], $result);
			}
		
	}