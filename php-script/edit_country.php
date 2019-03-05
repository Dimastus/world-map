<?php

	session_start();

	if($_SESSION['login']=='') {

		echo ' 
			<link rel="stylesheet" type="text/css" href="../../css/bootstrap/css/bootstrap.min.css">
			<link rel="stylesheet" type="text/css" href="../../css/bootstrap/css/signin.css">';

		echo "<div class=\"alert alert-danger\">Вы не должны быть здесь!</div>";
		echo '<script>setTimeout(\'location="../new_admin.php"\', 2000)</script>';

	}
	elseif ($_SESSION['login']==true && ($_SESSION['id_role']==1 || $_SESSION['id_role']==2)) {

		/*подключить файл с переменными БД*/
		require_once 'data_to_db.php';
		/*подключить файл с созданием соединения БД*/
		require_once 'connect_to_db.php';
		/*подключить файл с "обезвреживанием"*/
		require_once 'protect.php';

		$code = defend($_POST['code']);

		  echo '
			  <div class="modal-header bg-secondary p-1 pl-3 m-0">
		  		  <h5 class="modal-title text-warning font-weight-bold">Внесение изменений</h5>
				  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
					  <span aria-hidden="true">&times;</span>
				  </button>
			  </div>
			  <div class="modal-body" style="width: 100%; overflow-y: scroll; height: 40vh">';

			echo "<table class='table table-hover'>
					<thead>
						<tr>
							<th>Страна</th>
							<th>Удаление из списка</th>
						</tr>
						</thead><tbody>";
						$query1 = "SELECT `table_country`.* FROM `table_country` JOIN `table_for_tc-tu` ft1  ON ft1.`id_table_country` = `table_country`.`id_country` JOIN `table_users` t1  ON t1.`id` = ft1.`id_table_users` JOIN `table_for_tc-tu` ft2  ON ft2.`id_table_country` = `table_country`.`id_country` JOIN `table_users` t2  ON t2.`id` = ft2.`id_table_users` AND t2.`id` = '" . $code . "' GROUP BY `table_country`.`name_country`"; //запрос выборки данных из БД
			        	$result1 = $connection -> query($query1);//отправка запроса к MySQL
			        	if(!$result1) die ("<div class='alert alert-danger'>Сбой при доступе к БД: " . $connection -> error . "</div>");//в случае ошибки извлечения данных - вывод сообщения
			        	else {
			        		$rows_edit_country = $result1 -> num_rows;
							for($j = 0; $j < $rows_edit_country; ++$j)
						    {
						        $result1 -> data_seek($j);
						        $row_edit_country = $result1 -> fetch_array(MYSQLI_NUM);//получение отдельной строки таблицы
						        //print_r($row_edit_country);
						        echo "<tr>
						        <td><span style='cursor: pointer' title='$row_edit_country[2]'>" . $row_edit_country[1];
						        echo '</span></td>
									<td>
										<form action="index.php" method="post">
										    <div class="input-group mb-1 justify-content-center">
											    <div class="input-group-prepend">
											      <input type="hidden" name="delete" value="yes">
											      <input type="hidden" name="id_user" value=' . $code . '>
											      <input type="hidden" name="id_edit_country" value=' . $row_edit_country[0] . '>
											      <input type="submit" name="del_edit_country" class="btn btn-danger btn-sm rounded" value="Удалить">
											    </div>
											</div>
									    </form> 
									</td>
						        </tr>';
						    }				    
						}
				echo "</tbody>
				</table>			
		      	<form action='index.php' method='post'>
			      <div class='input-group mb-3'>
				  	<select class=\"form-control custom-select\" name='sel_country'>";
			    	  	$query_undef_country = "SELECT `name_country`, `id_country` FROM `table_country` LEFT JOIN `table_for_tc-tu` ON `table_country`.`id_country` = `table_for_tc-tu`.`id_table_country` WHERE `table_for_tc-tu`.`id_table_country` IS NULL"; //запрос выборки данных из БД
			        	$result_undef_country = $connection -> query($query_undef_country);//отправка запроса к MySQL
			        	if(!$result_undef_country) die ("<div class='alert alert-danger'>Сбой при доступе к БД: " . $connection -> error . "</div>");//в случае ошибки извлечения данных - вывод сообщения
			        	else {
			        		$rows_undef_country = $result_undef_country -> num_rows;      	        		
							for($j = 0; $j < $rows_undef_country; ++$j)
						    {
						        $result_undef_country -> data_seek($j);
						        $row_undef_country = $result_undef_country -> fetch_array(MYSQLI_NUM);//получение отдельной строки таблицы
						        //print_r($row_undef_country);
						        echo "<option value=" . $row_undef_country[1] . ">" . $row_undef_country[0] . "</option>";
						    }
						}
		    echo "	</select>
				    <div class='input-group-prepend'>
				      <input type='hidden' name='add' value='yes'>
				      <input type='hidden' name='id_user' value='" . $code . "'>
				      <input type='submit' name='add_edit_country' class='btn btn-success rounded-right' value='добавить'>
				    </div>
			      </div>
				</form> ";

		echo '	  </div></div>
			  <div class="modal-footer bg-secondary p-1 m-0">
				  <button type="button" class="btn btn-danger" data-dismiss="modal">Закрыть</button>
			  </div>
		  ';

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