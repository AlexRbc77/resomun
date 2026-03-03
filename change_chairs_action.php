<html>
<?php session_start(); include("database.php");?>
<head>
<link rel='stylesheet' href='style.css' type='text/css'>
<title><?php echo !empty($_SESSION) ? "Add or delete chair" : header("LOCATION: login.php");?></title>
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
echo "<br>";
$type = $_GET['change_type'];
$usr_id = $_GET['person'];
$commit_id = $_GET['commit_id'];
$conf_id = $_GET['conf_number'];
$user_already_has_role = requestfromstring("SELECT usr_id FROM participated_in WHERE conf_id=".$conf_id." AND usr_id=".$usr_id)[0]['usr_id'] != NULL;

if($user_already_has_role) {
    
    //if ADD, change role [DONE]
    if($type == "ADD") {
        $participation_id = requestfromstring("SELECT part_id FROM participated_in WHERE usr_id=".$usr_id." AND conf_id=".$conf_id)[0]['part_id'];
        requestfromstring("UPDATE participated_in SET commit_id=".$commit_id." WHERE part_id=".$participation_id);
        requestfromstring("UPDATE has_role SET role_id=5 WHERE part_id=".$participation_id);
        
        green("Changed a role to chair");

    }
    //if DEL, delete role [DONE]
    if($type == "DEL") {
        $participation_id = requestfromstring("SELECT part_id FROM participated_in WHERE usr_id=".$usr_id." AND conf_id=".$conf_id)[0]['part_id'];
        
        requestfromstring("DELETE FROM participated_in WHERE part_id=".$participation_id);
        
        red("Deleted a chair");
    }
} else {
    //if ADD, add role [DONE]
    if($type == "ADD") {
        $participation_id = requestfromstring("INSERT INTO participant VALUES (default) RETURNING part_id")[0]['part_id'];
        requestfromstring("INSERT INTO participated_in(usr_id, conf_id, part_id, commit_id) VALUES
        (".$usr_id.",".$conf_id.",".$participation_id.",".$commit_id.")");
        requestfromstring("INSERT INTO has_role(role_id, part_id) VALUES(5,".$participation_id.")");
        
        green("Added a new chair");
    }
}
gotolink("change_chairs.php?conf_number=".$conf_id."&commit_id=".$commit_id);

?>
</body>


<footer>
<center>
<?php echo(date("D M dS Y"));?>
</center>
</footer>

</html>