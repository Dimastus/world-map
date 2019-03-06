<?php

	echo '
		<link rel="stylesheet" type="text/css" href="/css/bootstrap/css/bootstrap.min.css">
		<link rel="stylesheet" type="text/css" href="/css/bootstrap/css/signin.css">';

	//mysql_set_charset('utf8');
	// echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />";

	$connection = new mysqli($db_hostname, $db_username, $db_password, $db_database);
	$connection->set_charset("utf8");
    if(!$connection) die("<div class='alert alert-danger'>Ошибка подключения к БД: " . $connection -> error . "</div>");