<?php
include '../../inc/core.php';
$id = mysql_real_escape_string(htmlspecialchars($_POST['id']));
$paypal_address = mysql_real_escape_string(htmlspecialchars($_POST['paypal_address']));
$dl_timer = mysql_real_escape_string(htmlspecialchars($_POST['dl_timer']));
$html_code = $_POST['html_code'];
$site_title = mysql_real_escape_string(htmlspecialchars($_POST['site_title']));
$maxFilesize = mysql_real_escape_string(htmlspecialchars($_POST['maxFilesize']));
$parallelUploads = mysql_real_escape_string(htmlspecialchars($_POST['parallelUploads']));
$commission = mysql_real_escape_string(htmlspecialchars($_POST['commission']));
$minimum_payout = mysql_real_escape_string(htmlspecialchars($_POST['minimum_payout']));
$url = mysql_real_escape_string(htmlspecialchars($_POST['url']));
$show_payment = mysql_real_escape_string(htmlspecialchars($_POST['show_payment']));
$email_server = mysql_real_escape_string(htmlspecialchars($_POST['email_server']));
$email_username = mysql_real_escape_string(htmlspecialchars($_POST['email_username']));
$email_password = mysql_real_escape_string(htmlspecialchars($_POST['email_password']));
$email_port = mysql_real_escape_string(htmlspecialchars($_POST['email_port']));

if($us['rights'] == 0) {
$result = mysql_query("UPDATE settings SET site_title='$site_title', paypal_address='$paypal_address', dl_timer='$dl_timer', html_code='$html_code', maxfileSize='$maxFilesize', parallelUploads='$parallelUploads', commission='$commission', minimum_payout='$minimum_payout', url='$url', show_payment='$show_payment', email_server='$email_server', email_port='$email_port', email_username='$email_username', email_password='$email_password' WHERE id='1'")or die(mysql_error());
    if($result){
        mysql_query("insert into actions (user,date,description,ip,username) values('$_SESSION[userid]', NOW(), 'genel uygulama ayarlarını değiştirdi.', '$ip', '$us[1]')");
    }
}
?>