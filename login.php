<?php
session_start();
require "bd.php";
if (!isset($_SESSION['username'])){
	if ($_SERVER['REQUEST_METHOD'] == "POST"){
		list($user, $password) = clear($_POST);
		$str = [];

		if (strlen($user) == 0 || strlen($user) <= 5){
			$str[] = "Имя пользователя должно быть не меньше 6 букв";
		} elseif (preg_match('/[^a-zA-Z0-9._]/', $user)){
			$str[] = "Имя пользователя должно быть a-zA-Z0-9";
		}

		if (strlen($password) == 0 || strlen($password) <= 5){
			$str[] = "Пароль должно быть не меньше 6 букв";
		} elseif (preg_match('/[^a-zA-Z0-9._]/', $password)){
			$str[] = "Пароль должно быть a-zA-Z0-9";
		}

		if ($str){
			echo "<ul><li>".implode("</li><li>", $str)."</li></ul>";
		} else {
			if (isRegisterUser($user, $password)){
				$_SESSION['username'] = $user;
				header('location: index.php');
			} else{
				echo "<ul><li>Не верный имя пользователя или пароль</li></ul>";
			}
		}
	}
} else {
	header('location: index.php');
}


function clear(array $data):array{
	$array = array();
	foreach ($data as $key => $value) {
		if ($key == "login" || $key = "password"){
			$array[] = $data[$key] = htmlentities(trim($value));
		}
	}	
	return $array;
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Document</title>
</head>
<body>
	<form action="<?= $_SERVER['PHP_SELF'] ?>" method="POST">
		<input type="text" placeholder="login" name="login" value="<?= $_POST['login'] ?? '' ?>"><br>
		<input type="password" placeholder="password" name="password" value="<?= $_POST['password'] ?? '' ?>"></br>
		<input type="submit" value="Log In">
	</form>
</body>
</html>