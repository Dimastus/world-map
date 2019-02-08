<?php
session_start();

echo "  <link rel=\"stylesheet\" type=\"text/css\" href=\"../css/bootstrap/css/bootstrap.min.css\">";
echo "  <link rel=\"stylesheet\" type=\"text/css\" href=\"../css/bootstrap/css/signin.css\">";

if($_SESSION['login']=='') {
  echo "<div class=\"alert alert-danger\">Вы не должны быть здесь!</div>";
  echo '<script>setTimeout(\'location="../admin/index.php"\', 2000)</script>';//автоматическое перенаправление на страницу панели админа
}
elseif ($_SESSION['login']==true && ($_SESSION['id_role']==1 || $_SESSION['id_role']==2)) {
	/*подключить файл с переменными БД*/
	require_once 'data_to_db.php';
	/*подключить файл с созданием соединения БД*/
	require_once 'connect_to_db.php';

	$person = $_POST['formDelete'];
	if(empty($person)) {
		echo("<div class='alert alert-danger'>Вы ничего не выбрали.</div>");
	}
	else {
		foreach ($person as $key) {
			$query2 = "SELECT foto FROM table_persons WHERE id_position = $key";//запрос для получения ссылки на картинку для её удаления
		    if(!$result2 = $connection -> query($query2)) {
		        echo "<div class='alert alert-danger'>Сбой при доступе к БД: $query1<br>" . $connection -> error . "<br></div>";//если произошла ошибка, вывод сообщения
		    }
		    else {
			    $query1 = "DELETE FROM table_persons WHERE table_persons.id_position = $key";//запрос для БД на удаление
			    if(!$result1 = $connection -> query($query1)){
			    	echo "<div class='alert alert-danger'>Сбой при удалении данных: $query1<br>" . $connection -> error . "<br></div>";//если произошла ошибка, вывод сообщения
			    }
			    else {
			    	$rows = $result2 -> num_rows;
			          for($j = 0; $j < $rows; ++$j)
			          {
			            $result2 -> data_seek($j);
			            $row = $result2 -> fetch_array(MYSQLI_NUM);//получение отдельной строки таблицы			            
			            if(!unlink("../" . $row[0])){ //функция удаления
			              echo "/nОшибка удаления изображения";
			            }
			          }
			    }
			}
		}
    if(count($person) == 1) {
    	echo "<div class='alert alert-danger'>Запись удалена<br></div>";
    }
    else {
    	echo "<div class='alert alert-danger'>Записи удалены<br></div>";
    }
    echo "<script>setTimeout('location=\"../admin/index.php\"', 2000)</script>";
	}
}
else {
  echo "<div class='alert alert-danger'>У Вас нет доступа!</div>";
  echo '<script>setTimeout(\'location="../admin/index.php"\', 2000)</script>';//автоматическое перенаправление на страницу панели админа
}
?>