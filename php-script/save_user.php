<?php
	session_start();

	echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />";

	echo "<!-- Подключение Bootstrap -->
	<link rel=\"stylesheet\" type=\"text/css\" href=\"../css/bootstrap/css/bootstrap.min.css\">
	<link rel=\"stylesheet\" type=\"text/css\" href=\"../css/bootstrap/css/signin.css\">
	<script type=\"text/javascript\" src=\"../css/bootstrap/js/jquery.min.js\"></script>
	<script type=\"text/javascript\" src=\"../css/bootstrap/js/bootstrap.min.js\"></script>
	<script type=\"text/javascript\" src=\"../css/bootstrap/js/popper.min.js\"></script>";

    /*подключить файл с "обезвреживанием"*/
    require_once 'protect.php';

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

	//если пользователь не ввел логин или пароль, то выдаем ошибку и останавливаем скрипт
	if (empty($login) or empty($password)){
		echo "<h2 class='alert alert-danger'>Вы ввели не всю информацию :( </h2><div class='alert alert-warning'>Через несколько секунд Вы будете автоматически перенаправлены на страницу регистрации. Если этого не произошло нажмите <a href='registration.php'>здесь</a></div>";
	    echo '<script>setTimeout(\'location="registration.php"\', 3000)</script>';//автоматическое перенаправление на страницу регистрации
		exit ("<hr>");
	}


	//Проверяем полученную капчу
	//Обрезаем пробелы с начала и с конца строки
	$captcha = defend(trim($_POST["captcha"]));
	if(isset($_POST["captcha"]) && !empty($captcha)){
	    //Сравниваем полученное значение сo значением из сессии.
	    if(($_SESSION["rand"] != $captcha) && ($_SESSION["rand"] != "")){
	        // Если капча не верна, то возвращаем пользователя на страницу регистрации, и там выведем ему сообщение об ошибке что он ввёл неправильную капчу.
	        $error_message = "<p class='alert alert-danger'><strong>Ошибка!</strong> Вы ввели неправильную капчу </p>";
	   		echo "<h2 class='alert alert-danger'>Введён неправильный код с картинки :( </h2><div class='alert alert-warning'>Через несколько секунд Вы будете автоматически перенаправлены на страницу регистрации. Если этого не произошло нажмите <a href='registration.php'>здесь</a></div>" ;
	   		echo '<script>setTimeout(\'location="registration.php"\', 5000)</script>';//автоматическое перенаправление на страницу регистрации
	        //Останавливаем скрипт
	        exit("<hr>");
	    }
	}
	else{
	    //Если капча не передана либо оно является пустой
	    echo "<h2 class='alert alert-danger'><strong>Ошибка!</strong> Отсутствует проверечный код :( </h2><div class='alert alert-warning'>Через несколько секунд Вы будете автоматически перенаправлены на страницу регистрации. Если этого не произошло нажмите <a href='registration.php'>здесь</a></div>" ;
		echo '<script>setTimeout(\'location="registration.php"\', 5000)</script>';//автоматическое перенаправление на страницу регистрации
	    exit("<hr>");
	}

	//если логин и пароль введены, то обрабатываем их, чтобы теги и скрипты не работали, мало ли что люди могут ввести
	$login = defend($login);
	$password = defend($password);

	//"солим" пароль
	$salt = generateSalt();
	$saltedPassword = md5($password.$salt);

	 // подключаемся к базе
	/*подключить файл с переменными БД*/
	require_once 'data_to_db.php';
	/*подключить файл с созданием соединения БД*/
	require_once 'connect_to_db.php';// файл bd.php должен быть в той же папке, что и все остальные, если это не так, то просто измените путь


	 // проверка на существование пользователя с таким же логином
	$query = "SELECT id FROM table_users WHERE login='$login'";
	$result = $connection -> query($query);//отправка запроса к MySQL
	if(!$result) die ("<div class='alert alert-danger'>Сбой при доступе к БД: " . $connection -> error . "</div>");
	$myrow = $result -> fetch_array(MYSQLI_ASSOC);
	if (!empty($myrow['id'])){
		echo "<h2 class='alert alert-danger'>Извините, введённый вами логин уже зарегистрирован. Введите другой логин.:( </h2><div class='alert alert-warning'>Через несколько секунд Вы будете автоматически перенаправлены на страницу регистрации. Если этого не произошло нажмите <a href='registration.php'>здесь</a></div>" ;
		echo '<script>setTimeout(\'location="registration.php"\', 3000)</script>';//автоматическое перенаправление на страницу регистрации
	    exit ("<hr>");
	}

	 // если такого нет, то сохраняем данные
	$query2 = "INSERT INTO table_users (login, password, salt, id_role) VALUES('$login', '$saltedPassword', '$salt', '3')";
	$result2 = $connection -> query($query2);
	if(!$result2) die ("<div class='alert alert-danger'>Сбой при доступе к БД: " . $connection -> error . "</div>");//в случае ошибки извлечения данных - вывод сообщения

	// Проверяем, есть ли ошибки
	if ($result2 == 'TRUE'){
	    echo "<h2 class='alert alert-success'>Вы успешно зарегистрированы!:) </h2><div class='alert alert-success'>Через несколько секунд Вы будете автоматически перенаправлены на страницу авторизации. Если этого не произошло нажмите <a href='admin/index.php'>здесь</a></div>" ;
		echo '<script>setTimeout(\'location="../admin/index.php"\', 3000)</script>';//автоматическое перенаправление на страницу панели админа
	}
	else {
		echo "<h2 class='alert alert-danger'>Ошибка! Вы не зарегистрированы :( </h2><div class='alert alert-warning'>Через несколько секунд Вы будете автоматически перенаправлены на страницу регистрации. Если этого не произошло нажмите <a href='registration.php'>здесь</a></div>" ;
		echo '<script>setTimeout(\'location="registration.php"\', 3000)</script>';//автоматическое перенаправление на страницу регистрации
	}

	function generateSalt(){
		$salt = '';
		$saltLength = 8; //длина соли
		for($i=0; $i<$saltLength; $i++) {
			$salt .= chr(mt_rand(33,126)); //символ из ASCII-table
		}
		return $salt;
	}
?>