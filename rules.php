<?php
include("inc/header.php");
?>

<section id="main">
    <section id="content">
        <div class="container">

            <div class="row">
                <div class="col-sm-12">
                    <!-- Recent Items -->
                    <div class="card">
                        <div class="card-header">
                            <h2><?php echo $lang["your_rules"];?> <small><?php echo $lang["your_buy_sell_rules"];?></small></h2>
                            <ul class="actions">
                                <li>
                                    <a href="newrule.php">
                                        <i class="zmdi zmdi-edit"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <div class="card-body m-t-0">
                            <table class="table table-inner table-vmiddle">
                                <thead>
                                <tr>
                                    <th><?php echo $lang["coin"];?></th>
                                    <th><?php echo $lang["buy_type"];?></th>
                                    <th><?php echo $lang["time"];?></th>
                                    <th><?php echo $lang["buy"];?> %</th>
                                    <th><?php echo $lang["sell_on_profit"];?> %</th>
                                    <th><?php echo $lang["stop_loss"];?> %</th>
                                    <th><?php echo $lang["actions"];?></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $stmt = $dbh->prepare("select * from rules order by id desc");
                                $stmt->execute();
                                $i = 0;
                                while ($rules = $stmt->fetch()) {
                                    $i++;
                                    $time = $rules['time'];
                                    if($time < 60){
                                        $time = $time." mins";
                                    }elseif($time % 1440 == 0){
                                        $time = ($time/1440)." day";
                                    }elseif($time % 60 == 0){
                                        $time = ($time/60)." hours";
                                    }
                                ?>
                                <tr>
                                    <td><?php echo substr($rules['coin'],4,4);?></td>
                                    <td><?php if($rules['buy_type'] == 1){echo "Price (-)";}elseif($rules['buy_type'] == 2){echo "Price (+)";}elseif($rules['buy_type'] == 3){echo "Volume (-)";}elseif($rules['buy_type'] == 4){echo "Volume (+)";} ?></td>
                                    <td><?php echo $time;?></td>
                                    <td><?php echo $rules['buy_percent'];?></td>
                                    <td><?php echo $rules['sell_on_profit'];?></td>
                                    <td><?php echo $rules['stop_loss'];?></td>
                                    <td style="width: 145px;">
                                        <a href="editrule.php?id=<?php echo $rules['id'];?>" class="btn btn-primary">
                                            <i class="zmdi zmdi-edit"></i>
                                        </a>
                                        <a href="javascript:;" onclick="setRuleStatus(<?php echo $rules['status'];?>,<?php echo $rules['id'];?>); return false;" class="btn btn-<?php if($rules['status'] == 1){echo "warning";}else{echo "success";} ?>">
                                            <i class="zmdi zmdi-<?php if($rules['status'] == 1){echo "pause";}else{echo "play";} ?>"></i>
                                        </a>
                                        <a href="javascript:;" onclick="deleteRule(<?php echo $rules['id'];?>); return false;" class="btn btn-danger">
                                            <i class="zmdi zmdi-delete"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php } ?>
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
    function setRuleStatus(status,id){
        $.post('inc/data.php?type=setRuleStatus',{status: status,id:id},function(r){
            location.reload();
        });
    }
    function deleteRule(id){
        $.post('inc/data.php?type=deleteRule',{id:id},function(r){
            location.reload();
        });
    }
</script>
<?php
include("inc/footer.php");
?>
