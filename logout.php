<?php
	include 'inc/core.php';
	if($us) {
		$stmt = $dbh->prepare("DELETE FROM remember WHERE user_id='$us[id]'");
		$stmt->execute();
		session_destroy();
	}
	header("Location: index.php");
?>