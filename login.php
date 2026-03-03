<html>
<?php session_start(); include("database.php");?>
<head>
<link rel='stylesheet' href='style.css' type='text/css'>
<title><?php echo empty($_SESSION) ? "Login page" : header("LOCATION: myresomun.php");?></title>
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

<center>
<form action="login_action.php" method="post" id="login_form">
Email <br><input type="email" name="email" placeholder="elmo@monster.com" id="email_input"><br>
Password <br><input type="password" name="sponsor" placeholder="secretpassword1234!" id="password_input"><br>
<input type="submit" value="login" id="login_submit"><br>

</form>
</center>


</body>


<footer>
<center>
<?php echo(date("D M dS Y"));?>
</center>
</footer>

</html>