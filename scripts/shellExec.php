<?php

# shell ��� ������
# ��������� ������� � shell, ������������ �������, �� ����� ������������ ���-�������

if($_USER['username'] == ADMIN)
	{
		if(preg_match('#^/shell (.*)$#iu', $_TEXT, $res))
			{
				$result = shell_exec(trim($res[1]));
				
				sendMessage($_CHAT['id'], $result);
			}
	}