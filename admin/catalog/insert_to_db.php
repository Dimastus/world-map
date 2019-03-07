<?php

	session_start();

	if ($_SESSION['login'] == '') {

		echo ' 
			<link rel="stylesheet" type="text/css" href="/css/bootstrap/css/bootstrap.min.css">
			<link rel="stylesheet" type="text/css" href="/css/bootstrap/css/signin.css">';

		echo "<div class=\"alert alert-danger\">Вы не должны быть здесь!</div>";
		echo '<script>setTimeout(\'location="../new_admin.php"\', 2000)</script>';

	}
	elseif ( $_SESSION['login'] == true && ($_SESSION['id_role'] == 1 || $_SESSION['id_role'] == 2) ) {

		echo ' 
			<link rel="stylesheet" type="text/css" href="/css/bootstrap/css/bootstrap.min.css">
			<link rel="stylesheet" type="text/css" href="/css/bootstrap/css/signin.css">';

		$way = "../../php-script/";	//часть пути для подключения файлов

		// подключаемся к базе
		require_once $way . 'data_to_db.php';
		require_once $way . 'connect_to_db.php';
		/*подключить файл с функциями*/
		require_once 'function.php';

		//вставка данных о стране и должностных лицах
		if ( isset($_POST['insertData']) ) {
			
			//данные по стране
			$name_country = defend(my_ucfirst(mb_strtolower($_POST['name_country'])));
			$index_country = defend(mb_strtoupper($_POST['index_country']));
			$continent_country = defend($_POST['continent_country']);
			$accessory_block = defend($_POST['accessory_block']);
			$info_about_country = defend($_POST['info_about_country']);

			if(isset($_FILES['search_flag'])) {
				// проверяем, можно ли загружать изображение
				$check = can_upload($_FILES['search_flag']);

				if($check === true){
					// загружаем изображение на сервер
					$full_name = make_upload($_FILES['search_flag']);
					$flag_country =  $full_name ;
					echo "<div class='alert alert-success'><strong>Файл успешно загружен!</strong></div>";
				}
				else{
					// выводим сообщение об ошибке
					echo "<div class='alert alert-danger'><strong>$check</strong></div>";
				}
	        }

			//запрос для добавления страны и данных о ней
			$query_insert_country = "INSERT INTO table_country VALUES (NULL ,'" . $name_country . "', '" . $index_country . "', '" . $continent_country . "', '" . $accessory_block . "', '" . $info_about_country . "', '" . $flag_country . "')";

	        if(!$result_insert_country = $connection -> query($query_insert_country)) die("Сбой при вставке данных: " . $connection -> error . "<br> Number error: " . $connection -> errno);

			//запрос для добавления данных в таблицу-связку table_for_tc-tu
			$table = "'table_for_tc-tu'";
	        $query_insert_data = "INSERT INTO `map_site`.`table_for_tc-tu` (`id`, `id_table_country`, `id_table_users`, `comment`) VALUES (NULL, (SELECT table_country.id_country FROM table_country WHERE table_country.name_country = '" . $name_country . "'), (SELECT table_users.id FROM table_users WHERE table_users.login = '" . $_SESSION['login'] . "'), NULL)";
			if(!$result_insert_data = $connection -> query($query_insert_data)) die ("Сбой при вставке данных: " . $connection -> error . "<br> Number error: " . $connection -> errno);
			//for admin duplicate data
	        $query_insert_data_admin = "INSERT INTO `map_site`.`table_for_tc-tu` (`id`, `id_table_country`, `id_table_users`, `comment`) VALUES (NULL, (SELECT table_country.id_country FROM table_country WHERE table_country.name_country = '" . $name_country . "'), (SELECT table_users.id FROM table_users WHERE table_users.id_role = '1'), NULL)";
			if(!$result_insert_data_admin = $connection -> query($query_insert_data_admin)) die ("Сбой при вставке данных: " . $connection -> error . "<br> Number error: " . $connection -> errno);

	        echo "<div class='alert alert-success'>Запись добавлена<br></div>";
	        echo "<script>setTimeout('location=\"../new_admin.php\"', 3000)</script>";

		}

		//обновление дынных о стране и должностных лицах
		/*if ( isset($_POST['updateData']) ) {

			//print_r($_POST);
		    //print_r($_FILES);
	        if($_FILES['flag_edit']['name'] == ''){
	            $flag_country = $_POST['flag_country'];
	        }
	        else {
	            //удаление старого изображения
	              if(!unlink("../../" . $_POST['flag_country'])){ //функция удаления
	                echo "/nОшибка удаления изображения";
	              }
	            // проверяем, можно ли загружать изображение
	            $check = can_upload($_FILES['flag_edit']);

	            if($check === true){
	              // загружаем изображение на сервер
	              $full_name_photo = make_upload($_FILES['flag_edit']);
	              $flag_country =  $full_name_photo ;
	              echo "<div class='alert alert-success'>Файл <strong>$full_name_photo</strong> успешно загружен!<br></div>";
	            }
	            else{
	              // выводим сообщение об ошибке
	              echo "<div class='alert alert-danger'><strong>$check</strong></div>";
	            }
	        }

	        $name_country_new = defend($_POST['name_country_new']);
	        $index_country = defend($_POST['index_country']);
	        $continent_country = defend($_POST['continent_country']);
	        $accessory_block = defend($_POST['accessory_block']);
	        $info_about_country = defend($_POST['info_about_country']);
	        $name_country_old = defend($_POST['name_country_old']);

	        $query_update_country = "UPDATE table_country SET name_country = '". $name_country_new .
	    								    "', index_country = '" . $index_country .
	    								    "', continent_country = '" . $continent_country .
	    								    "', accessory_block = '" . $accessory_block .
	    								    "', info_about_country = '" . $info_about_country .
	    								    "', flag_country = '" . $flag_country .
	        "' WHERE name_country = '" . $name_country_old . "'";

	        if(!$result_update_country = $connection -> query($query_update_country)) {
	        	echo "<div class='alert alert-danger'>Сбой при выборе данных: $query_update_country<br/>" . $connection -> error . "</div>";//если произошла ошибка, вывод сообщения
	        	//echo '<script>setTimeout(\'location="../admin/index.php"\', 2000)</script>';
	        }
	        else {
	        	echo "<div class='alert alert-success'>Запись изменена</div>";
	    	    //echo '<script>setTimeout(\'location="../admin/index.php"\', 2000)</script>';
	        }

			foreach ($_POST['user'] as $user_id => $user_data) {

				$position_person = defend($user_data['position_person']);
				$full_name = defend($user_data['full_name']);
				$reference_info = defend($user_data['reference_info']);
				$id_person = defend($user_data['id_person']);
				$old_foto = defend($user_data['old_foto']);
				// print_r($_FILES);

				if($_FILES["$id_person"]['name']['search_foto_edit'] == '') {
					$foto = defend($user_data['old_foto']);
					// echo "netu";
				}
				else {
					// echo $_FILES["$id_person"]['name']['search_foto_edit'];
					//удаление старого изображения 
					if(!unlink("../../" . $old_foto)){ //функция удаления
						echo "/nОшибка удаления изображения";
					}
					// проверяем, можно ли загружать изображение
					$check = can_upload_more($_FILES["$id_person"]);

					if($check === true){
						// загружаем изображение на сервер
						$full_name_photo = make_upload_more($_FILES["$id_person"]);
						$foto =  $full_name_photo ;
						echo "<div class='alert alert-success'>Файл <strong>$full_name_photo</strong> успешно загружен!</div>";
					}
					else {
						// выводим сообщение об ошибке
						echo "<strong class='alert alert-danger'>$check</strong>";
					}
				}

				$query_edit_person = "UPDATE table_persons SET position_person = '". $position_person .
															"', full_name = '" . $full_name .
															"', reference_info = '" . $reference_info .
															"', foto = '" . $foto .
															"' WHERE table_persons.id_country = (SELECT table_country.id_country FROM table_country WHERE table_country.name_country = '" . $name_country_old . "') AND id_position = '" . $id_person . "'";

				if(!$result_edit_person = $connection -> query($query_edit_person)) {
					echo "<div class='alert alert-danger'>Сбой при выборе данных: $query_edit_person<br/>" . $connection -> error . "<br></div>";
					//echo '<script>setTimeout(\'location="../admin/index.php"\', 5000)</script>';
				}
				else {
					echo "<div class='alert alert-success'>Запись изменена</div>";
					//echo '<script>setTimeout(\'location="../admin/index.php"\', 2000)</script>';
				}
			}

		}*/

		//удаление страны и её должностных лиц
		if ( isset($_POST['deleteData']) ) {
			//print_r($_POST);

			echo "delete";

			/*$name_country = defend($_POST['name_country_old']);
			$query_flag_country = "SELECT flag_country FROM table_country WHERE name_country = '$name_country'";

			$query_foto_person = "SELECT foto FROM table_persons WHERE table_persons.id_country = (SELECT table_country.id_country FROM table_country WHERE table_country.name_country = '$name_country')";//запрос к БД на выбор изображений должностных лиц, страну которых удаляем
			if(!$result_foto_person = $connection -> query($query_foto_person)) {
				echo "<div class='alert alert-danger'>Сбой при удалении данных: $query_flag_country<br>" . $connection -> error . "<br></div>";//если произошла ошибка, вывод сообщения
			}
			else {                     
				$fotoPers = $result_foto_person -> num_rows;
				for($y = 0; $y < $fotoPers; ++$y) {
					$result_foto_person -> data_seek($y);
					$rezPers = $result_foto_person -> fetch_array(MYSQLI_NUM);//получение отдельной строки таблицы
					if(!unlink("../../" . $rezPers[0])){ //функция удаления
						echo "/nОшибка удаления фото должностного лица";
					}
				}
			}

			if(!$result_flag_country = $connection -> query($query_flag_country)){
				echo "<div class='alert alert-danger'>Сбой при удалении данных: $query_flag_country<br>" . $connection -> error . "<br></div>";
			}
			else {
				$query_del_country = "DELETE FROM table_country WHERE name_country = '$name_country'";
				if(!$result_del_country = $connection -> query($query_del_country)){
					echo "<div class='alert alert-danger'>Сбой при удалении данных: $query_del_country<br>" . $connection -> error . "<br></div>";
				}
				else {
					$rows = $result_flag_country -> num_rows;
					for($j = 0; $j < $rows; ++$j) {
						$result_flag_country -> data_seek($j);
						$row = $result_flag_country -> fetch_array(MYSQLI_NUM);//получение отдельной строки таблицы
						if(!unlink("../../" . $row[0])){ //функция удаления
							echo "/nОшибка удаления флага страны";
						} 
					}            
					echo "<div class='alert alert-success'>Запись удалена</div>";
					echo '<script>setTimeout(\'location="../new_admin.php"\', 2000)</script>';
				}
			}*/

		}

		//обновление дынных о стране
		if ( isset($_POST['updateDataCountry']) ) {

			if($_FILES['flag_edit']['name'] == ''){
			            $flag_country = $_POST['flag_country'];
			        }
	        else {
	            //удаление старого изображения
	              if(!unlink("../../" . $_POST['flag_country'])){ //функция удаления
	                echo "/nОшибка удаления изображения";
	              }
	            // проверяем, можно ли загружать изображение
	            $check = can_upload($_FILES['flag_edit']);

	            if($check === true){
	              // загружаем изображение на сервер
	              $full_name_photo = make_upload($_FILES['flag_edit']);
	              $flag_country =  $full_name_photo ;
	              echo "<div class='alert alert-success'>Файл <strong>$full_name_photo</strong> успешно загружен!<br></div>";
	            }
	            else{
	              // выводим сообщение об ошибке
	              echo "<div class='alert alert-danger'><strong>$check</strong></div>";
	            }
	        }

	        $name_country_new = defend($_POST['name_country_new']);
	        $index_country = defend($_POST['index_country']);
	        $continent_country = defend($_POST['continent_country']);
	        $accessory_block = defend($_POST['accessory_block']);
	        $info_about_country = defend($_POST['info_about_country']);
	        $name_country_old = defend($_POST['name_country_old']);

	        $query_update_country = "UPDATE table_country SET name_country = '". $name_country_new .
	    								    "', index_country = '" . $index_country .
	    								    "', continent_country = '" . $continent_country .
	    								    "', accessory_block = '" . $accessory_block .
	    								    "', info_about_country = '" . $info_about_country .
	    								    "', flag_country = '" . $flag_country .
	        "' WHERE name_country = '" . $name_country_old . "'";

	        if(!$result_update_country = $connection -> query($query_update_country)) {
	        	echo "<div class='alert alert-danger'>Сбой при выборе данных: $query_update_country<br/>" . $connection -> error . "</div>";//если произошла ошибка, вывод сообщения
	        	//echo '<script>setTimeout(\'location="../admin/index.php"\', 2000)</script>';
	        }
	        else {
	        	echo "<div class='alert alert-success'>Запись изменена</div>";
	    	    echo '<script>setTimeout(\'location="../new_admin.php"\', 2000)</script>';
	        }

		}

		//обновление дынных о должностных лицах
		if ( isset($_POST['updateDataPerson']) ) {

			//echo "updateDataPerson<br>";
			//print_r($_POST);

			foreach ($_POST['user'] as $user_id => $user_data) {

				$position_person = defend($user_data['position_person']);
				$full_name = defend($user_data['full_name']);
				$reference_info = defend($user_data['reference_info']);
				$id_person = defend($user_data['id_person']);
				$old_foto = defend($user_data['old_foto']);
				// print_r($_FILES);

				if($_FILES["$id_person"]['name']['search_foto_edit'] == '') {
					$foto = defend($user_data['old_foto']);
					// echo "netu";
				}
				else {
					// echo $_FILES["$id_person"]['name']['search_foto_edit'];
					//удаление старого изображения 
					if(!unlink("../../" . $old_foto)){ //функция удаления
						echo "/nОшибка удаления изображения";
					}
					// проверяем, можно ли загружать изображение
					$check = can_upload_more($_FILES["$id_person"]);

					if($check === true){
						// загружаем изображение на сервер
						$full_name_photo = make_upload_more($_FILES["$id_person"]);
						$foto =  $full_name_photo ;
						echo "<div class='alert alert-success'>Файл <strong>$full_name_photo</strong> успешно загружен!</div>";
					}
					else {
						// выводим сообщение об ошибке
						echo "<strong class='alert alert-danger'>$check</strong>";
					}
				}

				$query_edit_person = "UPDATE table_persons SET position_person = '". $position_person .
															"', full_name = '" . $full_name .
															"', reference_info = '" . $reference_info .
															"', foto = '" . $foto .
															"' WHERE table_persons.id_position = '" . $id_person . "'";

				if(!$result_edit_person = $connection -> query($query_edit_person)) {
					echo "<div class='alert alert-danger'>Сбой при выборе данных: $query_edit_person<br/>" . $connection -> error . "<br></div>";
					//echo '<script>setTimeout(\'location="../admin/index.php"\', 5000)</script>';
				}
				else {
					echo "<div class='alert alert-success'>Запись изменена</div>";
					echo '<script>setTimeout(\'location="../new_admin.php"\', 2000)</script>';
				}
			}

		}

		//удаление должностного лица
		if ( isset($_POST['what_this']) && $_POST['what_this'] == 'delPerson' ) {

			$id_person = defend($_POST['what_this_id']);

			$query_select_person = "SELECT foto FROM table_persons WHERE id_position = '$id_person'";

			if(!$result_select_person = $connection -> query($query_select_person)){
				echo "<div class='alert alert-danger'>Сбой при выборе данных: $query_del_country<br>" . $connection -> error . "<br></div>";
			}
			else {
				$rows = $result_select_person -> num_rows;
				for($j = 0; $j < $rows; ++$j) {
					$result_select_person -> data_seek($j);
					$row = $result_select_person -> fetch_array(MYSQLI_NUM);//получение отдельной строки таблицы
					if(!unlink("../../" . $row[0])){ //функция удаления
						echo "/nОшибка удаления фотографии";
					} 
				}
				echo "<div class='alert alert-success'>Фотография удалена</div>";
			}

			$query_del_person = "DELETE FROM table_persons WHERE id_position = '$id_person'";

			if(!$result_del_person = $connection -> query($query_del_person)){
				echo "<div class='alert alert-danger'>Сбой при удалении данных: $query_del_country<br>" . $connection -> error . "<br></div>";
			}
			else {       
				echo "<div class='alert alert-success'>Запись удалена</div>";
				echo '<script>setTimeout(\'location="../admin/new_admin.php"\', 2000)</script>';
			}
			
		}

		//изменение пароля пользователя
		if ( isset($_POST['updatePassword']) ) {

			//print_r($_POST);
			
			if (isset($_POST['old_password']) || isset($_POST['new_password']) || isset($_POST['new_dub_password'])){

			    $old_password = defend($_POST['old_password']);
			    $new_password = defend($_POST['new_password']);
			    $new_dub_password = defend($_POST['new_dub_password']);

			    if ($old_password == '' || $new_password == '' || $new_dub_password == ''){

			        unset($old_password);
			        unset($new_password);
			        unset($new_dub_password);

			    }

			}

			if (empty($old_password) || empty($new_password) || empty($new_dub_password)) {

			    echo "<h2 class='alert alert-danger'>Вы ввели не всю информацию :( </h2>" ;
			    exit ("<hr>");

			}

			$old_password = defend($old_password);
			$new_password = defend($new_password);
			$new_dub_password = defend($new_dub_password);
			$login = $_SESSION['login'];

			$query = "SELECT * FROM table_users WHERE login = '$login'";

			$result = $connection -> query($query);
			if(!$result) die ("<div class='alert alert-danger'>Сбой при доступе к БД: " . $connection -> error . "</div>");

			$myrow = $result -> fetch_array(MYSQLI_ASSOC);

		    $salt = $myrow['salt'];

		    if ($myrow['password'] == md5($old_password.$salt)) {

		    	if ($new_password == $new_dub_password) {

		    		$new_salt = generateSalt();
		    		$saltedPassword = md5($new_password.$new_salt);

		    		$query_update_password = "UPDATE table_users SET password = '". $saltedPassword .
														    		"', salt = '" . $new_salt .
						    										"' WHERE login = '" . $login . "'";

			        if(!$result_update_password = $connection -> query($query_update_password)) {

			        	echo "<div class='alert alert-danger'>Сбой при выборе данных: $query_update_password<br/>" . $connection -> error . "</div>";
			        	echo '<script>setTimeout(\'location="../new_admin.php"\', 5000)</script>';

			        }
			        else {

			        	echo "<div class='alert alert-success'>Пароль изменен</div>";
			    	    echo '<script>setTimeout(\'location="../new_admin.php"\', 5000)</script>';

			        }

		    	}
		    	else {

			        echo "<h2 class='alert alert-danger'>Извините, введённые Вами <span class='font-weight-bold'>новые пароли</span> не совпадают! :( </h2>";
		    	    echo '<script>setTimeout(\'location="../new_admin.php"\', 5000)</script>';

		    	}

		    }
		    else {

		        echo "<h2 class='alert alert-danger'>Извините, введённый Вами <span class='font-weight-bold'>старый пароль</span> не верен! :( </h2>";
	    	    echo '<script>setTimeout(\'location="../new_admin.php"\', 5000)</script>';

		    }

		}

		//добавление должностного лица
		if ( isset($_POST['addPerson'])) {

		    $position_person = defend($_POST['addPersonPosition']);
		    $name_person = defend($_POST['addPersonName']);
		    $info_person = defend($_POST['addPersonInfo']);
		    $name_country = defend($_POST['addPersonCountry']);

		    if ( isset($_FILES['addPersonFoto']) ) {

				$check = can_upload($_FILES['addPersonFoto']);

				if ($check === true) {

					$full_name_photo = make_upload($_FILES['addPersonFoto']);
					$foto_person =  $full_name_photo ;

					echo "<div class='alert alert-success'>Файл <strong>$full_name_photo</strong> успешно загружен!</div>";

				}
				else {

					echo "<strong class='alert alert-danger'>$check</strong>";

				}

		    }

		    $query_add_person = "INSERT INTO table_persons VALUES (NULL,'" . $position_person . "',
												                        '" . $name_person . "',
												                        '" . $info_person . "',
		                        (SELECT table_country.id_country FROM table_country WHERE table_country.name_country = '" . $name_country . "'),
		                        0, '" . $foto_person . "')";

		    if ( !$result_add_person = $connection -> query($query_add_person) ) die ("Сбой при вставке данных: " . $connection -> error . "<br> Number error: " . $connection -> errno);

		    echo "<div class='alert alert-success'>Должностное лицо добавлено</div>";
    	    echo '<script>setTimeout(\'location="/admin/new_admin.php"\', 3000)</script>';

		}

		//для таблицы пользователей (4 обработчика)
        //"обработчик" кнопки удаления пользователя
		if (isset($_POST['delete_user']) && $_POST['id']) {

			$query_del = "DELETE FROM table_users WHERE id = " . $_POST['id'];

			$result_del = $connection -> query($query_del);
			if(!$result_del) die ("<div class='alert alert-danger'>Сбой при доступе к БД: " . $connection -> error . "</div>");

    	    echo "<div class='alert alert-success display-1 font-weight-bold'>Пользователь удален</div>";
    	    echo '<script>setTimeout(\'location="/admin/new_admin.php"\', 3000)</script>';

		}

		//"обработчик" кнопки удаления редактируемой страны у пользователя
		if (isset($_POST['del_edit_country']) && $_POST['id_user'] && $_POST['id_edit_country']) {

			$query_del_edit = "DELETE FROM `table_for_tc-tu` WHERE id_table_users = " . $_POST['id_user'] . " AND id_table_country = " . $_POST['id_edit_country'];

			$result_del_edit = $connection -> query($query_del_edit);
			if(!$result_del_edit) die ("<div class='alert alert-danger'>Сбой при доступе к БД: " . $connection -> error . "</div>");

    	    echo "<div class='alert alert-success display-1 font-weight-bold'>Страна удалена</div>";
    	    echo '<script>setTimeout(\'location="/admin/new_admin.php"\', 3000)</script>';

		}

		//"обработчик" кнопки добавления редактируемой страны пользователю
		if (isset($_POST['add_edit_country']) && $_POST['id_user']) {

			$query_add_edit = "INSERT INTO `table_for_tc-tu` (`id`, `id_table_country`, `id_table_users`, `comment`) VALUES (NULL, '" . $_POST['sel_country'] . "', '" . $_POST['id_user'] . "', NULL)";

			$result_add_edit = $connection -> query($query_add_edit);
			if(!$result_add_edit) die ("<div class='alert alert-danger'>Сбой при доступе к БД: " . $connection -> error . "</div>");

    	    echo "<div class='alert alert-success display-1 font-weight-bold'>Страна добавлена</div>";
    	    echo '<script>setTimeout(\'location="/admin/new_admin.php"\', 3000)</script>';

		}

		//"обработчик" кнопки изменения привилегий пользователя
		if (isset($_POST['up_privilege']) && $_POST['id']) {

			$query_up = "UPDATE table_users SET id_role = '" . $_POST['sel_priv'] . "' WHERE id = " . $_POST['id'];

			$result_up = $connection -> query($query_up);
			if(!$result_up) die ("<div class='alert alert-danger'>Сбой при доступе к БД: " . $connection -> error . "</div>");
			
    	    echo "<div class='alert alert-success display-1 font-weight-bold'>Привилегии изменены</div>";
    	    echo '<script>setTimeout(\'location="/admin/new_admin.php"\', 3000)</script>';

		}

	}
	else {

		echo ' 
			<link rel="stylesheet" type="text/css" href="/css/bootstrap/css/bootstrap.min.css">
			<link rel="stylesheet" type="text/css" href="/css/bootstrap/css/signin.css">';

		echo "
			<div class='alert alert-danger'>У Вас нет доступа к информации. Обратитесь к администратору по телефону <strong>(411) 13-02</strong>, либо пришлите письмо на адрес <strong>sham@givc.vs.mil.by</strong> с объяснением для чего Вам нужен доступ к панели администратора.
			</div>
			<form action='/php-script/session_destroy.php' method='post' class='form-inline'>
				<input type='submit' name='destroy' class='btn btn-dark btn-lg m-5' value='Выход'>
			</form>";		 

	}

?>