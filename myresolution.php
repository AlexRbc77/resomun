<html>
<?php session_start(); include("database.php");?>
<head>
<link rel='stylesheet' href='style.css' type='text/css'>
<?php $reso_title = requestfromstring("SELECT reso_title FROM resolution WHERE reso_id=".$_GET['reso_id'])[0]['reso_title'];?>
<title><?php echo !empty($_SESSION) ? $reso_title : header("LOCATION: login.php");?></title>
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
<?php $committee = $_GET['committee'];
$reso_id = $_GET['reso_id'];

$edit_open = requestfromstring("SELECT edit FROM resolution WHERE reso_id=".$reso_id)[0]['edit'];
$status = requestfromstring("SELECT reso_status FROM resolution WHERE reso_id=".$reso_id)[0]['reso_status'];

if(!$edit_open) {
    alert("Editing for this resolution (id: ".$reso_id.") is not open");
    gotolink("myresomun.php");
}
$user_id = requestfromstring("SELECT usr_id FROM usr WHERE email='".$_SESSION['email']."'")[0]['usr_id'];
$commit_title = requestfromstring("SELECT commit_title FROM committee WHERE commit_id=".$committee)[0]['commit_title'];
$reso_title = requestfromstring("SELECT reso_title FROM resolution WHERE reso_id=".$reso_id)[0]['reso_title'];
$conf_title = requestfromstring("SELECT conf_title FROM conference, committee_of WHERE conference.conf_id = committee_of.conference_id AND committee_of.committee_id=".$committee)[0]['conf_title'];

$part_id = requestfromstring("SELECT main_sub_id FROM main_sub WHERE resolution_id=".$reso_id)[0]['main_sub_id'];
$del_title = get_country_from_part_number($part_id);
?>
<div class="common_header">
<div class="reso_header">
<h2><b>Forum:</b> <?php echo $conf_title ?></h2>
<h2><b>Issue:</b> <?php echo $reso_title ?></h2>
<h2><b>Main submitter:</b> <?php echo $del_title ?></h2>
<h2><b>Signatories: </b><?php echo signatories_string(get_signatures($reso_id));?></h2>
</div>
<div class='reso_qrcode'>
<img src='qrcodes/RESOMUN_<?php echo $reso_id?>.png' id="qr_image" style="max-width:100%;max-height:85%;">
<p id='resocode'>Scan this code with the app to sign the resolution or use ResoCode: <?php echo "ResoMUN:".$part_id.";".$reso_id."!".$committee?></p>
</div>
</div>

&#9;<h4>Current status: <?php echo $status;?></h4>

<form action="change_status.php" method="post">
    <label for="new_status">Change status:</label>
    <select name="new_status">
        <option value="working paper">Working Paper</option>
        <option value="draft resolution">Draft Resolution</option>
        <option value="resolution">Resolution</option>
    </select>
    <input value='<?php echo $reso_id?>' name='reso_id' type='number' hidden='true'>
    <input value='<?php echo $committee?>' name='commit_id' type='number' hidden='true'>
    <input value="Switch status" type='submit'>
</form>

<?php 
$part_id = requestfromstring("SELECT part_id FROM participated_in WHERE usr_id = ".$user_id."AND commit_id = ".$committee)[0]['part_id'];
$is_main_sub = requestfromstring("SELECT main_sub_id FROM main_sub WHERE resolution_id = ".$reso_id)[0]['main_sub_id'] == $part_id; //or if you're chair or if officer (to be done later);

if(!$is_main_sub) {
    alert("You are not main sub to this resolution. You cannot modify it");
    header("LOCATION: myresomun.php");
}
?>
<form action='save_resolution.php' method='post'>
<?php
load_resolution($reso_id);
?>
<input value='<?php echo $reso_id?>' type='number' hidden=true name='reso_id'>
<input value='<?php echo $committee?>' type='number' hidden=true name='commit_id'>
<input value='Save Resolution' type='submit' name='action' onclick='alert("You saved your resolution");'>
<input value='Create Clause' type='submit' name='action'>
<input value='Preview' type='submit' name='action'>
</form>



</body>

<?php button_link("myresomun.php", "My ResoMUN");?>
<footer>
<center>
<?php echo(date("D M dS Y"));?>
</center>
</footer>

</html>