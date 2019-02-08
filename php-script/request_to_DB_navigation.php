<?php

echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />";

/*подключить файл с переменными БД*/
require_once 'data_to_db.php';
/*подключить файл с созданием соединения БД*/
require_once 'connect_to_db.php';
/*подключить файл с "обезвреживанием"*/
require_once 'protect.php';

$poisk_country = defend($_POST['poisk_country']);
$query1 = "SELECT * FROM table_country WHERE index_country='". $poisk_country . "'"; //запрос выборки данных из БД
$result1 = $connection -> query($query1);//отправка запроса к MySQL
if(!$result1) die ("Сбой при доступе к БД: " . $connection -> connect_error);//в случае ошибки извлечения данных - вывод сообщения
$rows = $result1 -> num_rows;
if($rows == null) {
	echo "нет данных по стране";
}
else {
	for($j = 0; $j < $rows; ++$j)
	{
	    $result1 -> data_seek($j);
	    $row = $result1 -> fetch_array(MYSQLI_NUM);//получение отдельной строки таблицы
	    echo <<<_END
	    <table border=1 class="style_table">
	    	<tr>
				<td>Страна</th>
				<td>Блок</td>
				<td>Часть света</td>
				<td>Индекс</td>
				<td>Флаг</td>
	    	</tr>
	    	<tr>
				<td>$row[1]</td>
				<td>$row[4]</td>
				<td>$row[3]</td>
				<td>$row[2]</td>
				<td>$row[6]</td>
	    	</tr>
	    </table>
_END;

        for($i = 0; $i < count($row); ++$i)
        {
            /*echo "$row[$i] | <br>";*/

        }
    }
}

/*закрытие подключения к БД*/
require_once 'close_to_connect_with_db.php';
?>