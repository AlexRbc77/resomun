<html>
<?php session_start(); include("database.php");?>
<head>
<link rel='stylesheet' href='style.css' type='text/css'>
<title><?php echo !empty($_SESSION) ? "Add delegate to committee" : header("LOCATION: login.php");?></title>
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

$participation = requestfromstring("INSERT INTO participant VALUES (default) RETURNING part_id")[0]['part_id'];
requestfromstring("INSERT INTO participated_in(usr_id, conf_id, part_id, commit_id) VALUES (".$_POST['person_choice'].",".$_POST['conf_id'].",".$participation.",".$_POST['commit_id'].")");
requestfromstring("INSERT INTO represents(part_id, del_id) VALUES (".$participation.",".$_POST['delegation_choice'].")");
requestfromstring("INSERT INTO has_role(role_id, part_id) VALUES (9,".$participation.")");

header("LOCATION: mycommittee.php?commit_choice=".$_POST['commit_id']);
?>
</body>

<?php button_link("myresomun.php", "My ResoMUN");?>
<footer>
<center>
<?php echo(date("D M dS Y"));?>
</center>
</footer>

</html>