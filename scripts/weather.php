<?php

if(isset($_MESS['location']))
	{
		$lat = $_MESS['location']['latitude'];
		$lon = $_MESS['location']['longitude'];
		$appid = 'f15182ef9cb64768a8e3c14de3e61ffe'; // ключ openweather. можно оставить мой ;)
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "http://api.openweathermap.org/data/2.5/weather?lat=".$lat."&lon=".$lon."&appid=".$appid."&units=metric&lang=ru");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);

		$response = curl_exec($ch);
		curl_close($ch);  

		$response = json_decode($response, true);
		$weather   =  $response['weather'][0]['main'];
		$description = $response['weather'][0]['description'];
		$icon    = $response['weather'][0]['icon'];
		$temp    =  $response['main']['temp'];
		$pressure  = $response['main']['pressure'];
		$humidity  = $response['main']['humidity'];
		$temp_min  = $response['main']['temp_min'];
		$temp_max  = $response['main']['temp_max'];
		$speed   = $response['wind']['speed'];
		$name    = $response['name'];

		$resultweather = "[".$name."]\nПогода: *".$description."*\nТемпература: *".round($temp)."*ᵒC\nВлажность: *".$humidity."*%\nВетер: *".$speed."*м/с";
		
		sendMessage($_CHAT['id'], $resultweather, 'Markdown');
	}