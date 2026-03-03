<html>
<?php session_start(); include("database.php");?>
<head>
<link rel='stylesheet' href='style.css' type='text/css'>
<?php
list($part, $reso_id, $committee) = sscanf($_GET['resocode'], "ResoMUN:%d;%d!%d");
?>
<?php $reso_title = requestfromstring("SELECT reso_title FROM main_sub, resolution WHERE resolution.reso_id = main_sub.resolution_id AND reso_id = ".$reso_id." AND main_sub.commit_id=".$committee." AND main_sub_id=".$part)[0]['reso_title'];?>
<?php
if($reso_title == NULL) {
    alert("The resolution was not found");
    gotolink("myresomun.php");
}
?>
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


<?php 
$user_id = requestfromstring("SELECT usr_id FROM usr WHERE email='".$_SESSION['email']."'")[0]['usr_id'];
$commit_title = requestfromstring("SELECT commit_title FROM committee WHERE commit_id=".$committee)[0]['commit_title'];
$reso_title = requestfromstring("SELECT reso_title FROM resolution WHERE reso_id=".$reso_id)[0]['reso_title'];
$conf_title = requestfromstring("SELECT conf_title FROM conference, committee_of WHERE conference.conf_id = committee_of.conference_id AND committee_of.committee_id=".$committee)[0]['conf_title'];
$conf_id = requestfromstring("SELECT conf_id FROM conference, committee_of WHERE conference.conf_id = committee_of.conference_id AND committee_of.committee_id=".$committee)[0]['conf_id'];
$part_id = requestfromstring("SELECT main_sub_id FROM main_sub WHERE resolution_id=".$reso_id)[0]['main_sub_id'];
$del_title = get_country_from_part_number($part_id);

$self_part_id = requestfromstring("SELECT part_id FROM participated_in WHERE commit_id=".$committee." AND conf_id=".$conf_id." AND usr_id = ".$user_id)[0]['part_id'];
$is_delegate = requestfromstring("SELECT * FROM has_role NATURAL JOIN participated_in WHERE role_id=9 AND commit_id=".$committee." AND part_id=".$self_part_id)[0];
$signed_resolution = requestfromstring("SELECT * FROM signed WHERE reso_id=".$reso_id." AND part_id=".$self_part_id)[0];
$main_sub = $part_id == $self_part_id;

$main_sub_signed = (requestfromstring("SELECT signature FROM signed WHERE part_id=".$part_id." AND reso_id=".$reso_id)[0]['signature']) != NULL;
$status = requestfromstring("SELECT reso_status FROM resolution WHERE reso_id=".$reso_id)[0]['reso_status'];
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
<?php 
if((!empty($is_delegate) && empty($signed_resolution)) || ($main_sub && !$main_sub_signed)) {
    button_link("sign_resolution.php?part_id=".$self_part_id."&reso_id=".$reso_id."&resocode=".$_GET['resocode'], "Sign resolution");
} else if(!empty($signed_resolution)) {
    echo "<p style='font-size: 15px;'> Thank you for signing the resolution </p>";
} else {
    echo "<p style='font-size: 15px;'> You can't sign the resolution </p>";
}
?>
</div>
</div>
&#9;<h4>Current status: <?php echo $status;?></h4>
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
<center>
<div style='overflow:auto;width:100%;'>
<div class="update" style='float:left; width:50%;'>
<?php button_link("spectate_resolution.php?resocode=".$_GET['resocode'], "Update");?>
</div>
<div class="pdf" style='float:left; width:50%;'>
<?php button_link("resolution_to_pdf.php?reso_id=".$reso_id."&committee=".$committee, "Print to PDF <img src='PDF.svg' style='height:20px;'>");?>
</div>
<?php 
if(is_delegate_of_committee($self_part_id, $committee)) {
    echo "<form action='ammend_resolution.php' method='get'>
    <label for='ammend_type'>Ammend resolution</label>
    <select name='ammend_type'>
    <option value='ADD'> Add (clause, subclause or subsubclause) </option>
    <option value='EDIT'> Edit (clause, subclause or subsubclause) </option>
    <option value='DEL'> Delete (clause, subclause or subsubclause) </option>
    </select>
    <input type='number' value='".$reso_id."' hidden='true' name='reso_id'>
    <input type='submit' value='Ammend resolution'>
    </form>";
}
?>
</div>
<?php button_link("myresomun.php", "My ResoMUN");?>
<br>
<br>
</center>
</body>


<footer>
<center>
<?php echo(date("D M dS Y"));?>
</center>
</footer>

</html>