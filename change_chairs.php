<html>
<?php session_start(); include("database.php");?>
<head>
<link rel='stylesheet' href='style.css' type='text/css'>
<title><?php echo !empty($_SESSION) ? "Chair assignments" : header("LOCATION: login.php");?></title>
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
$committee = $_GET['commit_id'];
$commit_title = requestfromstring("SELECT commit_title FROM committee WHERE commit_id=".$committee)[0]['commit_title'];
$conference = $_GET['conf_number'];
$current_chairs = requestfromstring("SELECT firstname, lastname, usr_id FROM usr
NATURAL JOIN participated_in
NATURAL JOIN has_role
WHERE role_id = 5
AND email != '".$_SESSION['email']."'
AND commit_id = ".$committee." ORDER BY firstname ASC");
?>
<h2>Chairs for <?php echo $commit_title?></h2>
<?php
if(count($current_chairs) == 0) {
    red("You have no chairs for this committee. You can add some if you want.");
} else {
    green("Here are your chairs.");
    echo "<table>";
        foreach($current_chairs as $chair) {
            echo 
            "<tr><td><form action='change_chairs_action.php' method='GET'>
            ".$chair['firstname']." ".$chair['lastname']."
            </td><td><input type='submit' value='Remove Chair'></td></tr>
            <input type='number' hidden='true' name='commit_id' value=".$committee.">
            <input type='number' hidden='true' name='conf_number' value='".$conference."'>
            <input type='text' hidden='true' name='change_type' value='DEL'>
            <input type='number' hidden='true' name='person' value=".$chair['usr_id'].">
            </form>";
        }
        echo "</table>";
}
?>
<form action='change_chairs_action.php' method='GET' >
    <select name='person'>
        <?php
            $people = requestfromstring("SELECT usr_id, firstname, lastname FROM usr WHERE email != '".$_SESSION['email']."' ORDER BY lastname ASC");
            foreach($people as $person) {
                echo "<option value=".$person['usr_id'].">".$person['firstname']." ".$person['lastname']."</option>";
            }
        ?> 
    </select>
    <input value='<?php echo $committee?>' hidden='true' name='commit_id' type='number'>
    <input type='number' hidden='true' name='conf_number' value='<?php echo $conference?>'>
    <input type='text' hidden='true' name='change_type' value='ADD'>
    <input value='Add Chair' type='submit'>

</form>
<?php button_link("myresomun.php", "My ResoMUN");?>


</body>


<footer>
<center>
<?php echo(date("D M dS Y"));?>
</center>
</footer>

</html>