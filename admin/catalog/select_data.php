<?php
	
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

	$way = '../../php-script/';		//часть пути для подключения файлов

    // подключаемся к базе
	require_once $way . 'data_to_db.php';
	require_once $way . 'connect_to_db.php';
	/*подключить файл с функциями*/
	require_once 'function.php';

	$name_country_old = defend(my_ucfirst($_POST['code']));
    $query_country_old = "SELECT * FROM table_country WHERE name_country = '" . $name_country_old ."' ORDER BY name_country ASC";//запрос к БД на выбор

    if(!$result_country_old = $connection -> query($query_country_old)){
		echo "<div class='alert alert-danger'>Сбой при выборе данных: $query_country_old<br>" . $connection -> error . "<br><br></div>";//если произошла ошибка, вывод сообщения
    }
    else {
		$rows_country_old = $result_country_old -> num_rows;
		for($i = 0; $i < $rows_country_old; ++$i){

			$result_country_old -> data_seek($i);
			$row_country_old = $result_country_old -> fetch_array(MYSQLI_NUM);//получение отдельной строки таблицы

			$name_country = defend($row_country_old[1]);
			$info_about_country = defend($row_country_old[5]);
			$index_country = defend($row_country_old[2]);

			echo '
				<ul class="nav nav-tabs" id="myTab" role="tablist">
					<li class="nav-item">
						<a class="nav-link active" id="country-tab" data-toggle="tab" href="#country" role="tab" aria-controls="country" aria-selected="true">Страна</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" id="person-tab" data-toggle="tab" href="#person" role="tab" aria-controls="person" aria-selected="false">Должностные лица</a>
					</li>
					<li class="nav-item">
						<a class="nav-link disabled" id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false">Вооружение</a>
					</li>
				</ul>
				<form action="catalog/insert_to_db.php" method="post" enctype="multipart/form-data">
					<div class="tab-content bg-white p-4" id="myTabContent">
						<div class="tab-pane fade show active" id="country" role="tabpanel" aria-labelledby="country-tab">
							<table class="table table-dark">
								<thead>
									<tr>
										<th scope="col">Название</th>
										<th scope="col">Индекс</th>
										<th scope="col">Часть света</th>
										<th scope="col">Блок</th>
										<th scope="col">Флаг</th>
										<th scope="col">Информация</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td scope="col">
											<input type="text" name="name_country_new" class="input-group-text bg-light w-100" placeholder="Введите название страны" required value="' . $name_country . '">
											<input type="hidden" name="name_country_old" value="' . $name_country . '">
										</td>
										<td scope="col">
											<select class="form-control custom-select" name="index_country" id="selectIndexCountry" required>';
											  	//получение всех частей света из таблицы table_continent для сравнения
							                    $query_index = "SELECT * FROM table_country_indexes";
							                    $res_index = $connection -> query($query_index);
							                    $rows_index = $res_index -> num_rows;
							                    //print_r($rows_cont);
							                    for($i = 0; $i < $rows_index; ++$i)
							                    {
													$res_index -> data_seek($i);
													$row_index = $res_index -> fetch_array(MYSQLI_NUM);
													echo "<option "; //. $row_cont[0] . 
													if ($row_index[2] == $index_country) {
														echo " selected ";
													}
													echo 'title="' . $row_index[1] . '">' . $row_index[2] . '</option>';
							                    }
									echo '
											</select>
										</td>
										<td scope="col">
											<select class="form-control custom-select" name="continent_country" id="selectContinentCountry" required>';
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
										</td>
										<td scope="col">
											<select class="form-control custom-select" name="accessory_block" id="selectAccessoryBlock" required placeholder="Name">';
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
										</td>
										<td style="width: 10px">
											<img class="w-100" src="../' . $row_country_old[6] . '"></img>
											<input type="text" name="flag_country" class="skrit" value="' . $row_country_old[6] . '" readonly>
											<input type="file" name="flag_edit" class="btn btn-outline-secondary btn-sm" id="exampleFormControlFile1">
										</td>
										<td scope="col">												
											<button class="btn btn-secondary btn-sm ml-4" type="button" data-toggle="collapse" data-target="#multiCollapseExCountry" aria-expanded="false" aria-controls="multiCollapseExCountry">Показать</button>
										</td>
									</tr>
									<tr>                
										<td colspan="6">
											<div class="collapse multi-collapse" id="multiCollapseExCountry">
												<div class="card card-body">
													<textarea id="infoAboutCountry" name="info_about_country" class="form-control mytextarea" aria-label="With textarea" placeholder="Введите информацию о стране" rows="8">' . $info_about_country . '</textarea>
												</div>
											</div>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
						<div class="tab-pane fade" id="person" role="tabpanel" aria-labelledby="person-tab">
							<table class="table table-dark">
								<thead>
									<tr>
										<th>Занимаемая должность</th>
										<th>ФИО</th>
										<th>Фото</th>
										<th class="text-center">Информация</th>
										<th class="text-center">Удаление</th>
									</tr>
								</thead>
								<tbody>';

									$query_person_old = "SELECT * FROM table_persons WHERE table_persons.id_country = (SELECT table_country.id_country FROM table_country WHERE table_country.name_country = '" . $name_country . "')";

									if(!$result_person_old = $connection -> query($query_person_old)){
										echo "<div class='alert alert-danger'>Сбой при выборе данных: $query_person_old<br>" . $connection -> error . "<br><br></div>";
									}
									$rows_person_old = $result_person_old -> num_rows;
									for($i = 0; $i < $rows_person_old; ++$i){
										$result_person_old -> data_seek($i);
										$row_person_old = $result_person_old -> fetch_array(MYSQLI_NUM);
										$position_person = defend($row_person_old[1]);
										$full_name = defend($row_person_old[2]);
										$reference_info = defend($row_person_old[3]);
											//<input type="text" name="position_person" class="input-group-text bg-light w-100"  placeholder="Введите должность" required value="' . $position_person . '">
										$del_person = "insert_to_db.php";
										echo "<tr>
												<td>
													<textarea name='user[$row_person_old[0]][position_person]' class='form-control' aria-label='With textarea' placeholder='Введите должность' required>$position_person</textarea>
													<input type='hidden' name='user[$row_person_old[0]][id_person]' value='$row_person_old[0]'>
												</td>
												<td>
													<textarea name='user[$row_person_old[0]][full_name]' class='form-control'  placeholder='Введите фамилию имя отчество' required>$full_name</textarea>
												</td>
												<td style='width: 10px'>
													<img src='../$row_person_old[6]' width='150px' class='foto'></img>
													<input type='text' name='user[$row_person_old[0]][old_foto]' class='input-group-text bg-light skrit' value='$row_person_old[6]' readonly>
													<input type='file' name='$row_person_old[0][search_foto_edit]' class='btn btn-outline-secondary btn-sm'>
												</td>
												<td class='text-center'>												
													<button class='btn btn-secondary btn-sm' type='button' data-toggle='collapse' data-target='#multiCollapseExPerson$row_person_old[0]' aria-expanded='false' aria-controls='multiCollapseExPerson$row_person_old[0]'>Показать</button>
												</td>
												<td class='text-center'>												
													<input name='delPerson' class='btn btn-danger btn-sm bg-dark text-danger font-weight-bold' type='button' value='удалить'  onclick=\"requestData('$del_person')\">
												</td>
											</tr>
											<tr>                
												<td colspan='6'>
													<div class='collapse multi-collapse' id='multiCollapseExPerson$row_person_old[0]'>
														<div class='card card-body'>
															<textarea id='infoAboutPerson$row_person_old[0]' name='user[$row_person_old[0]][reference_info]' class='form-control mytextarea' aria-label='With textarea' placeholder='Введите информацию о должностном лице' rows='8'>$reference_info</textarea>
														</div>
													</div>
												</td>
											</tr>
										";
									}
								echo '</tbody>
							</table>
							<input type="button" name="plusPerson" class="btn btn-secondary btn-sm mr-5" value="Добавить должностное лицо">

						</div>
						<div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
							<div class="text-warning display-1 font-weight-bold">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Suscipit libero sunt rem eos, eum corporis reiciendis consectetur rerum ipsum. Ipsam tempore optio quis obcaecati similique nobis molestias doloremque facere! Perferendis quam, ut pariatur. Magni voluptas facilis in aut nisi perferendis possimus expedita aliquid voluptatem, rerum reiciendis iusto perspiciatis aspernatur excepturi deserunt velit animi ad. Cupiditate, at illo similique natus dolor vitae laudantium accusantium cumque itaque enim soluta sed! Quam facere ullam, quisquam illum. Obcaecati suscipit quis reiciendis esse, quia sunt amet doloremque voluptas, ad provident accusantium iusto optio tempore eos illo ab deserunt quas enim deleniti perspiciatis itaque velit quos.</div>
						</div>
					</div>
					<div class="d-flex justify-content-end mb-5">
						<div class="btn-group btn-group-lg mr-5">
							<input type="submit" name="updateData" class="btn btn-success" value="Сохранить изменения" title="сохранение внесенных изменений">
							<input type="submit" name="deleteData" class="btn btn-danger" value="Удалить" title="удаление страны и её должностных лиц">
						</div>
					</div>
				</form>
			';
		}
	}