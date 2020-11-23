<?php
session_start();
header('ContentType: application/json');
require "bootstrap.php";
if (isset($_SESSION['username'])){
	$id_user = getInfoUser($_SESSION['username'])['id'];
	$id_movie = $_POST['id'];
	$rating = $_POST['rating'];
		//int $user_id, int $movie_id, int $rating
	if (isUserThere($id_user, $id_movie)){
		if (writeRating([$id_user, $id_movie, $rating])){
			echo getRatingMovie($id_movie);
			exit();
		}
		http_response_code(500); // ошибка сервера
		exit();
	}
	http_response_code(409); //конфликт
	exit();
}

http_response_code(401); // пользователь не авторизован
?>