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
                                    <th>Order Type</th>
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
                                    ?>
                                    <tr>
                                        <td class="f-500 c-cyan"><?php echo $j;?></td>
                                        <td><?php echo $coins[$i];?></td>
                                        <td><?php echo ucfirst($openOrders[$k]['type']);?></td>
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

