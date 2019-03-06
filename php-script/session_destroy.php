<?php

	session_start();
	$_SESSION = array();
	setcookie(session_name(), '', time() - 25900, '/');
	session_destroy();

	//echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />";

	echo "<!-- Подключение Bootstrap -->
	<link rel=\"stylesheet\" type=\"text/css\" href=\"../css/bootstrap/css/bootstrap.min.css\">
	<link rel=\"stylesheet\" type=\"text/css\" href=\"../css/bootstrap/css/signin.css\">";

	echo "<h2 class='alert alert-danger'>Вы завершили сеанс.</h2><div class='alert alert-warning'>Через несколько секунд Вы будете автоматически перенаправлены на страницу авторизации. Если этого не произошло нажмите <a href='admin/new_admin.php'>здесь</a></div>" ;
	echo '<script>setTimeout(\'location="../admin/new_admin.php"\',3000)</script>';
	
?>