<?php

  echo '<script>setTimeout(\'location="/admin/new_admin.php"\', 0)</script>';

  
// echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />";

// echo "  <link rel=\"stylesheet\" type=\"text/css\" href=\"../css/bootstrap/css/signin.css\">";
/*
	function can_upload($file){
		// если имя пустое, значит файл не выбран
		if($file['name'] == '')
			return '<div class="alert alert-danger">Вы не выбрали фото.<br></div>';

		 //если размер файла 0, значит его не пропустили настройки сервера из-за того, что он слишком большой 
		if($file['size'] == 0)
			return '<div class="alert alert-danger">Файл слишком большой.</div>';

		// разбиваем имя файла по точке и получаем массив
		$getMime = explode('.', $file['name']);
		// нас интересует последний элемент массива - расширение
		$mime = strtolower(end($getMime));
		// объявим массив допустимых расширений
		$types = array('jpg', 'png', 'gif', 'bmp', 'jpeg');

		// если расширение не входит в список допустимых - return
		if(!in_array($mime, $types))
			return '<div class="alert alert-danger">Недопустимый тип файла.</div>';

		return true;
	}

	function make_upload($file){
		// формируем уникальное имя картинки: случайное число и name
		$name = mt_rand(0, 10000) . $file['name'];
		copy($file['tmp_name'], '../img/image/' . $name);
		return $full_name = 'img/image/' . $name;
	}


	function can_upload_more($file){
		// если имя пустое, значит файл не выбран
		if($file['name']['search_foto_edit'] == '')
			return '<div class="alert alert-danger">Вы не выбрали фото.<br></div>';

		 // если размер файла 0, значит его не пропустили настройки	сервера из-за того, что он слишком большой 
		if($file['size']['search_foto_edit'] == 0)
			return '<div class="alert alert-danger">Файл слишком большой.</div>';

		// разбиваем имя файла по точке и получаем массив
		$getMime = explode('.', $file['name']['search_foto_edit']);
		// нас интересует последний элемент массива - расширение
		$mime = strtolower(end($getMime));
		// объявим массив допустимых расширений
		$types = array('jpg', 'png', 'gif', 'bmp', 'jpeg');

		// если расширение не входит в список допустимых - return
		if(!in_array($mime, $types))
			return '<div class="alert alert-danger">Недопустимый тип файла.</div>';

		return true;
	}

	function make_upload_more($file){
		// формируем уникальное имя картинки: случайное число и name
		$name = mt_rand(0, 10000) . $file['name']['search_foto_edit'];
		copy($file['tmp_name']['search_foto_edit'], '../img/image/' . $name);
		return $full_name = 'img/image/' . $name;
	}
	*/