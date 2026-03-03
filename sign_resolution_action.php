<html>
<?php session_start(); include("database.php");?>
<head>
<link rel='stylesheet' href='style.css' type='text/css'>
<title><?php echo !empty($_SESSION) ? "Sign resolution" : "Please log in";?></title>
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
$file_name = $_POST['signature'];
$already_signed = (requestfromstring("SELECT * FROM signed WHERE part_id=".$_POST['part_id']." AND reso_id=".$_POST['reso_id'])[0]['part_id']) != NULL;
if($already_signed) {
    requestfromstring("UPDATE signed SET signature='".$file_name."' WHERE reso_id=".$_POST['reso_id']." AND part_id=".$_POST['part_id']);
} else {
    requestfromstring("INSERT INTO signed(part_id, reso_id, signature) VALUES(".$_POST['part_id'].",".$_POST['reso_id'].", '".$file_name."')");
}
 
alert("Thank you for signing the resolution");
gotolink("spectate_resolution.php?resocode=".$_POST['resocode']);


?>
</body>


<footer>
<center>
<?php echo(date("D M dS Y"));?>
</center>
</footer>

</html>