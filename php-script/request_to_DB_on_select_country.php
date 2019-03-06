<?php
session_start();

  echo '<script>setTimeout(\'location="/admin/new_admin.php"\', 0)</script>';

  
/*
echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />";

echo "  <link rel=\"stylesheet\" type=\"text/css\" href=\"../css/bootstrap/css/bootstrap.min.css\">";
echo "  <link rel=\"stylesheet\" type=\"text/css\" href=\"../css/bootstrap/css/signin.css\">";


echo "<script type=\"text/javascript\" src=\"../css/bootstrap/js/jquery.min.js\"></script>";*//*

if($_SESSION['login']==''){
  echo "<span class=\"alert alert-danger\">Вы не должны быть здесь!</span>";
  echo '<script>setTimeout(\'location="../admin/index.php"\', 2000)</script>';
}
elseif ($_SESSION['login']==true && ($_SESSION['id_role']==1 || $_SESSION['id_role']==2)){

  	echo "<div class='container'>
            <h2>Редактирование информации о стране</h2>
            <div class='input-group mb-3'>
              <div class='input-group-prepend'>
                <label class='input-group-text' for=\"id_country_select\">Выберите часть света</label>
              </div>
              <select class=\"form-control custom-select\" name=\"selected_country\" id=\"id_country_select\" onchange=\"fun1()\" required>
                <option selected disabled>---</option>
                <option>Австралия и Океания</option>
                <option>Азия</option>
                <option>Африка</option>
                <option>Европа</option>
                <option>Северная Америка</option>
                <option>Южная Америка</option>
              </select>
            </div>
          </div>";
?>
<script>
  function fun1(){
    jQuery.noConflict();
    jQuery(function(){
      var $ = jQuery;
      var sel = document.getElementById("id_country_select");
      var txt = sel.options[sel.selectedIndex].text;
      $.ajax({
        type:'POST',
        url:'../php-script/request_to_DB_on_select_country.php',
        data:"param="+txt,
        success: function(html) {
          $(".info-box").html(html);
        }
      });
    });
  };
</script>
<?php
  if(isset($_POST['param']))
  {
		require_once 'data_to_db.php';
		require_once 'connect_to_db.php';
    require_once 'protect.php';

    $parametr = defend($_POST['param']);
		$query1 = "SELECT name_country FROM table_country WHERE continent_country = '" . $parametr . "' ORDER BY name_country ASC";
		if(!$result1 = $connection -> query($query1)) die ("<div class='alert alert-danger'>Сбой при доступе к БД: " . $connection -> error . "</div>");
		else {
			$rows = $result1 -> num_rows;

			echo "<div class='container'>
              <form action='index.php' method='post'>
  			       <div class='input-group mb-3'>
                 <div class='input-group-prepend'>
                   <label class='input-group-text' for='name_country_old'>Выберите страну</label>
                  </div>
      				   <select name='name_country_old' class='form-control custom-select'>";
    				      for($j = 0; $j < $rows; ++$j)
    				      {
        						echo "<option>";
    				        $result1 -> data_seek($j);
    				        $row = $result1 -> fetch_array(MYSQLI_NUM);
    				        foreach ($row as $key => $value) {
    				           echo $value;
      					    }
    				        echo '</option>';
    				      }
  		echo "    </select>
                <div class='input-group-append'>
        				  <input type='hidden' name='select_country' value='yes'>
      			      <input type='submit' class=\"btn btn-outline-secondary\"  value='Выбрать'>
                </div>
    				  </form>
              <div class='info-box2'></div>
            </div><hr>";
		}
	}
}
else {
  echo "<span class='alert alert-danger'>У Вас нет доступа!</div>";
  echo '<script>setTimeout(\'location="../admin/index.php"\', 2000)</script>';
}*/
?>