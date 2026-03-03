<html>
<?php session_start(); include("database.php");?>
<head>
<link rel='stylesheet' href='style.css' type='text/css'>
<title><?php echo !empty($_SESSION) ? "Ammendment submission" : header("LOCATION: login.php");?></title>
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
$user_id = requestfromstring("SELECT usr_id FROM usr WHERE email='".$_SESSION['email']."'")[0]['usr_id']; //OK
$conf_id = requestfromstring("SELECT conference_id FROM main_sub, committee_of WHERE committee_of.committee_id = main_sub.commit_id AND main_sub.resolution_id = ".$_GET['reso_id'])[0]['conference_id']; //Problem
$part_id = requestfromstring("SELECT part_id FROM participated_in WHERE usr_id=".$user_id." AND conf_id=".$conf_id)[0]['part_id'];
list($clause_number, $clause_id) = explode(";", $_GET['clause_number']);
$ammend_id = requestfromstring("INSERT INTO ammendment(ammendment_sub, ammend_type, ammend_body, reso_id) VALUES (".$part_id.", '".$_GET['ammend_type']."', '".$_GET['clause_number'].";".$_GET['contents']."', ".$_GET['reso_id'].") RETURNING ammend_id")[0]['ammend_id'];
$committee = requestfromstring("SELECT commit_id FROM main_sub WHERE resolution_id=".$_GET['reso_id'])[0]['commit_id'];
$sub_id = requestfromstring("SELECT main_sub_id FROM main_sub WHERE resolution_id=".$_GET['reso_id'])[0]['main_sub_id'];

if($ammend_id != NULL) {
    echo green("Your ammendment has been registered, it will now be sent to the chairs");
    

    $ammend_string = "";

    $ammend_string .= "<h2 style='font-size: 1.8em;'> Ammendment submitted by the delegate of ".get_country_from_part_number($part_id)." </h2>";
    $t = $_GET['ammend_type'] == "DEL" ? "delete" : strtolower($_GET['ammend_type']);
    $ammend_string .= "<h3> The delegate would like to <u>".$t."</u> the following: </h3>";
    $ammend_string .= "<p style='font-size: 1.5em;'>".$clause_number.": ";
    $contents = $t != "add" ? get_clause_contents($_GET['clause_number'])[1] : $_GET['contents'];
    $ammend_string .= level(count(explode(".", $clause_number)) + 1).format_clause(filter_clause($contents), "o")."</p>";
    if($t == "edit") {
        $ammend_string .= "<h3> <b>Would become:</b> </h3>";
        $ammend_string .= "<p style='font-size: 1.5em;'>".$clause_number.": ";
        $ammend_string .= level(count(explode(".", $clause_number)) + 1).format_clause(filter_clause($_GET['contents']), "o")."</p>";
        $ammend_string .= "<h3> <b>In the resolution submitted by the delegate of ".get_main_sub_country($_GET['reso_id'])." </b> </h3>";
    } else if($t == "delete"){
        $ammend_string .= "<h3> <b>From the resolution submitted by the delegate of ".get_main_sub_country($_GET['reso_id'])." </b> </h3>";
    } else {
        $ammend_string .= "<h3> <b>To the resolution submitted by the delegate of ".get_main_sub_country($_GET['reso_id'])." </b> </h3>";
    }
    echo $ammend_string;



    $f = fopen("resolution_pdf/ammendment_$ammend_id.html", "w");
    fwrite($f, $ammend_string);
    fclose($f);
    system("sudo sh print_to_pdf.sh resolution_pdf/ammendment_$ammend_id.html resolution_pdf/ammendment_$ammend_id.pdf");
    //Still need the qr code for the ammendment + ammend_code
    echo "<br>";
    button_link("resolution_pdf/ammendment_$ammend_id.pdf", "My Ammendment");
    echo "<br>";
    button_link("spectate_resolution.php?resocode="."ResoMUN:".$sub_id.";".$_GET['reso_id']."!".$committee, "Back to the resolution");
    button_link("myresomun.php", "My ResoMUN");
} else {
    echo red("There was a problem with your ammendment. If the problem persists, contact the system administrator");
}

?>


</body>


<footer>
<center>
<?php echo(date("D M dS Y"));?>
</center>
</footer>

</html>