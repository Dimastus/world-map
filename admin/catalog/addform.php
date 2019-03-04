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
	
	$way = "../../php-script/";		//часть пути для подключения файлов

    // подключаемся к базе
	require_once $way . 'data_to_db.php';
	require_once $way . 'connect_to_db.php';
	/*подключить файл с функциями для загрузки картинок*/
	include_once $way . 'func_for_img.php';
	/*подключить файл с "обезвреживанием"*/
	require_once $way . 'protect.php';

	$query_index = "SELECT * FROM `table_country_indexes` ORDER BY name_country ASC";
	$result_index = $connection -> query($query_index);

	if(!$result_index) die("<div class='alert alert-danger'>Сбой при доступе к БД: " . $connection -> error . "</div>");
	else {
			$rows_index = $result_index -> num_rows;

			echo '
					<ul class="nav nav-tabs" id="myTab" role="tablist">
						<li class="nav-item">
							<a class="nav-link active" id="country-tab" data-toggle="tab" href="#country" role="tab" aria-controls="country" aria-selected="true">Страна</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" id="person-tab" data-toggle="tab" href="#person" role="tab" aria-controls="person" aria-selected="false">Должностное лицо</a>
						</li>
						<li class="nav-item">
							<a class="nav-link disabled" id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false">Вооружение</a>
						</li>
					</ul>
					<form method="post" action="catalog/insert_to_db.php" enctype="multipart/form-data">
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
											<td scope="col"><input type="text" name="name_country" class="input-group-text bg-light w-100" placeholder="Введите название страны" required></td>
											<td scope="col">
												<select class="form-control custom-select" name="index_country" id="selectIndexCountry" required>
												  <option selected disabled></option>';
													for($n = 0; $n < $rows_index; ++$n){
												        $result_index -> data_seek($n);
												        $row_index = $result_index -> fetch_array(MYSQLI_NUM);
														echo '<option value="' . $row_index[2] . '">' . $row_index[1] . ' = ' . $row_index[2] . '</option>';
													}
										echo '
												</select>
											</td>
											<td scope="col">
												<select class="form-control custom-select" name="continent_country" id="selectContinentCountry" required>
													<option selected disabled></option>';

			                                        $query_block = "SELECT continent FROM table_continent ORDER BY continent ASC";							                    
								                    require 'data_option.php';

													echo '</select>
											</td>
											<td scope="col">
												<select class="form-control custom-select" name="accessory_block" id="selectAccessoryBlock" required placeholder="Name">
													<option selected disabled></option>';

								                    $query_block = "SELECT name_block FROM table_block ORDER BY name_block ASC";							                    
								                    require 'data_option.php';

								                  echo '</select>
											</td>
											<td scope="col">  
												<input type="file" name="search_flag" class="btn btn-outline-secondary" id="exampleFormControlFile1">
											</td>
											<td scope="col">												
												<button class="btn btn-secondary btn-sm ml-4" type="button" data-toggle="collapse" data-target="#multiCollapseExCountry" aria-expanded="false" aria-controls="multiCollapseExCountry">Показать</button>
											</td>
										</tr>
										<tr>                
										  <td colspan="6">
										    <div class="collapse multi-collapse" id="multiCollapseExCountry">
										      <div class="card card-body">
										        <textarea id="infoAboutCountry" name="info_about_country" class="form-control mytextarea" aria-label="With textarea" placeholder="Введите информацию о стране" rows="8"></textarea>
										      </div>
										    <div>
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
											<th>Информация</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td>
												<input type="text" name="position_person" class="input-group-text bg-light w-100"  placeholder="Введите должность" required>
											</td>
											<td>
												<input type="text" name="full_name" class="input-group-text bg-light w-100"  placeholder="Введите фамилию имя отчество" required>
											</td>
											<td>
												<input type="file" class="btn btn-outline-secondary" name="search_foto">
											</td>
											<td>												
												<button class="btn btn-secondary btn-sm ml-4" type="button" data-toggle="collapse" data-target="#multiCollapseExPerson" aria-expanded="false" aria-controls="multiCollapseExPerson">Показать</button>
											</td>
										</tr>
										<tr>                
										  <td colspan="6">
										    <div class="collapse multi-collapse" id="multiCollapseExPerson">
										      <div class="card card-body">
										        <textarea id="infoAboutPerson" name="info_about_person" class="form-control mytextarea" aria-label="With textarea" placeholder="Введите информацию о должностном лице" rows="8"></textarea>
										      </div>
										    <div>
										  </td>
										</tr>
									</tbody>
								</table>
								<input type="button" name="plusPerson" class="btn btn-secondary btn-sm mr-5" value="+">

							</div>
							<div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
								<div class="text-warning display-1 font-weight-bold">...</div>
							</div>
						</div>
						<input type="submit" name="insertData" class="btn btn-success btn-lg float-right mr-5" value="Сохранить">
					</form>
			';
	}