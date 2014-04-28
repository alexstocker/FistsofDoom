<?php
require_once('../../includes/preheader.php');

$sql = 'SELECT SUM(count_0),	SUM(count_1),	SUM(count_2),	SUM(count_3), SUM(count_4) FROM fod_count';
$result = qr($sql);

$r = '';
$i = 0;
foreach($result as $k => $v){
	if(is_numeric($k)){
	$r['count_'.$i] = $v;
	$total += $v;
	$i++;
	}
}
$r['total'] = $total;
//header('Content-disposition: attachment; filename=jsonFile.json');
header('Content-type: application/json');
echo json_encode($r);