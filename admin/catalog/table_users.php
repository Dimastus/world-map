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

		$way = "../../php-script/";	//часть пути для подключения файлов

		// подключаемся к базе
		require_once $way . 'data_to_db.php';
		require_once $way . 'connect_to_db.php';
		/*подключить файл с функциями*/
		require_once 'function.php';

		$query = "SELECT * FROM table_users ORDER BY `table_users`.`id_role`";
		$result = $connection -> query($query); //извлекаем из базы все данные о пользователе с введенным логином
		if(!$result) die ("<div class='alert alert-danger'>Сбой при доступе к БД: " . $connection -> error . "</div>");
		$rows = $result -> num_rows;

		echo "<div class='container-fluid mt-3'>
		        <table class=\"table table-hover table-dark text-center justify-content-center\">
		          <thead>
		            <tr>
		              <th>Логин</th>
		              <th>Уровень привилегий</th>
		              <th>Pедактируемые страны</th>
		              <th>Дополнительно</th>
		            </tr>
		          </thead>
		          <tbody>";

		for($i=0; $i<$rows; $i++){ 

			$myrow = $result -> fetch_array(MYSQLI_ASSOC);
			echo "	<tr>
					  <th scope='row'>" . $myrow['login'] . "</th>";
				echo "<td data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"'Aдминистратора сайта' имеет возможность управлять пользователями и изменять информацию о стране и её должностных лицах;\n'Aдминистратора БД' имеет возможность только изменять информацию о стране и её должностных лицах;\n'Пользователь сайта' используется только для регистрации пользователя в БД.\"><strong class='replace'>" . $myrow['id_role'] . "</strong></td>
					  <td>
				    	<div class='input-group mb-3 justify-content-center'>
					      <div name='edit_country'>";
					        	$query1 = "SELECT `table_country`.* FROM `table_country` JOIN `table_for_tc-tu` ft1  ON ft1.`id_table_country` = `table_country`.`id_country` JOIN `table_users` t1  ON t1.`id` = ft1.`id_table_users` JOIN `table_for_tc-tu` ft2  ON ft2.`id_table_country` = `table_country`.`id_country` JOIN `table_users` t2  ON t2.`id` = ft2.`id_table_users` AND t2.`login` = '" . $myrow['login'] . "' GROUP BY `table_country`.`name_country`"; //запрос выборки данных из БД
					        	$result1 = $connection -> query($query1);//отправка запроса к MySQL
					        	if(!$result1) die ("<div class='alert alert-danger'>Сбой при доступе к БД: " . $connection -> error . "</div>");//в случае ошибки извлечения данных - вывод сообщения
					        	else {
					        		$rows_edit_country = $result1 -> num_rows;
									for($j = 0; $j < $rows_edit_country; ++$j)
								    {
								        $result1 -> data_seek($j);
								        $row_edit_country = $result1 -> fetch_array(MYSQLI_NUM);//получение отдельной строки таблицы
								        //print_r($row_edit_country);
								        echo "<span title='$row_edit_country[2]' style='cursor: pointer'>" . $row_edit_country[1];
								        echo '</span>,  ';
								    }		
								    echo "<br>
									      </div>";
								}
					  echo "</div>				    	  
					      </div>
						</td>
					  <td>					  
						<button class='btn btn-outline-secondary btn-sm ml-4 p-0' type='button' data-toggle='collapse' data-target='#multiCollapseEx" . $myrow['id'] . "' aria-expanded='false' aria-controls='multiCollapseEx" . $myrow['id'] . "'>
							<img src='../../img/icons/icons8-information.png' width='35px'></button>
					  </td>
				  </tr>
				  <tr>
					<td colspan='4'>
				  		<div class='collapse multi-collapse' id='multiCollapseEx" . $myrow['id'] . "'>
				  			<table style='widht: 100%' class=\"table table-hover bg-secondary\">
					  			<tr>		  			
									<td>Изменить уровень привилегий на</td>
									<td>Изменение редактируемых стран</td>
									<td>Удаление пользователя</td>
					  			</tr>
					  			<tr>		  			
									<td>									
									    <form action='catalog/insert_to_db.php' method='post'>
									    	<div class='input-group mb-3'>
										      <select name='sel_priv' class=\"form-control custom-select\">
										        <option value='1'>администратор сайта</option>
										        <option value='2'>администратор БД</option>
										        <option value='3'>пользователь</option>
										      </select>
									    	  <div class='input-group-prepend'>
											      <input type='hidden' name='up_priv' value='yes'>
											      <input type='hidden' name='id' value='" . $myrow['id'] . "'>
											      <input type='submit' name='up_privilege' class='btn btn-success rounded-right' value='применить'>
										      </div>
									      </div>
									    </form>  
									</td>
									<td class='d-flex justify-content-center'>
										<button type='button' class='btn btn-outline-primary m-0 p-1' data-toggle='modal' data-target='#editCountryModal" . $myrow['id'] . "' onclick='editCountry(" . $myrow['id'] . ")'>
											<img src='/img/icons/icons8-pencil.png' width='35px'>
										</button>
										<div class='modal fade text-dark' id='editCountryModal" . $myrow['id'] . "' tabnew_admin='-1' role='dialog' aria-labelledby='editCountryModalLabel' aria-hidden='true'>
											<div class='modal-dialog modal-dialog-centered' role='document'>
												<div class='modal-content'>
													<div class='edit_country_person'></div>
												</div>
											</div>
										</div>
									</td>";

								echo "<td>
										<form action='catalog/insert_to_db.php' method='post'>
										    <div class='input-group mb-3 justify-content-center'>
											    <div class='input-group-prepend'>
													<input type='hidden' name='delete' value='yes'>
													<input type='hidden' name='id' value='" . $myrow['id'] . "'>
													<input type='submit' name='delete_user' class='btn btn-outline-danger rounded font-weight-bold' value='удалить'>
											    </div>
											</div>
									    </form>  
								    </td>
								</tr>	
							</table>
						</div>
					</td>";
			echo "</td></tr>";
			
		}
		//справка по привилегиям (bootstrap tooltip)
		echo"</tbody></table></div>";
		echo "<script>
			    var z = document.getElementsByClassName('replace');
			    innerHTML = z.innerHTML;
			    textContent = z.textContent;
			    //console.log(innerHTML); 
			    //console.log(textContent); 
			    for(var i = 0; i < z.length; i++){
			    	//console.log(z[i]);		    
				    switch(z[i].innerHTML){
				      case '1':
				        z[i].innerHTML = 'Администратор сайта';
				        break;
				      case '2':
				        z[i].innerHTML = 'Администратор БД';
				        break;
				      case '3':
				        z[i].innerHTML = 'Пользователь';
				        break;
				    }
				};   
			  </script>";
		echo "<script>
				function editCountry(i){
				  jQuery.noConflict();
				  jQuery(function(){
				    var $ = jQuery;
				      $.ajax({
				          url: 'catalog/edit_country.php',
				          type: 'POST',
				          data: {code: i},
				          async: false,
				          success: function(html) {
				            $('.edit_country_person').html(html);
				      }
				    });
				  });
				};
			</script>";

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