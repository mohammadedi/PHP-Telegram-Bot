<?php

require_once 'settings.php';

# Функции и вот это всё

# простая отправка сообщения
function sendMessage($id_chat, $text, $mark = '', $id_message = '')
	{
		// $text = empty($text) ? 'undef or empty var' : $text;
		$toSend = array('method' => 'sendMessage', 'chat_id' => $id_chat, 'text' => $text);
		isset($id_message) ? $toSend['reply_to_message_id'] = $id_message : '';
		isset($mark) ? $toSend['parse_mode'] = $mark : '';
		
		$ch = curl_init(API_URL);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($toSend));
		curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
		$a = curl_exec($ch);
		return json_decode($a, true);
	}

# редактирование
function editMessage($id_message, $id_chat, $text, $mark = '')
	{
		$toSend = array('method' => 'editMessageText', 'message_id' => $id_message, 'chat_id' => $id_chat, 'text' => $text);
		!empty($mark) ? $toSend['parse_mode'] = $mark : '';
		
		$ch = curl_init(API_URL);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($toSend));
		curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
		$a = curl_exec($ch);
		return json_decode($a, true);
	}

# отправка клавы с выборкой
function sendKeyboard($id_chat, $text, $mark = '', $id_message = '', $keyboard = array())
	{			
		$toSend = array('method' => 'sendMessage', 'chat_id' => $id_chat, 'text' => $text);
		isset($id_message) ? $toSend['reply_to_id_message'] = $id_message : '';
		isset($mark) ? $toSend['parse_mode'] = $mark : '';
		
		!empty($keyboard) ? $toSend['reply_markup'] = array('keyboard' => $keyboard, 'one_time_keyboard' => false, 'resize_keyboard' => true) : '';
		
		$ch = curl_init(API_URL);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($toSend));
		curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
		$a = curl_exec($ch);
		return json_decode($a, true);
	}

# Отправка картинки
# передача файла реализована через класс CURLFile. Если не работает, то делаем следующее:
# строку $cfile = new CURLFile(realpath($path));
# заменяем на
# $cfile = '@'.realpath($path);
function sendImage($id_chat, $path, $local = true, $caption = '', $message_id = '')
	{
		
		if(!$local)
			{
				$ch2 = curl_init($path);
				curl_setopt($ch2, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch2, CURLOPT_USERAGENT, 'Mozilla/2.28 for Android 1488 Yoba edition');
				$a = curl_exec($ch2);
				
				$__tmp = explode('.', $path);
				$ext = end($__tmp);
				
				$fop = fopen('data/tmp.'.$ext, 'w');
				flock($fop, LOCK_EX);
				fwrite($fop, $a);
				flock($fop, LOCK_UN);
				fclose($fop);
				$path = 'data/tmp.'.$ext;
			}
				
		
		$cfile = new CURLFile(realpath($path));
		$toSend = array('method' => 'sendPhoto', 'chat_id' => $id_chat, 'photo' => $cfile);
		!empty($caption) ? $toSend['caption'] = $caption : '';
		!empty($message_id) ? $toSend['reply_to_message_id'] = $message_id : '';
		
		$ch = curl_init(API_URL);
		
		
		
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $toSend);
		$a = curl_exec($ch);
	}

# отправка файла
# передача файла реализована через класс CURLFile. Если не работает, то делаем следующее:
# строку $cfile = new CURLFile(realpath($path));
# заменяем на
# $cfile = '@'.realpath($path);
function sendFile($id_chat, $path, $local = true, $caption = '', $message_id = '')
	{
		if(!$local)
			{
				$ch2 = curl_init($path);
				curl_setopt($ch2, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch2, CURLOPT_USERAGENT, 'Mozilla/2.28 for Android 1488 Yoba edition');
				$a = curl_exec($ch2);
				
				$ext = end(explode('.', $path));
				
				$fop = fopen('data/tmp.'.$ext, 'w');
				flock($fop, LOCK_EX);
				fwrite($fop, $a);
				flock($fop, LOCK_UN);
				fclose($fop);
				$path = 'data/tmp.'.$ext;
			}
				

		$cfile = new CURLFile(realpath($path));
		$toSend = array('method' => 'sendDocument', 'document' => $cfile, 'chat_id' => $id_chat);
		!empty($caption) ? $toSend['caption'] = $caption : '';
		!empty($message_id) ? $toSend['reply_to_message_id'] = $message_id : '';
		
		$ch = curl_init(API_URL);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $toSend);
		curl_exec($ch);
	}

# форвард
function forwardMessage($id_chat, $from_id, $message_id)
	{
		$toSend = array('method' => 'forwardMessage', 'chat_id' => $id_chat, 'from_chat_id' => $from_id, 'message_id' => $message_id);
		
		$ch = curl_init(API_URL);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($toSend));
		curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
		curl_exec($ch);
	}

# покинуть чат
function leaveChat($chat)
	{
		$toSend = array('method' => 'leaveChat', 'chat_id' => $chat);
		$ch = curl_init(API_URL);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($toSend));
		curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
		curl_exec($ch);
	}

# удаление 
function deleteMessage($id_chat, $id_message)
	{
		$toSend = array('method' => 'deleteMessage', 'chat_id' => $id_chat, 'message_id' => $id_message);
		$ch = curl_init(API_URL);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($toSend));
		curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
		curl_exec($ch);
	}

# sendAction
function sendChatAction($id_chat, $action)
	{
		#typing for text messages, upload_photo for photos, record_video or upload_video for videos, record_audio or upload_audio for audio files, upload_document for general files, find_location for location data, record_video_note or upload_video_note for video notes.
		
		$toSend = array('method' => 'sendChatAction', 'chat_id' => $id_chat, 'action' => $action);
		$ch = curl_init(API_URL);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($toSend));
		curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
		curl_exec($ch);
	}

# webhook
function setWebhook($url, $delete = false)
	{
		$webhook = $delete == true ? 'delete' : $url;
		$toSend = array('url' => $webhook, 'method' => 'setWebhook');
		$ch = curl_init(API_URL);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($toSend));
		curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
		$a = curl_exec($ch);
		echo $a;
	}


function recognizeVoice($_MESS = array())
	{
		if(isset($_MESS['voice']))
			{
				$_VOICE = $_MESS['voice'];				
				$ch = curl_init(API_URL.'getFile');
				$toSend = array('file_id' => $_VOICE['file_id']);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
				curl_setopt($ch, CURLOPT_TIMEOUT, 10);
				curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($toSend));
				curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
				$_tmp = curl_exec($ch);
				$_tmp = json_decode($_tmp, true);
				$file_path = $_tmp['result']['file_path'];
				
				$url = "https://api.telegram.org/file/bot".BOT_TOKEN."/".$file_path;
				$f = file_get_contents($url);
				# раскомментировать код ниже, если хочется, чтобы все складывалось в voice локально
				/*$fop = fopen('/path/to/bot/data/voice/'.$_VOICE['file_id'].'.ogg', 'w');
				flock($fop, LOCK_EX);
				fputs($fop, $f);
				flock($fop, LOCK_UN);
				fclose($fop);*/
								
				$ch = curl_init('http://asr.yandex.net/asr_xml?uuid='.md5(rand(1,9)).'&key='.SPEECHKIT_TOKEN.'&topic=queries&lang=ru-RU');
				curl_setopt($ch, CURLOPT_POST, true);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $f);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
				curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: audio/ogg;codecs=opus'));
				curl_setopt($ch, CURLOPT_VERBOSE, true);
				$a = curl_exec($ch);
				
				$rr = simplexml_load_string($a);
				$success = $rr->attributes()->success;

				if($success == 1)
					{
						$speech = $rr->variant[0]->__toString();
					}
				else
					{
						$speech = false;
					}
			}
		else
			{
				$speech = false;
			}
		return $speech;
	}
	
function addNull($str = '0')
	{
		if(mb_strlen($str, 'utf-8') == 1)
			{
				$out = '0'.$str;
			}
		else
			{
				$out = $str;
			}
		return $out;
	}