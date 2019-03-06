<?php
session_start();


  echo '<script>setTimeout(\'location="/admin/new_admin.php"\', 0)</script>';



if($_SESSION['login']==''){
  echo "<span class=\"alert alert-danger\">Вы не должны быть здесь!</span>";
  echo '<script>setTimeout(\'location="../admin/index.php"\', 2000)</script>';//автоматическое перенаправление на страницу панели админа
}
elseif ($_SESSION['login']==true && ($_SESSION['id_role']==1 || $_SESSION['id_role']==2)) {

	/*
	require_once 'data_to_db.php';
	require_once 'connect_to_db.php';
	
	$query = "SELECT * FROM table_users";
	$result = $connection -> query($query);
	if(!$result) die ("<div class='alert alert-danger'>Сбой при доступе к БД: " . $connection -> error . "</div>");
	$rows = $result -> num_rows;

	echo "<div class='container-fluid w-75'>
	        <h1>Таблица пользователей</h1>
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
					<button class='btn btn-light btn-sm ml-4' type='button' data-toggle='collapse' data-target='#multiCollapseEx" . $myrow['id'] . "' aria-expanded='false' aria-controls='multiCollapseEx" . $myrow['id'] . "'>Показать</button>
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
								    <form action='index.php' method='post'>
								    	<div class='input-group mb-3'>
									      <select name='sel_priv' class=\"form-control custom-select\">
									        <option value='1'>администратор сайта</option>
									        <option value='2'>администратор БД</option>
									        <option value='3'>пользователь</option>
									      </select>
								    	  <div class='input-group-prepend'>
										      <input type='hidden' name='up_priv' value='yes'>
										      <input type='hidden' name='id' value='" . $myrow['id'] . "'>
										      <input type='submit' name='up_privilege' class='btn btn-success' value='применить'>
									      </div>
								      </div>
								    </form>  
								</td>
								<td class='d-flex justify-content-center'>
									<button type='button' class='btn btn-primary' data-toggle='modal' data-target='#exampleModal" . $myrow['id'] . "' onclick='editCountry(" . $myrow['id'] . ")'>
									  Изменить
									</button>
								  	<div class='modal fade text-dark' id='exampleModal" . $myrow['id'] . "' tabindex='-1' role='dialog' aria-labelledby='exampleModalLabel' aria-hidden='true'>
								  	  <div class='modal-dialog modal-dialog-centered' role='document'>
								  	    <div class='modal-content'>
											<div class='edit_country_person'></div>
								  	    </div>
								  	  </div>
								  	</div>
								</td>";

							echo "<td>
									<form action='index.php' method='post'>
									    <div class='input-group mb-3 justify-content-center'>
										    <div class='input-group-prepend'>
										      <input type='hidden' name='delete' value='yes'>
										      <input type='hidden' name='id' value='" . $myrow['id'] . "'>
										      <input type='submit' name='delete_user' class='btn btn-danger' value='удалить'>
										    </div>
										</div>
								    </form>  
							    </td>
							</tr>	
						</table>
					</div>
				</td>";

		$query_continent = "SELECT `table_country`.`continent_country` FROM `table_country` JOIN `table_for_tc-tu` ft1  ON ft1.`id_table_country` = `table_country`.`id_country` JOIN `table_users` t1  ON t1.`id` = ft1.`id_table_users` JOIN `table_for_tc-tu` ft2  ON ft2.`id_table_country` = `table_country`.`id_country` JOIN `table_users` t2  ON t2.`id` = ft2.`id_table_users` AND t2.`login` = '" . $myrow['login'] . "' GROUP BY `table_country`.`continent_country`";
		$result_continent = $connection -> query($query_continent);
		if(!$result_continent) die("<div class='alert alert-danger'>Сбой при доступе к БД: " . $connection -> error . "</div>");
		else {
				echo "<ul>";
	    		$rows_continent = $result_continent -> num_rows;
				for($n = 0; $n < $rows_continent; ++$n)
			    {
			        $result_continent -> data_seek($n);
			        $row_continent = $result_continent -> fetch_array(MYSQLI_NUM);//получение отдельной строки таблицы
			        //print_r($row_continent);						        
			        echo "<li>" . $row_continent[0] . "</li><ol>";
					$query_country = "SELECT `table_country`.`name_country` FROM `table_country` JOIN `table_for_tc-tu` ft1  ON ft1.`id_table_country` = `table_country`.`id_country` JOIN `table_users` t1  ON t1.`id` = ft1.`id_table_users` JOIN `table_for_tc-tu` ft2  ON ft2.`id_table_country` = `table_country`.`id_country` JOIN `table_users` t2  ON t2.`id` = ft2.`id_table_users` AND t2.`login` = '" . $myrow['login'] . "' AND `table_country`.`continent_country` = '" . $row_continent[0] . "' GROUP BY `table_country`.`name_country`";
			        $result_country = $connection -> query($query_country);
			        if(!$result_country) die ("<div class='alert alert-danger'>Сбой при доступе к БД: " . $connection -> error . "</div>");
			        else {
			        	$rows_country = $result_country -> num_rows;
			        	for ($m=0; $m < $rows_country; $m++) { 
			        		$result_country -> data_seek($m);
			        		$row_country = $result_country -> fetch_array(MYSQLI_NUM);
			        		//print_r($row_country);
			        		echo "<li>" . $row_country[0] . "</li>";
			        	}
			        }
			        echo "</ol>";
			    }		
			    echo "</ul>";					    
		}
		echo "</td></tr>";
	}
	//справка по привилегиям (bootstrap tooltip)
	echo"</tbody></table></div><hr>";
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
			          url: '../php-script/edit_country.php',
			          type: 'POST',
			          data: {code: i},
			          async: false,
			          success: function(html) {
			            $('.edit_country_person').html(html);
			      }
			    });
			  });
			};
		</script>"; */ 
}
else {
  echo "<div class='alert alert-danger'>У Вас нет доступа!</div>";
  echo '<script>setTimeout(\'location="../admin/index.php"\', 2000)</script>';//автоматическое перенаправление на страницу панели админа
}