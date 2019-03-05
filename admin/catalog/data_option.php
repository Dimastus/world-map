<?php	

	session_start();

	if($_SESSION['login'] == '') {

		echo ' 
			<link rel="stylesheet" type="text/css" href="../../css/bootstrap/css/bootstrap.min.css">
			<link rel="stylesheet" type="text/css" href="../../css/bootstrap/css/signin.css">';

		echo "<div class=\"alert alert-danger\">Вы не должны быть здесь!</div>";
		echo '<script>setTimeout(\'location="../new_admin.php"\', 2000)</script>';

	}
	elseif ($_SESSION['login'] == true && ($_SESSION['id_role'] == 1 || $_SESSION['id_role'] == 2)) {

		//$query_block = "SELECT" . $arg1 . " FROM " . $arg2;
		$res_block = $connection -> query($query_block);
		if(!$res_block)
			die("<div class='alert alert-danger'>Сбой при доступе к БД: " . $connection -> error . "</div>");
		else {
			$rows_block = $res_block -> num_rows;
			//print_r($rows_cont);
			for($i = 0; $i < $rows_block; ++$i){
			  $res_block -> data_seek($i);
			  $row_block = $res_block -> fetch_array(MYSQLI_NUM);
			  echo "<option>" . $row_block[0] . "</option>";
			}
		}

	}
	else {

		echo ' 
			<link rel="stylesheet" type="text/css" href="../../css/bootstrap/css/bootstrap.min.css">
			<link rel="stylesheet" type="text/css" href="../../css/bootstrap/css/signin.css">';

		echo "
			<div class='alert alert-danger'>У Вас нет доступа к информации. Обратитесь к администратору по телефону <strong>(411) 13-02</strong>, либо пришлите письмо на адрес <strong>sham@givc.vs.mil.by</strong> с объяснением для чего Вам нужен доступ к панели администратора.
			</div>
			<form action='/php-script/session_destroy.php' method='post' class='form-inline'>
				<input type='submit' name='destroy' class='btn btn-dark btn-lg m-5' value='Выход'>
			</form>";
	
	}