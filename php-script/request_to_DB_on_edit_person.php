<?php
session_start();

  echo '<script>setTimeout(\'location="/admin/new_admin.php"\', 0)</script>';

  
/*echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />";

echo "  <link rel=\"stylesheet\" type=\"text/css\" href=\"../css/bootstrap/css/bootstrap.min.css\">";
echo "  <link rel=\"stylesheet\" type=\"text/css\" href=\"../css/bootstrap/css/signin.css\">";


echo "<script type=\"text/javascript\" src=\"../css/bootstrap/js/jquery.min.js\"></script>";*//*

if($_SESSION['login']==''){
  echo "<span class=\"alert alert-danger\">Вы не должны быть здесь!</span>";
  echo '<script>setTimeout(\'location="../admin/index.php"\', 2000)</script>';//автоматическое перенаправление на страницу панели админа
}
elseif ($_SESSION['login']==true && ($_SESSION['id_role']==1 || $_SESSION['id_role']==2)){

  echo "<div class='container'>
          <h2>Редактирование должностного лица</h2>
          <div class='input-group mb-3'>
            <div class='input-group-prepend'>
              <label class='input-group-text' for=\"id_country_select\">Выберите часть света</label>
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
        url:'../php-script/request_to_DB_on_edit_person.php',
        data:"param="+txt,
        success: function(html) {
          $(".info-box").html(html);
        }
      });
    });
  };
</script>
<?php

  if(isset($_POST['edit'])){   
    require_once 'data_to_db.php';
    require_once 'connect_to_db.php';
    include_once 'func_for_img.php';
    require_once 'protect.php';

    //print_r($_POST);

    foreach ($_POST['user'] as $user_id => $user_data) {

      $position_person = defend($user_data['position_person']);
      $full_name = defend($user_data['full_name']);
      $reference_info = defend($user_data['reference_info']);
      $sel_country = defend($_POST['sel_country']);
      $id_person = defend($user_data['id_person']);
      $old_foto = defend($user_data['old_foto']);
      // print_r($_FILES);

      if($_FILES["$id_person"]['name']['search_foto_edit'] == '') {
        $foto = defend($user_data['old_foto']);
        // echo "netu";
      }
      else {
        // echo $_FILES["$id_person"]['name']['search_foto_edit'];
        //удаление старого изображения 
        if(!unlink("../" . $old_foto)){ //функция удаления
          echo "/nОшибка удаления изображения";
        }
        // проверяем, можно ли загружать изображение
        $check = can_upload_more($_FILES["$id_person"]);

        if($check === true){
          // загружаем изображение на сервер
          $full_name_photo = make_upload_more($_FILES["$id_person"]);
          $foto =  $full_name_photo ;
          echo "<div class='alert alert-success'>Файл <strong>$full_name_photo</strong> успешно загружен!</div>";
        }
        else {
          // выводим сообщение об ошибке
          echo "<strong class='alert alert-danger'>$check</strong>";
        }
      }

      $query3 = "UPDATE table_persons SET position_person = '". $position_person .
                      "', full_name = '" . $full_name .
                      "', reference_info = '" . $reference_info .
                      "', foto = '" . $foto .
                      "' WHERE table_persons.id_country = (SELECT table_country.id_country FROM table_country WHERE table_country.name_country = '" . $sel_country . "') AND id_position = '" . $id_person . "'";//запрос для БД на обновление данных

      if(!$result3 = $connection -> query($query3)) {
        echo "<div class='alert alert-danger'>Сбой при выборе данных: $query3<br/>" . $connection -> error . "<br/></div>";//если произошла ошибка, вывод сообщения
        echo '<script>setTimeout(\'location="../admin/index.php"\', 2000)</script>';
      }
      else {
        echo "<div class='alert alert-success'>Запись изменена</div>";
        echo '<script>setTimeout(\'location="../admin/index.php"\', 2000)</script>';
      }
    }
  }

  if(isset($_POST['param'])) {
  	require_once 'data_to_db.php';
  	require_once 'connect_to_db.php';
  	include_once 'func_for_img.php';
    require_once 'protect.php';

  	//echo "<h2>Редактирование информации о должностных лицах</h2>";

    $parametr = defend($_POST['param']);
  	$query1 = "SELECT name_country FROM table_country WHERE continent_country = '" . $parametr . "' ORDER BY name_country ASC"; //запрос выборки данных из БД
  	$result1 = $connection -> query($query1);//отправка запроса к MySQL
  	if(!$result1) die ("<div class='alert alert-danger'>Сбой при доступе к БД: " . $connection -> error . "</div>");//в случае ошибки извлечения данных - вывод сообщения
  	else {
  		$rows = $result1 -> num_rows;
  		echo " <form action = 'index.php' method = 'post' enctype=\"multipart/form-data\">
  		          <div class='input-group mb-3'>
                  <div class='input-group-prepend'>
                    <label class='input-group-text' for='name_country'>Выберите страну</label>
                  </div>
    		          <select name='name_country' class=\"form-control custom-select\">";
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
    		echo "    </select>
                </div>
      				  <input type='hidden' name='select_persons' value='yes'>
      				  <input type='hidden' name='edit_persons' value='yes'>
  		          <input type='submit' class='btn btn-primary' value='Выбрать'>
  		        </form></div><hr>";
  	}
  }
}
else {
  echo "<div class='alert alert-danger'>У Вас нет доступа!</div>";
  echo '<script>setTimeout(\'location="../admin/index.php"\', 2000)</script>';//автоматическое перенаправление на страницу панели админа
}*/
?>