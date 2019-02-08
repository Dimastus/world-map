<?php
session_start();
/*
echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />";

echo "  <link rel=\"stylesheet\" type=\"text/css\" href=\"../css/bootstrap/css/bootstrap.min.css\">";
echo "  <link rel=\"stylesheet\" type=\"text/css\" href=\"../css/bootstrap/css/signin.css\">";*/


if($_SESSION['login']==''){
    echo "<div class=\"alert alert-danger\">Вы не должны быть здесь!</div>";
    echo '<script>setTimeout(\'location="../admin/index.php"\', 2000)</script>';//автоматическое перенаправление на страницу панели админа
}
elseif ($_SESSION['login']==true && ($_SESSION['id_role']==1 || $_SESSION['id_role']==2)){
    /*подключить файл с переменными БД*/
    require_once 'data_to_db.php';
    /*подключить файл с созданием соединения БД*/
    require_once 'connect_to_db.php';
    /*подключить файл с функциями для загрузки картинок*/
    include_once 'func_for_img.php';
    /*подключить файл с "обезвреживанием"*/
    require_once 'protect.php';

    if(isset($_POST['edit_country'])){   //проверка отправки данных программе для всех полей
        // print_r($_FILES);
        /*выбор картинок*/
        if($_FILES['search_flag_edit']['name'] == ''){
            $flag_country = $_POST['flag_country'];
        }
        else {
            //удаление старого изображения         
              if(!unlink("../" . $row[0])){ //функция удаления
                echo "/nОшибка удаления изображения";
              }
            // проверяем, можно ли загружать изображение
            $check = can_upload($_FILES['search_flag_edit']);

            if($check === true){
              // загружаем изображение на сервер
              $full_name_photo = make_upload($_FILES['search_flag_edit']);
              $flag_country =  $full_name_photo ;
              echo "<div class='alert alert-success'>Файл <strong>$full_name_photo</strong> успешно загружен!<br></div>";
            }
            else{
              // выводим сообщение об ошибке
              echo "<div class='alert alert-danger'><strong>$check</strong></div>";
            }
        }

        $name_country_new = defend($_POST['name_country_new']);
        $index_country = defend($_POST['index_country']);
        $continent_country = defend($_POST['continent_country']);
        $accessory_block = defend($_POST['accessory_block']);
        $info_about_country = defend($_POST['info_about_country']);
        $name_country_old = defend($_POST['name_country_old']);

        $query3 = "UPDATE table_country SET name_country = '". $name_country_new .
    								    "', index_country = '" . $index_country .
    								    "', continent_country = '" . $continent_country .
    								    "', accessory_block = '" . $accessory_block .
    								    "', info_about_country = '" . $info_about_country .
    								    "', flag_country = '" . $flag_country .
        "' WHERE name_country = '" . $name_country_old . "'";//запрос для БД на обновление данных
        if(!$result3 = $connection -> query($query3)){
        	echo "<div class='alert alert-danger'>Сбой при выборе данных: $query3<br/>" . $connection -> error . "</div>";//если произошла ошибка, вывод сообщения
        	echo '<script>setTimeout(\'location="../admin/index.php"\', 2000)</script>';
        }
        else {
        	echo "<div class='alert alert-success'>Запись изменена</div>";
    	    echo '<script>setTimeout(\'location="../admin/index.php"\', 2000)</script>';
        }
    }
}
else {
    echo "<div class='alert alert-danger'>У Вас нет доступа!</div>";
    echo '<script>setTimeout(\'location="../admin/index.php"\', 2000)</script>';//автоматическое перенаправление на страницу панели админа
}
?>