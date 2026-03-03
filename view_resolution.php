<html>
<?php session_start(); include("database.php");?>
<head>
<link rel='stylesheet' href='style.css' type='text/css'>
<?php $reso_title = requestfromstring("SELECT reso_title FROM resolution WHERE reso_id = ".$_GET['reso_id'])[0]['reso_title'];?>
<title><?php echo !empty($_SESSION) ? $reso_title  : header("LOCATION: login.php");?></title>
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
$user_id = requestfromstring("SELECT usr_id FROM usr WHERE email='".$_SESSION['email']."'")[0]['usr_id'];
$commit_title = requestfromstring("SELECT commit_title FROM committee WHERE commit_id=".$committee)[0]['commit_title'];
$reso_title = requestfromstring("SELECT reso_title FROM resolution WHERE reso_id=".$reso_id)[0]['reso_title'];
$conf_title = requestfromstring("SELECT conf_title FROM conference, committee_of WHERE conference.conf_id = committee_of.conference_id AND committee_of.committee_id=".$committee)[0]['conf_title'];
$conf_id = requestfromstring("SELECT conf_id FROM conference, committee_of WHERE conference.conf_id = committee_of.conference_id AND committee_of.committee_id=".$committee)[0]['conf_id'];
$part_id = requestfromstring("SELECT main_sub_id FROM main_sub WHERE resolution_id=".$reso_id)[0]['main_sub_id'];
$del_title = get_country_from_part_number($part_id);

$self_part_id = requestfromstring("SELECT part_id FROM participated_in WHERE conf_id=".$conf_id." AND usr_id = ".$user_id)[0]['part_id'];

if($self_part_id != $part_id && !is_conf_officer($conf_id, $self_part_id)) {
    alert("You are not allowed to view this document");
    gotolink("myresomun.php");
}

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

<div class='resolution'>
THE <?php echo strtoupper($commit_title)?>,<br>
<?php echo string_resolution($reso_id);?>
</div><center>
<h1>Signatories</h1>
</center>
<?php
$result = "<div style='width:100%;overflow:auto;' id='signature_div'>";
$signatures = requestfromstring("SELECT signed.part_id, signature, del_id FROM signed, represents WHERE represents.part_id = signed.part_id AND signed.part_id != ".$part_id." AND reso_id=".$reso_id." ORDER BY del_id");

foreach($signatures as $sign) {
	$result .= signature_div($sign);
}
$result .= "</div>";
$main_sub = requestfromstring("SELECT part_id, signature FROM signed, main_sub WHERE main_sub.main_sub_id = signed.part_id AND reso_id=".$reso_id)[0];
$result .= main_sub_signature_div($main_sub);
echo $result;

?>
<?php 
if($self_part_id != $part_id || !is_conf_officer($conf_id, $self_part_id)) {
    button_link("myresolution.php?reso_id=".$reso_id."&committee=".$committee, "Edit");
}

?>
<?php button_link("resolution_to_pdf.php?reso_id=".$reso_id."&committee=".$committee, "Print to PDF");?>
<?php button_link("myresomun.php", "My ResoMUN");?>
</body>


<footer>
<center>
<?php echo(date("D M dS Y"));?>
</center>
</footer>

</html>