<html>
<?php session_start(); include("database.php");?>
<head>
<link rel='stylesheet' href='style.css' type='text/css'>
<title><?php echo !empty($_SESSION) ? $_SESSION['firstname']."'s ResoMUN" : header("LOCATION: login.php");?></title>
<style>
    h3 {
        font-size: 1.5em;
    }


    </style>
</head>
<header id='header'>
<center>
<h1> Resolution Edition Software Of MUN </h1>
</center>
<script type='text/javascript'>
function pika() {
    document.getElementById('logo').setAttribute('src', 'pikachu.jpg');
}
</script>
<img src='resomun_logo.png' id='logo' style='position:absolute;right:10px;overflow:auto;top:10px;' ondblclick="pika()">
<script type='text/javascript'>
var logo = document.getElementById('logo');
var header = document.getElementById('header');
logo.setAttribute('height', header.scrollHeight);



</script>
<?php echo $_SESSION['email'] != NULL ? "<p id='user_info'> Logged in as ".$_SESSION['firstname']." ".$_SESSION['lastname']."</p>".button_link("logoff.php", "Log off") : "Not logged in ".button_link("login.php", "Login");?>
</header>
<body>
<div class="myresomun">
<div class="conferences">
    <center>
<?php 
$user_id = requestfromstring("SELECT usr_id FROM usr WHERE email='".$_SESSION['email']."'")[0]['usr_id'];
$conferences = requestfromstring("SELECT conference.conf_id, conf_title, usr_id, role_id FROM conference, has_role, participated_in 
WHERE has_role.part_id = participated_in.part_id 
AND conference.conf_id = participated_in.conf_id
AND usr_id = ".$user_id."
AND role_id <= 4"); //Get my conferences that I am participating in as officer

?>
<h3> My conferences </h3>
<form action='myconference.php' method='get'>
<select name='conf_choice'>
<?php
foreach($conferences as $conf) {
    echo "<option value=".$conf['conf_id'].">".$conf['conf_title']."</option>";
}
?>
</select>
<input type='submit' value='Manage Conference'>
</form>
<?php button_link("create_conference_form.php", "Create a new conference");?>
</center>
</div> <!--All the conferences you were SECGEN or DEP-SECGEN for + create a conference-->
<!--Start with creating a conference-->


<div class="committees">
    <center>
<h3> My committees </h3>
<?php
$committees_chaired = requestfromstring("SELECT committee.commit_id, commit_title, conf_title FROM committee, participated_in, has_role, conference
WHERE has_role.role_id = 5
AND has_role.part_id = participated_in.part_id
AND committee.commit_id = participated_in.commit_id
AND participated_in.conf_id = conference.conf_id
AND participated_in.usr_id = ".$user_id);

foreach($conferences as $conf) {
    $committees_officered = requestfromstring("SELECT commit_id, commit_title FROM committee, committee_of WHERE committee_of.committee_id=committee.commit_id AND committee_of.conference_id=".$conf['conf_id']);
}
?>
<form action='mycommittee.php' method='get'>
<select name='commit_choice'>
<?php
foreach($committees_chaired as $commit) {
    echo "<option value=".$commit['commit_id'].">".$commit['commit_title']."</option>";
}
foreach($conferences as $conf) {
    $committees_officered = requestfromstring("SELECT commit_id, commit_title FROM committee, committee_of WHERE committee_of.committee_id=committee.commit_id AND commit_title NOT LIKE 'OFFICERS_%' AND committee_of.conference_id=".$conf['conf_id']);
    foreach($committees_officered as $commit) {
        echo "<option value=".$commit['commit_id'].">".$commit['commit_title']."</option>";
    }
}
?>
</select>
<input type='submit' value='Manage Committee'>
</form>
    </center>
</div> <!--All the committees you chaired (in there you can see each committee's resolutions)-->




<div class="resolutions">
    <center>
<h3> My resolutions </h3>
<?php
$resolutions = requestfromstring("SELECT reso_id, reso_title, participated_in.commit_id FROM resolution, main_sub, participated_in WHERE main_sub.resolution_id = resolution.reso_id AND participated_in.part_id = main_sub.main_sub_id AND participated_in.usr_id = ".$user_id);
?>  
<?php
foreach($resolutions as $reso) {
    echo button_link("myresolution.php?reso_id=".$reso['reso_id']."&committee=".$reso['commit_id'], $reso['reso_title'])."<br>";
}
?>
<form action="create_resolution.php" method='post'> 
    <input type="text" name='reso_title' placeholder='The issue of baby penguins' size='25'><!--Choose conference/committee pair-->
    <?php
    $committees_delegated = requestfromstring("SELECT conference.conf_id, committee.commit_id, conf_title, commit_title FROM participated_in, has_role, committee, conference 
    WHERE has_role.part_id = participated_in.part_id 
    AND conference.conf_id = participated_in.conf_id 
    AND committee.commit_id = participated_in.commit_id 
    AND committee.commit_title NOT LIKE '%OFFICERS_%'
    AND has_role.role_id = 9
    AND participated_in.usr_id = ".$user_id);
    ?>
    <select name="conf_commit">
    <?php
    foreach($committees_delegated as $comconf) {
        echo "<option value='".$comconf['conf_id'].",".$comconf['commit_id']."'> ".$comconf['conf_title']."/".$comconf['commit_title']." </option><br>";
    }
    ?>
    </select>
    <input type='submit' value='Create Resolution'>

</form>
</center>
</div> <!--All the resolutions your either signed or wrote-->

<div class="see_papers">
    <center>
<h3>See a resolution published by another delegate</h3>

<form method="get" action="spectate_resolution.php">
<label for='resocode'>ResoCode</label>
<input type='search' name='resocode'>
<input type='submit' value='Search'>

</form>
</center>

</div>

</div>

<div class='self_register'>
    <center>
    <h3>Self register for a committee</h3>
    <?php
    $committees_not_registered = requestfromstring("SELECT DISTINCT conference.conf_id, committee.commit_id, conf_title, commit_title FROM committee, conference, committee_of
    WHERE (conference.conf_id, committee.commit_id, conf_title, commit_title) NOT IN (SELECT conference.conf_id, committee.commit_id, conf_title, commit_title FROM participated_in, has_role, committee, conference
        WHERE has_role.part_id = participated_in.part_id
        AND conference.conf_id = participated_in.conf_id
        AND committee.commit_id = participated_in.commit_id
        AND committee.commit_title NOT LIKE '%OFFICERS_%'
        AND has_role.role_id = 9
        AND participated_in.usr_id = ".$user_id.")
    AND committee_of.committee_id = committee.commit_id
    AND committee_of.conference_id = conference.conf_id
    AND conference.conf_id NOT IN (SELECT conf_id FROM participated_in WHERE usr_id = ".$user_id.")
    AND committee.commit_title NOT LIKE '%OFFICERS_%'
    ORDER BY conf_title");
    $delegations = requestfromstring("SELECT * FROM delegation");
    ?>
    <form action='self_register.php' method='post'>
    <select name='conf_commit'>
        <?php foreach($committees_not_registered as $cnr)
        echo "<option value='".$cnr['conf_id']."/".$cnr['commit_id']."'>{$cnr['conf_title']}/{$cnr['commit_title']}</option>"?>
    </select>
    <input hidden='true' type='number' name='usr_id' value='<?php echo $user_id?>'>
    <select name='delegation_choice'>
        <?php foreach($delegations as $d) {
            echo "<option value='{$d['del_id']}'>{$d['country']}</option>";
        }
        ?>
    </select>
    <input type='submit' value='Register for delegation'><br>
    <p3>Note that a chair or conference officer will delete your assignment if it was unauthorized</p3>
    </form>
    </center>
</div>
    
    <div class='tutorial'>
        <center>
        <a href='documentation.pdf'><h3>How to use ResoMUN</h3></a>
    </center>
        
    </div>
    
</div>
</body>


<footer>
<center>
<?php echo(date("D M dS Y"));?>
</center>
</footer>

</html>