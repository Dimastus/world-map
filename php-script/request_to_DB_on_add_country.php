<?php
session_start();
  echo '<script>setTimeout(\'location="/admin/new_admin.php"\', 0)</script>';

/*echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />";

echo "  <link rel=\"stylesheet\" type=\"text/css\" href=\"../css/bootstrap/css/bootstrap.min.css\">";
echo "  <link rel=\"stylesheet\" type=\"text/css\" href=\"../css/bootstrap/css/signin.css\">";*/

/*
if($_SESSION['login']==''){
  echo "<span class=\"alert alert-danger\">Вы не должны быть здесь!</span>";
  echo '<script>setTimeout(\'location="../admin/index.php"\', 2000)</script>';//автоматическое перенаправление на страницу панели админа
}
elseif ($_SESSION['login']==true && ($_SESSION['id_role']==1 || $_SESSION['id_role']==2)){
  require_once 'data_to_db.php';
  require_once 'connect_to_db.php';
  include_once 'func_for_img.php';
  require_once 'protect.php';

  //форма для добавления записей в таблицу БД
  echo <<<_END
  <div class="container">
    <h2>Добавление страны</h2>
    <form action='index.php' method='post' enctype="multipart/form-data">
      <div class="input-group mb-3">
        <div class='input-group-prepend'>
          <label for="name_country" class="input-group-text">Название</label>
        </div>
        <input type="text" name='name_country' required="required" class="form-control" placeholder="Название страны">
        <div class='input-group-append'>
          <button type="button" class="btn btn-outline-secondary" data-toggle="modal" data-target="#exampleModal">Справка</button>
        </div>     
      </div>
      <div class='input-group mb-3'>
          <div class='input-group-prepend'>
            <label for="exampleFormControlFile1" class='input-group-text'>Флаг страны</label>
          </div>        
        <div class='input-group-append'>
          <input type='hidden' name='flag_country'>        
          <input type="file" name="search_flag" class="btn btn-outline-secondary" id="exampleFormControlFile1">
        </div>
      </div>
      <div class='input-group mb-3'>
        <div class='input-group-prepend'>
          <label class='input-group-text' for="selectContinentCountry">Часть света</label>
        </div>
          <select class="form-control custom-select" name="continent_country" id='selectContinentCountry' required>
            <option selected disabled>---</option>
            <option>Австралия и Океания</option>
            <option>Азия</option>
            <option>Африка</option>
            <option>Европа</option>
            <option>Северная Америка</option>
            <option>Южная Америка</option>
          </select>
      </div>
      <div class='input-group mb-3'>
        <div class='input-group-prepend'>
          <label class='input-group-text' for="selectAccessoryBlock">Принадлежность к блоку</label>
        </div>
        <select class="form-control custom-select" name="accessory_block" id='selectAccessoryBlock' required>
          <option selected disabled>---</option>
          <option>НАТО</option>
          <option>ОДКБ</option>
        </select>
      </div>
      <div class="input-group mb-3">
        <div class="input-group-prepend">
          <span class="input-group-text">Информации о стране</span>
        </div>
        <textarea class="form-control mytextarea" aria-label="With textarea" name="info_about_country" id='infoAboutCountry'  placeholder="Информация о стране" wrap="hard"></textarea>
      </div>
      <div class="input-group mb-3">
        <div class='input-group-prepend'>
          <label for="index_country" class="input-group-text">Индекс страны</label>
        </div>
        <input type='text' name='index_country' class='form-control' required="required" placeholder="Индекс страны">
        <div class='input-group-append'>
          <button type="button" class="btn btn-outline-secondary" data-toggle="modal" data-target="#exampleModal">Справка</button>
        </div>
      </div>

      <input type='submit' class='btn btn-primary' value='Добавить страну'>
    </form>
  </div><hr>
_END;
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
                  if(!$result) die ("<div class='alert alert-danger'>Сбой при доступе к БД: " . $connection -> error . '</div>');
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
else {
  echo "<div class='alert alert-danger'>У Вас нет доступа!</div>";
  echo '<script>setTimeout(\'location="../admin/index.php"\', 2000)</script>';//автоматическое перенаправление на страницу панели админа
}

*/
?>