<?php

    function protect_page(){
    	if(!$_SESSION['login']){
    		echo "<div class='alert alert-danger'>Вы не залогинены, Вам сюда нельзя</div>"; // можете сделать редирект на какую старницу
            echo '<script>setTimeout(\'location="/admin/new_admin.php"\', 3000)</script>';//автоматическое перенаправление на страницу панели админа
    	}
    }

    function defend($peremennaya){
        $peremennaya = stripslashes($peremennaya);
        $peremennaya = addslashes($peremennaya);
        //$peremennaya = htmlspecialchars($peremennaya);
        $peremennaya = trim($peremennaya);
        return $peremennaya;
    }