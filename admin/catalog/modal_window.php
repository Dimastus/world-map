<?php

	session_start();

	if($_SESSION['login'] == '') {

		echo ' 
			<link rel="stylesheet" type="text/css" href="/css/bootstrap/css/bootstrap.min.css">
			<link rel="stylesheet" type="text/css" href="/css/bootstrap/css/signin.css">';

		echo "<div class=\"alert alert-danger\">Вы не должны быть здесь!</div>";
		echo '<script>setTimeout(\'location="../new_admin.php"\', 2000)</script>';

	}
	elseif ($_SESSION['login'] == true && ($_SESSION['id_role'] == 1 || $_SESSION['id_role'] == 2)) {
			
		echo "
			<script src='../tinymce/tinymce.min.js'></script>
			<script>
				tinymce.init({ 
					selector:'.mytextarea',      
					language: 'ru',
					height: '480'
				});
			</script>
		";

		$way = '../../php-script/';		//часть пути для подключения файлов
		
		// подключаемся к базе
		require_once $way . 'data_to_db.php';
		require_once $way . 'connect_to_db.php';
		/*подключить файл с функциями*/
		require_once 'function.php';

		if ($_POST['what_this'] == "country") {

			$countryID =  defend($_POST['what_this_id']);
		    $query_country_old = "SELECT * FROM table_country WHERE id_country = '" . $countryID ."' ORDER BY name_country ASC";//запрос к БД на выбор

		    if(!$result_country_old = $connection -> query($query_country_old)){
				echo "<div class='alert alert-danger'>Сбой при выборе данных: $query_country_old<br>" . $connection -> error . "<br><br></div>";//если произошла ошибка, вывод сообщения
		    }
		    else {
				$rows_country_old = $result_country_old -> num_rows;
				for($i = 0; $i < $rows_country_old; ++$i){

					$result_country_old -> data_seek($i);
					$row_country_old = $result_country_old -> fetch_array(MYSQLI_NUM);//получение отдельной строки таблицы

					$name_country = defend($row_country_old[1]);
					$info_about_country = $row_country_old[5];
					$index_country = defend($row_country_old[2]);
					$continent_country = defend($row_country_old[3]);
					$block_country = defend($row_country_old[4]);

					echo '		
						<form action="catalog/insert_to_db.php" method="post" enctype="multipart/form-data">
							<div class="tab-content bg-white p-4" id="myTabContent">
								<div class="tab-pane fade show active bg-dark p-3 rounded" id="country_modal" role="tabpanel" aria-labelledby="country-tab">
									<div class="input-group mb-3">
										<div class="input-group w-50">
											<div class="input-group-prepend">
												<label for="name_country_new" class="input-group-text">Название</label>
											</div>
											<input type="text" name="name_country_new" class="form-control bg-light rounded-right" placeholder="Введите название страны" required value="' . $name_country . '">
											<input type="hidden" name="name_country_old" value="' . $name_country . '">
										</div>
										<div class="input-group w-50">
											<div class="input-group-prepend ml-2">
												<label for="index_country" class=" form-control input-group-text">Индекс</label>
											</div>
											<select class="form-control custom-select bg-light" name="index_country" id="selectIndexCountry" required>';
											  	//получение всех частей света из таблицы table_continent для сравнения
							                    $query_index = "SELECT * FROM table_country_indexes ORDER BY name_country ASC";
							                    $res_index = $connection -> query($query_index);
							                    $rows_index = $res_index -> num_rows;
							                    print_r($rows_cont);
							                    for($i = 0; $i < $rows_index; ++$i)
							                    {
													$res_index -> data_seek($i);
													$row_index = $res_index -> fetch_array(MYSQLI_NUM);
													echo "<option "; //. $row_cont[0] . 
													if ($row_index[2] == $index_country) {
														echo " selected ";
													}
													echo '>' . $row_index[2] . ' = ' . $row_index[1] . '</option>';
							                    }
											echo '
											</select>
										</div>
									</div>
									<div class="input-group mb-3">
										<div class="w-50 input-group">											
											<div class="input-group-prepend">
												<label for="continent_country" class="input-group-text">Часть света</label>
											</div>
											<select class="form-control custom-select bg-light" name="continent_country" id="selectContinentCountry" required>';
				                                //получение всех частей света из таблицы table_continent для сравнения
							                    $query_cont = "SELECT continent FROM table_continent ORDER BY continent ASC";
							                    $res_cont = $connection -> query($query_cont);
							                    $rows_cont = $res_cont -> num_rows;
							                    //print_r($rows_cont);
							                    for($i = 0; $i < $rows_cont; ++$i)
							                    {
													$res_cont -> data_seek($i);
													$row_cont = $res_cont -> fetch_array(MYSQLI_NUM);
													echo "<option "; //. $row_cont[0] . 
													if ($row_cont[0] == $row_country_old[3]) {
														echo " selected";
													}
													echo ">" . $row_cont[0] . "</option>";
							                    }
											echo '</select>
										</div>
										<div class="w-50 input-group">
											<div class="input-group-prepend ml-2">
												<label for="accessory_block" class="input-group-text">Блок</label>
											</div>
											<select class="form-control custom-select bg-light" name="accessory_block" id="selectAccessoryBlock" required placeholder="Name">';
							                    $query_block = "SELECT name_block FROM table_block ORDER BY name_block ASC";
							                    $res_block = $connection -> query($query_block);
							                    $rows_block = $res_block -> num_rows;
							                    //print_r($rows_cont);
							                    for($i = 0; $i < $rows_block; ++$i)
							                    {
							                      $res_block -> data_seek($i);
							                      $row_block = $res_block -> fetch_array(MYSQLI_NUM);
							                      echo "<option "; //. $row_cont[0] . 
							                      if ($row_block[0] == $row_country_old[4]) {
							                        echo " selected";
							                      }
							                      echo ">" . $row_block[0] . "</option>";
							                    }
											echo '</select>
										</div>
									</div>
									<div class="input-group mb-3">
										<div class="input-group-prepend">
											<label for="flag_country" class="input-group-text">Флаг</label>
										</div>
										<div class="form-control bg-light rounded-right text-center h-100">
											<img src="../' . $row_country_old[6] . '" width="150px" class="img-thumbnail bg-dark"></img>
											<label class="w-75">' . $row_country_old[6] . '</label>													
											<input type="file" name="flag_edit" class="btn btn-outline-secondary btn-sm" id="exampleFormControlFile1" value="Добавить">
										</div>
										<input type="text" name="flag_country" class="skrit" value="' . $row_country_old[6] . '" readonly>
									</div>
									<div class="input-group mb-3 text-center">
										<label for="info_about_country" class="rounded-top bg-light w-100 mb-0 p-2 text-center">Информация</label>
										<textarea name="info_about_country" class="mytextarea" aria-label="With textarea" placeholder="Введите информацию о стране">' . $info_about_country . '</textarea>
									</div>
								</div>
							</div>
							<div class="d-flex justify-content-end mb-2">
								<div class="btn-group btn-group-lg mr-5">
									<input type="submit" name="updateDataCountry" class="btn btn-success" value="" title="сохранение внесенных изменений">
								</div>
							</div>
						</form>
					';
				}
			}

		} 
		elseif ($_POST['what_this'] == "person") {

			$personID = defend($_POST['what_this_id']);
			$query_person_old = "SELECT * FROM table_persons WHERE table_persons.id_position = " . $personID;	    	

			if(!$result_person_old = $connection -> query($query_person_old)){
				echo "<div class='alert alert-danger'>Сбой при выборе данных: $query_person_old<br>" . $connection -> error . "<br><br></div>";
			}
			else {
				$rows_person_old = $result_person_old -> num_rows;
				for($i = 0; $i < $rows_person_old; ++$i){
					$result_person_old -> data_seek($i);
					$row_person_old = $result_person_old -> fetch_array(MYSQLI_NUM);
					$position_person = defend($row_person_old[1]);
					$full_name = defend($row_person_old[2]);
					$reference_info = defend($row_person_old[3]);
						//<input type="text" name="position_person" class="input-group-text bg-light w-100"  placeholder="Введите должность" required value="' . $position_person . '">
					echo '<form action="catalog/insert_to_db.php" method="post" enctype="multipart/form-data">
							<div class="tab-content bg-white p-4" id="myTabContent">
								<div class="tab-pane fade show active bg-dark p-3 rounded" id="person_modal" role="tabpanel" aria-labelledby="person-tab">';
							echo "  <div class='input-group mb-3'>
										<div class='input-group w-50'>
											<div class='input-group-prepend'>
												<label for='user[$row_person_old[0]][position_person]' class='input-group-text'>Должность</label>
											</div>
											<input name='user[$row_person_old[0]][position_person]' class='form-control text-center bg-light rounded-right' aria-label='With textarea' placeholder='Введите должность' value='$position_person' required>
											<input type='hidden' name='user[$row_person_old[0]][id_person]' value='$row_person_old[0]'>
										</div>
										<div class='input-group w-50'>
											<div class='input-group-prepend ml-2'>			
												<label for='user[$row_person_old[0]][full_name]' class='input-group-text'>ФИО</label>
											</div>
											<input name='user[$row_person_old[0]][full_name]' class='form-control text-center bg-light'  placeholder='Введите фамилию имя отчество' value='$full_name' required>
										</div>
									</div>
									<div class='input-group mb-3 text-center'>
										<div class='input-group-prepend'>
											<label for='flag_country' class='input-group-text'>Фото</label>
										</div>
										<div class='form-control bg-light h-100'>
											<img src='../$row_person_old[6]' width='150px' class='img-thumbnail bg-dark'></img>
											<div class='w-100'>$row_person_old[6]</div>
											<input type='text' name='user[$row_person_old[0]][old_foto]' class='input-group-text bg-light skrit' value='$row_person_old[6]' readonly>
											<input type='file' name='$row_person_old[0][search_foto_edit]' class='btn btn-outline-secondary btn-sm'>
										</div>
									</div>						
									<div class='input-group mb-3 text-center'>
										<label for='user[$row_person_old[0]][reference_info]' class='rounded-top bg-light w-100 mb-0 p-2 text-center'>Информация</label>	
										<textarea id='infoAboutPerson$row_person_old[0]' name='user[$row_person_old[0]][reference_info]' class='form-control mytextarea' aria-label='With textarea' placeholder='Введите информацию о должностном лице' rows='15'>$reference_info</textarea>
									</div>
								</div>
							</div>
							<div class='d-flex justify-content-end mb-2'>
								<div class='btn-group btn-group-lg mr-5'>
									<input type='submit' name='updateDataPerson' class='btn btn-success' value='' title='сохранение внесенных изменений'>
								</div>
							</div>
						</form>";
				}
			}

		}
		elseif ($_POST['what_this'] == "password") {

			echo "
				<form action='catalog/insert_to_db.php' method='post' enctype='multipart/form-data'>
					<div class='input-group w-100 mb-3'>
						<div class='input-group-prepend'>			
							<label for='old_password' class='input-group-text'>Старый пароль</label>
						</div>
						<input name='old_password' type='password' size='15' maxlength='15' placeholder='Введите старый пароль' class='form-control text-center bg-light' required='required'>
					</div>
					<div class='input-group w-100 mb-3'>
						<div class='input-group-prepend'>			
							<label for='new_password' class='input-group-text'>Новый пароль</label>
						</div>
						<input name='new_password' type='password' size='15' maxlength='15' placeholder='Введите новый пароль' class='form-control text-center bg-light' required='required'>
					</div>
					<div class='input-group w-100'>
						<div class='input-group-prepend'>			
							<label for='new_dub_password' class='input-group-text'>Новый пароль</label>
						</div>
						<input name='new_dub_password' type='password' size='15' maxlength='15' placeholder='Введите новый пароль ещё раз' class='form-control text-center bg-light' required='required'>
					</div>
					<div class='d-flex justify-content-center mt-3'>
						<div class='btn-group btn-group-sm'>
							<input type='submit' name='updatePassword' class='btn btn-success font-weight-bold' value='' title='сохранение внесенных изменений'>
						</div>
					</div>
				</form>
				";

		}
		elseif ($_POST['what_this'] == "addPerson") {

			$addPersonCountry = defend($_POST['nameCountry']);
			
			echo '
				<h3 class="p-0 m-0 text-center">Добавление должностного лица</h3>
				<form action="catalog/insert_to_db.php" method="post" enctype="multipart/form-data">
					<div class="tab-content bg-white p-4" id="myTabContent">
						<div class="tab-pane fade show active bg-dark p-3 rounded" id="person_modal" role="tabpanel" aria-labelledby="person-tab">';
					echo "  <div class='input-group mb-3'>
								<div class='input-group w-50'>
									<div class='input-group-prepend'>
										<label for='addPersonPosition' class='input-group-text'>Должность</label>
									</div>
									<input type='text' name='addPersonPosition' class='form-control text-center bg-light rounded-right' placeholder='Введите должность' required>
								</div>
								<div class='input-group w-50'>
									<div class='input-group-prepend ml-2'>			
										<label for='addPersonName' class='input-group-text'>ФИО</label>
									</div>
									<input type='text' name='addPersonName' class='form-control text-center bg-light'  placeholder='Введите фамилию имя отчество' required>
									<input type='hidden' name='addPersonCountry' value='" . $addPersonCountry . "'>
								</div>
							</div>
							<div class='input-group mb-3 text-center'>
								<div class='input-group-prepend'>
									<label for='addPersonFoto' class='input-group-text'>Фото</label>
								</div>
								<div class='form-control bg-light h-100'>
									<input name='addPersonFoto' type='file' class='btn btn-outline-secondary btn-sm w-100'>
								</div>
							</div>						
							<div class='input-group mb-3 text-center'>
								<label for='addPersonInfo' class='rounded-top bg-light w-100 mb-0 p-2 text-center'>Информация</label>	
								<textarea id='infoAddPerson' name='addPersonInfo' class='form-control mytextarea' aria-label='With textarea' placeholder='Введите информацию о должностном лице' rows='15'>$reference_info</textarea>
							</div>
						</div>
					</div>
					<div class='d-flex justify-content-end mb-2'>
						<div class='btn-group btn-group-lg mr-5'>
							<input type='submit' name='addPerson' class='btn btn-success' value='' title='сохранение внесенных изменений'>
						</div>
					</div>
				</form>";

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