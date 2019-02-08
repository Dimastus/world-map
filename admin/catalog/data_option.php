<?php

	//$query_block = "SELECT" . $arg1 . " FROM " . $arg2;
	$res_block = $connection -> query($query_block);
	if(!$res_block)
		die("<div class='alert alert-danger'>Сбой при доступе к БД: " . $connection -> error . "</div>");
	else {
		$rows_block = $res_block -> num_rows;
		//print_r($rows_cont);
		for($i = 0; $i < $rows_block; ++$i){
		  $res_block -> data_seek($i);
		  $row_block = $res_block -> fetch_array(MYSQLI_NUM);
		  echo "<option>" . $row_block[0] . "</option>";
		}
	}