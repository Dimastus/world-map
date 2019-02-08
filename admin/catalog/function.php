<?php

	/*   функция "обезвреживания" переменных и вводимых значений   */
    function defend($peremennaya){
        $peremennaya = stripslashes($peremennaya);
        $peremennaya = addslashes($peremennaya);
        //$peremennaya = htmlspecialchars($peremennaya);
        $peremennaya = trim($peremennaya);
        return $peremennaya;
    }
    
	function my_ucfirst($string, $e ='utf-8') {
		if (function_exists('mb_strtoupper') && function_exists('mb_substr') && !empty($string)) {
			$string = mb_strtolower($string, $e);
			$upper = mb_strtoupper($string, $e);
			preg_match('#(.)#us', $upper, $matches);
			$string = $matches[1] . mb_substr($string, 1, mb_strlen($string, $e), $e);
		} else {
			$string = ucfirst($string);
		}
		return $string;
	}

	function can_upload($file){
		// если имя пустое, значит файл не выбран
		if($file['name'] == '')
			return '<div class="alert alert-danger">Вы не выбрали фото.<br></div>';

		/* если размер файла 0, значит его не пропустили настройки
		сервера из-за того, что он слишком большой */
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
		$way_to_img =  '../../img/';

		copy($file['tmp_name'], $way_to_img . $name);
		return $full_name = 'img/' . $name;
	}


	function can_upload_more($file){
		// если имя пустое, значит файл не выбран
		if($file['name']['search_foto_edit'] == '')
			return '<div class="alert alert-danger">Вы не выбрали фото.<br></div>';

		/* если размер файла 0, значит его не пропустили настройки
		сервера из-за того, что он слишком большой */
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
		$way_to_img =  '../../img/';

		copy($file['tmp_name']['search_foto_edit'], $way_to_img . $name);
		return $full_name = 'img/' . $name;
	}
	