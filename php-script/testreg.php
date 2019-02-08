<?php
    session_start();//  вся процедура работает на сессиях. Именно в ней хранятся данные  пользователя, пока он находится на сайте. Очень важно запустить их в  самом начале странички!!!
	
	//echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />";

    /*подключить файл с "обезвреживанием"*/
    require_once 'protect.php';

    echo "<!-- Подключение Bootstrap -->
    <link rel=\"stylesheet\" type=\"text/css\" href=\"../css/bootstrap/css/bootstrap.min.css\">
    <link rel=\"stylesheet\" type=\"text/css\" href=\"../css/bootstrap/css/signin.css\">
    <script type=\"text/javascript\" src=\"../css/bootstrap/js/jquery.min.js\"></script>
    <script type=\"text/javascript\" src=\"../css/bootstrap/js/bootstrap.min.js\"></script>
    <script type=\"text/javascript\" src=\"../css/bootstrap/js/popper.min.js\"></script>";

    //заносим введенный пользователем логин в переменную $login, если он пустой, то уничтожаем переменную
    if (isset($_POST['login'])){
        $login = defend($_POST['login']);
        if ($login == ''){
            unset($login);
        }
    }

    //заносим введенный пользователем пароль в переменную $password, если он пустой, то уничтожаем переменную
    if (isset($_POST['password'])){
        $password = defend($_POST['password']);
        if ($password ==''){
            unset($password);
        }
    }

    if (empty($login) or empty($password)) //если пользователь не ввел логин или пароль, то выдаем ошибку и останавливаем скрипт
        {
        echo "<h2 class='alert alert-danger'>Вы ввели не всю информацию :( </h2><div class='alert alert-warning'>Через несколько секунд Вы будете автоматически перенаправлены на страницу авторизации. Если этого не произошло нажмите <a href='admin/index.php'>здесь</a></div>" ;
        echo '<script>setTimeout(\'location="../admin/index.php"\', 3000)</script>';//автоматическое перенаправление на страницу авторизации
        exit ("<hr>");
        }

    //если логин и пароль введены,то обрабатываем их, чтобы теги и скрипты не работали, мало ли что люди могут ввести
    $login = defend($login);
    $password = defend($password);

    // подключаемся к базе
    /*подключить файл с переменными БД*/
    require_once 'data_to_db.php';
    /*подключить файл с созданием соединения БД*/
    require_once 'connect_to_db.php';

    $query = "SELECT * FROM table_users WHERE login='$login'";
    $result = $connection -> query($query); //извлекаем из базы все данные о пользователе с введенным логином
    if(!$result) die ("<div class='alert alert-danger'>Сбой при доступе к БД: " . $connection -> error . "</div>");
    $myrow = $result -> fetch_array(MYSQLI_ASSOC);
    if (empty($myrow['password'])){
        //если пользователя с введенным логином не существует
        echo "<h2 class='alert alert-danger'>Извините, введённый вами login не существует! :( </h2><div class='alert alert-warning'>Через несколько секунд Вы будете автоматически перенаправлены на страницу авторизации. Если этого не произошло нажмите <a href='admin/index.php'>здесь</a></div>";
            echo '<script>setTimeout(\'location="../admin/index.php"\', 3000)</script>';//автоматическое перенаправление на страницу панели админа
        exit ("<hr>");
    }
    else {
        //если существует, то сверяем пароли
        $salt = $myrow['salt'];
        if ($myrow['password']==md5($password.$salt)) {
            //если пароли совпадают, то запускаем пользователю сессию! Можете его поздравить, он вошел!
            $_SESSION['login']=$myrow['login'];
            $_SESSION['id_role']=$myrow['id_role'];//эти данные очень часто используются, вот их и будет "носить с собой" вошедший пользователь
            echo "<h2 class='alert alert-success'>Вы успешно вошли в панель администратора! :) </h2><div class='alert alert-warning'>Через несколько секунд Вы будете автоматически перенаправлены. Если этого не произошло нажмите <a href='admin/index.php'>здесь</a></div>" ;
            echo '<script>setTimeout(\'location="../admin/index.php"\', 3000)</script>';//автоматическое перенаправление на страницу панели админа
        }
        else {
            //если пароли не сошлись
            echo "<h2 class='alert alert-danger'>Извините, введённый вами пароль не верен! :( </h2><div class='alert alert-warning'>Через несколько секунд Вы будете автоматически перенаправлены на страницу авторизации. Если этого не произошло нажмите <a href='admin/index.php'>здесь</a></div>" ;
            echo '<script>setTimeout(\'location="../admin/index.php"\', 3000)</script>';//автоматическое перенаправление на страницу панели админа
            exit ("<hr>");
        }
    }