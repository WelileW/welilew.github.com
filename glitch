<html>
 <head>
  <title>Glitch</title>
 </head>
<body>
<?php
	function glitch($img , $n) 
  {
/* Так как, к сожелению, почему-то Zend не хочет работать с jpg(а именно в нем приходят изображенияиз vk),
 приходится вынужденно сохранять на сервер фотографии. Таким образом мы переводим их из jpg в gif, родной библеотеке gd */
 //   header("Content-Type: image/gif");
	copy($img , './' . $n . '.gif');
	$im = imagecreatefromgif('./' . $n . '.gif');
	
	$Rh = rand(3,8);
	for ($i = 0; $i < $Rh ; $i++)
	{
		$RH = rand(20,80);
		$Rw = rand(0,50);
		imagecopymerge($im, $im, $Rw , $RH , 0 , $RH , 100-$Rw , $RH , 10);
	}
	imagegif( $im , './' . $n . '.gif');
	return('./' . $n . '.gif');
  }
/* Данные, полученные при регистрации приложения */
	$client_id = '3943201'; //ID приложения
	$client_secret = 'l3C13V5jirZz6aOFMLLr'; // Ключ
	$redirect_uri = 'http://localhost/glitch/em'; // Собственно адрес нашего сайта

/* Формируем данные для запроса vk */
	$url = 'http://oauth.vk.com/authorize';
	
	$params = array(
		'client_id'     => $client_id,
		'redirect_uri'  => $redirect_uri,
		'response_type' => 'code'
	);

/* Есть ли у нас code? */
	
	if (isset($_GET['code'])){
		$params = array(
			'client_id'		=> $client_id,
			'client_secret'	=> $client_secret,
			'code'			=> $_GET['code'],
			'display'		=> 'page',
			'redirect_uri'	=> $redirect_uri
		);
		
		$token = json_decode(file_get_contents(		// Делаем запрос token. Без него нельзя получить информацию с сервера.
		'https://oauth.vk.com/access_token' . '?' . urldecode(http_build_query($params))
		), true);
	 
	/* Token получили? */	
		if (isset($token['access_token'])){
			$a = time();
			$b = (string) $a;
			$params = array(
				'uids'			=> $token['user_id'],
				'count'			=> '25',
				'access_token'	=> $token['access_token']
			);
			
			$IdFriends = json_decode(file_get_contents(  // Запрос Id 25-ти друзей нашего пользователя(user_id)
			'https://api.vk.com/method/friends.get' . 
			'?' . urldecode(http_build_query($params))), 
			true);
			$IdFriend = $IdFriends['response'];
	
			
	/* Перебор друзей, создание массива с их фотографиями */
			for($i = 0 ; $i < 25 ; $i++){
				 $params = array(
					'uids'			=> $IdFriend[$i],
					'fields'		=> 'photo_100',
					'access_token'	=> $token['access_token']
				);
				$Foto[$i] = json_decode(file_get_contents(
				'https://api.vk.com/method/users.get'
				. '?' . urldecode(http_build_query($params))), true);
				$GFoto[$i] = $Foto[$i]['response'][0][$n];
				$GFoto[$i] = glitch($GFoto[$i] , $i);
				
			}
	/* Оформление картинок в виде плитки по 5 штук в строке и столбце */
			
			for($i = 0; $i < 5 ; $i++){
				for($j = 0; $j < 5 ; $j++){
					$c = $i*5+$j;
					echo '<img src="' . $GFoto[$c] . '" 
					hspace="4" vspace="20" border="2"/>';
				}
				echo '<br>';
			}
		//echo '<img src="' . $Foto[0]['response'][0]["photo_100"] . '" />';

		}
	}
	
	
	else{

		
/* Если code нет, то получаем его */
		echo '<p align="center">';
		echo $link = '<a 
		href="' . $url . '?' . urldecode(http_build_query($params)) . '"
		><img src="1.png" vspace="400"></a>';
		echo '</p>';
// В результате мы получили code:  - он нужен, чтобы получить token
//  http://localhost/glitch?code=480d0308c272cbe4e0

	}
	
	
	
	

?>
</body>
</html>
