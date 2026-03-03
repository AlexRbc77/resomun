<html>
<?php session_start(); include("database.php");?>
<head>
<link rel='stylesheet' href='style.css' type='text/css'>
<title>Template</title>
</head>
<header id='header'>
<center>
<h1> Resolution Edition Software Of MUN </h1>
</center>
<img src='resomun_logo.png' id='logo' style='position:absolute;right:10px;overflow:auto;top:10px;'>
<script type='text/javascript'>
var logo = document.getElementById('logo');
var header = document.getElementById('header');
logo.setAttribute('height', header.scrollHeight);
</script>
<?php echo "<p id='user_info'> Logged in as ".$_SESSION['firstname']." ".$_SESSION['lastname']."</p>";?>
</header>
<body>


<?php
$email = $_POST['email'];
$sponsor = $_POST['sponsor'];

$request = "SELECT * FROM usr WHERE email='".$email."' AND password = md5('".$sponsor."')";

$r = requestfromstring($request);

if(empty($r)) {
	echo "<p style='color:red;'> ".$email." is not a registered user (it's not too late to sign up) OR the password is wrong (and that's on you) </p>";
	button_link("create_account.php", "Create an account");
	button_link("index.php", "Back to index page");
} else {
	$profile = $r[0];
	$_SESSION['email'] = $profile['email'];
	$_SESSION['firstname'] = $profile['firstname'];
	$_SESSION['lastname'] = $profile['lastname'];
	echo "<p style='color:green;'> Login successful. Welcome back ".$profile['firstname']." ".$profile['lastname']."</p>";
	button_link("index.php", "Back to index page");
	button_link("myresomun.php", "My ResoMUN");
	header("LOCATION: myresomun.php");
}

?>



</body>


<footer>
<center>
<?php echo(date("D M dS Y"));?>
</center>
</footer>

</html>