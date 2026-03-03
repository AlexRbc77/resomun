<html>
<?php session_start(); include("database.php");?>
<head>
<link rel='stylesheet' href='style.css' type='text/css'>
<title><?php echo !empty($_SESSION) ? "Create a conference" : header("LOCATION: login.php");?></title>
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
<?php echo $_SESSION['email'] != NULL ? "<p id='user_info'> Logged in as ".$_SESSION['firstname']." ".$_SESSION['lastname']."</p>".button_link("logoff.php", "Log off") : "Not logged in ".button_link("login.php", "Login");?>
</header>
<body>


<form action='create_conference_action.php' method='post'>
    <label for='conf_title'>Conference Initials and year ex:TESTMUN2020</label>
    <input type='text' name='conf_title' id='conf_title' placeholder="INITIALSYEAR"><br>
    <input type='submit' value='Create Conference'>
</form>



</body>


<?php button_link("myresomun.php", "My ResoMUN");?>
<footer>
<center>
<?php echo(date("D M dS Y"));?>
</center>
</footer>

</html>