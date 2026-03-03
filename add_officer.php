<html>
<?php session_start(); include("database.php");?>
<head>
<link rel='stylesheet' href='style.css' type='text/css'>
<title><?php echo !empty($_SESSION) ? "Add Officer" : header("LOCATION: login.php");?></title>
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
try {
    $conf_title = requestfromstring("SELECT conf_title FROM conference WHERE conf_id=".$_GET['conf_number'])[0]['conf_title'];
    //Get conference title
    //Check to see if person doesn't already have a role
    $users_in_conference = requestfromstring("SELECT usr_id FROM participated_in WHERE conf_id=".$_GET['conf_number']);
    $already_has_role = false;
    foreach($users_in_conference as $u) {
        if($u['usr_id'] == $_GET['person']) {
            $already_has_role = true;
        break;
        }
    }
    if($already_has_role) {
        //Person already signed up so you can change their role
        $part_number = requestfromstring("SELECT part_id FROM participated_in WHERE usr_id=".$_GET['person'])[0]['part_id'];
        requestfromstring("UPDATE has_role SET role_id=".$_GET['role']." WHERE part_id=".$part_number);
    } else {
        //Person not signed up yet so let's do that
        $participant_number = requestfromstring("INSERT INTO participant VALUES(default) RETURNING part_id")[0]['part_id'];
        $officers = requestfromstring("SELECT commit_id FROM committee WHERE commit_title='OFFICERS_".$conf_title."'")[0]['commit_id'];
        requestfromstring("INSERT INTO participated_in(usr_id, conf_id, part_id, commit_id) VALUES (".$_GET['person'].",".$_GET['conf_number'].",".$participant_number.",".$officers.")");
        requestfromstring("INSERT INTO has_role (part_id, role_id) VALUES (".$participant_number.",".$_GET['role'].")");
    }
    alert("You added a new officer");
    gotolink("myconference.php?conf_choice=".$_GET['conf_number']);
} catch (Exception $e) {
    echo $e;
}

?>



</body>


<footer>
<center>
<?php echo(date("D M dS Y"));?>
</center>
</footer>

</html>