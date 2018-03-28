<?php
include 'inc/core.php';
if($us) {
    $stmt = $dbh->prepare("DELETE FROM remember WHERE user_id='$us[id]'");
    $stmt->execute();
    session_destroy();
}
echo "<script type='text/javascript'> document.location = 'index.php'; </script>";
?>