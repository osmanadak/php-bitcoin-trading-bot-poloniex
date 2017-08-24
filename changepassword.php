<?php
include("inc/header.php");
$coins = $polo->get_trading_pairs();
?>

<section id="main">
    <section id="content">
        <div class="container">
            <div class="card" style="padding: 20px;">
                <div class="card-header">
                    <h2>Change Password <small>You can change your admin password here</small></h2>
                </div>
                <div class="form-group">
                    <div class="fg-line">
                        <input type="password" class="form-control" name="password" id="password" placeholder="Change your password">
                    </div>
                </div>
                <div class="form-group">
                    <div class="fg-line">
                        <button type="button" class="btn btn-success" onclick="changePassword(); return false;">Change Password</button>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </section>
</section>
<script>
    function changePassword(){
        if($('#password').val() != "") {
            $.post('inc/data.php?type=changePassword', {password: $('#password').val()}, function (r) {
                toastr.options.closeButton = true;
                toastr.success('Password changed successfully!', 'Password Changed!', {
                    "positionClass": "toast-top-center",
                    timeOut: 5000
                });
            });
        }
    }
</script>
<?php
include("inc/footer.php");
?>
