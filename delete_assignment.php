<?php session_start(); include("database.php");?>
<?php
var_dump($_GET);
requestfromstring("DELETE FROM participated_in WHERE part_id=".$_GET['part_id']);
requestfromstring("DELETE FROM signed WHERE part_id=".$_GET['part_id']);
header("LOCATION: mycommittee.php?commit_choice=".$_GET['commit_id']);

?>