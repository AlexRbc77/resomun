<html>
<?php session_start(); include("database.php");?>
<head>
<link rel='stylesheet' href='style.css' type='text/css'>
<title><?php 
$conf_title = requestfromstring("SELECT conf_title FROM conference WHERE conf_id = ".$_GET['conf_choice'])[0]['conf_title'];
echo !empty($_SESSION) ? $conf_title : header("LOCATION: login.php");
?></title>
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
<div class="conference_page">

    <?php 
    if($conf_title == NULL) {
    alert("You have no conferences to manage");
    gotolink("myresomun.php");
    }
    ?>
    <div class='officers'>
    <h3>Officers of <?php echo $conf_title;?></h3>
    <?php
    $officers = requestfromstring("SELECT firstname, lastname, role_title 
    FROM usr, role_table, participated_in, conference, has_role
    WHERE usr.usr_id = participated_in.usr_id
    AND conference.conf_id = participated_in.conf_id
    AND has_role.role_id = role_table.role_id
    AND has_role.part_id = participated_in.part_id
    AND (has_role.role_id <= 4 OR has_role.role_id = 6 OR has_role.role_id = 7) 
    AND conference.conf_title='".$conf_title."' 
    AND usr.usr_id IN (SELECT usr_id FROM participated_in);");
    array_to_table($officers);
    ?>
    <br>
    <form action='add_officer.php' method='GET'>
        <select name='person'>
            <?php
            $people = requestfromstring("SELECT * FROM usr WHERE email != '".$_SESSION['email']."' ORDER BY lastname ASC");
            foreach($people as $person) {
                echo "<option value=".$person['usr_id'].">".$person['firstname']." ".$person['lastname']."</option>";
            }
            ?> 
        </select>
        <select name='role'>
            <?php
            $roles = requestfromstring("SELECT * FROM role_table
            WHERE role_id <= 4 OR role_id=6 OR role_id=7");
            foreach($roles as $r) {
                echo "<option value=".$r['role_id'].">".$r['role_title']."</option>";
            }
            ?> 
        </select>
        <input type='number' hidden='true' name='conf_number' value='<?php echo $_GET['conf_choice']?>'>

        <input type='submit' value='Add officer'>
    </form>    
    </div>

    <div class='committees'>
    <h3>Committees in <?php echo $conf_title;?></h3>
    <?php
    $committee_list = requestfromstring("SELECT commit_title, commit_id FROM committee, committee_of
    WHERE committee_of.conference_id=".$_GET['conf_choice']."
    AND committee_of.committee_id = committee.commit_id
    AND commit_title NOT LIKE 'OFFICERS%'");
    if(empty($committee_list)) {
        red("No committees were found. Try making one.");
    } else {
        echo "<table>";
        foreach($committee_list as $committee) {
            echo 
            "<tr><td><form action='change_chairs.php' method='GET'>
            <input type='number' hidden='true' name='commit_id' value=".$committee['commit_id'].">".$committee['commit_title']."
            <input type='number' hidden='true' name='conf_number' value='".$_GET['conf_choice']."'></td><td>
            <input type='submit' value='Change chairs'></td></tr>
            </form>";
        }
        echo "</table>";
    }
    ?>
    <form action='create_committee_action.php' method='GET'>
        <input type='text' name='committee_title' placeholder='New Committee'>
        <input type='number' hidden='true' name='conf_number' value='<?php echo $_GET['conf_choice']?>'>
        <input type='submit' value='Add Committee'>
    </form>
    </div>

    <div class='resolutions'>   
    <h3>Resolutions published at <?php echo $conf_title;?></h3>

        <div class='reso_search'>
        <form action='myconference.php' method='get' width='100%'>
        <input value=<?php echo $_GET['conf_choice']?> hidden='true' name='conf_choice' type='number'>
        <label for='search_reso'>Search resolutions</label>
        <input type='search' name='search_reso' placeholder='The problem with bananas'>
        <input type='submit' value='submit'>
        </form>
        <?php echo search_resolutions_conference($_GET['search_reso'], $_GET['conf_choice'])?>

        </div>
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