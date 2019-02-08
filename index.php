<!DOCTYPE html>
<html>
<head>  
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <link rel="icon" href="img/image/globe.ico">
  <title>Карта мира</title>

  <!-- Подключение Bootstrap -->
    <link rel="stylesheet" type="text/css" href="css/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/bootstrap/css/signin.css">
    <script type="text/javascript" src="css/bootstrap/js/jquery-3.3.1.slim.min.js"></script>
    <script type="text/javascript" src="css/bootstrap/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="css/bootstrap/js/popper.min.js"></script>
  <!-- Подключение стилей -->
    <link rel="stylesheet" media="all" href="css/jquery-jvectormap.css">
    <link rel="stylesheet" href="css/style.css">
  <!-- Подключение скриптов jVectorMap-->
    <script src="js/jquery-1.8.2.js"></script>
    <script src="js/jquery-jvectormap.js"></script>
    <script src="js/lib/jquery-mousewheel.js"></script>
    <script src="js/src/jvectormap.js"></script>
    <script src="js/src/abstract-element.js"></script>
    <script src="js/src/abstract-canvas-element.js"></script>
    <script src="js/src/abstract-shape-element.js"></script>
    <script src="js/src/svg-element.js"></script>
    <script src="js/src/svg-group-element.js"></script>
    <script src="js/src/svg-canvas-element.js"></script>
    <script src="js/src/svg-shape-element.js"></script>
    <script src="js/src/svg-path-element.js"></script>
    <script src="js/src/svg-circle-element.js"></script>
    <script src="js/src/svg-image-element.js"></script>
    <script src="js/src/svg-text-element.js"></script>
    <script src="js/src/vml-element.js"></script>
    <script src="js/src/vml-group-element.js"></script>
    <script src="js/src/vml-canvas-element.js"></script>
    <script src="js/src/vml-shape-element.js"></script>
    <script src="js/src/vml-path-element.js"></script>
    <script src="js/src/vml-circle-element.js"></script>
    <script src="js/src/vml-image-element.js"></script>
    <script src="js/src/map-object.js"></script>
    <script src="js/src/region.js"></script>
    <script src="js/src/marker.js"></script>
    <script src="js/src/vector-canvas.js"></script>
    <script src="js/src/simple-scale.js"></script>
    <script src="js/src/ordinal-scale.js"></script>
    <script src="js/src/numeric-scale.js"></script>
    <script src="js/src/color-scale.js"></script>
    <script src="js/src/legend.js"></script>
    <script src="js/src/data-series.js"></script>
    <script src="js/src/proj.js"></script> 
    <script src="js/src/map.js"></script>
    <script src="js/jquery-jvectormap-world-mill-en.js"></script>    
    <script>
      jQuery.noConflict();
      jQuery(function(){
        var $ = jQuery;
        //фокусировка на какой-нибудь регион по нажатию кнопки
        /*$('.focus-single').click(function(){
          $('#map1').vectorMap('set', 'focus', {region: 'BY', animate: true});
        });
        $('#focus-multiple').click(function(){
          $('#map1').vectorMap('set', 'focus', {regions: ['BY', 'RU'], animate: true});
        });
        $('#focus-init').click(function(){
          $('#map1').vectorMap('set', 'focus', {scale: 1, x: 0.5, y: 0.5, animate: true});
        });*/
        
        $('#map1').vectorMap({
          map: 'world_mill_en',
          panOnDrag: true,
          regionsSelectable: false,/* работает в связке с onRegionSelected */
          focusOn: {
            x: 0.5,
            y: 0.5,
            scale: 1,
            animate: true
          },
          labels: {
            regions: {
              render: function(code){
                  var doNotShow = ['JM','BS','SV','BZ','CR','HT','SR',
                                   'TT','GT','HN','PR','FK','LU','SI',
                                   'HR','ME','_1','MK','AL','EH','GM',
                                   'GW','TG','GQ','RW','BI','_2','DJ',
                                   '_0','PS','LB','IL','LS','SZ','QA',
                                   'CY','BT','KW','BN','NC','VU','FJ',
                                   'SB','TL','TF','AM','SK','BA','MD',
                                   'AT','LV','BE','NL','CH','RS','TJ'];

                   if (doNotShow.indexOf(code) === -1) {
                     return code.split('-')[0];
                   }
              },
              offsets: function(code){
                  return {
                      'US': [-280, 30],
                      'CA': [-25, 25],
                      'GT': [0, 2],
                      'SV': [0, 1],
                      'HN': [-2, 0],
                      'NI': [1, 1],
                      'CU': [3, 0],
                      'VE': [3, 0],
                      'CO': [-1, 1],
                      'PE': [-2, 1],
                      'BR': [8, -3],
                      'BO': [-3, 2],
                      'AR': [-3, -10],

                      'IS': [0, 3],
                      'GB': [3, 4],
                      'IE': [-1, 1],
                      'FR': [60, -50],
                      'PT': [-2, 0],
                      'ES': [-2, 1],
                      'NL': [1, -1],
                      'BE': [1, 1],
                      'CH': [-1, 0],
                      'DE': [-2, 0],
                      'CZ': [-2, 1],
                      'AT': [3, 2],
                      'HU': [0, 2],
                      'GR': [-2, 0],
                      'BG': [0, 2],
                      'RO': [0, 2],
                      'DK': [-2, 0],

                      'BY': [0, 2],
                      'UA': [0, -2],
                      'MD': [2, 0],


                      'NO': [-23, 40],
                      'SE': [-4, 0],
                      'FI': [2, 0],

                      'MA': [10, -10],
                      'MR': [3, 1],
                      'ML': [5, 0],
                      'SN': [-1, -1],
                      'GN': [2, -2],
                      'BJ': [0, -1],
                      'LR': [-1, 1],
                      'CM': [-1, 7],
                      'GA': [-1, 1],
                      'CG': [1, 1],
                      'CF': [0, 1],
                      'SS': [0, 3],
                      'CD': [4, 0],
                      'AO': [0, 3],
                      'ZA': [-3, 3],
                      'NA': [-1, 0],
                      'ZM': [-5,6],
                      'SO': [2, 5],
                      'ET': [-4, 0],

                      'YE': [-1, 3],
                      'OM': [-2, 6],
                      'JO': [-2, 0],

                      'AF': [-4, 1],
                      'PK': [-1, 4],
                      'IN': [-10, 0],
                      'NP': [-1, 1],
                      'BD': [0, 2],
                      'MM': [0, -2],
                      'TH': [-1, -5],
                      'LA': [-2, -2],
                      'VN': [8, 5],
                      'ID': [-3, 2],
                      'MY': [7, 5],
                      'PG': [-10, 0],
                      'AU': [0, -10],
                      'NZ': [3, 0],
                      'KR': [0, 3],
                      'KP': [0, 2],
                      'JP': [6, 2]
                  }[code.split('-')[0]];
              }
            },
            markers: {
              render: function(index){
                  return 'Marker '+index;
              }
            }
          },
          series: {
            regions: [{
              scale: /*{
                red: '#ff0000',
                green: '#00ff00'}*/
                ['#66C2A5', '#FC8D62', '#8DA0CB', '#E78AC3', '#A6D854', '#FF7F50', '#DC143C', '#8B008B', '#008B8B ', '#B22222', '#007F00', '#FFD700', '#CD5C5C', '#191970', '#FFA500', '#FF4500', '#DA70D6', '#DB7093', '#7F007F', '#FF0000', '#4169E1', '#2E8B57', '#6A5ACD', '#708090', '#00FF7F', '#4682B4', '#008080', '#40E0D0', '#EE82EE', '#FFFF00', '#191970', '199419', '0bcdef', '199900', '1A2B3C', '#6FFC2A5', '#FFF8D62', '#8FFA0CB', '#EFF8AC3', '#AFFD854', '#FFF7F50', '#DFF143C', '#8FF008B', '#0FF8B8B ', '#BFF2222', '#0FF7F00', '#FFFD700', '#CFF5C5C', '#1FF1970', '#FFFF500', '#FFFF500', '#DA7FFD6', '#DB7FF93', '#7F007FF', '#FF0FF0', '#416FE1', '#2E85FF', '#6AFACD', '#FF0090', '#00FFFF', '#4682F4', '#008F80', '#40EFD0', '#EE82FE', '#19F70', '#DB7FF93', '#7F007FF', '#FF0FF0', '#416FE1', '#2E85FF', '#6AFACD', '#FF0090', '#40E0D0','#66C2A5', '#FC8D62', '#8DA0CB', '#E78AC3', '#A6D854', '#FF7F50', '#DC143C', '#8B008B', '#008B8B ', '#B22222', '#007F00', '#FFD700', '#CD5C5C', '#191970', '#FFA500', '#FF4500', '#DA70D6', '#DB7093', '#7F007F', '#FF0000', '#4169E1', '#2E8B57', '#6A5ACD', '#708090', '#00FF7F', '#4682B4', '#008080', '#40E0D0', '#EE82EE', '#FFFF00', '#191970', '199419', '0bcdef', '199900', '1A2B3C', '#6FFC2A5', '#FFF8D62', '#8FFA0CB', '#EFF8AC3', '#AFFD854', '#FFF7F50', '#DFF143C', '#8FF008B', '#0FF8B8B ', '#BFF2222', '#0FF7F00', '#FFFD700', '#CFF5C5C', '#1FF1970', '#FFFF500', '#FFFF500', '#DA7FFD6', '#DB7FF93', '#7F007FF', '#FF0FF0', '#416FE1', '#2E85FF', '#6AFACD', '#FF0090', '#00FFFF', '#4682F4', '#008F80', '#40EFD0', '#EE82FE', '#19F70', '#DB7FF93', '#7F007FF', '#FF0FF0', '#416FE1', '#2E85FF', '#6AFACD', '#FF0090', '#40E0D0', '#66C2A5', '#FC8D62', '#8DA0CB'],
              normalizeFunction: 'polynomial',
              values: {
                "AF": 16.63,
                "AL": 11.58,
                "DZ": 158.97,
                "AO": 85.81,
                "AG": 1.1,
                "AR": 351.02,
                "AM": 8.83,
                "AU": 1219.72,
                "AT": 366.26,
                "AZ": 52.17,
                "BS": 7.54,
                "BH": 21.73,
                "BD": 105.4,
                "BB": 3.96,
                "BY": 52.89/*'red's*/,
                "BE": 461.33,
                "BZ": 1.43,
                "BJ": 6.49,
                "BT": 1.4,
                "BO": 19.18,
                "BA": 16.2,
                "BW": 12.5,
                "BR": 2023.53,
                "BN": 11.96,
                "BG": 44.84,
                "BF": 8.67,
                "BI": 1.47,
                "KH": 11.36,
                "CM": 21.88,
                "CA": 1563.66,
                "CV": 1.57,
                "CF": 2.11,
                "TD": 7.59,
                "CL": 199.18,
                "CN": 5745.13,
                "CO": 283.11,
                "KM": 0.56,
                "CD": 12.6,
                "CG": 11.88,
                "CR": 35.02,
                "CI": 22.38,
                "HR": 59.92,
                "CY": 22.75,
                "CZ": 195.23,
                "KP": 234,
                "SS": 789,
                "SO": 1,
                "DK": 304.56,
                "DJ": 1.14,
                "DM": 0.38,
                "DO": 50.87,
                "EC": 61.49,
                "EG": 216.83,
                "SV": 21.8,
                "GQ": 14.55,
                "GL": 10000,
                "ER": 2.25,
                "EE": 19.22,
                "ET": 30.94,
                "FJ": 3.15,
                "FI": 231.98,
                "FR": 2555.44,
                "GA": 12.56,
                "GM": 1.04,
                "GE": 11.23,
                "DE": 3305.9,
                "GH": 18.06,
                "GR": 305.01,
                "GD": 0.65,
                "GT": 40.77,
                "GN": 4.34,
                "GW": 0.83,
                "GY": 2.2,
                "HT": 6.5,
                "HN": 15.34,
                "HK": 226.49,
                "HU": 132.28,
                "IS": 12.77,
                "IN": 1430.02,
                "ID": 695.06,
                "IR": 337.9,
                "IQ": 84.14,
                "IE": 204.14,
                "IL": 201.25,
                "IT": 2036.69,
                "JM": 13.74,
                "JP": 5390.9,
                "JO": 27.13,
                "KZ": 129.76,
                "KE": 32.42,
                "KI": 0.15,
                "KR": 986.26,
                "KW": 117.32,
                "KG": 4.44,
                "LA": 6.34,
                "LV": 23.39,
                "LB": 39.15,
                "LS": 1.8,
                "LR": 0.98,
                "LY": 77.91,
                "LT": 35.73,
                "LU": 52.43,
                "MK": 9.58,
                "MG": 8.33,
                "MW": 5.04,
                "MY": 218.95,
                "MV": 1.43,
                "ML": 9.08,
                "MT": 7.8,
                "MR": 3.49,
                "MU": 9.43,
                "MX": 1004.04,
                "MD": 5.36,
                "MN": 5.81,
                "ME": 3.88,
                "MA": 91.7,
                "MZ": 10.21,
                "MM": 35.65,
                "NA": 11.45,
                "NP": 15.11,
                "NL": 770.31,
                "NZ": 138,
                "NI": 6.38,
                "NE": 5.6,
                "NG": 206.66,
                "NO": 413.51,
                "OM": 53.78,
                "PK": 174.79,
                "PA": 27.2,
                "PG": 8.81,
                "PY": 17.17,
                "PE": 153.55,
                "PH": 189.06,
                "PL": 438.88,
                "PT": 223.7,
                "QA": 126.52,
                "RO": 158.39,
                "RU": 1476.91,
                "RW": 5.69,
                "WS": 0.55,
                "ST": 0.19,
                "SA": 434.44,
                "SN": 12.66,
                "RS": 38.92,
                "SC": 0.92,
                "SL": 1.9,
                "SG": 217.38,
                "SK": 86.26,
                "SI": 46.44,
                "SB": 0.67,
                "ZA": 354.41,
                "ES": 1374.78,
                "LK": 48.24,
                "KN": 0.56,
                "LC": 1,
                "VC": 0.58,
                "SD": 65.93,
                "SR": 3.3,
                "SZ": 3.17,
                "SE": 444.59,
                "CH": 522.44,
                "SY": 59.63,
                "TW": 426.98,
                "TJ": 5.58,
                "TZ": 22.43,
                "TH": 312.61,
                "TL": 0.62,
                "TG": 3.07,
                "TO": 0.3,
                "TT": 21.2,
                "TN": 43.86,
                "TR": 729.05,
                "TM": 0.6,
                "UG": 17.12,
                "UA": 136.56,
                "AE": 239.65,
                "GB": 2258.57,
                "US": 14624.18,
                "UY": 40.71,
                "UZ": 37.72,
                "VU": 0.72,
                "VE": 285.21,
                "VN": 101.99,
                "YE": 30.02,
                "ZM": 15.69,
                "ZW": 5.57,
                "CU": 0.1,
                "GL": 123.23,
              }
            }]
          },
          onRegionClick: function(event, code){
            /*if(this.regions == 'BY'){
              alert('Belarus');
            }
            else {
              alert('information about country');
            }*/
            console.log('region-click', code);
            var map = $('#map1').vectorMap('get', 'mapObject');
            /*alert(code);*/
            $.ajax({
              url: "php-script/request_to_DB.php",
              type: 'POST',
              data: {code: code},
              async: false,
              success: function(html) {
                $(".info-box").html(html);
              }
            });
            $( '#myBtn' ).trigger( 'click' );
            /*$('.info-box').append('<span>' + code либо название страны map.getRegionName(code) + '</span></br>');*/
          },        
          onRegionSelected: function(event, code){
            console.log('selected the region');
            //alert("You select is block NATO, example");
          }/* работает только при наличии regionsSelectable */
        });
      });
    </script>
</head>
<body>
  <!-- Подключение к БД -->
  <?php
    require_once 'php-script/data_to_db.php';
    require_once 'php-script/connect_to_db.php';
  ?>

  <!-- Навигационная панель -->
  <header id="mainmenu">
    <ul>
      <li class="parent"><a href="#">Части света</a>
        <ul>
          <li class="parent"><a href="#">Европа</a>
            <ul id="d2">
              <?php
                $query1 = "SELECT * FROM table_country WHERE continent_country='Европа' ORDER BY name_country ASC";//запрос выборки данных из БД
                $result1 = $connection -> query($query1);//отправка запроса к MySQL
                if(!$result1) die ("<div class='alert alert-danger'>Сбой при доступе к БД: " . $connection -> connect_error . "</div>");//в случае ошибки извлечения данных - вывод сообщения
                $rows = $result1 -> num_rows;
                for($j = 0; $j < $rows; ++$j)
                {
                    $result1 -> data_seek($j);
                    $row = $result1 -> fetch_array(MYSQLI_NUM);//получение отдельной строки таблицы
                    echo <<<_END
                    <li class='focus-single' title='$row[2]' onclick="LiClick('$row[2]')"><a href="#">$row[1]</a></li>
_END;

                }
              ?>
            </ul>
          </li>
          <li class="parent"><a href="#">Азия</a>
            <ul>
              <?php

                $query1 = "SELECT * FROM table_country WHERE continent_country='Азия' ORDER BY name_country ASC";//запрос выборки данных из БД
                $result1 = $connection -> query($query1);//отправка запроса к MySQL
                if(!$result1) die ("<div class='alert alert-danger'>Сбой при доступе к БД: " . $connection -> connect_error . "</div>");//в случае ошибки извлечения данных - вывод сообщения
                $rows = $result1 -> num_rows;
                for($j = 0; $j < $rows; ++$j)
                {
                    $result1 -> data_seek($j);
                    $row = $result1 -> fetch_array(MYSQLI_NUM);//получение отдельной строки таблицы
                    echo <<<_END
                    <li title='$row[2]' onclick="LiClick('$row[2]')"><a href="#">$row[1]</a></li>
_END;
                }

              ?>
            </ul>
          </li>
          <li class="parent"><a href="#">Северная Америка</a>
            <ul>
              <?php

                $query1 = "SELECT * FROM table_country WHERE continent_country='Северная Америка' ORDER BY name_country ASC";//запрос выборки данных из БД
                $result1 = $connection -> query($query1);//отправка запроса к MySQL
                if(!$result1) die ("<div class='alert alert-danger'>Сбой при доступе к БД: " . $connection -> connect_error . "</div>");//в случае ошибки извлечения данных - вывод сообщения
                $rows = $result1 -> num_rows;
                for($j = 0; $j < $rows; ++$j)
                {
                    $result1 -> data_seek($j);
                    $row = $result1 -> fetch_array(MYSQLI_NUM);//получение отдельной строки таблицы
                    echo <<<_END
                    <li class='focus-single' title='$row[2]' onclick="LiClick('$row[2]')"><a href="#">$row[1]</a></li>
_END;
                }

              ?>
            </ul>
          </li>
          <li class="parent"><a href="#">Южная Америка</a>
            <ul>
              <?php

                $query1 = "SELECT * FROM table_country WHERE continent_country='Южная Америка' ORDER BY name_country ASC";//запрос выборки данных из БД
                $result1 = $connection -> query($query1);//отправка запроса к MySQL
                if(!$result1) die ("<div class='alert alert-danger'>Сбой при доступе к БД: " . $connection -> connect_error . "</div>");//в случае ошибки извлечения данных - вывод сообщения
                $rows = $result1 -> num_rows;
                for($j = 0; $j < $rows; ++$j)
                {
                    $result1 -> data_seek($j);
                    $row = $result1 -> fetch_array(MYSQLI_NUM);//получение отдельной строки таблицы
                    echo <<<_END
                    <li class='focus-single' title='$row[2]' onclick="LiClick('$row[2]')"><a href="#">$row[1]</a></li>
_END;
                }

              ?>
            </ul>
          </li>
          <li class="parent"><a href="#">Африка</a>
            <ul>
              <?php

                $query1 = "SELECT * FROM table_country WHERE continent_country='Африка' ORDER BY name_country ASC";//запрос выборки данных из БД
                $result1 = $connection -> query($query1);//отправка запроса к MySQL
                if(!$result1) die ("<div class='alert alert-danger'>Сбой при доступе к БД: " . $connection -> connect_error . "</div>");//в случае ошибки извлечения данных - вывод сообщения
                $rows = $result1 -> num_rows;
                for($j = 0; $j < $rows; ++$j)
                {
                    $result1 -> data_seek($j);
                    $row = $result1 -> fetch_array(MYSQLI_NUM);//получение отдельной строки таблицы
                    echo <<<_END
                    <li class='focus-single' title='$row[2]' onclick="LiClick('$row[2]')"><a href="#">$row[1]</a></li>
_END;
                }

              ?>
            </ul>
          </li>
          <li class="parent"><a href="#">Австралия и Океания</a>
            <ul>
              <?php

                $query1 = "SELECT * FROM table_country WHERE continent_country='Австралия и Океания' ORDER BY name_country ASC";//запрос выборки данных из БД
                $result1 = $connection -> query($query1);//отправка запроса к MySQL
                if(!$result1) die ("<div class='alert alert-danger'>Сбой при доступе к БД: " . $connection -> connect_error . "</div>");//в случае ошибки извлечения данных - вывод сообщения
                $rows = $result1 -> num_rows;
                for($j = 0; $j < $rows; ++$j)
                {
                    $result1 -> data_seek($j);
                    $row = $result1 -> fetch_array(MYSQLI_NUM);//получение отдельной строки таблицы
                    echo <<<_END
                    <li class='focus-single' title='$row[2]' onclick="LiClick('$row[2]')"><a href="#">$row[1]</a></li>
_END;
                }

              ?>
            </ul>
          </li>
        </ul>
      </li>
      <li class="parent"><a href="#">НАТО</a>
        <ul id="d2">
           <?php
              $query1 = "SELECT * FROM table_country WHERE accessory_block='НАТО' ORDER BY name_country ASC";//запрос выборки данных из БД
              $result1 = $connection -> query($query1);//отправка запроса к MySQL
              if(!$result1) die ("<div class='alert alert-danger'>Сбой при доступе к БД: " . $connection -> connect_error . "</div>");//в случае ошибки извлечения данных - вывод сообщения
              $rows = $result1 -> num_rows;
              for($j = 0; $j < $rows; ++$j)
              {
                  $result1 -> data_seek($j);
                  $row = $result1 -> fetch_array(MYSQLI_NUM);//получение отдельной строки таблицы
                  echo <<<_END
                  <li class='focus-single' title='$row[2]' onclick="LiClick('$row[2]')"><a href='#'>$row[1]</a></li>
_END;
              }
            ?>
        </ul>
      </li>
      <li class="parent"><a href="#">ОДКБ</a>
        <ul>
          <?php
            $query1 = "SELECT * FROM table_country WHERE accessory_block='ОДКБ' ORDER BY name_country ASC";//запрос выборки данных из БД
            $result1 = $connection -> query($query1);//отправка запроса к MySQL
            if(!$result1) die ("<div class='alert alert-danger'>Сбой при доступе к БД: " . $connection -> connect_error . "</div>");//в случае ошибки извлечения данных - вывод сообщения
            $rows = $result1 -> num_rows;
            for($j = 0; $j < $rows; ++$j)
            {
                $result1 -> data_seek($j);
                $row = $result1 -> fetch_array(MYSQLI_NUM);//получение отдельной строки таблицы
                echo <<<_END
                <li class='focus-single' title='$row[2]' onclick="LiClick('$row[2]')"><a href='#'>$row[1]</a></li>
_END;
            }
          ?>
        </ul>
      </li>
      <li class="cont">
        <!-- Форма поиска -->
        <div class="for-search">
          <input name="query" id="search" class="form-control mr-sm-1" type="search" placeholder="Поиск" aria-label="Search">
          <button class="btn btn-secondary border-dark my-2 my-sm-0" onclick="btnSearch()"><img src="img/image/search.ico" alt="search" width='16px'></button>
        </div>
      </li>
      <li class="cont"><a href="http://world-map.vs/admin/" target="_blank">Панель администратора</a></li>
    </ul>
  </header>

  <!-- Модальное окно для вывода информации о стране -->
  <button class="btn btn-secondary" data-toggle="modal" data-target="#myTarget" id="myBtn">Описание страны</button>
  <div id="myModal" class="modal fade">
      <div class="modal-dialog modal-dialog-centered modal-lg">
          <div class="modal-content">
            <div class="info-box"></div>
          </div>
      </div>
  </div>

  <!-- Карта мира -->
  <div class="body-map">
    <div id="map1" class="container-fluid"></div>
  </div>
  
  <!-- <button id="focus-single">Сфокусироваться на Беларусь</button>
  <button id="focus-multiple">Сфокусироваться на Беларусь и Россию</button>
  <button id="focus-coords">Focus on Cyprus</button>
  <button id="focus-init">Return to the initial state</button> -->

  <!-- Закрытие соединения с БД -->
  <?php
    require_once 'php-script/close_to_connect_with_db.php';
  ?>
  <div class="footer"><a href="http://givc.vs.mil.by">ГИВЦ ВС</a> &copy; <? echo date('Y') ?></div>


  <!-- скрипт для выбора страны из панели навигации -->
  <!-- скрипт для открытия модального окна -->
  <script>
    function LiClick(perem){
        jQuery.noConflict();
        jQuery(function(){
          var $ = jQuery;
            $.ajax({
                url: "php-script/request_to_DB.php",
                type: 'POST',
                data: {code: perem},
                async: false,
                success: function(html) {
                  $(".info-box").html(html);
                }
            });
        });
        $( '#myBtn' ).trigger( 'click' );
    }; 
    $(document).ready(function(){
      $("#myBtn").click(function(){
          $("#myModal").modal('show');
      });
    }); 
  </script>
  <!-- скрипт для открытия модального окна с результатами поиска-->
  <script>
     function btnSearch(){
       var search = document.getElementById('search').value;
       if(!search){
        alert("Введите название страны для поиска");
        return false;
       }
       else {
         jQuery.noConflict();
         jQuery(function(){
           var $ = jQuery;
           $.ajax({  
               url: "php-script/search.php",               
               type: 'POST',
               data: {query: search},
               async: false, 
               success: function(html){  
                   $(".info-box").html(html);  
               }
           });
           return false;
         });
         $( '#myBtn' ).trigger( 'click' );
       } 
     }; 
     $(document).ready(function(){
       $("#myBtn").click(function(){
           $("#myModal").modal();
       });
     });  
  </script>
</body>
</html>