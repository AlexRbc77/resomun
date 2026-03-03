<?php session_start(); include("database.php");?>
<?php 

$reso_id = $_GET['reso_id'];

$committee = $_GET['commit_id'];

$can_edit = requestfromstring("SELECT edit FROM resolution WHERE reso_id=".$reso_id)[0]['edit'];

if($can_edit) {
    requestfromstring("UPDATE resolution SET edit=false WHERE reso_id=".$reso_id);
    gotolink("mycommittee.php?commit_choice=".$committee);
} else {
    requestfromstring("UPDATE resolution SET edit=true WHERE reso_id=".$reso_id);
    gotolink("mycommittee.php?commit_choice=".$committee);
}