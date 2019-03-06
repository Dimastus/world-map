<?php  	
	session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>	
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<link rel="icon" href="../img/image/wrench.ico">
	<title>NEW ADMIN</title>
	<!-- Подключение Bootstrap -->
	<link rel="stylesheet" type="text/css" href="../css/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="../css/bootstrap/css/signin.css">

	<script>
		//script with name of the country
		/*function universe__function(i){
			jQuery.noConflict();
			jQuery(function(){
				var $ = jQuery;
				$.ajax({
					url: "catalog/select_data.php",
					type: 'POST',
					data: {code: i},
					async: false,
					success: function(html){
						$(".info-box").html(html);
					}
				});
			});
		};*/

		//script for form added country
		/*function requestData(i){
			jQuery.noConflict();
			jQuery(function(){
				var $ = jQuery;
				$.ajax({
					url: "catalog/" + i,
					type: 'POST',
					//data: {code: i},
					async: false,
					success: function(html){
						$(".info-box").html(html);
					}
				});
			});
		};*/

		//script for modal window in file select_data.php
		function sendData(fileName, countryName, whatThis, whatThisId, way){
			jQuery.noConflict();
			jQuery(function(){
				var $ = jQuery;
				$.ajax({
					url: "catalog/" + fileName,
					type: 'POST',
					data: {
						nameCountry: countryName,
						what_this: whatThis,
						what_this_id: whatThisId					
					},
					async: false,
					success: function(html){
						$("." + way).html(html);
					}
				});
			});
		};
	</script>
</head>
<body>
	<?php

		mb_internal_encoding("UTF-8");
		if ($_SESSION['login'] == ''){
			echo <<<_END
			<div class="container-fluid">
				<form action="../php-script/testreg.php" method="post" class="form-signin">
					<h2 class="form-signin-heading">Панель администратора</h2>
					<p>
						<label for="inputLogin" class="sr-only">Ваш логин:</label>
						<input id="inputLogin" name="login" type="text" size="15" maxlength="15" placeholder="Login" class="form-control" required="required">
					</p>
					<p>
						<label for="inputPassword" class="sr-only">Ваш пароль:</label>
						<input id="inputPassword" name="password" type="password" size="15" maxlength="15" placeholder="Password" class="form-control" required="required">
					</p>
					<p>
						<input type="submit" name="submit" value="Войти" class="btn btn-lg btn-primary btn-block">
						<br>
						<a href="../php-script/registration.php" class="btn btn-lg btn-danger btn-block">Зарегистрироваться</a>
						<br>
						<a href="/" class="btn btn-sm btn-secondary btn-block" target="_blank">Перейти на сайт</a>
					</p>
				</form>
			</div>
_END;
		}
		else {  

			if( $_SESSION['login'] == true && ($_SESSION['id_role'] == 1 || $_SESSION['id_role'] == 2) ) {

				$way = "../php-script/";	//часть пути для подключения файлов

				require_once $way . 'data_to_db.php';
				require_once $way . 'connect_to_db.php';
				include_once 'catalog/function.php';

				$query = "SELECT * FROM table_users WHERE login='" . $_SESSION['login'] . "'";			
				$result = $connection -> query($query); //извлекаем из базы все данные о пользователе с введенным логином
				
				if(!$result) die ("<div class='alert alert-danger'>Сбой при доступе к БД: " . $connection -> error . "</div>");
				$myrow = $result -> fetch_array(MYSQLI_ASSOC);

				$editPassword = '"modal_window.php"';
				$wayEditPassword = '"editPassword-modal-box"';
				$whatThisPassword = '"password"';

				echo "<div class='row bg-dark m-0 py-3'>
						<div class='col-10 d-flex justify-content-center align-items-center p-0 text-center'>
							<a class='display-4 text-uppercase font-weight-bold text-white ml-5' href='new_admin.php'>Панель администратора</a>
						</div>
						<div class='col-2 d-flex justify-content-end p-0 pr-sm-3'>
							<div class='text-white'>
								<span class='navbar-text'>
									Добро пожаловать, <strong class='text-white'>" . $myrow['login'] . "</strong>
								</span>
								<div class='d-flex'>
									<form action='#' method='post' class='form-inline'>
										<input type='button' name='editPassword' class='form-inline btn btn-warning btn-sm' value='Изменить пароль' onclick='sendData(" . $editPassword . ", null, " . $whatThisPassword . ", null, " . $wayEditPassword . ")'  data-toggle='modal' data-target='#modalEditPassword'>
									</form>
									<form action='../php-script/session_destroy.php' method='post' class='form-inline ml-2'>
										<input type='submit' name='destroy' class='btn btn-warning btn-sm' value='Выход'>
									</form>
								</div>
							</div>
						</div>
					</div>";

				/* дерево стран, принадлежащие определенному пользователю */
				$query_continent = "SELECT `table_country`.`continent_country` FROM `table_country` JOIN `table_for_tc-tu` ft1  ON ft1.`id_table_country` = `table_country`.`id_country` JOIN `table_users` t1  ON t1.`id` = ft1.`id_table_users` JOIN `table_for_tc-tu` ft2  ON ft2.`id_table_country` = `table_country`.`id_country` JOIN `table_users` t2  ON t2.`id` = ft2.`id_table_users` AND t2.`login` = '" . $myrow['login'] . "' GROUP BY `table_country`.`continent_country`";
				$result_continent = $connection -> query($query_continent);
				if(!$result_continent) die("<div class='alert alert-danger'>Сбой при доступе к БД: " . $connection -> error . "</div>");
				else {
						echo '<div class="row m-0">
								<div class="col-3 col-sm-3 col-md-3 col-lg-3 col-xl-2 bg-dark">
									<div class="dropdown-divider"></div>
									<ul class="nav flex-column text-white px-2">';
						    		$rows_continent = $result_continent -> num_rows;
									for($n = 0; $n < $rows_continent; ++$n){
								        $result_continent -> data_seek($n);
								        $row_continent = $result_continent -> fetch_array(MYSQLI_NUM);//получение отдельной строки таблицы
								        // print_r($row_continent);
								        echo "<li class='font-weight-bold'><button class='btn btn-secondary btn-sm btn-block my-1' type='button' data-toggle='collapse' data-target='#multiCollapseEx$n' aria-expanded='false' aria-controls='multiCollapseEx$n'>" . $row_continent[0] . "</button></li><ol>";
										$query_country = "SELECT `table_country`.`name_country` FROM `table_country` JOIN `table_for_tc-tu` ft1  ON ft1.`id_table_country` = `table_country`.`id_country` JOIN `table_users` t1  ON t1.`id` = ft1.`id_table_users` JOIN `table_for_tc-tu` ft2  ON ft2.`id_table_country` = `table_country`.`id_country` JOIN `table_users` t2  ON t2.`id` = ft2.`id_table_users` AND t2.`login` = '" . $myrow['login'] . "' AND `table_country`.`continent_country` = '" . $row_continent[0] . "' GROUP BY `table_country`.`name_country`";
								        $result_country = $connection -> query($query_country);
								        if(!$result_country) die("<div class='alert alert-danger'>Сбой при доступе к БД: " . $connection -> error . "</div>");
								        else {
								        	echo "<div class='collapse multi-collapse' id='multiCollapseEx$n'>";
								        	$rows_country = $result_country -> num_rows;
								        	for($m=0; $m < $rows_country; $m++){ 
								        		$result_country -> data_seek($m);
								        		$row_country = $result_country -> fetch_array(MYSQLI_NUM);
								        		// print_r($row_country);

								        		$whenSend = "'select_data.php'";
								        		$nameCountry = "'$row_country[0]'";
								        		$waySendNameCountry = "'info-box'";

								        		echo '<li class="my-2 table__li_hover pl-1" onclick="sendData(' . $whenSend . ', ' . $nameCountry . ', null, null, ' . $waySendNameCountry . ')"><span>' . $row_country[0] . '</span></li>';
								        	}
								        }
								        echo "</div></ol>";
								    }

								    $addCountry = "'addform.php'";
								    $wayAddCountry = "'info-box'";

								    echo '
								    </ul>
								    <p>
									    <input type="button" name="addCountry" value="Добавить страну" class="btn btn-md btn-primary btn-block mt-5" onclick="sendData(' . $addCountry . ', null, null, null, ' . $wayAddCountry . ')">
								    </p>';

								    if($myrow['id_role'] == 1){

									    $tableCountry = "'table_users.php'";
									    $wayTableCountry = "'info-box'";

									    echo '
									    <p>
										    <input type="submit" name="tableUsers" value="Таблица пользователей" class="btn btn-md btn-primary btn-block mt-1" onclick="sendData(' . $tableCountry . ', null, null, null, ' . $wayTableCountry . ')">
									    </p>';
									}

					    echo '</div>
						      <div class="col-9 col-sm-9 col-md-9 col-lg-9 col-xl-10 p-0 d-flex justify-content-start">							    
									<div class="info-box container-fluid p-0"></div>
							  </div>
						</div>';

				}

				echo '<!-- Modal -->
				<div class="modal fade" id="modalEditPassword" tabindex="-1" role="dialog" aria-labelledby="modalEditPasswordLabel" aria-hidden="true">
					<div class="modal-dialog modal-dialog-centered" role="document">
						<div class="modal-content">
							<div class="modal-header bg-secondary p-1 pl-3 m-0">
								<h5 class="modal-title text-warning font-weight-bold" id="modalEditPasswordLabel">Окно редактирования</h5>
								<button type="button" class="close m-0 p-0 mr-1" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true" class="text-white m-0 p-0">&times;</span>
								</button>
							</div>
							<div class="modal-body">
								<div class="editPassword-modal-box"></div>
							</div>
							<div class="modal-footer bg-secondary p-1 m-0">
								<button type="button" class="btn btn-danger btn-sm font-weight-bold" data-dismiss="modal">Закрыть</button>
							</div>
						</div>
					</div>
				</div>';

			}
			else {
				
				echo "
					<div class='alert alert-danger'>У Вас нет доступа к информации. Обратитесь к администратору по телефону <strong>(411) 13-02</strong>, либо пришлите письмо на адрес <strong>sham@givc.vs.mil.by</strong> с объяснением для чего Вам нужен доступ к панели администратора.
					</div>
					<form action='/php-script/session_destroy.php' method='post' class='form-inline'>
						<input type='submit' name='destroy' class='btn btn-dark btn-lg m-5' value='Выход'>
					</form>";
			  
			}
		}
	
	?>		
  <script type="text/javascript" src="../css/bootstrap/js/jquery.min.js"></script>
  <script type="text/javascript" src="../css/bootstrap/js/bootstrap.min.js"></script>
  <script type="text/javascript" src="../css/bootstrap/js/popper.min.js"></script>
</body>
</html>