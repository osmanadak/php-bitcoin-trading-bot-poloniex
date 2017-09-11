<?php
include("inc/header.php");
?>
<section id="main">
    <section id="content">
        <div class="container">

            <div class="mini-charts">
                <div class="row">
                    <div class="col-sm-6 col-md-3">
                        <div class="mini-charts-item bgm-cyan">
                            <div class="clearfix">
                                <div class="chart stats-bar"></div>
                                <div class="count">
                                    <small><?php echo $lang["total_balance"];?></small>
                                    <h2>
                                        <?php
                                        $stmt = $dbh->prepare("select * from total_btc order by id desc limit 1");
                                        $stmt->execute();
                                        $row = $stmt->fetch();
                                        $lastAmount = $row['amount'];
                                        echo $lastAmount;
                                        ?>
                                        BTC</h2>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6 col-md-3">
                        <div class="mini-charts-item bgm-lightgreen">
                            <div class="clearfix">
                                <div class="chart count">
                                    <h2><?php
                                        $stmt = $dbh->prepare("select * from total_btc where date > (NOW() - INTERVAL 1 DAY) order by id asc limit 1");
                                        $stmt->execute();
                                        $row = $stmt->fetch();
                                        $yesterdaysAmount = $row['amount'];
                                        if($yesterdaysAmount == ""){
                                            $yesterdaysAmount = 0;
                                        }
                                        $profitToday = cikarmaBtc($lastAmount,$yesterdaysAmount);
                                        echo number_format(($profitToday*100)/($lastAmount-$profitToday), 2, ',', '.');
                                        ?> %</h2>
                                </div>
                                <div class="count">
                                    <small><?php echo $lang["profit_today"];?></small>
                                    <h2>
                                        <?php echo $profitToday; ?> BTC</h2>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6 col-md-3">
                        <div class="mini-charts-item bgm-orange">
                            <div class="clearfix">
                                <div class="chart count">
                                    <h2>
                                        <?php
                                            $stmt = $dbh->prepare("select * from total_btc where date > (NOW() - INTERVAL 7 DAY) order by id asc limit 1");
                                            $stmt->execute();
                                            $row = $stmt->fetch();
                                            $lastWeekAmount = $row['amount'];
                                            if($lastWeekAmount == ""){
                                                $lastWeekAmount = 0;
                                            }
                                            $profitThisWeek =  cikarmaBtc($lastAmount,$lastWeekAmount);
                                            echo number_format(($profitThisWeek*100)/($lastAmount-$profitThisWeek), 2, ',', '.');
                                        ?> %
                                    </h2>
                                </div>
                                <div class="count">
                                    <small><?php echo $lang["profit_last_7_days"];?></small>
                                    <h2>
                                        <?php

                                        echo $profitThisWeek;
                                        ?>
                                        BTC</h2>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6 col-md-3">
                        <div class="mini-charts-item bgm-bluegray">
                            <div class="clearfix">
                                <div class="chart count">
                                    <h2>
                                        <?php
                                            $stmt = $dbh->prepare("select * from total_btc where date > (NOW() - INTERVAL 30 DAY) order by id asc limit 1");
                                            $stmt->execute();
                                            $row = $stmt->fetch();
                                            $lastMonthAmount = $row['amount'];
                                            if($lastMonthAmount == ""){
                                                $lastMonthAmount = 0;
                                            }
                                            $profitThisMonth = cikarmaBtc($lastAmount,$lastMonthAmount);
                                            echo number_format(($profitThisMonth*100)/($lastAmount-$profitThisMonth), 2, ',', '.');
                                        ?> %
                                    </h2>
                                </div>
                                <div class="count">
                                    <small><?php echo $lang["profit_last_30_days"];?></small>
                                    <h2>
                                        <?php
                                        echo $profitThisMonth;
                                        ?>
                                        BTC</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <!-- Recent Items -->
                    <div class="card">
                        <div class="card-header">
                            <h2><?php echo $lang["balances"];?> <small><?php echo $lang["your_current_balances"];?></small></h2>
                        </div>
                        <?php
                        $balances = $polo->get_balances();

                        ?>

                        <div class="card-body m-t-0">
                            <table class="table table-inner table-vmiddle">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th><?php echo $lang["coin"];?></th>
                                    <th><?php echo $lang["balance"];?></th>
                                    <th><?php echo $lang["btc_value"];?></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $stmt = $dbh->prepare("SELECT * from current_balances order by id asc");
                                $stmt->execute();
                                while($row = $stmt->fetch()){
                                    ?>
                                    <tr>
                                        <td><?php echo $row['id'];?></td>
                                        <td><?php echo $row['coin'];?></td>
                                        <td><?php echo $row['balance'];?></td>
                                        <td><?php echo $row['btc_value'];?></td>
                                    </tr>
                                <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-sm-12">
                    <!-- Recent Items -->
                    <div class="card">
                        <div class="card-header">
                            <h2><?php echo $lang["last_activities"];?> <small><?php echo $lang["your_last_buy_sell_activities"];?></small></h2>
                        </div>

                        <div class="card-body m-t-0">
                            <table class="table table-inner table-vmiddle">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th><?php echo $lang["notification"];?></th>
                                    <th><?php echo $lang["date"];?></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $stmt = $dbh->prepare("select * from notifications order by id desc limit 10");
                                $stmt->execute();
                                $i = 0;
                                while ($row = $stmt->fetch()) {
                                    $i++;
                                    ?>
                                    <tr>
                                        <td><?php echo $i;?></td>
                                        <td><?php echo $row['text'];?></td>
                                        <td><?php echo $row['date'];?></td>
                                    </tr>
                                <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-sm-12">
                    <!-- Recent Items -->
                    <div class="card">
                        <div class="card-header">
                            <h2><?php echo $lang["your_rules"];?> <small><?php echo $lang["your_buy_sell_rules"];?></small></h2>
                        </div>

                        <div class="card-body m-t-0">
                            <table class="table table-inner table-vmiddle">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th><?php echo $lang["coin"];?></th>
                                    <th><?php echo $lang["buy_type"];?></th>
                                    <th><?php echo $lang["time"];?></th>
                                    <th><?php echo $lang["buy"];?> %</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $stmt = $dbh->prepare("select * from rules");
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
                                        <td><?php echo $i;?></td>
                                        <td><?php echo $rules['coin'];?></td>
                                        <td><?php if($rules['buy_type'] == 1){echo "Dump";}elseif($rules['buy_type'] == 2){echo "Pump";} ?></td>
                                        <td><?php echo $time;?></td>
                                        <td><?php echo $rules['buy_percent'];?></td>
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
<?php
include("inc/footer.php");
?>
