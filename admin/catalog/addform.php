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

		//Подключение визуального редактора tinymce
		echo "
			<script src='../tinymce/tinymce.min.js'></script>
			<script>
				tinymce.init({ 
					selector:'.mytextarea',      
					language : 'ru'
				});
			</script>
		";
		
		$way = "../../php-script/";		//часть пути для подключения файлов

	    // подключаемся к базе
		require_once $way . 'data_to_db.php';
		require_once $way . 'connect_to_db.php';
		include_once 'function.php';

		$query_index = "SELECT * FROM `table_country_indexes` ORDER BY name_country ASC";
		$result_index = $connection -> query($query_index);

		if(!$result_index) die("<div class='alert alert-danger'>Сбой при доступе к БД: " . $connection -> error . "</div>");
		else {

				$rows_index = $result_index -> num_rows;

				echo '
						<form method="post" action="catalog/insert_to_db.php" enctype="multipart/form-data">
							<div class="tab-content bg-white p-4" id="myTabContent">
								<div class="tab-pane fade show active bg-dark p-3 rounded" id="country_modal" role="tabpanel" aria-labelledby="country-tab">								
									<div class="input-group mb-3">
										<div class="input-group w-50">
											<div class="input-group-prepend">
												<label for="name_country" class="input-group-text">Название</label>
											</div>
											<input type="text" name="name_country" class="form-control bg-light rounded-right" placeholder="Введите название страны" required>
										</div>
										<div class="input-group w-50">
											<div class="input-group-prepend ml-2">
												<label for="index_country" class=" form-control input-group-text">Индекс</label>
											</div>
											<select class="form-control custom-select bg-light" name="index_country" id="selectIndexCountry" required>
												  <option selected disabled></option>';
													for($n = 0; $n < $rows_index; ++$n){
												        $result_index -> data_seek($n);
												        $row_index = $result_index -> fetch_array(MYSQLI_NUM);
														echo '<option value="' . $row_index[2] . '">' . $row_index[1] . ' = ' . $row_index[2] . '</option>';
													}
										echo '
											</select>
										</div>
									</div>							
									<div class="input-group mb-3">
										<div class="input-group w-50">
											<div class="input-group-prepend">
												<label for="index_country" class=" form-control input-group-text">Часть света</label>
											</div>
											<select class="form-control custom-select" name="continent_country" id="selectContinentCountry" required>
													<option selected disabled></option>';

			                                        $query_block = "SELECT continent FROM table_continent ORDER BY continent ASC";							                    
								                    require 'data_option.php';

									  echo '</select>
										</div>
										<div class="input-group w-50">
											<div class="input-group-prepend ml-2">
												<label for="index_country" class=" form-control input-group-text">Блок</label>
											</div>
											<select class="form-control custom-select" name="accessory_block" id="selectAccessoryBlock" required placeholder="Name">
												<option selected disabled></option>';

							                    $query_block = "SELECT name_block FROM table_block ORDER BY name_block ASC";							                    
							                    require 'data_option.php';

					                  echo '</select>
										</div>
									</div>
									<div class="input-group mb-3">
										<div class="input-group-prepend">
											<label for="search_flag" class="input-group-text">Флаг</label>
										</div>
										<div class="form-control bg-light rounded-right text-center h-100">
											<input type="file" name="search_flag" class="btn btn-outline-secondary btn-sm w-100" id="exampleFormControlFile1">												
										</div>
									</div>							
									<div class="input-group mb-3 text-center">
										<label for="info_about_country" class="rounded-top bg-light w-100 mb-0 p-2 text-center">Информация</label>
								        <textarea id="infoAboutCountry" name="info_about_country" class="form-control mytextarea" aria-label="With textarea" placeholder="Введите информацию о стране"></textarea>
									</div>
								</div>
							</div>
							<input type="submit" name="insertData" class="btn btn-success btn-lg float-right mr-5" value="" title="сохранить">
						</form>
				';

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