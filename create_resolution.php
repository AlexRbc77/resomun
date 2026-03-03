<html>
<?php session_start(); include("database.php");?>
<head>
<link rel='stylesheet' href='style.css' type='text/css'>
<title><?php echo !empty($_SESSION) ? "Create resolution" : header("LOCATION: login.php");?></title>
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
if(strlen($_POST['reso_title']) < 10) {
    alert("You can't create a resolution without a title!");
    header("LOCATION: myresomun.php");
}


$user_id = requestfromstring("SELECT usr_id FROM usr WHERE email='".$_SESSION['email']."'")[0]['usr_id'];
$confcom = explode(',', $_POST['conf_commit']);
$conference = $confcom[0]; $committee = $confcom[1];
$part_id = requestfromstring("SELECT part_id FROM participated_in WHERE usr_id = ".$user_id."AND commit_id = ".$committee)[0]['part_id'];
if($conference != NULL && $committee != NULL && strlen($_POST['reso_title']) > 5) {
    $reso_id = requestfromstring("INSERT INTO resolution(reso_title) VALUES('".$_POST['reso_title']."') RETURNING reso_id")[0]['reso_id'];
    requestfromstring("INSERT INTO main_sub (main_sub_id, resolution_id, commit_id) VALUES (".$part_id.",".$reso_id.",".$committee.")");
    requestfromstring("INSERT INTO signed (part_id, reso_id) VALUES (".$part_id.",".$reso_id.")");
    system("./qrgen.sh ".$reso_id." ".$part_id." ".$committee." ".$conference);

    header("LOCATION: myresomun.php");
} else {
    alert("You are missing a conference or committee");
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