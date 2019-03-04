<?php
	
	//Подключение визуального редактора tinymce
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

	$name_country_old = defend(my_ucfirst($_POST['nameCountry']));
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
			$info_about_country = $row_country_old[5];
			$index_country = defend($row_country_old[2]);
			$continent_country = defend($row_country_old[3]);
			$block_country = defend($row_country_old[4]);

			$modalWindow_country = "'modal_window.php'";
			$whatThis_country = "'country'";
			$whatThisId_country = $row_country_old[0];
			$way_country = "'info-modal-box'";

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
				<div class="tab-content bg-white p-4" id="myTabContent">
					<div class="tab-pane fade show active" id="country" role="tabpanel" aria-labelledby="country-tab">
						<form action="catalog/insert_to_db.php" method="post" enctype="multipart/form-data">
							<table class="table table-hover table-dark text-center">
								<thead>
									<tr>
										<th>Флаг</th>
										<th>Название</th>
										<th>Индекс</th>
										<th>Часть света</th>
										<th>Блок</th>
										<th>Информация</th>
										<th>Редактирование</th>
										<th>Удаление</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td><img src="../' . $row_country_old[6] . '" width="150px" class="img-thumbnail bg-dark"></img></td>
										<td><span class="text-light">' . $name_country . '</span></td>
										<td><span class="text-light">' . $index_country . '</span></td>
										<td><span class="text-light">' . $continent_country . '</span></td>
										<td><span class="text-light">' . $block_country . '</span></td>
										<td>
											<button class="btn btn-success btn-sm" type="button" data-toggle="collapse" data-target="#multiCollapseInformation" aria-expanded="false" aria-controls="multiCollapseInformation">Показать</button>
										</td>
										<td>
											<!-- Button trigger modal -->
											<button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#exampleModal" onclick="sendData(' . $modalWindow_country . ', null, ' . $whatThis_country . ', ' . $whatThisId_country . ', ' . $way_country . ')">
												редактировать
											</button>
										</td>
										<td>
											<input type="submit" name="deleteData" class="btn btn-danger btn-sm" value="Удалить" title="удаление страны и её должностных лиц">
										</td>
									</tr>
									<tr>                
										<td colspan="8">
											<div class="collapse multi-collapse" id="multiCollapseInformation">
												<div class="bg-dark">
													<article class="form-control text-left" aria-label="With textarea" placeholder="Информация">' . $info_about_country . '</article>
												</div>
											</div>
										</td>
									</tr>
								</tbody>
							</table>
						</form>
					</div>
					<div class="tab-pane fade text-center" id="person" role="tabpanel" aria-labelledby="person-tab">';
						$query_person_old = "SELECT * FROM table_persons WHERE table_persons.id_country = (SELECT table_country.id_country FROM table_country WHERE table_country.name_country = '" . $name_country . "')";

						if(!$result_person_old = $connection -> query($query_person_old)){
							echo "<div class='alert alert-danger'>Сбой при выборе данных: $query_person_old<br>" . $connection -> error . "<br><br></div>";
						}
						$rows_person_old = $result_person_old -> num_rows;
						echo "<div class='container-fluid bg-dark p-0'>
								<div class='row m-0 p-0'>";
						for($i = 0; $i < $rows_person_old; ++$i){
							$result_person_old -> data_seek($i);
							$row_person_old = $result_person_old -> fetch_array(MYSQLI_NUM);							
							$position_person = defend($row_person_old[1]);
							$full_name = defend($row_person_old[2]);
							$reference_info = defend($row_person_old[3]);
								//<input type="text" name="position_person" class="input-group-text bg-light w-100"  placeholder="Введите должность" required value="' . $position_person . '">

							$modalWindow_person = '"modal_window.php"';
							$whatThis_person = '"person"';
							$whatThisId_person = $row_person_old[0];
							$way_person = '"info-modal-box"';

							$way_to_icon = "../../img/icons/";

							echo "		
									<div class='col-6 border border-light p-0'>									
										<div class='container p-1'>
											<div class='row m-0 p-0'>
												<div class='col-3 p-1'>
													<img src='../$row_person_old[6]' width='150px' class='img-thumbnail bg-dark'></img>
												</div>
												<div class='col-8 text-left text-light p-1'>
													<div>$position_person</div>		
													<div>$full_name</div>
													<article id='infoAboutPerson$row_person_old[0]' name='user[$row_person_old[0]][reference_info]' class='text-up-admin'>$reference_info</article>
												</div>
												<div class='col-1 p-0'>
													<div class='d-block my-1'>
														<button name='editPerson' class='btn btn-outline-primary btn-md p-0' title='редактирование'  data-toggle='modal' data-target='#exampleModal' onclick='sendData(" . $modalWindow_person . ", null, " . $whatThis_person . ", " . $whatThisId_person . ", " . $way_person . ")'>
																<img src='" . $way_to_icon . "icons8-pencil.png' width='35px'>
														</button>
													</div>
													<div class='d-block my-1'>
														<button name='delPerson' class='btn btn-outline-danger btn-md p-0' title='удаление' onclick=requestData('$del_person1')>
																<img src='" . $way_to_icon . "icons8-delete.png' width='35px'>
														</button>
													</div>
													<div class='d-block my-1'>
														<button id='btn$row_person_old[0]' class='btn btn-outline-success btn-md p-0' onclick=\"topDown('infoAboutPerson$row_person_old[0]', 'btn$row_person_old[0]')\" title='показать больше информации'>
																<img src='" . $way_to_icon . "icons8-arrow-down.png' width='35px'>
														</button>
													</div>
												</div>
											</div>
										</div>	
									</div>";
						}
						echo "  </div>
							</div>";
						echo '
						<input type="button" name="plusPerson" class="btn btn-secondary btn-sm" value="Добавить должностное лицо">
					</div>
					<div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
					</div>
				</div>

				<!-- Modal -->
				<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
					<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
						<div class="modal-content">
							<div class="modal-header bg-secondary p-1 pl-3 m-0">
								<h5 class="modal-title text-warning font-weight-bold" id="exampleModalLabel">Окно редактирования</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class="modal-body myModal">
								<div class="info-modal-box"></div>
							</div>
							<div class="modal-footer bg-secondary p-1 m-0">
								<button type="button" class="btn btn-danger" data-dismiss="modal">Закрыть</button>
							</div>
						</div>
					</div>
				</div>
			';

			echo "<script>
					function topDown(number, but){
					  var btn = document.getElementById(but);
					  var link = document.getElementById(number);
					  if(link.className === 'text-up-admin') {
					    link.className += ' text-down-admin';
						btn.innerHTML = \"<img src='../../img/icons/icons8-arrow-up.png' width='35px'>\";
					    btn.setAttribute('title', 'скрыть информацию');
					  }
					  else {
					    link.className = 'text-up-admin';
						btn.innerHTML = \"<img src='../../img/icons/icons8-arrow-down.png' width='35px'>\";
					    btn.setAttribute('title', 'показать больше информации');
					  }
					}
				</script>";
		}
	}