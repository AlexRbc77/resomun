<html>
<?php session_start(); include("database.php");?>
<head>
<link rel='stylesheet' href='style.css' type='text/css'>
<title><?php echo !empty($_SESSION) ? "INSERT TITLE" : header("LOCATION: login.php");?></title>
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
<?php
$conf_number = $_GET['conf_number'];
$commit_title = $_GET['committee_title'];
$commit_id = requestfromstring("INSERT INTO committee(commit_title) VALUES ('".filter_clause($commit_title)."') RETURNING commit_id")[0]['commit_id'];
requestfromstring("INSERT INTO committee_of(committee_id, conference_id) VALUES (".$commit_id.",".$conf_number.")");
header("LOCATION: myresomun.php");
?>
</body>


<footer>
<center>
<?php echo(date("D M dS Y"));?>
</center>
</footer>

</html>