<html>
<?php session_start(); include("database.php");?>
<head>
<link rel='stylesheet' href='style.css' type='text/css'>
<title><?php echo !empty($_SESSION) ? "How to use ResoMUN" : header("LOCATION: login.php");?></title>
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
<?php echo $_SESSION['email'] != NULL ? "<p id='user_info'> Welcome back ".$_SESSION['firstname']." ".$_SESSION['lastname']."</p>".button_link("logoff.php", "Log off") : "";?>
</header>
<body>


INSERT CONTENT HERE



</body>


<footer>
<center>
<?php echo(date("D M dS Y"));?>
</center>
</footer>

</html>