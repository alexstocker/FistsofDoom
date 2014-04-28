<?php
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);
include_once 'includes/preheader.php';

if($_REQUEST['func'] == 'insert'){
	insert();
}

class getCoord {
	
		var $url = '';
		var $response = '';
		var $coords = '';
	
		function whereIS($loc) {
			$url = "http://maps.googleapis.com/maps/api/geocode/json?address=".$loc."&sensor=false";
			$this->url = $url;
			$response = $this->curlURL($url);
		}

		function curlURL($url) {
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			$response = curl_exec($ch);
			curl_close($ch);
			$this->response = json_decode($response);
			$this->outPut(json_decode($response));
			//return json_decode($response);
		}

		function outPut($response) {
			//var_dump($response->results[0]->geometry->location);
			if ($response->results[0]->geometry->location->lat != ''){
				$lat = $response->results[0]->geometry->location->lat;
				$long = $response->results[0]->geometry->location->lng;
				$colat = $response->results[0]->geometry->location->lat;
				$colng = $response->results[0]->geometry->location->lng;
			}else{
				$lat = '0.0';
				$long = '0.0';
				$colat = '0.0';
				$colng = '0.0';
			}
			//echo $i . 'Lat: ' . $lat . ' Long: ' . $long . '<br>';
			$this->coords = array('lat' => $colat, 'lng' => $colng);
			return $this->coords;
		}
}

function insert(){
	
	$coords = new getCoord();
	$coords->whereIS($_REQUEST['location']);
	//var_dump($coords->coords);
	$date = $_REQUEST['date'];
	$location = $_REQUEST['location'];
	$lat = $coords->coords['lat'];
	$lng = $coords->coords['lng'];
	$text_short = $_REQUEST['message'];
	$text_long = $_REQUEST['message'];
	$count_0 = $_REQUEST['casc'];
	$count_1 = $_REQUEST['casm'];
	$count_2 = $_REQUEST['casj'];
	$count_3 = $_REQUEST['cash'];
	$count_4 = $_REQUEST['casu'];
	$link = $_REQUEST['link'];
	
	$sql = "INSERT INTO fod_count 
			SET date = '$date',
			text_short = '$text_short',
			text_long = '$date',
			location = '$location',
			coords_lat = '$lat',
			coords_long = '$lng',
			count_0 = '$count_0',
			count_1 = '$count_1',
			count_2 = '$count_2',
			count_3 = '$count_3',
			count_4 = '$count_4',
			link = '$link'";
         
    $insert = qr($sql);
    if($insert){
    	echo 'Insert successful!<br>';
    }else{
    	echo 'Insert error<br>';
    }
}

function listrows(){
	$sql = "SELECT fodID,date,text_short,location,coords_lat,coords_long FROM fod_count ORDER BY fodID desc";
	$result = q($sql);
	foreach ($result as $r){
		$row .= $r['fodID'].' '.$r['date'].' '.$r['text_short'].' '.$r['text_long'].' '.$r['location'].' '.$r['coords_lat'].' '.$r['coords_long'];
			//foreach($r as $k => $v) {
			//$row .= $k.' '.$v;	
			//}
		$row .= '<br>';
	}
	echo $row;
}
listrows();
?>