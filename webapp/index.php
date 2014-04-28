<?php
	require_once('includes/preheader.php');

$sql = 'SELECT SUM(count_0),	SUM(count_1),	SUM(count_2),	SUM(count_3), SUM(count_4) FROM fod_count';
$result = q2($sql);
$name = $_GET['date'];
$location = $_GET['location'];
$lat = $_GET['lat'];
$lng = $_GET['lng'];
$text_short = $_GET['text_short'];
$text_long = $_GET['text_long'];
$count_0 = $_GET['count_0'];
$count_1 = $_GET['count_1'];
$count_2 = $_GET['count_2'];
$count_3 = $_GET['count_3'];
$count_4 = $_GET['count_4'];
$link = $_GET['link'];
$islam = $result[0]['SUM(count_1)'];

// Select all the rows in the markers table
$reqquery = "SELECT * FROM fod_count WHERE 1";
$reqresult = mysql_query($reqquery);
if (!$reqresult) {
  die('Invalid query: ' . mysql_error());
}

if (isset($_REQUEST['lat'])) { 
$insquery = sprintf("INSERT INTO fod_count " .
         " (date, text_short, location, coords_lat, coords_long, count_0, count_1, count_2, count_3, count_4, link ) " .
         " VALUES ('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s');",
         mysql_real_escape_string($name),
         mysql_real_escape_string($text_short),
         mysql_real_escape_string($location),
         mysql_real_escape_string($lat),
         mysql_real_escape_string($lng),  
         mysql_real_escape_string($count_0),
         mysql_real_escape_string($count_1),
         mysql_real_escape_string($count_2),
         mysql_real_escape_string($count_3),         
         mysql_real_escape_string($count_4),
         mysql_real_escape_string($link));
$result2 = mysql_query($insquery);
}
?>
<!DOCTYPE html>
<html>
<head>
<title><?php echo $_SERVER['SERVER_NAME']; ?> - the fists of doom</title>
<meta name="author" content="elex" />
<meta name="description" content="Religions are the fists of doom. Read about human casualty and colateral damage" />
<meta name="keywords" content="religion, doom, amargedon, war, fist, crusade, holy, islam, hindu, jude, christianity" />
<meta http-equiv="content-type" content="text/html; charset=windows-1252" />

<link rel="stylesheet" type="text/css" href="css/style.css" media="screen" />
<!--[if lt IE 9]>
<link rel="stylesheet" type="text/css" href="css/ie8style.css" media="screen" />
  <![endif]-->
<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
<script type='text/javascript' src='js/jquery/jquery-1.11.0.min.js'></script>
<script type='text/javascript' src='js/jquery/jquery.mobile-1.4.0.js'></script>
<link rel="stylesheet" type="text/css" href="js/jquery/jquery.mobile.theme-1.4.0.min.css" media="screen" />
<link rel="stylesheet" type="text/css" href="js/jquery/theme/derkreuzzug.min.css" media="screen" />
<link rel="stylesheet" type="text/css" href="js/jquery/jquery.mobile.structure-1.4.0.min.css" media="screen" />
<script type='text/javascript' src='js/script.js'></script>
<script type="text/javascript" src="https://maps.google.com/maps/api/js?sensor=false">
</script>
<script type='text/javascript' src='http://google-maps-utility-library-v3.googlecode.com/svn/tags/infobox/1.1.9/src/infobox.js'></script>


 <script type="text/javascript">
 //Sample code written by August Li
 var icon = new google.maps.MarkerImage("images/marker_fist.png",
 new google.maps.Size(54, 42), new google.maps.Point(0, 0),
 new google.maps.Point(16, 32));
  var icon_grey = new google.maps.MarkerImage("images/marker_fist_g.png",
 new google.maps.Size(54, 42), new google.maps.Point(0, 0),
 new google.maps.Point(16, 32));
 var center = null;
 var map = null;
 var currentPopup;
 var bounds = new google.maps.LatLngBounds();
 var marker;
 var geocoder;
 var infowindow;
 
  var centerChangedLast;
  var reverseGeocodedLast;
  var currentReverseGeocodeResponse;


 
function initMap() {
 	map = new google.maps.Map(document.getElementById("map"), {
 			center: new google.maps.LatLng(0, 0),
 			zoom: 14,

			 mapTypeId: google.maps.MapTypeId.ROADMAP,
			mapTypeControl: false,
 			mapTypeControlOptions: {
 				style: google.maps.MapTypeControlStyle.HORIZONTAL_BAR
 			},
 			navigationControl: true,
 			navigationControlOptions: {
 				style: google.maps.NavigationControlStyle.SMALL
 			}
 		});


 var html = "<form class='maps' action='' id=\'add_form' method=\'POST\' ENCTYPE=\'multipart/form-data\'>" +
					  "<input type='date' data-type='date' id='date' class='input' placeholder='1901-12-31'  />" +
                 "<input type='text' id='text_short' class='input' placeholder='Kurztext'  />" +
                 "<input type='text' id='location' class='input' placeholder='Ort'/>" +
                 "<input type='text' id='count_0' class='input' placeholder='Anzahl Christen'  />" +
                 "<input type='text' id='count_1' class='input' placeholder='Anzahl Moslems'  />" +
                 "<input type='text' id='count_2' class='input' placeholder='Anzahl Juden'  />" +
                 "<input type='text' id='count_3' class='input' placeholder='Anzahl Hindus'  />" +
				"<input type='text' id='count_4' class='input' placeholder='Anzahl Unbekannt'  />" +                 
                 "<input type='text' id='link' class='input' placeholder='Link zur Datenherkunft'  />" +
                 "<input type='button' class='btn_submit' value='Save & Close' onclick='saveData()' name='Submit'/>" +
                 "</form>";
var myOptions = {
                 content: html
                ,disableAutoPan: false
                ,pixelOffset: new google.maps.Size(-140, 0)
                ,infoBoxClearance: new google.maps.Size(1, 1)
                ,isHidden: false
                ,pane: "overlayMouseTarget"
                ,enableEventPropagation: false
        };

        infowindow = new InfoBox(myOptions);

    google.maps.event.addListener(map, "dblclick", function(event) {
        marker = new google.maps.Marker({
          position: event.latLng,
          icon: icon_grey,
           draggable: true,
          map: map
        });
        google.maps.event.addListener(marker, "click", function() {
          infowindow.open(map, marker);
        });
    });
 <?
 $query = mysql_query("SELECT * FROM fod_count");
 while ($row = mysql_fetch_array($query)){
 $name=$row['date'];
 $text_short=$row['text_short'];
 $location=$row['location'];
 $lat=$row['coords_lat'];
 $lon=$row['coords_long'];
 $ct0=$row['count_0'];
 $ct1=$row['count_1'];
 $ct2=$row['count_2'];
 $ct3=$row['count_3'];
  $ct4=$row['count_4'];
 $lnk=$row['link'];
 //$desc=$row['desc'];
 echo ("addMarker($lat, $lon,'<b>$name</b><br><b>$text_short</b><br><b>$location</b><br><img src=images/16_teutonic.png><b>$ct0</b><br><img src=images/16_islam.png><b>$ct1</b><br><img src=images/16_davidstern.png><b>$ct2</b><br><img src=images/16_hinduismus.png><b>$ct3</b><br><b>$ct4</b><br><a href=$lnk target=_blank>Would you like to know more?</a></b>');\n");
 }
 ?>
 center = bounds.getCenter();
 map.fitBounds(bounds);

geocoder = new google.maps.Geocoder();  
 } 
 </script>
 <link rel="icon" href="images/favicon.ico" type="image/x-icon">
 <link rel="apple-touch-icon" href="touch-icon-iphone.png" />
<link rel="apple-touch-icon" sizes="72x72" href="images/touch-icon-ipad.png" />
<link rel="apple-touch-icon" sizes="114x114" href="images/touch-icon-iphone4.png" />
</head>
<body onload="initMap()">
	<header class="header""><img src="images/256_fod_logo.png" alt="Fists of Doom Logo"></header>
	<div class="counter_wrapper">
	
		<div class="counter_img">
			<div class="img"><img src="images/64_teutonic.png" alt="Teutonic Logo"></div>
			<div class="img"><img src="images/64_islam.png" alt="Islam Logo"></div>
			<div class="img"><img src="images/64_davidstern.png" alt="Jude Logo"></div>
			<div class="img"><img src="images/64_hinduismus.png" alt="Hindi Logo"></div>
			<div class="img">unknown</div>
			<div class="img">in total</div>	
		</div>
		<div class="counters">
			<div class="casualties"><?php echo $result[0]['SUM(count_0)']; ?></div>
			<div class="casualties"><?php echo $result[0]['SUM(count_1)']; ?></div>
			<div class="casualties"><?php echo $result[0]['SUM(count_2)']; ?></div>
			<div class="casualties"><?php echo $result[0]['SUM(count_3)']; ?></div>
			<div class="casualties"><?php echo $result[0]['SUM(count_4)']; ?></div>
			<div class="casualties"><?php $sum = $result[0]['SUM(count_0)'] + $result[0]['SUM(count_1)'] + $result[0]['SUM(count_2)'] + $result[0]['SUM(count_3)'] + $result[0]['SUM(count_4)']; 
				echo $sum; ?></div>
		</div>
		<div id="showhelp" class="help">Help</div>
	</div>
	<article class="description">
				<? echo $_SERVER['SERVER_NAME']; ?> the fists of doom - Counting human casualties on "holy" crusades. What is to say that no crusade can be called "holy". Not in christian nor in an islamic or any other meaning.
		If you think this project can help to indicate on how absurd it is to fight for religious reasons till death, <u>doubleclick a location</u> on the map, <u>fill the form</u> with reasonable information and <b>safe</b>. Thank you!
	</article>
	<div id="page">
		
		<div id="search">
			<input type="text" id="address" placeholder="Search Location...">
			<input type="button" value="Go" onclick="geocode()">
		</div>
	</div>
	<div id="map"></div>
	<footer>
			<a href="http://validator.w3.org/check?uri=http%3A%2F%2Fwww.derkreuzzug.com%2F" title="<? echo $_SERVER['SERVER_NAME']; ?> w3c HTML5 Validator" target="_blank">HTML5</a> and <a href="http://jigsaw.w3.org/css-validator/validator?uri=http%3A%2F%2Fwww.derkreuzzug.com%2F&amp;profile=css3" title="<? echo $_SERVER['SERVER_NAME']; ?> w3c CSS3 Validator" target="_blank">CSS3</a> valid but political incorrect.
	</footer>
	<div id='submitmessage'></div>
	<div id='help'><ul><li>click on Markers (red) to get more Details
						<li>double click to create Markers (grey)</li>
						<li>click created Marker(grey) to get Form</li>
						<li>click save to submit Data</li></ul></div>
</body>
</html>