<?php
include 'inc/core.php';
if($us) { header("Location: dashboard.php"); exit(); }
if($_POST) {
$terms = $_POST['terms'];
$username = mysql_real_escape_string(htmlspecialchars($_POST['username']));
$email = mysql_real_escape_string(htmlspecialchars($_POST['email']));
$password = mysql_real_escape_string(htmlspecialchars($_POST['password']));
$password2 = mysql_real_escape_string(htmlspecialchars($_POST['password2']));

if($terms) {
if($username == '' || $email == '' || $password == '' || $password2 == '') {
$msg = "Please fill in all fields!";
}else{
$uch = mysql_fetch_array(mysql_query("SELECT id FROM users WHERE username='$username'"));
$ech = mysql_fetch_array(mysql_query("SELECT id FROM users WHERE email='$email'"));

if($uch) {
$msg = "The username you entered is already in use!";
}elseif($ech) {
$msg = "The email you entered is already in use!";
}elseif($password != $password2) {
$msg = "The passwords you entered does not match!";
}else{
$password = md5($password);
mysql_query("INSERT INTO users (username, password, email) VALUES('$username', '$password', '$email') ") or die(mysql_error());  
$_SESSION['userid'] = mysql_insert_id();
header("Location: dashboard.php");
exit();
}}
}else{
$msg = "You must agree to the terms of service!";
}

} 
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title><?php echo$st['site_title']; ?> - Register</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <!-- Bootstrap 3.3.2 -->
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <!-- Font Awesome Icons -->
    <link href="plugins/fontAwesome/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <!-- Theme style -->
    <link href="dist/css/AdminLTE.min.css" rel="stylesheet" type="text/css" />
    <!-- iCheck -->
    <link href="plugins/iCheck/square/blue.css" rel="stylesheet" type="text/css" />

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
  </head>

<body class="login-page">
<?php
if($msg != "") {
echo'<div class="alert alert-danger alert-dismissable">
<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
<h4><i class="icon fa fa-ban"></i> Oops!</h4>
'.$msg.'
</div>';
}
?>
 
    <div class="login-box">
      <div class="login-logo">
        <a href=""><?php echo$st['site_title']; ?></a>
      </div><!-- /.login-logo -->
      <div class="login-box-body">
        <p class="login-box-msg">Sign in to start your session</p>
        <form action="" method="post">
          <div class="form-group has-feedback">
            <input type="text" name="username" value="<?php echo$username; ?>" class="form-control" placeholder="Username"/>
            <span class="glyphicon glyphicon-user form-control-feedback"></span>
          </div>
          <div class="form-group has-feedback">
            <input type="text" name="email" value="<?php echo$email; ?>" class="form-control" placeholder="Email"/>
            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
          </div>
          <div class="form-group has-feedback">
            <input type="password" name="password" class="form-control" placeholder="Password"/>
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
          </div>
          <div class="form-group has-feedback">
            <input type="password" name="password2" class="form-control" placeholder="Retype password"/>
            <span class="glyphicon glyphicon-log-in form-control-feedback"></span>
          </div>
          <div class="row">
           <div class="col-xs-8">    
              <div class="checkbox icheck">
                <label>
                  <input name="terms" type="checkbox"> I agree to the <a href="#">terms</a>
                </label>
              </div>                        
            </div><!-- /.col -->
            <div class="col-xs-4">
              <button type="submit" class="btn btn-primary btn-block btn-flat">Register</button>
            </div><!-- /.col -->
          </div>
        </form>       

        
        <a href="login.php" class="text-center">I already have a membership</a>

      </div><!-- /.login-box-body -->
    </div><!-- /.login-box -->

    <!-- jQuery 2.1.3 -->
    <script src="plugins/jQuery/jQuery-2.1.3.min.js"></script>
    <!-- Bootstrap 3.3.2 JS -->
    <script src="bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
    <!-- iCheck -->
    <script src="plugins/iCheck/icheck.min.js" type="text/javascript"></script>
    <script>
      $(function () {
        $('input').iCheck({
          checkboxClass: 'icheckbox_square-blue',
          radioClass: 'iradio_square-blue',
          increaseArea: '20%' // optional
        });
      });
    </script>
  </body>
</html>