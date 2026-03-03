<html>
<?php session_start(); include("database.php");?>
<head>
<link rel='stylesheet' href='style.css' type='text/css'>
<?php $commit_title = requestfromstring("SELECT commit_title FROM committee WHERE commit_id=".$_GET['commit_choice'])[0]['commit_title'];?>
<title><?php echo !empty($_SESSION) ? $commit_title : header("LOCATION: login.php");?></title>
<script type='text/javascript'>

function confirm_delete() {
    return confirm("Do you really want to delete this assignment? That delegate will lose all data from this committee");
}

</script>
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

function confirm_delete() {
    return confirm("Do you really want to delete this ammendment? It'll be lost forever");
}

function confirm_pass() {
    return confirm("Do you really want to pass this ammendment?");
}
</script>
<?php echo $_SESSION['email'] != NULL ? "<p id='user_info'> Logged in as ".$_SESSION['firstname']." ".$_SESSION['lastname']."</p>".button_link("logoff.php", "Log off") : "Not logged in ".button_link("login.php", "Login");?>
</header>
<body>
<?php 
if($commit_title == NULL) {
    alert("You have no committees to manage");
    gotolink("myresomun.php");
}
?>
<?php
$user_id = requestfromstring("SELECT usr_id FROM usr WHERE email='".$_SESSION['email']."'")[0]['usr_id'];
$delegates = requestfromstring("SELECT firstname, lastname, country, participated_in.part_id 
FROM usr, participated_in, has_role, represents, delegation 
WHERE commit_id=".$_GET['commit_choice']." 
AND usr.usr_id = participated_in.usr_id
AND has_role.part_id = participated_in.part_id 
AND has_role.role_id = 9 
AND represents.part_id = participated_in.part_id 
AND delegation.del_id = represents.del_id ORDER BY country");
$conf_id = requestfromstring("SELECT committee_id, conference_id FROM committee_of WHERE committee_id = ".$_GET['commit_choice'])[0]['conference_id'];
$available = requestfromstring("SELECT usr_id, firstname, lastname FROM usr WHERE usr_id NOT IN ( SELECT usr.usr_id FROM usr, participated_in
WHERE usr.usr_id = participated_in.usr_id
AND conf_id = ".$conf_id.")
ORDER BY firstname ASC");

$chairing_committee = requestfromstring("SELECT committee.commit_id FROM committee, participated_in, has_role, conference
WHERE has_role.role_id = 5
AND has_role.part_id = participated_in.part_id
AND committee.commit_id = participated_in.commit_id
AND participated_in.conf_id = conference.conf_id
AND participated_in.usr_id = ".$user_id."
AND committee.commit_id = ".$_GET['commit_choice'])[0]['commit_id'] == $_GET['commit_choice'];

$part_id = requestfromstring("SELECT part_id FROM participated_in WHERE usr_id=".$user_id." AND conf_id=".$conf_id)[0]['part_id'];

$delegations = requestfromstring("SELECT * FROM delegation");

if(!$chairing_committee && !is_conf_officer($conf_id, $part_id)) {
    alert("You are not a chair of this committee");
    gotolink("myresomun.php");
}
?>
<div class="commit_page">
    <div class="delegates">
<h2>Delegates of <?php echo $commit_title?></h2>
<?php count($delegates) > 0 ? green("You have ".count($delegates)." delegate".(count($delegates) > 1 ? 's' : ' ')) : red("You have no delegates, try adding some");?>
<?php 
$commit_id = $_GET['commit_choice'];
echo "<table><tr><td><b>First name</b></td><td><b>Last name</b></td><td><b>Delegation</b></td><td><b>Delete assignment</b></td></tr>";
foreach($delegates as $del) {
    echo "<tr><td>{$del['firstname']}</td><td>{$del['lastname']}</td><td>{$del['country']}</td><td><a onclick='return confirm_delete()' href='delete_assignment.php?part_id={$del['part_id']}&commit_id=$commit_id'><button>Delete assignment</button></a></td></tr>";
}
echo "</table>";
?>
<br>
<form action='add_delegate.php' method='post'>
    <select name='person_choice'>
        <?php foreach($available as $del)
        echo "<option value='".$del['usr_id']."'>{$del['firstname']} {$del['lastname']}</option>"?>
    </select>
    <input type='number' value='<?php echo $conf_id?>' name='conf_id' hidden='true'>
    <input type='number' value='<?php echo $_GET['commit_choice']?>' name='commit_id' hidden='true'>
    <select name='delegation_choice'>
        <?php foreach($delegations as $d) {
            echo "<option value='{$d['del_id']}'>{$d['country']}</option>";
        }
        ?>
    </select>
    <input type='submit' value='Assign delegation'>
</form>
    </div>
<div class='resolutions'>
<h2>Resolutions published</h2>
<div class='reso_search'>
<form action='mycommittee.php' method='get'>
<input value=<?php echo $_GET['commit_choice']?> hidden='true' name='commit_choice' type='number'>
<label for='search_reso'>Search resolutions</label>
<input type='search' name='search_reso' placeholder='The problem with bananas'>
<input type='submit' value='submit'>
</form>
<?php echo search_resolutions($_GET['search_reso'], $_GET['commit_choice'])?>
</div>
</div>

<div class='ammendments'>
<h2>Ammendments submitted 
<?php
//List all the ammendments
$ammendments = requestfromstring("SELECT ammend_id, ammend_type, ammend_body, ammendment_sub, reso_title, resolution.reso_id FROM ammendment, main_sub, resolution WHERE main_sub.resolution_id = resolution.reso_id AND ammendment.reso_id = resolution.reso_id AND main_sub.commit_id=".$_GET['commit_choice']);
echo "(".count($ammendments).")</h2>";

foreach($ammendments as $ammend) {
    $ammend_string = "";
    $ammend_string .= "<h2 style='font-size: 1.8em;'> Ammendment submitted by the delegate of ".get_country_from_part_number($ammend['ammendment_sub'])." </h2>";
    $t = $ammend['ammend_type'] == "DEL" ? "delete" : strtolower($ammend['ammend_type']);
    
    $ammend_string .= "<h3> The delegate would like to <u>".$t."</u> the following: </h3>";
    
    $clause_number = explode(";", $ammend['ammend_body'])[0];
    $ammend_string .= "<p style='font-size: 1.5em;'>".$clause_number.": ";
    $contents = "";
    
    if($t == "add") {
        $contents .= filter_clause(explode(";", $ammend['ammend_body'])[2]);
    } else {
        $contents .= filter_clause(get_clause_contents(implode(";", array_slice(explode(";", $ammend['ammend_body']), 0, 2)))[1]);
    }
    
    $ammend_string .= level(count(explode(".", $clause_number)) + 1).format_clause(filter_clause($contents), "o")."</p>";
    
    if($t == "edit") {
        $ammend_string .= "<h3> <b>Would become:</b> </h3>";
        $ammend_string .= "<p style='font-size: 1.5em;'>".$clause_number.": ";
        $ammend_string .= level(count(explode(".", $clause_number)) + 1).format_clause(filter_clause(explode(";", $ammend['ammend_body'])[2]), "o")."</p>";
        $ammend_string .= "<h3> <b>In the resolution submitted by the delegate of ".get_main_sub_country($ammend['reso_id'])." </b> </h3>";
    } else if($t == "delete"){
        $ammend_string .= "<h3> <b>From the resolution submitted by the delegate of ".get_main_sub_country($ammend['reso_id'])." </b> </h3>";
    } else {
        $ammend_string .= "<h3> <b>To the resolution submitted by the delegate of ".get_main_sub_country($ammend['reso_id'])." </b> </h3>";
    }
    $ammend_string .= "<a href='resolution_pdf/ammendment_".$ammend['ammend_id'].".pdf'> <button> Ammendment PDF </button></a>";
    $ammend_string .= "<a href='pass_ammendment.php?ammend_id=".$ammend['ammend_id']."&commit_id=".$_GET['commit_choice']."' onclick='return confirm_pass()'> <button> Pass ammendment </button></a>";
    $ammend_string .= "<a href='fail_ammendment.php?ammend_id=".$ammend['ammend_id']."&commit_id=".$_GET['commit_choice']."' onclick='return confirm_delete()'> <button> Fail ammendment </button></a>";
    echo "<div class='ammend'>".$ammend_string."</div>";
}


?>

</div>
</div>
</body>

<?php button_link("myresomun.php", "My ResoMUN");?>
<footer>
<center>
<?php echo(date("D M dS Y"));?>
</center>
</footer>

</html>