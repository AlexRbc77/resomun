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

$s = "INSERT INTO usr(firstname, lastname, age, email, password)
VALUES ('".$_POST['fname']."','".$_POST['lname']."',".$_POST['age'].",'".$_POST['email']."',md5('".$_POST['birthday']."'));";

$result = requestfromstring($s);

if(count($result[0]) == 0) {
	echo "Account created succesfully<br>";
	button_link("index.php", "BACK TO INDEX");
}

?>



</body>


<footer>
<center>
<?php echo(date("D M dS Y"));?>
</center>
</footer>

</html>