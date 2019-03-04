<?php
  session_start();
?>
<!doctype html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <link rel="icon" href="../img/image/wrench.ico">
	<title>Панель администратора</title>

  <!-- Подключение Bootstrap -->
  <link rel="stylesheet" type="text/css" href="../css/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="../css/bootstrap/css/signin.css">

  <!-- Подключение визуального редактора tinymce -->
  <script src="../tinymce/tinymce.min.js"></script>
  <script>
    tinymce.init({ 
      selector:'.mytextarea',      
      language : "ru"
    });
  </script>
	<script>
    function universe__function(i){
      jQuery.noConflict();
      jQuery(function(){
        var $ = jQuery;
          $.ajax({
              url: "../php-script/" + i,
              type: 'POST',
              async: false,
              success: function(html) {
                $(".info-box").html(html);
          }
        });
      });
    };
	</script>
</head>
<body>
<?php
  mb_internal_encoding("UTF-8");
  if ($_SESSION['login']==''){
  echo <<<_END
    <div class="container">      
      <form action="../php-script/testreg.php" method="post" class="form-signin">
        <h2 class="form-signin-heading">Панель администратора</h2>
        <p>
          <label for="inputLogin" class="sr-only">Ваш логин:</label>
          <input id="inputLogin" name="login" type="text" size="15" maxlength="15" placeholder="Login" class="form-control" required="required">
        </p>
        <p>
          <label for="inputPassword" class="sr-only">Ваш пароль:</label>
          <input id="inputPassword" name="password" type="password" size="15" maxlength="15" placeholder="Password" class="form-control" required="required">
        </p>
        <p>
          <input type="submit" name="submit" value="Войти" class="btn btn-lg btn-primary btn-block">
          <br>
          <a href="../php-script/registration.php" class="btn btn-lg btn-danger btn-block">Зарегистрироваться</a>
          <br>
          <a href="/" class="btn btn-sm btn-secondary btn-block" target="_blank">Перейти на сайт</a>
        </p>        
      </form>
    </div>
_END;
}
  else {    
    // подключаемся к базе
    require_once '../php-script/data_to_db.php';
    require_once '../php-script/connect_to_db.php';
    /*подключить файл с функциями для загрузки картинок*/
    include_once '../php-script/func_for_img.php';
    /*подключить файл с "обезвреживанием"*/
    require_once '../php-script/protect.php';

    $query = "SELECT * FROM table_users WHERE login='" . $_SESSION['login'] . "'";
    $result = $connection -> query($query); //извлекаем из базы все данные о пользователе с введенным логином
    if(!$result) die ("<div class='alert alert-danger'>Сбой при доступе к БД: " . $connection -> error . "</div>");
    $myrow = $result -> fetch_array(MYSQLI_ASSOC);

    //вывод информации для админа сайта
    //вывод информации для админа БД
    if($myrow['id_role'] == 2 || $myrow['id_role'] == 1){

      echo "<nav class='navbar navbar-expand-lg navbar-dark bg-dark sticky-top'>
              <a class='navbar-brand' href='index.php'>Панель администратора</a>
              <button class='navbar-toggler' type='button' data-toggle='collapse' data-target='#navbarSupportedContent' aria-controls='navbarSupportedContent' aria-expanded='false' aria-label='Toggle navigation'>
                <span class='navbar-toggler-icon'></span>
              </button>
              <div class='collapse navbar-collapse' id='navbarSupportedContent'>
                <ul class='navbar-nav mr-auto'>";

      //таблица пользователей
      $table = "table_users.php";

      if($myrow['id_role'] == 1){
        echo <<<_END
              <li class='nav-item dropdown'>
                <a class='nav-link dropdown-toggle' href='#' id='navbarDropdown' role='button' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                  Таблица пользователей
                </a>
                <div class='dropdown-menu' aria-labelledby='navbarDropdown'>
                  <a class='dropdown-item' href='#' onclick="universe__function('$table')">Показать</a>
                </div>
              </li>
_END;
        //"обработчик" кнопки удаления пользователя
        if(isset($_POST['delete_user']) && $_POST['id']){
          $query_del = "DELETE FROM table_users WHERE id = " . $_POST['id'];
          $result_del = $connection -> query($query_del);
          if(!$result_del) die ("<div class='alert alert-danger'>Сбой при доступе к БД: " . $connection -> error . "</div>");
        }

        //"обработчик" кнопки удаления редактируемой страны у пользователя
        if(isset($_POST['del_edit_country']) && $_POST['id_user'] && $_POST['id_edit_country']){
          $query_del_edit = "DELETE FROM `table_for_tc-tu` WHERE id_table_users = " . $_POST['id_user'] . " AND id_table_country = " . $_POST['id_edit_country'];
          $result_del_edit = $connection -> query($query_del_edit);
          if(!$result_del_edit) die ("<div class='alert alert-danger'>Сбой при доступе к БД: " . $connection -> error . "</div>");
        }

        //"обработчик" кнопки добавления редактируемой страны пользователю
        if(isset($_POST['add_edit_country']) && $_POST['id_user']){
          $query_add_edit = "INSERT INTO `table_for_tc-tu` (`id`, `id_table_country`, `id_table_users`, `comment`) VALUES (NULL, '" . $_POST['sel_country'] . "', '" . $_POST['id_user'] . "', NULL)";
          $result_add_edit = $connection -> query($query_add_edit);
          if(!$result_add_edit) die ("<div class='alert alert-danger'>Сбой при доступе к БД: " . $connection -> error . "</div>");
        }

        //"обработчик" кнопки изменения привилегий пользователя
        if(isset($_POST['up_privilege']) && $_POST['id']){
          $query_up = "UPDATE table_users SET id_role = '" . $_POST['sel_priv'] . "' WHERE id = " . $_POST['id'];
          $result_up = $connection -> query($query_up);
          if(!$result_up) die ("<div class='alert alert-danger'>Сбой при доступе к БД: " . $connection -> error . "</div>");
          //echo '<script>setTimeout(\'location="admin_panel.php"\', 0)</script>';автоматическое перенаправление на страницу панели админа
        }

      }
      //меню админки  
      $add_country = "request_to_DB_on_add_country.php";
      $edit_country = "request_to_DB_on_select_country.php";
      $del_country = "request_to_DB_on_delete_country.php";
      $add_person = "request_to_DB_on_add_person.php";
      $edit_person = "request_to_DB_on_edit_person.php";
      $del_person = "request_to_DB_on_select_person.php";

      echo <<<_END
              <li class='nav-item dropdown'>
                <a class='nav-link dropdown-toggle' href='#' id='navbarDropdown' role='button' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                  Страна
                </a>
                <div class='dropdown-menu' aria-labelledby='navbarDropdown'>
                  <a class='dropdown-item' href='#' onclick="universe__function('$add_country')">Добавить</a>
                  <a class='dropdown-item' href='#' onclick="universe__function('$edit_country')">Редактировать</a>
                  <a class='dropdown-item' href='#' onclick="universe__function('$del_country')">Удалить</a>
                </div>
              </li>
              <li class='nav-item dropdown'>
                <a class='nav-link dropdown-toggle' href='#' id='navbarDropdown' role='button' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                  Должностное лицо
                </a>
                <div class='dropdown-menu' aria-labelledby='navbarDropdown'>
                  <a class='dropdown-item' href='#' onclick="universe__function('$add_person')">Добавить</a>
                  <a class='dropdown-item' href='#' onclick="universe__function('$edit_person')">Редактировать</a>
                  <a class='dropdown-item' href='#' onclick="universe__function('$del_person')">Удалить</a>
                </div>
              </li>
            </ul>
_END;
          echo "<span class='navbar-text'>
                Добро пожаловать, <strong>" . $_SESSION['login'] . "</strong>
            </span>
            <form action='../php-script/session_destroy.php' method='post' class='form-inline'>
              <input type='submit' name='destroy' class='btn btn-dark btn-sm ml-2 mr-4' value='Выход'>
            </form>
          </div>
        </nav>";

      //вывод информации при помощи ajax в <div class="info-box">
      echo "<div class=\"info-box\"><h1 class='display-1 text-center my-5 py-5'><a href='new_admin.php' target='_blank'>NEW ADMIN PANEL</a></h1></div>";

      //"обработчик" кнопки добавления должностного лица
      if(isset($_POST['name_country']) && isset($_POST['position_person']) && isset($_POST['full_name']) && isset($_POST['reference_info']) && isset($_POST['foto_person']))
      {
          $name_country = defend($_POST['name_country']);//присваивание переменной $position_person значения
          $position_person = defend($_POST['position_person']);//присваивание переменной $position_person значения
          $full_name = defend($_POST['full_name']);//присваивание переменной $full_name значения
          $reference_info = defend($_POST['reference_info']);//присваивание переменной $reference_info значения
          //$foto_person = $_POST['foto_person'];//присваивание переменной $foto_person значения

          /*выбор картинок*/
          if(isset($_FILES['search_foto'])) {
            // проверяем, можно ли загружать изображение
            $check = can_upload($_FILES['search_foto']);

            if($check === true){
              // загружаем изображение на сервер
              $full_name_photo = make_upload($_FILES['search_foto']);
              $foto_person =  $full_name_photo ;
              echo "<div class='alert alert-success'>Файл <strong>$full_name_photo</strong> успешно загружен!</div>";
            }
            else{
              // выводим сообщение об ошибке
              echo "<strong class='alert alert-danger'>$check</strong>";
            }
          }

          $query1 = "INSERT INTO table_persons VALUES (NULL,
                              '" . $position_person . "',
                              '" . $full_name . "',
                              '" . $reference_info . "',
                              (SELECT table_country.id_country FROM table_country WHERE table_country.name_country = '" . $name_country . "'),
                              0,
                              '" . $foto_person . "')";//запрос на добавление данных в БД

          if(!$result1 = $connection -> query($query1)) die ("Сбой при вставке данных: " . $connection -> error . "<br> Number error: " . $connection -> errno);//в случае ошибки извлечения данных - вывод сообщения
          echo "<div class='alert alert-success'>Запись <strong>($position_person, $full_name, $reference_info)</strong> добавлена</div>";
          echo '<script>setTimeout(\'location="index.php"\', 3000)</script>';//автоматическое перенаправление на страницу панели админа
      }

      //"обработчик" кнопки удаления должностного лица
      if(isset($_POST['select_persons']) && isset($_POST['delete_persons']))//проверка отправки данных программе для всех полей
      {
          $name_country = defend($_POST['name_country']);
          $query1 = "SELECT * FROM table_persons WHERE table_persons.id_country = (SELECT table_country.id_country FROM table_country WHERE table_country.name_country = '" . $name_country . "')";//запрос для БД на выборку
          if(!$result1 = $connection -> query($query1)){
            echo "<div class='alert alert-danger'>Сбой при удалении данных: $query1<br>" . $connection -> error . "<br></div>";//если произошла ошибка, вывод сообщения
          }
          else {
              $rows = $result1 -> num_rows;
              echo "<div class='container'>
                      <h2>Tаблица с выбором должностных(-ого) лиц(-а)</h2>
                      <form action='../php-script/request_to_DB_on_delete_person.php' method='post'>
                      <table class=\"table table-hover table-dark\">
                        <thead>
                          <tr>
                            <th scope=\"col\">Выбор</th>
                            <th scope=\"col\">ФИО</th>
                            <th scope=\"col\">Должность</th>
                            <th scope=\"col\">Информация</th>
                            <th scope=\"col\">Фото</th>
                          </tr>
                      </thead>";
                      for($j = 0; $j < $rows; ++$j)
                      {
                        $result1 -> data_seek($j);
                        $row = $result1 -> fetch_array(MYSQLI_NUM);//получение отдельной строки таблицы

              echo" <tbody>
                    <tr>
                        <td align='center'><input type='checkbox' name='formDelete[]' value='$row[0]'></td>
                        <td>$row[2]</td>
                        <td>$row[1]</td>
                        <td>$row[3]</td>
                        <td><img src=\"../$row[6]\" width='75px'></img></td>
                      </tr>
                    </tbody>";
                }
            echo "</table>
                <input type='submit' name='delete' value='Удалить' class='btn btn-primary'>
                </form></div><hr>";
          }
      }

      //"обработчик" кнопки обновления информации о должностном лице
      if(isset($_POST['select_persons']) && isset($_POST['edit_persons']))
      {
        $name_country = defend($_POST['name_country']);
        $query2 = "SELECT * FROM table_persons WHERE table_persons.id_country = (SELECT table_country.id_country FROM table_country WHERE table_country.name_country = '$name_country')";//запрос к БД на выбор

        if(!$result2 = $connection -> query($query2)){
          echo "<div class='alert alert-danger'>Сбой при выборе данных: $query2<br>" . $connection -> error . "<br></div>";//если произошла ошибка, вывод сообщения
        }
        else {
          $rows2 = $result2 -> num_rows;
          echo "<div class='container-fluid w-75'>
                  <h2>Pедактированиe информации о должностном(-ых) лице(-ах)</h2>
                  <form action='../php-script/request_to_DB_on_edit_person.php' method='post' enctype=\"multipart/form-data\">
                  <table class=\"table table-hover table-dark\">
                    <thead>
                      <tr>
                        <th scope=\"col\">Занимаемая должность</th>
                        <th scope=\"col\">ФИО</th>
                        <th scope=\"col\">Информация о должностном лице</th>
                        <th scope=\"col\">Фото</th>
                      </tr>
                    </thead>
                    <tbody>";

        for($i = 0; $i < $rows2; ++$i)
        {
          $result2 -> data_seek($i);
          $row2 = $result2 -> fetch_array(MYSQLI_NUM);//получение отдельной строки таблицы
          $position_person = defend($row2[1]);
          $full_name = defend($row2[2]);
          $reference_info = defend($row2[3]);// name='user[$row2[0]][reference_info]'
          echo <<<_END
              <tr>
                <td>
                  <textarea name='user[$row2[0]][position_person]' class="form-control" aria-label="With textarea" placeholder="Должность">$position_person</textarea>
                  <input type='hidden' name='user[$row2[0]][id_person]' value='$row2[0]'>
                </td>
                <td>
                  <textarea name='user[$row2[0]][full_name]' class="form-control" aria-label="With textarea" placeholder="Фамилия Имя Отчество">$full_name</textarea>
                </td>
                <td style="width: 20%">
                  <button class="btn btn-secondary btn-sm ml-4" type="button" data-toggle="collapse" data-target="#multiCollapseEx$row2[0]" aria-expanded="false" aria-controls="multiCollapseEx$row2[0]">Показать</button>
                </td>
                <td style="width: 1%">
                    <img src="../$row2[6]" width='100px' class="foto"></img>
                    <input type="text" name='user[$row2[0]][old_foto]' value="$row2[6]" class="input-group-text bg-light skrit" readonly>
                    <input type='file' name='$row2[0][search_foto_edit]' class="btn btn-outline-secondary btn-sm">
                </td>
              </tr>
              <tr>                
                <td colspan='4'>
                  <div class="collapse multi-collapse" id="multiCollapseEx$row2[0]">
                    <div class="card card-body">
                      <textarea name='user[$row2[0]][reference_info]' class="form-control mytextarea" aria-label="With textarea" placeholder="Информация" rows="8">$reference_info</textarea>
                    </div>
                  </div>
                </td>
              </tr>
_END;
          }
            echo "</tbody></table>
              <input type='hidden' name='sel_country' value='" . defend($_POST['name_country']) . "'>
              <input type='submit' name='edit' value='Обновить данные' class='btn btn-primary'>
              </form></div><hr>";
        }
      }

      //"обработчик" кнопки удаления страны
      if(isset($_POST['delete_country']))//проверка отправки данных программе для всех полей
      {
        $name_country = defend($_POST['name_country']);

        $query2 = "SELECT flag_country FROM table_country WHERE name_country = '$name_country'";

        /*блок кода, который отвечает за удаление фотографий должностных лиц, страна которых подвержена удалению*/
        $query3 = "SELECT foto FROM table_persons WHERE table_persons.id_country = (SELECT table_country.id_country FROM table_country WHERE table_country.name_country = '$name_country')";//запрос к БД на выбор изображений должностных лиц, страну которых удаляем
        if(!$result3 = $connection -> query($query3)){
          echo "<div class='alert alert-danger'>Сбой при удалении данных: $query2<br>" . $connection -> error . "<br></div>";//если произошла ошибка, вывод сообщения
        }
        else {          
          /*Удаление фото должностных лиц страны*/              
          $fotoPers = $result3 -> num_rows;
          for($y = 0; $y < $fotoPers; ++$y)
          {
            $result3 -> data_seek($y);
            $rezPers = $result3 -> fetch_array(MYSQLI_NUM);//получение отдельной строки таблицы
            if(!unlink("../" . $rezPers[0])){ //функция удаления
              echo "/nОшибка удаления фото должностного лица";
            }
          }
        }


        if(!$result2 = $connection -> query($query2)){
          echo "<div class='alert alert-danger'>Сбой при удалении данных: $query2<br>" . $connection -> error . "<br></div>";//если произошла ошибка, вывод сообщения
        }
        else {
          $query1 = "DELETE FROM table_country WHERE name_country = '$name_country'";//запрос для БД на удаление
          if(!$result1 = $connection -> query($query1)){
            echo "<div class='alert alert-danger'>Сбой при удалении данных: $query1<br>" . $connection -> error . "<br></div>";//если произошла ошибка, вывод сообщения
          }
          else {
            $rows = $result2 -> num_rows;
            for($j = 0; $j < $rows; ++$j)
            {
              /*Удаление флага страны*/
              $result2 -> data_seek($j);
              $row = $result2 -> fetch_array(MYSQLI_NUM);//получение отдельной строки таблицы
              if(!unlink("../" . $row[0])){ //функция удаления
                echo "/nОшибка удаления флага страны";
              } 
            }            
            echo "<div class='alert alert-success'>Запись удалена</div>";
          }
        }
      }

      //"обработчик" кнопки добавления страны
      if(isset($_POST['name_country']) && isset($_POST['flag_country']) && isset($_POST['continent_country']) && isset($_POST['accessory_block']) && isset($_POST['info_about_country']) && isset($_POST['index_country']))
      {
        $name_country = my_ucfirst(mb_strtolower($_POST['name_country']));//присваивание переменной $name_country значения
        $name_country = defend($name_country);
        $continent_country = defend($_POST['continent_country']);//присваивание переменной $continent_country значения
        $accessory_block = defend($_POST['accessory_block']);//присваивание переменной $accessory_block значения
        $info_about_country = defend($_POST['info_about_country']);//присваивание переменной $info_about_country значения
        $index_country = defend(mb_strtoupper($_POST['index_country']));//присваивание переменной $index_country значения

        /*выбор картинок*/
        if(isset($_FILES['search_flag'])) {
          // проверяем, можно ли загружать изображение
          $check = can_upload($_FILES['search_flag']);

          if($check === true){
            // загружаем изображение на сервер
            $full_name = make_upload($_FILES['search_flag']);
            $flag_country =  $full_name ;
            echo "<div class='alert alert-success'><strong>Файл успешно загружен!</strong></div>";
          }
          else{
            // выводим сообщение об ошибке
            echo "<div class='alert alert-danger'><strong>$check</strong></div>";
          }
        }

        $query1 = "INSERT INTO table_country VALUES (NULL ,'" . $name_country . "', '" . $index_country . "', '" . $continent_country . "', '" . $accessory_block . "', '" . $info_about_country . "', '" . $flag_country . "')";//запрос на добавление данных в БД
        //$result1->set_charset("utf8");
        if(!$result1 = $connection -> query($query1)) die ("Сбой при вставке данных: " . $connection -> error . "<br/> Number error: " . $connection -> errno);//в случае ошибки извлечения данных - вывод сообщения
        echo "<div class='alert alert-success'>Запись добавлена<br></div>";
      }

      //"обработчик" кнопки редактирования информации о стране
      if(isset($_POST['select_country']))//проверка отправки данных программе для всех полей
      {
        $name_country_old = defend(my_ucfirst(mb_strtolower($_POST['name_country_old'])));
        $query2 = "SELECT * FROM table_country WHERE name_country = '" . $name_country_old ."' ORDER BY name_country ASC";//запрос к БД на выбор

        if(!$result2 = $connection -> query($query2)){
          echo "<div class='alert alert-danger'>Сбой при выборе данных: $query2<br>" . $connection -> error . "<br/><br/></div>";//если произошла ошибка, вывод сообщения
        }
        else {          
          $rows2 = $result2 -> num_rows;
          for($i = 0; $i < $rows2; ++$i)
          {
            $result2 -> data_seek($i);
            $row2 = $result2 -> fetch_array(MYSQLI_NUM);//получение отдельной строки таблицы
            $name_country = defend($row2[1]);
            $info_about_country = defend($row2[5]);
            $index_country = defend($row2[2]);
            //print_r($row2);
            echo "
            <div class='container mb-3'>
              <h2>Pедактированиe информации о стране</h2>
              <form action = '../php-script/request_to_DB_on_edit_country.php' method = 'post' enctype = 'multipart/form-data'>
                <div class='input-group mb-3'>
                  <div class='input-group-prepend'>
                    <label for='name_country_new' class='input-group-text'>Название</label>
                  </div>
                  <textarea name='name_country_new' class='form-control' aria-label='With textarea'>$name_country</textarea>
                </div>
                <div class='input-group mb-3'>
                  <div class='input-group-prepend'>
                    <label for='flagCountry' class='input-group-text'>Флаг</label>
                  </div>
                    <img src='../$row2[6]' class='form-control col-1'></img>
                  <div class='input-group-append'>                    
                    <input type='text' class='input-group-text bg-light skrit' name='flag_country' value='$row2[6]' readonly>
                    <input type='file' class='btn btn-outline-secondary' name='search_flag_edit' id='flagCountry'>
                  </div>
                </div>
                <div class='input-group mb-3'>
                  <div class='input-group-prepend'>
                    <label class='input-group-text' for='continent_country'>Часть света</label>
                  </div>
                  <select name='continent_country' class='form-control custom-select' required>";
                    //получение всех частей света из таблицы table_continent для сравнения
                    $query_continent = "SELECT continent FROM table_continent";
                    $res_cont = $connection -> query($query_continent);
                    $rows_cont = $res_cont -> num_rows;
                    //print_r($rows_cont);
                    for($i = 0; $i < $rows_cont; ++$i)
                    {
                      $res_cont -> data_seek($i);
                      $row_cont = $res_cont -> fetch_array(MYSQLI_NUM);
                      echo "<option "; //. $row_cont[0] . 
                      if ($row_cont[0] == $row2[3]) {
                        echo " selected";
                      }
                      echo ">" . $row_cont[0] . "</option>";
                    }
                  echo"</select>
                </div>
                <div class='input-group mb-3'>
                  <div class='input-group-prepend'>
                    <label class='input-group-text' for='accessory_block'>Принадлежность к блоку</label>
                  </div>
                  <select name='accessory_block' class='form-control custom-select' required>";
                    $query_block = "SELECT name_block FROM table_block";
                    $res_block = $connection -> query($query_block);
                    $rows_block = $res_block -> num_rows;
                    //print_r($rows_cont);
                    for($i = 0; $i < $rows_block; ++$i)
                    {
                      $res_block -> data_seek($i);
                      $row_block = $res_block -> fetch_array(MYSQLI_NUM);
                      echo "<option "; //. $row_cont[0] . 
                      if ($row_block[0] == $row2[4]) {
                        echo " selected";
                      }
                      echo ">" . $row_block[0] . "</option>";
                    }
                  echo"</select>
                </div>
                <div class='input-group mb-3'>
                  <div class='input-group-prepend'>
                    <label for='info_about_country' class='input-group-text' required='required'>Информация о стране</label>
                  </div>
                  <textarea  class='form-control mytextarea' aria-label='With textarea' name='info_about_country' rows='8'>$info_about_country</textarea>
                </div>
                <div class='input-group mb-3'>
                  <div class='input-group-prepend'>
                    <label for='index_country' class='input-group-text'>Индекс страны</label>
                  </div>
                  <textarea class='form-control' name='index_country'>$index_country</textarea>
                  <div class='input-group-append'>
                    <button type='button' class='btn btn-outline-secondary' data-toggle='modal' data-target='#exampleModal'>Справка</button>
                  </div>
                </div>
                <input type='hidden' name='name_country_old' value='$name_country'>
                <input type='submit' name='edit_country' class='btn btn-primary' value='Обновить данные'>
              </form>
            </div><hr>
";
            echo <<<_MODAL
                  <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title" id="exampleModalLabel">Название и индекс страны</h5>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <div class="modal-body">
_MODAL;
                          $query = "SELECT * FROM table_country_indexes ORDER BY name_country ASC";
                          $result = $connection -> query($query);
                          if(!$result) die ("Сбой при доступе к БД: " . $connection -> error);
                          else {
                            $rows = $result -> num_rows;
                            for($j = 0; $j < $rows; ++$j){
                              $result -> data_seek($j);
                              $key = $result -> fetch_array(MYSQLI_NUM);
                              echo $key[1] . "  ==>  " . $key[2] . "<br>";
                            }
                          }
            echo <<<_MODAL
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-dismiss="modal">Выход</button>
                        </div>
                      </div>
                    </div>
                  </div>
_MODAL;
                  }
        }
      }
    }
    else {
      echo "<div class='alert alert-danger'>У Вас нет доступа к информации. Обратитесь к администратору по телефону <strong>(411) 13-02</strong>, либо пришлите письмо на адрес <strong>sham@givc.vs.mil.by</strong> с объяснением для чего Вам нужен доступ к панели администратора.</div>
          <form action='../php-script/session_destroy.php' method='post' class='form-inline'>
              <input type='submit' name='destroy' class='btn btn-dark mb-2' value='Выход'>
            </form>";
    }
}
      function my_ucfirst($string, $e = 'utf-8') {
          if (function_exists('mb_strtoupper') && function_exists('mb_substr') && !empty($string)) {
              $string = mb_strtolower($string, $e);
              $upper = mb_strtoupper($string, $e);
              preg_match('#(.)#us', $upper, $matches);
              $string = $matches[1] . mb_substr($string, 1, mb_strlen($string, $e), $e);
          } else {
              $string = ucfirst($string);
          }
          return $string;
      }

?>  
  </script>
  <script type="text/javascript" src="../css/bootstrap/js/jquery.min.js"></script>
  <script type="text/javascript" src="../css/bootstrap/js/bootstrap.min.js"></script>
  <script type="text/javascript" src="../css/bootstrap/js/popper.min.js"></script>
</body>
</html>