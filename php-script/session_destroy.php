<?php
	session_start();
	$_SESSION = array();
	setcookie(session_name(), '', time() - 25900, '/');
	session_destroy();

	echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />";

	echo "<!-- Подключение Bootstrap -->
	<link rel=\"stylesheet\" type=\"text/css\" href=\"../css/bootstrap/css/bootstrap.min.css\">
	<link rel=\"stylesheet\" type=\"text/css\" href=\"../css/bootstrap/css/signin.css\">
	<script type=\"text/javascript\" src=\"../css/bootstrap/js/jquery.min.js\"></script>
	<script type=\"text/javascript\" src=\"../css/bootstrap/js/bootstrap.min.js\"></script>
	<script type=\"text/javascript\" src=\"../css/bootstrap/js/popper.min.js\"></script>";

	echo "<h2 class='alert alert-danger'>Вы завершили сеанс.</h2><div class='alert alert-warning'>Через несколько секунд Вы будете автоматически перенаправлены на страницу авторизации. Если этого не произошло нажмите <a href='admin/index.php'>здесь</a></div>" ;
	echo '<script>setTimeout(\'location="../admin/index.php"\',3000)</script>';//автоматическое перенаправление на страницу панели админа
?>