<?php

# Выделяем токен и url апи
define('BOT_TOKEN', 'Bot TOKEN');
define('API_URL', 'https://api.telegram.org/bot'.BOT_TOKEN.'/');
define('WEBHOOK', 'https://test.ru/bot/process.php'); // Вебхук
define('ADMIN', 'D13410N3'); // nickname админа
define('FLOOD', 10); 

define('TRNSLT', ''); // когда-то тут был ключ переводчика

@mysql_connect(':/var/run/mysqld/mysqld.sock','root','bdpass') or die(mysql_error());
@mysql_select_db('tg_bot') or die(mysql_error());
mysql_set_charset('utf8');
# Функции и вот это всё

# простая отправка сообщения
function sendMessage($id_chat, $text, $mark = '', $id_message = '')
	{
		// $text = empty($text) ? 'undef or empty var' : $text;
		$toSend = array('method' => 'sendMessage', 'chat_id' => $id_chat, 'text' => $text);
		isset($id_message) ? $toSend['reply_to_id_message'] = $id_message : '';
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


# Отправка картинки
function sendImage($id_chat, $path, $local = true, $caption = '', $message_id = '')
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
				
		
		$toSend = array('method' => 'sendPhoto', 'photo' => '@'.realpath($path), 'chat_id' => $id_chat);
		!empty($caption) ? $toSend['caption'] = $caption : '';
		!empty($message_id) ? $toSend['reply_to_message_id'] = $message_id : '';
		
		$ch = curl_init(API_URL);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
		curl_setopt($ch, CURLOPT_POSTFIELDS, $toSend);
		curl_exec($ch);
	}

# отправка файла
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
				
		
		$toSend = array('method' => 'sendDocument', 'document' => '@'.realpath($path), 'chat_id' => $id_chat);
		!empty($caption) ? $toSend['caption'] = $caption : '';
		!empty($message_id) ? $toSend['reply_to_message_id'] = $message_id : '';
		
		$ch = curl_init(API_URL);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
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
				# пишем сообщение
				$_VOICE = $_MESS['voice'];
				$_tmp = apiRequest('getFile', array('file_id' => $_VOICE['file_id']));
				$file_path = $_tmp['file_path'];
				
				$url = "https://api.telegram.org/file/bot".BOT_TOKEN."/".$file_path;
				$f = file_get_contents($url);

				
				$ch = curl_init('http://asr.yandex.net/asr_xml?uuid='.md5(rand(1,9)).'&key=YANDEX SPEECH KIT KEY&topic=queries&lang=ru-RU'); // укажите ключ, если будете через него войс распознавать
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
	
# даже не думайте, зачем это нужно. Просто Это Нужно

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
# simple api request

function exec_curl_request($handle) {
  $response = curl_exec($handle);

  if ($response === false) {
    $errno = curl_errno($handle);
    $error = curl_error($handle);
    error_log("Curl returned error $errno: $error\n");
    curl_close($handle);
    return false;
  }

  $http_code = intval(curl_getinfo($handle, CURLINFO_HTTP_CODE));
  curl_close($handle);

  if ($http_code >= 500) {
    // do not wat to DDOS server if something goes wrong
    sleep(10);
    return false;
  } else if ($http_code != 200) {
    $response = json_decode($response, true);
    error_log("Request has failed with error {$response['error_code']}: {$response['description']}\n");
    if ($http_code == 401) {
      throw new Exception('Invalid access token provided');
    }
    return false;
  } else {
    $response = json_decode($response, true);
    if (isset($response['description'])) {
      error_log("Request was successfull: {$response['description']}\n");
    }
    $response = $response['result'];
  }

  return $response;
}
function apiRequest($method, $parameters) {
  if (!is_string($method)) {
    error_log("Method name must be a string\n");
    return false;
  }

  if (!$parameters) {
    $parameters = array();
  } else if (!is_array($parameters)) {
    error_log("Parameters must be an array\n");
    return false;
  }

  foreach ($parameters as $key => &$val) {
    // encoding to JSON array parameters, for example reply_markup
    if (!is_numeric($val) && !is_string($val)) {
      $val = json_encode($val);
    }
  }
  $url = API_URL.$method.'?'.http_build_query($parameters);

  $handle = curl_init($url);
  curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 5);
  curl_setopt($handle, CURLOPT_TIMEOUT, 60);

  return exec_curl_request($handle);
}