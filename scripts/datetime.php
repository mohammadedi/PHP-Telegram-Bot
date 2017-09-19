<?php

if($_TEXT == 'время')
	{
		$d = date('d.m.Y H:i:s');
		sendMessage($_CHAT['id'], $d);
	}