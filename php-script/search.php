<script>
    function clik(index){
        alert("You is selected the " + index);
    }
</script>

<?php
	/*подключить файл с переменными БД*/
    require_once 'data_to_db.php';
    /*подключить файл с созданием соединения БД*/
    require_once 'connect_to_db.php';
    /*подключить файл с "обезвреживанием"*/
    require_once 'protect.php';

    if (!mysql_connect($db_hostname, $db_username, $db_password)) {
        exit('Cannot connect to server');
    }
    if (!mysql_select_db($db_database)) {
        exit('Cannot select database');
    }

    mysql_query('SET NAMES utf8');

    if (!empty($_POST['query'])) { 
            $search_result = search($_POST['query']); 
            echo $search_result; 
    }

    function search ($query) 
    { 
        $query = trim($query); 
        $query = mysql_real_escape_string($query);
        $query = htmlspecialchars($query);

        if (!empty($query)) 
        { 
            if (strlen($query) < 3) {
                $text = '<div class="modal-header">
                           <h5 class="modal-title" id="exampleModalLongTitle">Результаты поиска</h5>
                           <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                             <span aria-hidden="true">&times;</span>
                           </button>
                        </div>
                        <div class="modal-body">
                            <p>Слишком короткий поисковый запрос.</p>
                        </div>
                        <div class="modal-footer">
                           <button type="button" class="btn btn-danger" data-dismiss="modal">Закрыть</button>
                        </div>';
                return $text; 
            } 
            else if (strlen($query) > 128) {
                $text = '<div class="modal-header">
                           <h5 class="modal-title" id="exampleModalLongTitle">Результаты поиска</h5>
                           <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                             <span aria-hidden="true">&times;</span>
                           </button>
                        </div>
                        <div class="modal-body">
                            <p>Слишком длинный поисковый запрос.</p>
                        </div>
                        <div class="modal-footer">
                           <button type="button" class="btn btn-danger" data-dismiss="modal">Закрыть</button>
                        </div>';
                return $text; 
            } 
            else { 
                $q = "SELECT `id_country`, `index_country`, `name_country`, `flag_country` FROM `table_country` WHERE `name_country` LIKE '%$query%'";

                $result = mysql_query($q);

                if (mysql_affected_rows() > 0) { 
                    $num = mysql_num_rows($result);

                    echo '<div class="modal-header">
                               <h5 class="modal-title" id="exampleModalLongTitle">Результаты поиска</h5>
                               <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                 <span aria-hidden="true">&times;</span>
                               </button>
                            </div>
                            <div class="modal-body">
                            <div class="modal-body">
                                <p>По запросу <b>\'' . $query . '\'</b> найдено совпадений: ' . $num . '</p>';
                    echo "<table class=\"table table-hover\">
                            <tr>
                                <th>№ п/п</th>
                                <th>Индекс</th>
                                <th>Название</th>
                                <th>Флаг</th>
                            </tr>";
                    for ($i=0; $i < $num; $i++) { 
                        $row = mysql_fetch_row($result);
                        $number = $i + 1;
                        echo <<<_END
                                <tr onclick="LiClick('$row[1]')">
                                    <td>$number</td>
                                    <td>$row[1]</td>
                                    <td>$row[2]</td>
                                    <td><img src="$row[3]" width="75px" class='img-thumbnail pull-left bg-secondary'></td>
                                </tr>
_END;
                    }
                    echo "</table>
                        </div>
                        <div class=\"modal-footer\">
                           <button type=\"button\" class=\"btn btn-danger\" data-dismiss=\"modal\">Закрыть</button>
                        </div>";                  
                } 
                else {
                    $text = '<div class="modal-header">
                               <h5 class="modal-title" id="exampleModalLongTitle">Результаты поиска</h5>
                               <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                 <span aria-hidden="true">&times;</span>
                               </button>
                            </div>
                            <div class="modal-body">
                                <p>По вашему запросу ничего не найдено.</p>
                            </div>
                            <div class="modal-footer">
                               <button type="button" class="btn btn-danger" data-dismiss="modal">Закрыть</button>
                            </div>';
                    return $text; 
                }
            } 
        }
        else {
            $text = '<div class="modal-header">
                       <h5 class="modal-title" id="exampleModalLongTitle">Результаты поиска</h5>
                       <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                         <span aria-hidden="true">&times;</span>
                       </button>
                    </div>
                    <div class="modal-body">
                        <p>Задан пустой поисковый запрос.</p>
                    </div>
                    <div class="modal-footer">
                       <button type="button" class="btn btn-danger" data-dismiss="modal">Закрыть</button>
                    </div>';            
            return $text; 
        }
    }
?>