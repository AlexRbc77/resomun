<html>
<?php session_start(); include("database.php");?>
<head>
<link rel='stylesheet' href='style.css' type='text/css'>
<title>Create account</title>
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
<form action="create_account_action.php" method="post" id="login_form">
	First name <input type="text" name="fname"><br>
	Last name <input type="text" name="lname"><br>
	Age <input type="number" min=12 max=69 name="age"><br>
	Email <input type="email" name="email"><br>
	Password <input type="password" name="birthday"><br>
	<input type="submit" value="submit">
</form>
</center>


</body>


<footer>
<center>
<?php echo(date("D M dS Y"));?>
</center>
</footer>

</html>