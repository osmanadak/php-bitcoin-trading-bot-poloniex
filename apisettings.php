<?php
include("inc/header.php");
$stmt = $dbh->prepare("select * from api_settings order by id asc limit 1");
$stmt->execute();
$row = $stmt->fetch();
?>

<section id="main">
    <section id="content">
        <div class="container">
            <form id="apiSettingsForm">
                <div class="card">
                    <div class="card-header">
                        <h2><?php echo $lang["api_settings"];?> <small><?php echo $lang["api_definition_and_settings"];?></small></h2>
                    </div>

                    <div class="card-body card-padding">
                        <div class="form-group">
                            <div class="fg-line">
                                <input name="api_key" id="api_key" type="text" class="form-control" placeholder="<?php echo $lang["api_key"];?>" value="<?php echo $row['api_key'];?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="fg-line">
                                <input name="api_secret" id="api_secret" type="text" class="form-control" placeholder="<?php echo $lang["api_secret"];?>" value="<?php echo $row['api_secret'];?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="fg-line">
                                <input name="btc_amount_per_buy" id="btc_amount_per_buy" type="number" class="form-control" placeholder="<?php echo $lang["btc_amount_per_buy"];?>" value="<?php echo $row['btc_amount_per_buy'];?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="fg-line">
                                <input name="buy_limit_per_coin" id="buy_limit_per_coin" type="number" class="form-control" placeholder="<?php echo $lang["buy_limit_per_coin"];?>" value="<?php echo $row['buy_limit_per_coin'];?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="fg-line">
                                <button type="button" class="btn btn-success" onclick="saveApiSettings(); return false;"><?php echo $lang["save_api_settings"];?></button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
</section>
<script>
    function saveApiSettings(){
        $.post('inc/data.php?type=saveApiSettings',$('#apiSettingsForm').serialize(),function(r){
            //window.location = 'coins.php';
            toastr.options.closeButton = true;
            toastr.success('Api Settings saved successfully!', 'Api Settings!', {"positionClass": "toast-top-center",timeOut: 5000});
        });
    }
</script>
<?php
include("inc/footer.php");
?>
