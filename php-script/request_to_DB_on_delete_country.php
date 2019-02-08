<?php
session_start();

echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />";

echo "  <link rel=\"stylesheet\" type=\"text/css\" href=\"../css/bootstrap/css/bootstrap.min.css\">";
echo "  <link rel=\"stylesheet\" type=\"text/css\" href=\"../css/bootstrap/css/signin.css\">";


echo "<script type=\"text/javascript\" src=\"../css/bootstrap/js/jquery.min.js\"></script>";

if($_SESSION['login']==''){
  echo "<div class=\"alert alert-danger\">Вы не должны быть здесь!</div>";
  echo '<script>setTimeout(\'location="../admin/index.php"\', 2000)</script>';//автоматическое перенаправление на страницу панели админа
}
elseif ($_SESSION['login']==true && ($_SESSION['id_role']==1 || $_SESSION['id_role']==2)){
  echo "<div class='container mb-3'>
          <h2>Удаление страны</h2>
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
        url:'../php-script/request_to_DB_on_delete_country.php',
        data:"param="+txt,
        success: function(html) {
          $(".info-box").html(html);
        }
      });
    });
  };
</script>
<?php
  if(isset($_POST['param']))//проверка отправки данных программе для всех полей
  {
    /*подключить файл с переменными БД*/
    require_once 'data_to_db.php';
    /*подключить файл с созданием соединения БД*/
    require_once 'connect_to_db.php';
    /*подключить файл с "обезвреживанием"*/
    require_once 'protect.php';

    $parametr = defend($_POST['param']);
    $query1 = "SELECT name_country FROM table_country WHERE continent_country = '" . $parametr . "' ORDER BY name_country ASC"; //запрос выборки данных из БД
    $result1 = $connection -> query($query1);//отправка запроса к MySQL
    if(!$result1) die ("<div class='alert alert-danger'>Сбой при доступе к БД: " . $connection -> error . "</div>");//в случае ошибки извлечения данных - вывод сообщения
    $rows = $result1 -> num_rows;

    echo "<form action='index.php' method = 'post'>
            <div class='input-group mb-3'>              
              <div class='input-group-prepend'>                
                <label for='name_country' class='input-group-text'>Выберите страну</label>
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
              <input type='hidden' name='delete_country' value='yes'>
              <input type='submit' class=\"btn btn-primary\" value='Удалить'>
          </form>
          </div><hr>";
  }
}
else {
  echo "<div class='alert alert-danger>У Вас нет доступа!</div>";
  echo '<script>setTimeout(\'location="../admin/index.php"\', 2000)</script>';//автоматическое перенаправление на страницу панели админа
}
?>