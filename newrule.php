<?php
include("inc/header.php");
$coins = $polo->get_trading_pairs();
?>

<section id="main">
    <section id="content">
        <div class="container">
            <form id="ruleForm">
                <div class="card">
                    <div class="card-header">
                        <h2>New Rule <small>New Rule Definition</small></h2>
                        <ul class="actions">
                            <li>
                                <a href="rules.php">
                                    <i class="zmdi zmdi-view-list"></i>
                                </a>
                            </li>
                        </ul>
                    </div>

                    <div class="card-body card-padding">
                        <div class="form-group">
                            <div class="fg-line">
                                <div class="select">
                                    <select id="coin" name="coin" class="form-control" onchange="coinChanged(this.value);">
                                        <option value="0">Select an Altcoin</option>
                                        <?php
                                        for($i = 0; $i < count($coins); $i++){
                                            if(substr($coins[$i],0,4) == "BTC_"){
                                                ?>
                                                <option value="<?php echo $coins[$i]; ?>"><?php echo substr($coins[$i],4,4); ?></option>
                                            <?php }} ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="fg-line">
                                Buy Price: <span id="buyPrice"> - </span>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="fg-line">
                                Sell Price: <span id="sellPrice"> - </span>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="fg-line">
                                <div class="select">
                                    <select id="buy_type" name="buy_type" class="form-control">
                                        <option>Select Buy Type</option>
                                        <option value="1">Dump</option>
                                        <option value="2">Pump</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="fg-line">
                                <div class="select">
                                    <select id="time" name="time" class="form-control">
                                        <option value="0">Select Time</option>
                                        <option value="1">1 minute</option>
                                        <option value="2">2 minutes</option>
                                        <option value="3">3 minutes</option>
                                        <option value="4">4 minutes</option>
                                        <option value="5">5 minutes</option>
                                        <option value="10">10 minutes</option>
                                        <option value="15">15 minutes</option>
                                        <option value="20">20 minutes</option>
                                        <option value="30">30 minutes</option>
                                        <option value="60">1 hour</option>
                                        <option value="120">2 hours</option>
                                        <option value="180">3 hours</option>
                                        <option value="360">6 hours</option>
                                        <option value="720">12 hours</option>
                                        <option value="1440">1 day</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="fg-line">
                                <div class="select">
                                    <select id="buy_percent" name="buy_percent" class="form-control">
                                        <option value="0">Select Buy %</option>
                                        <option value="1">1 %</option>
                                        <option value="2">2 %</option>
                                        <option value="3">3 %</option>
                                        <option value="4">4 %</option>
                                        <option value="5">5 %</option>
                                        <option value="10">10 %</option>
                                        <option value="15">15 %</option>
                                        <option value="20">20 %</option>
                                        <option value="25">25 %</option>
                                        <option value="30">30 %</option>
                                        <option value="35">35 %</option>
                                        <option value="40">40 %</option>
                                        <option value="45">45 %</option>
                                        <option value="50">50 %</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="fg-line">
                                <div class="select">
                                    <select id="sell_on_profit" name="sell_on_profit" class="form-control">
                                        <option value="0">Sell on Profit %</option>
                                        <option value="1">1 %</option>
                                        <option value="2">2 %</option>
                                        <option value="3">3 %</option>
                                        <option value="4">4 %</option>
                                        <option value="5">5 %</option>
                                        <option value="10">10 %</option>
                                        <option value="15">15 %</option>
                                        <option value="20">20 %</option>
                                        <option value="25">25 %</option>
                                        <option value="30">30 %</option>
                                        <option value="35">35 %</option>
                                        <option value="40">40 %</option>
                                        <option value="45">45 %</option>
                                        <option value="50">50 %</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="fg-line">
                                <div class="select">
                                    <select id="stop_loss" name="stop_loss" class="form-control">
                                        <option>Stop Loss %</option>
                                        <option value="1">1 %</option>
                                        <option value="2">2 %</option>
                                        <option value="3">3 %</option>
                                        <option value="4">4 %</option>
                                        <option value="5">5 %</option>
                                        <option value="10">10 %</option>
                                        <option value="15">15 %</option>
                                        <option value="20">20 %</option>
                                        <option value="25">25 %</option>
                                        <option value="30">30 %</option>
                                        <option value="35">35 %</option>
                                        <option value="40">40 %</option>
                                        <option value="45">45 %</option>
                                        <option value="50">50 %</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="fg-line">
                                <button type="button" class="btn btn-success" onclick="newRule(); return false;">Submit New Rule</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
</section>
<script>
    function coinChanged(coin){
        $.post('inc/data.php?type=getTicker',{coin: coin}, function(r){
            var result = JSON.parse(r);
            $('#buyPrice').html(result.highestBid);
            $('#sellPrice').html(result.lowestAsk);
        });
    }
    function newRule(){
        $.post('inc/data.php?type=newRule',$('#ruleForm').serialize(),function(r){
            window.location = 'rules.php';
        });
    }
</script>
<?php
include("inc/footer.php");
?>
