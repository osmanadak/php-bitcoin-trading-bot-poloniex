<?php
/**
 * Created by PhpStorm.
 * User: osmanadak
 * Date: 13.08.17
 * Time: 13:18
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


    if(!empty($openOrders)){
        for($i = 0; $i < count($openOrders); $i++){
            if($openOrders[$i]['type'] == "buy"){
                $polo->cancel_order($rules['coin'], $openOrders[$i]['orderNumber']);
            }else{
                $openOrderCount++;
            }
        }
    }

    echo "Open Order Count: ".$openOrderCount."<br>";

    for($i=0; $i<$openOrderCount;$i++){
        $orderNumber = $openOrders[$i]["orderNumber"];
        echo "Order Number: ".$orderNumber."<br>";
        $orderType = $openOrders[$i]["type"];
        if($orderType == "buy"){
            $polo->cancel_order($rules['coin'], $orderNumber);
        }else{
            $stmt = $dbh->prepare("SELECT * from processes where sellOrderNumber = '$orderNumber' limit 1");
            $stmt->execute();
            $row = $stmt->fetch();
            $stopLossPrice = $row['stopLossPrice'];
            echo "Stop Loss Price: ".$stopLossPrice."<br>";
            if($stopLossPrice > 0){
                $stmt2 = $dbh->prepare("SELECT * from data where coinType = '$rules[coin]' order by id desc limit 1");
                $stmt2->execute();
                $row2 = $stmt2->fetch();
                echo "Current Price: ".$row2['last']."<br>";
                if($row2['last'] < $stopLossPrice){
                    echo "Coin: ".$rules['coin']." Order Number: ".$orderNumber."<br>";
                    $cancelOrderStopLoss = $polo->cancel_order($rules['coin'], $orderNumber);
                    if(empty($cancelOrderStopLoss[error])){
                        $sellCancelOrder = $polo->sell($rules['coin'], $row2['last'], carpmaBtc($row["amount"],0.9975));
                        echo "<pre>";
                        print_r($sellCancelOrder);
                        $text = $row["amount"]." Stop Loss Sell Order saved for ".$rules['coin']." with price ".$row2['last'];
                        $stmt = $dbh->prepare("insert into notifications set text='$text', date = NOW()");
                        $stmt->execute();
                    }
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
    $lastVolume = array();
    while($row = $stmt->fetch()){
        $lastPrices[] =  $row['last'];
        $lastVolumes[] =  $row['baseVolume'];
    }
    $newestPrice = $lastPrices[0];
    $oldestPrice = $lastPrices[count($lastPrices) - 1];

    $newestVolume = $lastVolumes[0];
    $oldestVolume = $lastVolumes[count($lastVolumes) - 1];

    echo "Newest: ".$newestPrice."<br>Oldest: ".$oldestPrice."<br>";
    echo "Newest Volume: ".$newestVolume."<br>Oldest Volume: ".$oldestVolume."<br>";

    $percentChange = ($newestPrice - $oldestPrice)*100/$oldestPrice;
    $percentVolumeChange = ($newestVolume - $oldestVolume)*100/$oldestVolume;
    echo "Change %: ".$percentChange."<br>";
    echo "Change % (Volume): ".$percentVolumeChange."<br>";

    if($rules['buy_type'] == "1"){
        $buyPercent = $rules['buy_percent']*(-1);
    }
    if($rules['buy_type'] == "2"){
        $buyPercent = $rules['buy_percent'];
    }
    if($rules['buy_type'] == "3"){
        $buyPercent = $rules['buy_percent']*(-1);
    }
    if($rules['buy_type'] == "4"){
        $buyPercent = $rules['buy_percent'];
    }

    echo "Buy Percent: ".$buyPercent."<br><br>";

    if($rules['buy_type'] == "1" or $rules['buy_type'] == "2") {
        if (($buyPercent < 0 and $percentChange < $buyPercent) or ($buyPercent > 0 and $percentChange > $buyPercent)) {
            echo "Buying process<br>";
            $amount = bolmeBtc($settings['btc_amount_per_buy'], $newestPrice);
            $buyResult = $polo->buy($rules['coin'], $newestPrice, $amount);
            echo "<pre>";
            print_r($buyResult);
            if(!!empty($buyResult[error])){
                $sellPercent = 1 + ($rules['sell_on_profit'] / 100);
                $sellPrice = carpmaBtc($sellPercent, $newestPrice);
                if ($rules['stop_loss'] > 0) {
                    $stopLossPrice = carpmaBtc((1 - $rules['stop_loss'] / 100), $newestPrice);
                } else {
                    $stopLossPrice = 0;
                }

                $orderNumber = $buyResult["orderNumber"];
                $stmt = $dbh->prepare("INSERT INTO processes (coinType, buyPrice, sellPrice, stopLossPrice, status, orderNumber,amount) VALUES ('$rules[coin]', '$newestPrice', '$sellPrice', '$stopLossPrice', 1, '$orderNumber', '$amount')");
                $stmt->execute();
            }
        }
    }elseif($rules['buy_type'] == "3" or $rules['buy_type'] == "4") {
        if (($buyPercent < 0 and $percentVolumeChange < $buyPercent) or ($buyPercent > 0 and $percentVolumeChange > $buyPercent)) {
            echo "Buying process<br>";
            $amount = bolmeBtc($settings['btc_amount_per_buy'], $newestPrice);
            $buyResult = $polo->buy($rules['coin'], $newestPrice, $amount);
            echo "<pre>";
            print_r($buyResult);
            if(!!empty($buyResult[error])){
                $sellPercent = 1 + ($rules['sell_on_profit'] / 100);
                $sellPrice = carpmaBtc($sellPercent, $newestPrice);
                if ($rules['stop_loss'] > 0) {
                    $stopLossPrice = carpmaBtc((1 - $rules['stop_loss'] / 100), $newestPrice);
                } else {
                    $stopLossPrice = 0;
                }

                $orderNumber = $buyResult["orderNumber"];
                $stmt = $dbh->prepare("INSERT INTO processes (coinType, buyPrice, sellPrice, stopLossPrice, status, orderNumber,amount) VALUES ('$rules[coin]', '$newestPrice', '$sellPrice', '$stopLossPrice', 1, '$orderNumber', '$amount')");
                $stmt->execute();
            }
        }
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
                echo $oO["orderNumber"]."-".$a["orderNumber"]."<br>";
                if ($oO["orderNumber"] == $a["orderNumber"]) {
                    $alimAcikMi = true;
                    echo $rules['coin'] . "-Alim hala acik<br>";
                    $polo->cancel_order($rules['coin'], $a["orderNumber"]);
                    $stmtDelete = $dbh->prepare("delete from processes where ordeNumber = '$a[orderNumber]'");
                    $stmtDelete->execute();
                }
            }
            if ($alimAcikMi == false) {
                //Yani alım yapılmış
                echo "Sat<br>";
                //$ticker = $polo->get_ticker($a["coinType"]);
                //Sat

                $sellFirst = $polo->sell($rules['coin'], $a["sellPrice"], carpmaBtc($a["amount"],0.9975));
                echo "<pre></pre>";
                var_dump($sellFirst);
                if(empty($sellFirst[error])){
                    $text = $a["amount"]." Sell Order saved for ".$rules['coin']." with price ".$a["sellPrice"].". You bought with price ".$a['buyPrice'];
                    $stmt = $dbh->prepare("insert into notifications set text='$text', date = NOW()");
                    $stmt->execute();
                    $sellOrderNumber = $sellFirst["orderNumber"];
                }else{
                    $stmt = $dbh->prepare("delete from processes where id='$a[id]'");
                    $stmt->execute();
                }
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

$stmt = $dbh->prepare("TRUNCATE current_balances");
$stmt->execute();

foreach($balances as $key=>$value){
    if($value > 0) {
        $valueOnOpenOrder = $polo->get_open_orders("BTC_".$key);
        for($k=0; $k<count($valueOnOpenOrder); $k++){
            if($valueOnOpenOrder[$k]['orderNumber'] != ""){
                $value = toplamaBtc($value, $valueOnOpenOrder[$k]['amount']);
            }
        }

        $stmt = $dbh->prepare("select last from data where coinType='BTC_$key' order by id desc limit 1");
        $stmt->execute();
        $price = $stmt->fetch();
        $last = $price['last'];
        if($last == ""){
            $last = 1;
        }
        //echo $key."<br>Total: ".carpmaBtc($last, $value)."<br>Last:".$last."<br>Value: ".$value."<br><br>";
        if($last == 0){
            $last = 1;
        }
        $btcValue = carpmaBtc($last, $value);
        $total = $total + $btcValue;
        $stmt_balance = $dbh->prepare("insert into current_balances set coin = '$key', balance = '$value', btc_value = '$btcValue'");
        $stmt_balance->execute();
    }
}
$stmt = $dbh->prepare("insert into total_btc set amount = '$total', date=NOW()");
$stmt->execute();

$stmt = $dbh->prepare("delete total_btc where amount = 0");
$stmt->execute();
