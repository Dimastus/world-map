<?php

session_start();

  echo '<script>setTimeout(\'location="/admin/new_admin.php"\', 0)</script>';
    

/*echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />";

echo "  <link rel=\"stylesheet\" type=\"text/css\" href=\"../css/bootstrap/css/bootstrap.min.css\">";
echo "  <link rel=\"stylesheet\" type=\"text/css\" href=\"../css/bootstrap/css/signin.css\">";


echo "<script type=\"text/javascript\" src=\"../css/bootstrap/js/jquery.min.js\"></script>";*//*

if($_SESSION['login']==''){
  echo "<span class=\"notAccess\">Вы не должны быть здесь!</span>";
  echo '<script>setTimeout(\'location="../admin/index.php"\', 2000)</script>';
}
elseif ($_SESSION['login']==true && ($_SESSION['id_role']==1 || $_SESSION['id_role']==2)){

  echo "<div class='container'>
	    <h2>Удаление должностного лица</h2>
        <div class='input-group mb-3'>
            <div class='input-group-prepend'>
              <label class='input-group-text' for=\"selected_country\">Выберите часть света</label>
            </div>
	        <select name=\"selected_country\" id=\"id_country_select\" class=\"form-control custom-select\" onchange=\"fun1()\" required>
	          <option selected disabled>---</option>
	          <option>Австралия и Океания</option>
	          <option>Азия</option>
	          <option>Африка</option>
	          <option>Европа</option>
	          <option>Северная Америка</option>
	          <option>Южная Америка</option>
	        </select>
	    </div>";
?>
<script>
  function fun1(){
    jQuery.noConflict();
    jQuery(function(){
      var $ = jQuery;
      var sel = document.getElementById("id_country_select");
      var txt = sel.options[sel.selectedIndex].text;
      $.ajax({
        type:'POST',
        url:'../php-script/request_to_DB_on_select_person.php',
        data:"param="+txt,
        success: function(html) {
          $(".info-box").html(html);
        }
      });
    });
  };
</script>
<?php
  if(isset($_POST['param'])){
		require_once 'data_to_db.php';
		require_once 'connect_to_db.php';
	    require_once 'protect.php';

	    $parametr = defend($_POST['param']);
		$query1 = "SELECT name_country FROM table_country WHERE continent_country = '" . $parametr . "' ORDER BY name_country ASC"; //запрос выборки данных из БД
		$result1 = $connection -> query($query1);//отправка запроса к MySQL
		if(!$result1) die ("<div class='alert alert-danger'>Сбой при доступе к БД: " . $connection -> error . "</div>");//в случае ошибки извлечения данных - вывод сообщения
		else {
			$rows = $result1 -> num_rows;
			//request_to_DB_on_select_person
			echo "<form action = 'index.php' method = 'post'>
			        <div class='input-group mb-3'>
			            <div class='input-group-prepend'>
			              <label class='input-group-text' for='name_country'>Выберите страну</label>
			            </div>
				        <select class=\"form-control custom-select\" name='name_country'>";
			              for($j = 0; $j < $rows; ++$j)
					      {
							echo "<option>";
					        $result1 -> data_seek($j);
					        $row = $result1 -> fetch_array(MYSQLI_NUM);//получение отдельной строки таблицы
					        foreach ($row as $key => $value) {
					           echo $value;
							    }
					        echo '</option>';
					      }
				echo "  </select>
						<div class='input-group-prepend'>
						  <input type = 'hidden' name = 'select_persons' value = 'yes'>
						  <input type = 'hidden' name = 'delete_persons' value = 'yes'>
					      <input type = 'submit' class=\"btn btn-outline-secondary\" value='Выбрать'>
					    </div>
				      </div>
			      </form>
		      </div><hr>";
		}
	}
}
else {
  echo "<div class='alert alert-danger'>У Вас нет доступа!</div>";
  echo '<script>setTimeout(\'location="../admin/index.php"\', 2000)</script>';//автоматическое перенаправление на страницу панели админа
}*/
?>