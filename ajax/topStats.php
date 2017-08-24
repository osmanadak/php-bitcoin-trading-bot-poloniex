<?php
include '../../inc/core.php';
if($us) {
$start = mysql_real_escape_string($_GET['start']);
$end = mysql_real_escape_string($_GET['end']);
$publisher = mysql_real_escape_string($_GET['pub']);

/* Publisher select */
if($us['rights'] == 0) {
if($publisher != "") {
$publisher = $publisher;
}else{
$publisher = $us['id'];
}
}else{
$publisher = $us['id'];
}
/* Publisher select */
$du = mysql_fetch_array(mysql_query("SELECT SUM(size) AS s FROM uploads WHERE/* user_id='$publisher' AND*/ str_to_date(date, '%Y-%m-%d') BETWEEN '$start' AND '$end'"));
$vi = mysql_fetch_array(mysql_query("SELECT *, COUNT(country) AS con FROM views INNER JOIN uploads ON (file_id = uploads.id) WHERE/* uploads.user_id='$publisher' AND*/ str_to_date(views.date, '%Y-%m-%d') BETWEEN '$start' AND '$end'"));
$vi = mysql_fetch_array(mysql_query("SELECT *, COUNT(country) AS con FROM views INNER JOIN uploads ON (file_id = uploads.id) WHERE/* uploads.user_id='$publisher' AND*/ str_to_date(views.date, '%Y-%m-%d') BETWEEN '$start' AND '$end'"));
$dl = mysql_fetch_array(mysql_query("SELECT *, COUNT(country) AS con FROM downloads INNER JOIN uploads ON (file_id = uploads.id) WHERE/* uploads.user_id='$publisher' AND*/ str_to_date(downloads.date, '%Y-%m-%d') BETWEEN '$start' AND '$end'"));
$ea = mysql_fetch_array(mysql_query("SELECT *, SUM(amount) AS con FROM transactions INNER JOIN uploads ON (file_id = uploads.id) WHERE/* uploads.user_id='$publisher' AND*/ str_to_date(transactions.date, '%Y-%m-%d') BETWEEN '$start' AND '$end'"));

$earnings = $ea['con'] - ($ea['con'] * ((100-$st['commission']) / 100));

$array[] = array(
'disk_usage' => round($du['s'] / 1048576, 2)."MB",
'views' => $vi['con'],
'downloads' => $dl['con'],
'earnings' => number_format($earnings, 2)." $",
'cpa' => number_format($earnings / $dl['con'], 2, '.', '')." $",
'conversion_rate' => number_format($dl['con'] * 100 / $vi['con'], 2, '.', ''). " %",
'epc' => number_format($earnings / $vi['con'], 2, '.', '')." $"
);

echo json_encode($array);
}
?>