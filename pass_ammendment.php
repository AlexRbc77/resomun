<html>
<?php session_start(); include("database.php");?>
<head>
<link rel='stylesheet' href='style.css' type='text/css'>
<title><?php echo !empty($_SESSION) ? "Pass ammendment" : header("LOCATION: login.php");?></title>
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


$ammendment = requestfromstring("SELECT * FROM ammendment WHERE ammend_id=".$_GET['ammend_id'])[0];
$type = $ammendment['ammend_type'];




$clause_number = explode(";", $ammendment['ammend_body'])[0];
$clause_id = explode(";", $ammendment['ammend_body'])[1];
$clause_type = array('clause', 'sclause', 'ssclause')[count(explode('.', $clause_number))-1];
$reso_id = $ammendment['reso_id'];
$clause_contents = explode(";", $ammendment['ammend_body'])[2];

echo "<br>";
$clauses = get_clause_numbers($reso_id);


if($type == "EDIT") {
    requestfromstring("UPDATE ".$clause_type." SET ".$clause_type."_contents='".$clause_contents."' WHERE ".$clause_type."_id=".$clause_id);
    requestfromstring("DELETE FROM ammendment WHERE ammend_id=".$_GET['ammend_id']);//Delete the submitted ammendment
    alert("You edited ".$clause_type." n°".$clause_number);
    gotolink("mycommittee.php?commit_choice=".$_GET['commit_id']);
} else if($type == "DEL") {
    requestfromstring("DELETE FROM ".$clause_type." WHERE ".$clause_type."_id=".$clause_id);
    requestfromstring("DELETE FROM ammendment WHERE ammend_id=".$_GET['ammend_id']);//Delete the submitted ammendment
    alert("You deleted ".$clause_type." n°".$clause_number);
    gotolink("mycommittee.php?commit_choice=".$_GET['commit_id']);
} else { //Add
    if($clause_type == "clause") {
        $clause_id = create_clause($reso_id);
        update_clause($clause_id, $clause_contents);
        requestfromstring("DELETE FROM ammendment WHERE ammend_id=".$_GET['ammend_id']);//Delete the submitted ammendment
        alert("You added ".$clause_type." n°".$clause_number);
        gotolink("mycommittee.php?commit_choice=".$_GET['commit_id']);
    } else if($clause_type == "sclause") {
        $n = explode('.', $clause_number)[0];
        echo $n;
        $clause_id = 0;
        foreach($clauses as $clause) {
            if($clause[0] == $n) {
                $clause_id = $clause[1];
                $sclause_id = create_subclause($clause_id);
                update_sclause($sclause_id, $clause_contents);
                requestfromstring("DELETE FROM ammendment WHERE ammend_id=".$_GET['ammend_id']);//Delete the submitted ammendment
                alert("You added subclause n°".$clause_number);
                gotolink("mycommittee.php?commit_choice=".$_GET['commit_id']);
            }
        }
        alert("There was a problem with your ammendment");
        gotolink("mycommittee.php?commit_choice=".$_GET['commit_id']);
    } else { //ssclause
        $n = implode('.', array_slice(explode('.', $clause_number), 0, 2));
        echo $n;
        $clause_id = 0;
        foreach($clauses as $clause) {
            if($clause[0] == $n) {
                $clause_id = $clause[1];
                $ssclause_id = create_subsubclause($clause_id);
                update_ssclause($ssclause_id, $clause_contents);
                requestfromstring("DELETE FROM ammendment WHERE ammend_id=".$_GET['ammend_id']);//Delete the submitted ammendment
                alert("You added subsubclause n°".$clause_number);
                gotolink("mycommittee.php?commit_choice=".$_GET['commit_id']);
            }
        }
        alert("There was a problem with your ammendment");
        gotolink("mycommittee.php?commit_choice=".$_GET['commit_id']);
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