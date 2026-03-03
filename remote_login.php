<?php session_start(); include("database.php");?>


<?php

$email = $_GET['email'];
$sponsor = $_GET['sponsor'];

$request = "SELECT * FROM usr WHERE email='".$email."' AND password = md5('".$sponsor."')";

$r = requestfromstring($request);
?>
<?php
if(empty($r)) {
	$f = fopen("login_".0.".json", "w");
	fwrite($f, "{'login':'ERROR'}");
	fclose($f);
	gotolink("login_".0.".json");
} else {
	$profile = $r[0];
	$f = fopen("login_".$profile['usr_id'].".json", "w");
	fwrite($f, "{'login':'OK;".$profile['usr_id'].";".$profile['firstname']." ".$profile['lastname']."'}");
	fclose($f);
	gotolink("login_".$profile['usr_id'].".json");
}
?>

