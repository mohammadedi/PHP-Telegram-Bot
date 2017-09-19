<?php

# now playing

if(preg_match("#^/np ([a-zA-Z0-9\-\_]{5,})$#iu", $_TEXT, $cbb) || preg_match("#^/nowplaying ([a-zA-Z0-9\-\_]{5,})$#iu", $_TEXT, $cbb))
	{
		$user = $cbb[1];
		$url = 'http://ws.audioscrobbler.com/2.0/?method=user.getrecenttracks&user='.$user.'&api_key=1c15cbf8b0b1ec524d3633e8c55ad934&format=json&limit=1';
		$f = file_get_contents($url);
		
		$array = json_decode($f, TRUE);
		
		
		if(isset($array['error']))
			{
				$mess = 'Ошибка';
			}
		else
			{
				if(isset($array['recenttracks']['track'][0]['@attr']['nowplaying']))
					{
						$np = 'Now playing';
					}
				else
					{
						$np = 'Last played';
					}
				$mess = $np.': *'.$array['recenttracks']['track'][0]['artist']['#text'].'* - _'.$array['recenttracks']['track'][0]['name'].'_';
				
			}
		sendMessage($_CHAT['id'], $mess, 'Markdown', $_MESS['message_id']);
	}