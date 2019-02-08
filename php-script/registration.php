<?php
	session_start();
?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>Регистрация</title>		
		<!-- Подключение Bootstrap -->
		<link rel="stylesheet" type="text/css" href="../css/bootstrap/css/bootstrap.min.css">
		<link rel="stylesheet" type="text/css" href="../css/bootstrap/css/signin.css">
	</head>
	<body>
		<div class="container">      
		    <form action="save_user.php" method="post" class="form-signin">
		    	<h2 class="form-signin-heading">Регистрация</h2>
				<p>
				    <label for="inputLogin" class="sr-only">Ваш логин:<br></label>
				    <input name="login" type="text" size="15" maxlength="15" placeholder="Login" required="required" id="inputLogin"
class="form-control">
			    </p>
				<p>
				    <label for="inputPassword" class="sr-only">Ваш пароль:<br></label>
				    <input name="password" type="password" size="15" maxlength="15" placeholder="Password" required="required" id="inputPassword"
class="form-control">
			    </p>
				<p>
					<h4>Введите код с картинки:</h4>  
			        <p class="span1">
			            <img src="captcha.php" alt="Капча"/><br><br>
			            <input name="captcha" placeholder="Проверочный код" required="required" type="text" class="form-control">
			        </p>
				    <input type="submit" name="submit" class="btn btn-lg btn-success" value="Зарегистрироваться">
				</p>
			</form>
		</div>

	    <script type="text/javascript" src="../css/bootstrap/js/jquery-3.3.1.slim.min.js"></script>
	    <script type="text/javascript" src="../css/bootstrap/js/bootstrap.min.js"></script>
	    <script type="text/javascript" src="../css/bootstrap/js/popper.min.js"></script>
	</body>
</html>
