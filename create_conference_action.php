<html>
<?php session_start(); include("database.php");?>
<head>
<link rel='stylesheet' href='style.css' type='text/css'>
<title>Create conference verifications</title>
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
<?php echo $_SESSION['email'] != NULL ? "<p id='user_info'> Logged in as ".$_SESSION['firstname']." ".$_SESSION['lastname']."</p>" : "Not logged in ".button_link("login.php", "Login");?>
</header>
<body>

<?php

$conference_exists = requestfromstring("SELECT COUNT(id) FROM (SELECT conf_id AS id FROM conference WHERE conf_title='".$_POST['conf_title']."') AS bla")[0]['count'];

if($conference_exists == 0) {
    try {
        $conf_number = requestfromstring("INSERT INTO conference(conf_title) VALUES ('".$_POST['conf_title']."') RETURNING conf_id")[0]['conf_id']; //Create the actual conference in the database
        echo "<p style='color:green;'> Your conference was created, creating the Officers committee</p>";
        $officers = requestfromstring("INSERT INTO committee(commit_title) VALUES ('OFFICERS_".$_POST['conf_title']."') RETURNING commit_id")[0]['commit_id'];
        echo "<p style='color:green;'> Made the Officers committee</p>";
        //Create the officers committee
        requestfromstring("INSERT INTO committee_of(conference_id, committee_id) VALUES (".$conf_number.",".$officers.")");
        echo "<p style='color:green;'> Linked the Officers committee</p>";
        //Link the officers committee to the conference frehsly created
        //Now to put the creator as SecGen
        $user_id = requestfromstring("SELECT usr_id FROM usr WHERE email='".$_SESSION['email']."'")[0]['usr_id'];
        //Get the user id
        //Now to create new participant
        $participant = requestfromstring("INSERT INTO participant VALUES (default) RETURNING part_id")[0]['part_id'];
        requestfromstring("INSERT INTO participated_in(usr_id, conf_id, commit_id, part_id) VALUES (".$user_id.",".$conf_number.",".$officers.",".$participant.")");
        echo "<p style='color:green;'> Added you as a participant </p>";
        //Linked SecGen to conference
        //Add SecGen role in conference
        requestfromstring("INSERT INTO has_role(role_id, part_id) VALUES (1,".$participant.")");
        echo "<p style='color:green;'> Conference created and you're the Secretary General </p>";
        button_link("myresomun.php", "My Resomun");    
    } catch (Exception $e) {
        echo $e."<br> <p style='color:red;'> ERROR: Either your conference already exists OR try contacting your friendly neighborhood administrator at resomun@gmail.com </p>".button_link("myresomun.php", "My ResoMUN");
    }    
} else {
    echo "<br> <p style='color:red;'> ERROR: Either your conference already exists OR try contacting your friendly neighborhood administrator at resomun@gmail.com </p>".button_link("myresomun.php", "My ResoMUN");
}


?>



</body>


<footer>
<center>
<?php echo(date("D M dS Y"));?>
</center>
</footer>

</html>