<?php
/**
 * Created by PhpStorm.
 * User: osmanadak
 * Date: 19.08.17
 * Time: 18:48
 */
include("core.php");

if($_GET['type'] == "newRule"){
    $stmt = $dbh->prepare("insert into rules set coin = '$_POST[coin]',buy_type = '$_POST[buy_type]', time = '$_POST[time]', buy_percent = '$_POST[buy_percent]', sell_on_profit = '$_POST[sell_on_profit]', stop_loss = '$_POST[stop_loss]'");
    //$stmt->execute();
}
if($_GET['type'] == "editRule"){
    $stmt = $dbh->prepare("update rules set coin = '$_POST[coin]',buy_type = '$_POST[buy_type]', time = '$_POST[time]', buy_percent = '$_POST[buy_percent]', sell_on_profit = '$_POST[sell_on_profit]', stop_loss = '$_POST[stop_loss]' where id = '$_GET[id]'");
    //$stmt->execute();
}
if($_GET['type'] == "saveApiSettings"){
    $stmt = $dbh->prepare("update api_settings set api_key = '$_POST[api_key]', api_secret = '$_POST[api_secret]', btc_amount_per_buy = '$_POST[btc_amount_per_buy]', buy_limit_per_coin = '$_POST[buy_limit_per_coin]'");
    //$stmt->execute();
}
if($_GET['type'] == "changePassword"){
    $password = md5($_POST['password']);
    $stmt = $dbh->prepare("update users set password = '$password'");
    //$stmt->execute();
}
if($_GET['type'] == "cancelOrder"){
    //$cancelOrder = $polo->cancel_order($_POST['coin'],$_POST['orderNumber']);
}
if($_GET['type'] == "setRuleStatus"){
    if($_POST['status'] == "1"){
        $status = 0;
    }else{
        $status = 1;
    }
    $stmt = $dbh->prepare("update rules set status = '$status' where id = '$_POST[id]'");
    //$stmt->execute();
}
if($_GET['type'] == "deleteRule"){
    $stmt = $dbh->prepare("delete from rules where id = '$_POST[id]'");
    //$stmt->execute();
}
if($_GET['type'] == "getTicker"){
    $ticker = $polo->get_ticker($_POST['coin']);
    echo json_encode($ticker);
}
if($_GET['type'] == "getBtcData"){
    $stmt = $dbh->prepare("select * from total_btc order by id desc limit 20");
    $stmt->execute();
    $data = "";
    while($rows = $stmt->fetch()){
        $data = $data.",".$rows['amount'];
    }
}
