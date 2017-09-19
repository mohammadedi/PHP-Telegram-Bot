<?php
if($_USER['username'] == ADMIN)
	{
		if($_CHAT['id'] != $_INFO['id'])
			{
				if(preg_match("#ада уходи#iu", $_TEXT))
					{
						sendMessage($_CHAT['id'], 'Okay :(');
						sleep(3);
						leaveChat($_CHAT['id']);
					}
			}
	}