<?php
session_start();

if($_SESSION['login']==''){
  echo "<span class=\"alert alert-danger\">Вы не должны быть здесь!</span>";
  echo '<script>setTimeout(\'location="../admin/index.php"\', 2000)</script>';//автоматическое перенаправление на страницу панели админа
}
elseif ($_SESSION['login']==true && ($_SESSION['id_role']==1 || $_SESSION['id_role']==2)){
	/*подключить файл с переменными БД*/
	require_once 'data_to_db.php';
	/*подключить файл с созданием соединения БД*/
	require_once 'connect_to_db.php';

	$query = "SELECT * FROM table_users";
	$result = $connection -> query($query); //извлекаем из базы все данные о пользователе с введенным логином
	if(!$result) die ("<div class='alert alert-danger'>Сбой при доступе к БД: " . $connection -> error . "</div>");
	$rows = $result -> num_rows;

	echo "<div class='container-fluid w-75'>
	        <h1>Таблица пользователей</h1>
	        <table class=\"table table-hover table-dark text-center justify-content-center\">
	          <thead>
	            <tr>
	              <th scope=\"col\">Логин</th>
	              <th scope=\"col\">Уровень привилегий</th>
	              <th scope=\"col\">Pедактируемые страны</th>
	              <th scope=\"col\">Дополнительно</th>
	              <th scope=\"col\">Directory Tree</th>
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
		echo "<td>";

		/* дерево стран, принадлежащие определенному пользователю */
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
	echo '
		<ul class="nav flex-column bg-dark float-left mr-5 mb-5" style="width: 300px">
		  <li class="nav-item">
		    <a class="nav-link active text-white font-weight-bold" href="#">Africa</a>
			    <ol class="nav flex-column bg-dark text-white ml-5" style="width: 200px">
			      <li class="nav-item">
			        <a class="nav-link active text-white" href="#">Africa</a>
			      </li>
			      <li class="nav-item">
			        <a class="nav-link text-white" href="#">Asia</a>
			      </li>
			      <li class="nav-item">
			        <a class="nav-link text-white" href="#">Europe</a>
			      </li>
			      <li class="nav-item">
			        <a class="nav-link text-white" href="#">United State of Amerika</a>
			      </li>
			    </ol>
		  </li>
		  <li class="nav-item">
		    <a class="nav-link text-white font-weight-bold" href="#">Asia</a>
			    <ol class="nav flex-column bg-dark text-white ml-5" style="width: 200px">
			      <li class="nav-item">
			        <a class="nav-link active text-white" href="#">Africa</a>
			      </li>
			      <li class="nav-item">
			        <a class="nav-link text-white" href="#">Asia</a>
			      </li>
			      <li class="nav-item">
			        <a class="nav-link text-white" href="#">Europe</a>
			      </li>
			      <li class="nav-item">
			        <a class="nav-link text-white" href="#">United State of Amerika</a>
			      </li>
			    </ol>
		  </li>
		  <li class="nav-item">
		    <a class="nav-link text-white font-weight-bold" href="#">Europe</a>
			    <ol class="nav flex-column bg-dark text-white ml-5" style="width: 200px">
			      <li class="nav-item">
			        <a class="nav-link active text-white" href="#">Africa</a>
			      </li>
			      <li class="nav-item">
			        <a class="nav-link text-white" href="#">Asia</a>
			      </li>
			      <li class="nav-item">
			        <a class="nav-link text-white" href="#">Europe</a>
			      </li>
			      <li class="nav-item">
			        <a class="nav-link text-white" href="#">United State of Amerika</a>
			      </li>
			    </ol>
		  </li>
		  <li><input type="submit" name="asd" class="btn btn-warning btn-block mt-5" value="table person"></li>
		</ul>
		<div class="container-fluid">
			<ul class="nav nav-tabs" id="myTab" role="tablist">
			  <li class="nav-item">
			    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Country</a>
			  </li>
			  <li class="nav-item">
			    <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Person</a>
			  </li>
			  <li class="nav-item">
			    <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false">Tools</a>
			  </li>
			</ul>
				<form>
			<div class="tab-content bg-white p-4" id="myTabContent">
			  <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
				  Lorem ipsum dolor sit amet, consectetur adipisicing elit. Iusto, vitae minima dicta quisquam laborum praesentium voluptatum, nulla possimus hic suscipit ratione facere fuga fugit doloremque est laboriosam voluptatem soluta nam. Voluptas amet laborum animi fugit, sit, alias quam deserunt tempora voluptatem, tenetur asperiores repellendus. Omnis animi debitis inventore provident natus reiciendis assumenda reprehenderit corrupti explicabo, totam at perspiciatis accusamus nam eveniet consequatur, dignissimos ducimus. Earum ea eos atque sint dicta. Illum quaerat dolore at, repellat error dignissimos numquam cum unde cupiditate neque exercitationem accusamus provident voluptatem, minima mollitia veritatis eum, blanditiis. Ipsa alias possimus modi doloribus non corporis, quibusdam eligendi architecto animi aliquid dolorum. Perspiciatis necessitatibus modi ut quos exercitationem, tenetur, eveniet minus possimus inventore incidunt perferendis magni sunt cupiditate laudantium molestiae repellat labore recusandae itaque officiis sapiente quis! Mollitia laboriosam dolorem iure ducimus voluptate eligendi nesciunt, tempore magni! Voluptas omnis dolores ea non adipisci, est quidem deserunt atque enim quos nihil quasi, inventore sit magnam sapiente eius. Minima similique officiis nihil placeat, veniam, vero culpa eos unde numquam ex, perferendis illum deserunt expedita esse porro iusto excepturi saepe earum maiores sed consequuntur corporis quidem in? Cupiditate velit illum, neque enim dolores doloremque repellendus consequatur veniam odio vel perferendis, temporibus.
			  </div>
			  <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
				  Lorem ipsum dolor sit amet, consectetur adipisicing elit. Tenetur mollitia delectus a iusto saepe asperiores iste omnis, eos ipsa atque. Nesciunt dolore iure sequi aperiam. Nobis totam, laboriosam aut distinctio id ad reprehenderit corporis nihil amet iusto. Perspiciatis excepturi sunt corporis explicabo, modi, illum nisi rem quaerat odit nulla voluptate pariatur, ducimus accusamus ipsam fugit omnis dolorem ad rerum harum repellendus. Repellat culpa voluptatem provident dolorum autem ipsum, totam vero ex, earum delectus minus fuga illum magnam accusantium fugiat quidem sint sed ut, aperiam, quasi. Iste corporis libero ad eaque natus exercitationem tempore deleniti id magni quisquam. Natus incidunt et excepturi magnam itaque illo porro quae, harum nisi. Officia sit ducimus blanditiis assumenda corporis mollitia, doloremque, laudantium saepe iure eum in nihil perferendis laborum rem alias porro illum maxime deserunt, ex voluptas numquam fugit id facilis. Sed tempore consequuntur corporis non recusandae nesciunt eaque adipisci, nam! Est a id consequuntur, architecto provident fugiat ipsum numquam nulla repellendus obcaecati tempora autem voluptatem quas eius modi ipsa esse earum quos voluptas. Doloribus, perspiciatis veniam. Atque corrupti voluptatum ipsum illo eaque deserunt delectus maiores, expedita dolor reprehenderit enim, ad? Ab molestiae architecto adipisci aliquid, eligendi voluptatibus modi veniam ea! Totam, at facilis vel!
			  </div>
			  <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
				  Lorem ipsum dolor sit amet, consectetur adipisicing elit. Suscipit velit dolores voluptas amet sed delectus vitae accusantium aperiam saepe aliquid perferendis, non illum cupiditate, qui culpa, laudantium ullam. Ipsam iusto harum amet alias natus, officiis blanditiis tenetur, pariatur nisi voluptatibus, non saepe laudantium nemo incidunt et officia. In itaque veritatis officiis, id iure nobis aliquid laborum laudantium velit cupiditate obcaecati dicta repudiandae voluptates alias architecto culpa. Dolores eos consequuntur, est illum laborum veritatis beatae? Ex id reiciendis ut, natus tempore similique, eligendi non necessitatibus veritatis doloribus consequatur ipsa fuga ducimus veniam corporis quaerat odit velit voluptas laudantium maxime aperiam quibusdam, ratione assumenda. Rem, dolores dolorum qui laborum, iste reprehenderit, nihil tempora aspernatur eligendi repudiandae, maxime animi doloribus culpa quaerat voluptatum ut eaque eos harum sequi! Impedit, nobis consectetur voluptates deleniti earum expedita explicabo dolorem, nostrum quisquam ducimus ut aspernatur. Quibusdam voluptatibus cupiditate impedit, reprehenderit nemo itaque numquam, aperiam sequi, exercitationem, voluptatum porro eaque tempore odit? Consequatur, ab asperiores quisquam adipisci. Rem obcaecati non soluta, voluptatum quibusdam amet architecto doloremque sint, quo tempore esse quidem repudiandae? Harum illum nam deleniti, corporis natus odio dignissimos ipsum animi esse porro ducimus ab libero vitae aperiam, ipsam eos eius! Voluptatem veritatis veniam sapiente cupiditate!
			  </div>
			</div>
			  </form>
		</div>';
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
		</script>";  
}
else {
  echo "<div class='alert alert-danger'>У Вас нет доступа!</div>";
  echo '<script>setTimeout(\'location="../admin/index.php"\', 2000)</script>';//автоматическое перенаправление на страницу панели админа
}