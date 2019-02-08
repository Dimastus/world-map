<?php
    
    //echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />";    
    
    /*подключить файл с переменными БД*/
    require_once 'data_to_db.php';
    /*подключить файл с созданием соединения БД*/
    require_once 'connect_to_db.php';
    /*подключить файл с "обезвреживанием"*/
    require_once 'protect.php';

    /*SELECT * FROM table_country, table_persons WHERE table_country.id_country=table_persons.id_country AND table_country.id_country = 1*/
    $code = defend($_POST['code']);
    $query1 = "SELECT * FROM table_country, table_persons WHERE table_country.id_country = table_persons.id_country AND table_country.index_country = '". $code . "'"; //запрос выборки данных из БД
    $result1 = $connection -> query($query1);//отправка запроса к MySQL

    if(!$result1) die ("<div class=''>Сбой при доступе к БД: " . $connection -> error);//в случае ошибки извлечения данных - вывод сообщения
    $rows = $result1 -> num_rows;

    if($rows == null) {
        echo "<div class=\"modal-header\">
                <button class=\"close\" data-dismiss=\"modal\">X</button>            
              </div>
                <div class=\"alert alert-warning\"><h2>нет данных о стране</h2></div>";
    }
else {
        for($j = 0; $j < $rows; ++$j)
        {
            $result1 -> data_seek($j);
            $row = $result1 -> fetch_array(MYSQLI_NUM);//получение отдельной строки таблицы
            //print_r($row);
        }
        echo <<<_END
         <div class="modal-header">
            <button class="close" data-dismiss="modal">X</button>            
          </div>
          <div class="modal-body myModal">
            <div class="container">
              <h1><img src="$row[6]" width="100px" class='img-thumbnail pull-left bg-secondary'></img><strong>$row[1]</strong></h1>
              <div class="row">
                <div class="col">
                  <ul class="nav nav-tabs" id="myTab" role="tablist">
                      <li class="nav-item">
                        <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Общая информация</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Должностные лица</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" id="messages-tab" data-toggle="tab" href="#messages" role="tab" aria-controls="messages" aria-selected="false">Вооружение и техника</a>
                      </li>
                  </ul>
                  <div class="tab-content">
                    <div class="tab-pane active" id="home" role="tabpanel" aria-labelledby="home-tab">
                      <div class='container-fluid'>
                        <br><strong>Блок:</strong> $row[4]<br>
                        <strong>Часть света:</strong> $row[3]<br>
                        <strong>Индекс:</strong> $row[2]<br>
                        <strong>Справочная информация:</strong> 
_END;
                    echo  "<article id='art$row[0]' class='text-up'>$row[5]</article><button id='btn$row[0]' class=\"btn btn-outline-primary btn-sm\" onclick=\"topDown('art$row[0]', 'btn$row[0]')\">Показать больше</button>" . "
                      </div>
                    </div>
                    <div class=\"tab-pane\" id=\"profile\" role=\"tabpanel\" aria-labelledby=\"profile-tab\"><br>";
                      for($j = 0; $j < $rows; ++$j)
                      {  
                          $result1 -> data_seek($j);
                          $row = $result1 -> fetch_array(MYSQLI_NUM);
                          echo "
                          <div class='row'>
                            <div class='col-3'>
                              <img src=\"$row[13]\" class='img-thumbnail'></img>
                            </div>
                            <div class='col-9'> 
                              <strong>Должность:</strong> $row[8]<br>
                              <strong>ФИО:</strong> $row[9]<br>    
                              <strong>Информация о должностном лице:</strong><article id='pers$row[7]' class='text-up'>$row[10]</article><button id='btn$row[7]' class=\"btn btn-outline-primary btn-sm\" onclick=\"topDown('pers$row[7]', 'btn$row[7]')\">Показать больше</button> 
                            </div>
                          </div><hr>";
                      }
                    echo <<<_END
                    </div>
                    <div class="tab-pane" id="messages" role="tabpanel" aria-labelledby="messages-tab">
                      <span class='w-100' style="color: red; font-size: 2em; font-weight: bold">Данная вкладка находится в режиме доработки.<br>Приносим свои извинения!</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button class="btn btn-danger" data-dismiss="modal">Закрыть</button>
          </div>
          <script>
            function topDown(number, but){
              var btn = document.getElementById(but);
              var link = document.getElementById(number);
              if(link.className === 'text-up') {
                link.className += ' text-down';
                btn.innerHTML = "Скрыть";
              }
              else {
                link.className = 'text-up';
                btn.innerHTML = "Показать больше";
              }
            }
          </script>
_END;
}
    
              /**/
    /*закрытие подключения к БД*/
    require_once 'close_to_connect_with_db.php';
?>

