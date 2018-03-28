<?php
/**
 * Created by PhpStorm.
 * User: osmanadak
 * Date: 20.08.17
 * Time: 19:43
 */
include("inc/header.php");
$stmt = $dbh->prepare("select * from rules group by coin");
$stmt->execute();
$coins = array();
while ($a = $stmt->fetch()) {
    $coins[] = $a['coin'];
}
?>

<section id="main">
    <section id="content">
        <div class="container">

            <div class="row">
                <div class="col-sm-12">
                    <!-- Recent Items -->
                    <div class="card">
                        <div class="card-header">
                            <h2>Open Orders <small>Manage your Open Orders</small></h2>
                        </div>

                        <div class="card-body m-t-0">
                            <table class="table table-inner table-vmiddle">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Coin Code</th>
                                    <th>Amount</th>
                                    <th>Buy Price</th>
                                    <th>Sell Price</th>
                                    <th>Current Price</th>
                                    <th>Profit Status (BTC)</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $j = 0;
                                for($i=0; $i<count($coins); $i++){
                                    $openOrders = $polo->get_open_orders($coins[$i]);
                                    if(count($openOrders) > 0 ){
                                        for($k=0; $k<count($openOrders); $k++){
                                            if($openOrders[$k]['orderNumber'] != ""){
                                                $j++;
                                                $stmt2 = $dbh->prepare("select * from processes where sellOrderNumber = '".$openOrders[$k]['orderNumber']."'");
                                                $stmt2->execute();
                                                $row = $stmt2->fetch();
                                                $currentPrice = $polo -> get_ticker($coins[$i]);
                                                $profitStatus = carpmaBtc($openOrders[$k]['amount'],cikarmaBtc($currentPrice['last'],$row['buyPrice']));
                                    ?>
                                    <tr>
                                        <td class="f-500 c-cyan"><?php echo $j;?></td>
                                        <td><?php echo $coins[$i];?></td>
                                        <td><?php echo $openOrders[$k]['amount'];?></td>
                                        <td><?php echo $row['buyPrice'];?></td>
                                        <td><?php echo $row['sellPrice'];?></td>
                                        <td><?php echo $currentPrice['last'];?></td>
                                        <td <?php if($profitStatus < 0){echo "style='color: red;'";}else{echo "style='color: green;'";}?>><?php echo $profitStatus;?></td>
                                        <td style="width: 10%;">
                                            <button class="btn btn-danger" onclick="cancelOrder('<?php echo $coins[$i];?>','<?php echo $openOrders[$k]['orderNumber'];?>');">Cancel Order</button>
                                        </td>
                                    </tr>
                                <?php }}}} ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</section>
<script>
    function cancelOrder(coin,orderNumber){
        $.post('inc/data.php?type=cancelOrder', {coin:coin, orderNumber: orderNumber}, function (r) {
            toastr.options.closeButton = true;
            toastr.success('Order canceled successfully!', 'Order Canceled!', {
                "positionClass": "toast-top-center",
                timeOut: 5000
            });
        });
    }
</script>
<?php
include("inc/footer.php");
?>

