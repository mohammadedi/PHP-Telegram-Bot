<?php
# если создатель бота в чате напишет "бот уходи", бот обидится и покинет чат 
if($_USER['username'] == ADMIN)
	{
		if($_CHAT['id'] != $_INFO['id'])
			{
				if(preg_match("#бот уходи#iu", $_TEXT))
					{
						sendMessage($_CHAT['id'], 'Okay :(');
						sleep(3);
						leaveChat($_CHAT['id']);
					}
			}
	}