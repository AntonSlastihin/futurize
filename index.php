<?php
session_start();
require "bootstrap.php";

?>
<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Document</title>
</head>
<body>
	<style>
		.movie{
			border: 1px solid #ccc;
			padding: 20px;
			overflow: auto;
		}
		.movie + .movie{
			margin-top: 10px;
		}
		.movie > img{
			width: 50px;
			float: left;
			display: block;
			margin-right: 20px;
		}
		.content > *{
			margin: 0;
		}
		.rating{
			cursor: pointer;
			position: relative;
			display: inline-flex;
		}
		.rating div.active{
			color: orange;
		}
		.inside{
			position: absolute;
			left: 0;
			top: 0;
			height: 100%;
			/*width: 0%;*/
			width: 0%;
			display: flex;
			color: gold;
			overflow: hidden;
		}
		
	</style>

	<?php if (isset($_SESSION['username']) && ($user = getInfoUser($_SESSION['username']))): ?>
	Добро пожаловать!, <?= $user['name'] ?>
	<br>
	<a href="destroy.php">Выйти</a>
<?php endif ?>

<?php foreach ($movies as $movie): ?>
	<div class="movie" data-id="<?= $movie['id'] ?>">
		<img src="<?= $movie['image'] ?>" alt="">
		<div class="content">
			<h3><?= $movie['name'] ?></h3>
			<p><?= $movie['author'] ?></p>
			<div class="rating">
				<div>&#9733;</div>
				<div>&#9733;</div>
				<div>&#9733;</div>
				<div>&#9733;</div>
				<div>&#9733;</div>
				<div class="inside" style="width: <?= getRatingMovie($movie['id']) ?>%">
					<div>&#9733;</div>
					<div>&#9733;</div>
					<div>&#9733;</div>
					<div>&#9733;</div>
					<div>&#9733;</div>
				</div>
			</div>
		</div>
	</div>
<?php endforeach ?>


<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
	

	$('.rating').on('mousemove', function(e){
		$(this).children('.inside').hide();
		var index = $(e.target).index();
		var block = $(this).children('div').not('.inside');
		$(block).css("color", "black");
		for (var i = 0; i <= index; i++) {
			$(block).eq(i).css("color", "gold");
		}

	});
	$('.rating').on('mouseleave', function(e){
		var block = $(this).children('div').not('.inside');
		$(block).css('color', 'black');
		$(this).children('.inside').show();
	});
	$('.rating > div').on('click', function(e){
		var mark = $(this).index() + 1;
		var id_movie = $(this).parents('.movie').attr('data-id');
		var inside = $(this).parent().children('.inside');
		$.ajax({
			url: "handler.php",
			method: "POST",
			data: {id: id_movie, rating: mark},
			error: function(data, status, error){
				if (error == "Unauthorized"){
					alert('Необходимо войти, чтобы поставить оценку');
				} else if (error == "Internal Server Error"){
					alert('Ошибка');
				} else if (error == "Conflict"){
					alert('Вы уже оставляли оценку');
				}
			},
			success: function(data){
				$(inside).width(data + "%");
			}
		});
	});

</script>
</body>
</html>