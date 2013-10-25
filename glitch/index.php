<html>
 <head>
  <title>Glitch</title>
 </head>
<body>
<?php
	function glitch($img) 
  {
 //  header("Content-Type: image/jpg");
/* Так как в процессе теста данной функции последний член массива всегда был некорректный(по непонятным причинам), 
мы добавляем еще 1 элемент. Он нам абсолютно не нужен. Он просто возьмет статус valid Image resource и канет в Лету. */
	$img[25] = $img[0];
	for ($j = 0; $j < 26 ; $j++){
		$im = imagecreatefromjpg($img[j]);
		$glim[$j] = imagecreate(100 , 100);
		$glim[$j] = $im;
		$Random = rand(3,8);
		
		for ($i = 0; $i < $Random ; $i++)
		{
		
			$gim = imagecreate(100 , 100);
			$Rh = rand(20 , 80);
			$Rw = rand(0 , 40);
			imagecopy($gim, $im, $Rw , 100-$Rh , 0 , 100-$Rh , 100-$Rw , $Rh);  // Берется кусочек изображения

			$G = rand(0 , 100);
			imagefilter($gim, IMG_FILTER_COLORIZE, 0, $G, 0);  // Немного изменяется и ->
			imagecopymerge($glim[$j] , $gim, $Rw, 100-$Rh, $Rw, 100-$Rh, 100-$Rw , $Rh, 80);  // Наносится водяной знак. На мой взгляд вполне глитч)
			imagedestroy($gim);
			
		}
	}
	imagedestroy($im);
/* Соберем все в одну большую картинку */
	$glitch = imagecreate(560 , 560);
	$background = imagecolorallocate($glitch , 255 , 255 , 255 );
	$black = imagecolorallocate($glitch, 0, 0, 0);
	for ($i = 4; $i >= 0; $i--){
		for ($j = 4; $j >=0; $j--){
			imagefilledrectangle($glitch , $i*114 , $j*114 , $i*114+103 , $j*114+103 , $black); // Строим рамки
			imagecopy($glitch , $glim[$i*5+$j], $i*114+2, $j*114+2, 0, 0, 100 , 100);  // Вставляем картинки в рамки
		}
	}

	imagegif( $glitch , './glitch.gif');
			for ($i = 0; $i < 3; $i++)
		{
			imagedestroy($glim[$i]);
		}
	imagedestroy($glitch);
	return('./glitch.gif');
  }
/* Данные, полученные при регистрации приложения */
	$client_id = '3943201'; //ID приложения
	$client_secret = 'l3C13V5jirZz6aOFMLLr'; // Ключ
	$redirect_uri = 'http://localhost/glitch'; // Собственно адрес нашего сайта

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
			
			'scope'			=> 'wall,photos',
			
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
			for($i = 0 ; $i < 25 ; $i++)
			{
				 $params = array(
					'uids'			=> $IdFriend[$i],
					'fields'		=> 'photo_100',
					'access_token'	=> $token['access_token']
				);
				$Foto[$i] = json_decode(file_get_contents(
				'https://api.vk.com/method/users.get'
				. '?' . urldecode(http_build_query($params))), true);
				$GFoto[$i] = $Foto[$i]['response'][0]['photo_100'];		
			}
						
			$Glitch = glitch($GFoto);  // Делаем из 25 обычных фото маленький коллаж
			echo '<img src="' . $Glitch . '"/>';
		}
	}
	
	else{
		
/* Если code нет, то получаем его */

		$Hello = imagecreate(155 , 50);
		$background = imagecolorallocate($Hello , 0 , 50 , 0 );
		$col = imagecolorallocate($Hello, 255, 255, 255);
		imagefttext ( $Hello , 30 , 0 , 5 , 40 , $col , './arial.ttf' , 'GLITCH' );
		imagepng($Hello, '1.png');
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
