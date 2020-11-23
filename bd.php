<?php

function get_connect(){
	$db = new PDO("mysql:host=localhost; dbname=bd", "root", "mamochka71");
	$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
	return $db;
}
function getInfoUser(string $username){
	$db = get_connect();
	$stmt = $db->query("SELECT * FROM users WHERE login = '$username'")->fetch();
	return $stmt;
}
function getAllMovies(){
	$db = get_connect();
	$stmt = $db->query("SELECT * FROM movies");
	return $stmt->fetchAll();
}

function isRegisterUser(string $user, string $password){
	$db = get_connect();
	$stmt = $db->query("SELECT * FROM users WHERE login = '$user'")->fetch();
	if ($stmt){
		$get_pass = $stmt['password'];
		if (password_verify($password, $get_pass)){
			return true;
		}
	}
	return false;
}

function getRatingMovie(int $movie_id){
	$query = "SELECT avg(rating) total FROM 
	rating WHERE id_movie = '$movie_id'";
	$db = get_connect();
	$stmt = $db->query($query)->fetch();
	if (!empty($stmt)){
		return ($stmt['total'] / 5) * 100;
	}
	return 0;
}

function writeRating(array $data){
	$query = "INSERT INTO rating (id_user, id_movie, rating) VALUES (?, ?, ?)";
	$db = get_connect();
	$stmt = $db->prepare($query);
	$stmt = $stmt->execute($data);
	return $stmt;
}
function isUserThere($id_user, $id_movie){
	$query = "SELECT * FROM rating WHERE 
	id_movie = $id_movie AND id_user = $id_user";
	$db = get_connect();
	$stmt = $db->query($query)->fetchAll();
	if (count($stmt) > 0){
		return false;
	}
	return true;
}

?>