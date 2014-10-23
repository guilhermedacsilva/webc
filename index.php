<?php
require_once "include_me.php";
tryLogin();
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Web C</title>
		<meta charset="UTF-8">
		<link rel="stylesheet" href="css/bootstrap.min.css">
		<link rel="stylesheet" href="css/signin.css">
		<script src="js/bootstrap.min.js"></script>
	</head>
	<body>
		<div class="container">
			<form class="form-signin" role="form" method="post">
				<h2 class="form-signin-heading">Entrar no Web C</h2>
				<input name="ra" type="number" class="form-control" placeholder="RA" min="999999" max="2000000" autofocus required>
				<input name="name" type="text" class="form-control" placeholder="Nome" required>
				<br>
				<button class="btn btn-lg btn-primary btn-block" type="submit">Entrar</button>
			</form>
		</div>
	</body>
</html>
