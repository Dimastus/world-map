<?php

	//print_r($_POST);

	$way = "../../php-script/";	//часть пути для подключения файлов

	// подключаемся к базе
	require_once $way . 'data_to_db.php';
	require_once $way . 'connect_to_db.php';
	/*подключить файл с функциями*/
	require_once 'function.php';
	
	//вставка данных о стране и должностных лицах
	if(isset($_POST['insertData'])) {

		//?????????????????????? подумать над дополнительным добавлением должностных лиц, когда страна уже есть в БД, а необходимо добавить кого-нибудь ?????????????????
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

        //данные по долностному лицу
		$position_person = defend($_POST['position_person']);
		$full_name = defend($_POST['full_name']);
		$info_about_person = defend($_POST['info_about_person']);
		if(isset($_FILES['search_foto'])) {
            // проверяем, можно ли загружать изображение
            $check = can_upload($_FILES['search_foto']);

            if($check === true){
              // загружаем изображение на сервер
              $full_name_photo = make_upload($_FILES['search_foto']);
              $foto_person =  $full_name_photo ;
              echo "<div class='alert alert-success'>Файл <strong>$full_name_photo</strong> успешно загружен!</div>";
            }
            else{
              // выводим сообщение об ошибке
              echo "<strong class='alert alert-danger'>$check</strong>";
            }
		}

		//запрос для добавления страны и данных о ней
		$query_insert_country = "INSERT INTO table_country VALUES (NULL ,'" . $name_country . "', '" . $index_country . "', '" . $continent_country . "', '" . $accessory_block . "', '" . $info_about_country . "', '" . $flag_country . "')";
        if(!$result_insert_country = $connection -> query($query_insert_country)) die("Сбой при вставке данных: " . $connection -> error . "<br> Number error: " . $connection -> errno);

		//запрос для добавления должностного лица и данных по нему
        $query_insert_person = "INSERT INTO table_persons VALUES (NULL, '" . $position_person . "', '" . $full_name . "', '" . $info_about_person . "', (SELECT table_country.id_country FROM table_country WHERE table_country.name_country = '" . $name_country . "'), 0, '" . $foto_person . "')";
		if(!$result_insert_person = $connection -> query($query_insert_person)) die ("Сбой при вставке данных: " . $connection -> error . "<br> Number error: " . $connection -> errno);

        echo "<div class='alert alert-success'>Запись добавлена<br></div>";
        //echo "<script>setTimeout('location=\"../new_admin.php\"', 2000)</script>";

	}

	//обновление дынных о стране и должностных лицах
	if(isset($_POST['updateData'])) {

		//print_r($_POST);
	    //print_r($_FILES);
        /*выбор картинок*/
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

	}

	//удаление страны и её должностных лиц
	if(isset($_POST['deleteData'])) {
		//print_r($_POST);

		$name_country = defend($_POST['name_country_old']);
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
			}
		}

	}

?>