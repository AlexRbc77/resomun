<html>
<?php session_start(); include("database.php");?>


<?php $committee = $_GET['committee'];
$reso_id = $_GET['reso_id'];
$user_id = requestfromstring("SELECT usr_id FROM usr WHERE email='".$_SESSION['email']."'")[0]['usr_id'];
$commit_title = requestfromstring("SELECT commit_title FROM committee WHERE commit_id=".$committee)[0]['commit_title'];
$reso_title = requestfromstring("SELECT reso_title FROM resolution WHERE reso_id=".$reso_id)[0]['reso_title'];
$conf_title = requestfromstring("SELECT conf_title FROM conference, committee_of WHERE conference.conf_id = committee_of.conference_id AND committee_of.committee_id=".$committee)[0]['conf_title'];
$conf_id = requestfromstring("SELECT conf_id FROM conference, committee_of WHERE conference.conf_id = committee_of.conference_id AND committee_of.committee_id=".$committee)[0]['conf_id'];
$part_id = requestfromstring("SELECT main_sub_id FROM main_sub WHERE resolution_id=".$reso_id)[0]['main_sub_id'];
$del_title = get_country_from_part_number($part_id);
?>

<title><?php echo $reso_title?></title>
<?php 
$contents = "<style>
table,
td,
tr {
    border-collapse: collapse;
    border: 2px solid black;
}

.conferences {
    background-color: lightblue;
    border: 1px lightgreen solid;
}

.clause {
    width: 100%;
    box-sizing: border-box;
}

.sclause {
    width: 100%;
    box-sizing: border-box;
}

.ssclause {
    width: 100%;
    box-sizing: border-box;
}

.reso_header {
    width: 75%;
    float: left;
    display: block;
    margin-left: 3%;
}

.reso_qrcode {
    width: 20%;
    display: block;
    float:right;
    margin-right: 2%;
}

.common_header {
    width: 100%;
    height: auto;
    padding-top: 10px;
    display: block;
    overflow: auto;
    font-size: 1.8em;
    float:center;
    margin: 10px 10px 10px 0px;
}

.resolution {
    font-size: 1.5em;
    margin-left: 0.63cm;
    padding-left: 5%;
    padding-right: 5%;
}

  @media print  
{
    .sign_div {
        page-break-inside: avoid;
    }
    .main_sign_div {
        page-break-inside: avoid;
    }
    ol.clause {
        page-break-inside: avoid;
    }
    .signatories_div {
        page-break-inside: avoid;
    }
}

</style>
";

$contents .= "
<header></header>
<body>
<div class='common_header'>
<div class='reso_header'>
<h2><b>Forum:</b> ".$conf_title."</h2>
<h2><b>Issue:</b> ".$reso_title ."</h2>
<h2><b>Main submitter:</b> ".$del_title."</h2>
<h2><b>Signatories: </b>".signatories_string(get_signatures($reso_id))."</h2>
</div>
<div class='reso_qrcode'>
<img src='../qrcodes/RESOMUN_".$reso_id.".png' id='qr_image' width='75%'>
<p style='font-size:10px' > ResoCode: ResoMUN:".$part_id.";".$reso_id."!".$committee."</p>
</div>
</div><br>";
$contents .= "
<div class='resolution'>
THE ".strtoupper($commit_title).",<br>
".string_resolution($reso_id)."
</div><center>
<div class='signatories_div'>
<h1>Signatories</h1>
</center>";
$contents .= "<div style='width:100%;overflow:auto;' id='signature_div'>";
$signatures = requestfromstring("SELECT signed.part_id, signature, del_id FROM signed, represents WHERE represents.part_id = signed.part_id AND signed.part_id != ".$part_id." AND reso_id=".$reso_id." ORDER BY del_id");

foreach($signatures as $sign) {
	$contents .= signature_div($sign);
}
$contents .= "</div>";
$main_sub = requestfromstring("SELECT part_id, signature FROM signed, main_sub WHERE main_sub.main_sub_id = signed.part_id AND reso_id=".$reso_id)[0];
$contents .= main_sub_signature_div($main_sub);
$contents .= "
</body>
<style>
.sign_div {
    width:45%;
}
.sign_div img {
    width:50%;
    height:50%;
}

</style></div>
<p style='font-size:10px;float:right;'> Written by ";
$user_id = requestfromstring("SELECT usr_id FROM participated_in WHERE part_id=".$part_id)[0]['usr_id'];
$name = requestfromstring("SELECT (firstname || ' ' || lastname) AS name FROM usr WHERE usr_id=".$user_id)[0]['name'];
$contents .= $name." with <a href='http://resomun.tech'> ResoMUN </a> </p>";
$file_name = "Resolution_".$reso_id.".html";
echo $contents;
shell_exec("sudo touch ".$file_name);
$file = fopen("resolution_pdf/".$file_name, "w");
fwrite($file, $contents);
fclose($file);

shell_exec("sudo rm resolution_pdf/Resolution_".$reso_id.".pdf");
shell_exec("sudo python3 print_to_pdf.py resolution_pdf/".$file_name." resolution_pdf/Resolution_".$reso_id.".pdf");
gotolink("resolution_pdf/Resolution_".$reso_id.".pdf");


?>



</html>