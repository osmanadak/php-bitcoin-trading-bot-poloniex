<?php
/**
 * Created by PhpStorm.
 * User: osmanadak
 * Date: 13.08.17
 * Time: 13:18
 *Cron
 */

require_once('../inc/settings.php');


$coins = $polo->get_trading_pairs();
$data = $polo->get_ticker();

$xcoin = count($coins);

for ($i=0; $i < $xcoin ; $i++) {

    $x = $coins[$i];
    $xCheck = substr($x, 0, 3);

    if($xCheck == 'BTC'){
        //echo '<pre>';
        //echo '<br>';
        $coinId = $coins[$i]; //  BTC_XRP

        //echo $coinId;

        $last = $data[$coinId][last];
        $lowestAsk = $data[$coinId][lowestAsk];
        $highestBid = $data[$coinId][highestBid];
        $percentChange = $data[$coinId][percentChange];
        $baseVolume = $data[$coinId][baseVolume];
        $quoteVolume = $data[$coinId][quoteVolume];
        $high24hr = $data[$coinId][high24hr];
        $low24hr = $data[$coinId][low24hr];

        $date = date("Y-m-j H:i:s");

        $stmt = $dbh->prepare("INSERT INTO data (coinType, last, lowestAsk, highestBid, percentChange, baseVolume, quoteVolume, isFrozen, high24hr, low24hr, date, etc) VALUES ('$coinId', '$last', '$lowestAsk', '$highestBid', '$percentChange', '$baseVolume', '$quoteVolume', '', '$high24hr', '$low24hr', '$date', '')");

        if($stmt->execute()){
            //echo ' - Data added!';
        } else {
            echo  'Error!';
        }

    }
}

$stmt1 = $dbh->prepare("SELECT * from rules where status = 1");
$stmt1->execute();

while($rules = $stmt1->fetch()){
    $openOrderCount = 0;
    echo $rules['coin']."<br>";
    $openOrders = $polo -> get_open_orders($rules['coin']);

    if(empty($openOrders) == 1){
        $openOrderCount = 0;
    }else{
        $openOrderCount = count($openOrders);
    }
    echo "Open Order Count: ".$openOrderCount."<br>";

    for($i=0; $i<$openOrderCount;$i++){
        $orderNumber = $openOrders[$i]["orderNumber"];
        $orderType = $openOrders[$i]["type"];
        if($orderType == "buy"){
            $polo->cancel_order($rules['coin'], $orderNumber);
        }else{
            //echo "SELECT * from processes where orderNumber = '$orderNumber' limit 1<br>";
            $stmt = $dbh->prepare("SELECT * from processes where sellOrderNumber = '$orderNumber' limit 1");
            $stmt->execute();
            $row = $stmt->fetch();
            $stopLossPrice = $row['stopLossPrice'];
            echo "Stop Loss Price: ".$stopLossPrice."<br>";
            if($stopLossPrice > 0){
                $stmt2 = $dbh->prepare("SELECT * from data where coinType = '$rules[coin]' order by id desc limit 1");
                $stmt2->execute();
                $row2 = $stmt2->fetch();
                echo $row2['last']."-".$stopLossPrice."<br>";
                if($row2['last'] < $stopLossPrice){
                    $polo->cancel_order($rules['coin'], $orderNumber);
                    $satis = $polo->sell($rules['coin'], $row2['last'], carpmaBtc($row["amount"],0.9975));
                    $text = $row["amount"]." Stop Loss Sell Order saved for ".$rules['coin']." with price ".$row2['last'];
                    $stmt = $dbh->prepare("insert into notifications set text='$text', date = NOW()");
                    $stmt->execute();
                    /*echo "<pre></pre>";
                    print_r($satis);*/
                }
            }
        }
    }

    if($openOrderCount >= $settings['buy_limit_per_coin']){
        echo "Limit: ".$settings['buy_limit_per_coin'].", Open Orders: ".$openOrderCount."<br>";
        continue;
    }
    $time = $rules['time'];
    $stmt = $dbh->prepare("SELECT * from data where coinType = '$rules[coin]' and date > (NOW() - INTERVAL $time MINUTE) order by date desc");
    $stmt->execute();
    $lastPrices = array();
    while($row = $stmt->fetch()){
        $lastPrices[] =  $row['last'];
    }
    $newestPrice = $lastPrices[0];
    $oldestPrice = $lastPrices[count($lastPrices) - 1];

    echo "Newest: ".$newestPrice."<br>Oldest: ".$oldestPrice."<br>";

    $percentChange = ($newestPrice - $oldestPrice)*100/$oldestPrice;
    echo "Change %: ".$percentChange."<br>";

    if($rules['buy_type'] == "1"){
        $buyPercent = $rules['buy_percent']*(-1);
    }
    if($rules['buy_type'] == "2"){
        $buyPercent = $rules['buy_percent'];
    }

    echo "Buy Percent: ".$buyPercent."<br><br>";

    if(($buyPercent < 0 and $percentChange < $buyPercent) or ($buyPercent > 0 and $percentChange > $buyPercent)) {
        echo "Alim yapacak<br>";
        $amount = bolmeBtc($settings['btc_amount_per_buy'], $newestPrice);
        $buyResult = $polo->buy($rules['coin'], $newestPrice, $amount);
        $sellPercent = 1 + ($rules['buy_percent']/100);
        $sellPrice = carpmaBtc( 1.03, $newestPrice);
        if($rules['stop_loss'] > 0){
            $stopLossPrice = carpmaBtc( (1-$rules['stop_loss']/100), $newestPrice);
        }else{
            $stopLossPrice = 0;
        }

        $orderNumber = $buyResult["orderNumber"];
        $stmt = $dbh->prepare("INSERT INTO processes (coinType, buyPrice, sellPrice, stopLossPrice, status, orderNumber,amount) VALUES ('$rules[coin]', '$newestPrice', '$sellPrice', '$stopLossPrice', 1, '$orderNumber', '$amount')");
        $stmt->execute();

    }

    sleep(1); // ?!
    $openOrders = $polo -> get_open_orders($rules['coin']);

    if(!empty($openOrders[error])){ // hata döndürdüyse hiçbir işlem yapma
        echo 'hata dondu, bundan dolayi hicbir islem yapilmadi.<br>';
    }else{

        $stmt = $dbh->prepare("select * from processes where status='1' and coinType='$rules[coin]' order by id asc");
        $stmt->execute();
        while ($a = $stmt->fetch()) {
            $alimAcikMi = false;

            foreach ($openOrders as $oO) {
                if ($oO["orderNumber"] == $a["orderNumber"]) {
                    $alimAcikMi = true;
                    echo $rules['coin'] . "-Alim hala acik<br>";
                }
            }
            if ($alimAcikMi == false) {
                //Yani alım yapılmış
                echo "Sat<br>";
                //$ticker = $polo->get_ticker($a["coinType"]);
                //Sat

                $satis = $polo->sell($rules['coin'], $a["sellPrice"], carpmaBtc($a["amount"],0.9975));
                $text = $a["amount"]." Sell Order saved for ".$rules['coin']." with price ".$a["sellPrice"];
                $stmt = $dbh->prepare("insert into notifications set text='$text', date = NOW()");
                $stmt->execute();
                echo "<pre></pre>";
                var_dump($satis);
                $sellOrderNumber = $satis["orderNumber"];
                $stmt = $dbh->prepare("update processes set status = 0, sellOrderNumber = '$sellOrderNumber' where id='$a[id]'");
                $stmt->execute();
            }
        }
    }

}


$stmt = $dbh->prepare("DELETE FROM data WHERE date < (NOW() - INTERVAL 25 HOUR)");
$stmt->execute();

$balances = $polo->get_balances();
$total = 0;
foreach($balances as $key=>$value){
    if($value > 0) {
        $stmt = $dbh->prepare("select last from data where coinType='BTC_$key' order by id desc limit 1");
        $stmt->execute();
        $price = $stmt->fetch();
        $last = $price['last'];
        //echo $key."-".carpmaBtc($last, $value)."-".$last."-".$value."<br>";
        if($last == 0){
            $last = 1;
        }
        $total = $total + carpmaBtc($last, $value);
    }
}
$stmt = $dbh->prepare("insert into total_btc set amount = '$total', date=NOW()");
$stmt->execute();

?>