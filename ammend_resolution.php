<html>
<?php session_start(); include("database.php");?>
<head>
<link rel='stylesheet' href='style.css' type='text/css'>
<?php $reso_id = $_GET['reso_id']; $reso_title = requestfromstring("SELECT reso_title FROM resolution WHERE reso_id = ".$reso_id)[0]['reso_title'];?>
<title><?php echo !empty($_SESSION) ? "Ammend ".$reso_title  : header("LOCATION: login.php");?></title>
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
<h2>Ammendment for: <?php echo $reso_title?></h2>

<?php 
$ammend_type = $_GET['ammend_type'];
$reso_id = $_GET['reso_id'];
$committee = requestfromstring("SELECT commit_id FROM main_sub WHERE resolution_id=".$_GET['reso_id'])[0]['commit_id'];
$sub_id = requestfromstring("SELECT main_sub_id FROM main_sub WHERE resolution_id=".$_GET['reso_id'])[0]['main_sub_id'];

if($ammend_type == 'EDIT') {
    if($_GET['clause_number'] == NULL) {
        echo "<form action='ammend_resolution.php' method='get'>";
        echo "<label for='clause_number'> Choose the clause (or subclause or subsubclause) you want to edit </label>";
        echo "<input type='number' name='reso_id' value='$reso_id' hidden='true'>";
        echo "<select name='clause_number'>";
        foreach(get_clause_numbers($reso_id) as $cnum) {
            echo "<option value='".$cnum[0].";".$cnum[1]."'>".$cnum[0]."</option>";
        }
        echo "</select> <input value='Ammend' type='submit'> <input value='EDIT' name='ammend_type' hidden='true' type='text'>
        </form>";
    } else {
        echo "<form action='ammendment_action.php' method='get'>";
        list($number, $contents) = get_clause_contents($_GET['clause_number']);
        echo $number."<br>";
        echo "<textarea name='contents' cols='75' rows='5'>";
        echo $contents;
        echo "</textarea>";
        echo "<input type='text' name='clause_number' value='".$_GET['clause_number']."' hidden='true'>";
        echo "<input type='number' name='reso_id' value='$reso_id' hidden='true'>";
        echo "<input value='Ammend' type='submit'> <input value='EDIT' name='ammend_type' hidden='true' type='text'>
        </form>";
    }
    

} else if($ammend_type == 'ADD') {
    if($_GET['clause_number'] == NULL) {
        echo "<form action='ammend_resolution.php' method='get'>";
        echo "<label for='clause_number'> Enter your new clause number </label>";
        echo "<input type='text' name='clause_number' placeholder='1.a.iv'> ";
        echo "<input type='number' name='reso_id' value='$reso_id' hidden='true'>";
        echo "<input value='Ammend' type='submit'> <input value='ADD' name='ammend_type' hidden='true' type='text'>
        </form>";
    } else {
        foreach(get_clause_numbers($reso_id) as $cnum) { 
            if(strval($cnum[0]) == $_GET['clause_number']) {
                alert("You're trying to add a clause that already exists. Try submitting an EDIT ammendment instead.");
                gotolink("spectate_resolution.php?resocode="."ResoMUN:".$sub_id.";".$_GET['reso_id']."!".$committee);
            }
        }
        echo "<form action='ammendment_action.php' method='get'>";
        list($number, $contents) = get_clause_contents($_GET['clause_number']);
        echo $number."<br>";
        echo "<textarea name='contents' cols='75' rows='5' placeholder='Encourages the use of bananas in biological warfare...'>";
        echo "</textarea>";
        echo "<input type='text' name='clause_number' value='".$_GET['clause_number'].";0' hidden='true'>";
        echo "<input type='number' name='reso_id' value='$reso_id' hidden='true'>";
        echo "<input value='Ammend' type='submit'> <input value='ADD' name='ammend_type' hidden='true' type='text'>
        </form>";
    }

} else { //DELETE
    if($_GET['clause_number'] == NULL) {
        echo "<form action='ammend_resolution.php' method='get'>";
        echo "<label for='clause_number'> Choose the clause (or subclause or subsubclause) you want to delete </label>";
        echo "<input type='number' name='reso_id' value='$reso_id' hidden='true'>";
        echo "<select name='clause_number'>";
        foreach(get_clause_numbers($reso_id) as $cnum) {
            echo "<option value='".$cnum[0].";".$cnum[1]."'>".$cnum[0]."</option>";
        }
        echo "</select> <input value='Ammend' type='submit'> <input value='DEL' name='ammend_type' hidden='true' type='text'>
        </form>";
    } else {
        echo "<form action='ammendment_action.php' method='get'>";
        list($number, $contents) = get_clause_contents($_GET['clause_number']);
        echo "<p style='font-size:1.5em'>".$number.":</p> ";
        echo "<p style='font-size:1.5em' name='contents'>";
        echo $contents;
        echo "</p>";
        echo "<input type='text' name='clause_number' value='".$_GET['clause_number']."' hidden='true'>";
        echo "<input type='number' name='reso_id' value='$reso_id' hidden='true'>";
        echo "<input value='Ammend' type='submit'> <input value='DEL' name='ammend_type' hidden='true' type='text'>
        </form>";

    }
}





?>

</body>


<footer>
<center>
<?php echo(date("D M dS Y"));?>
</center>
</footer>

</html>