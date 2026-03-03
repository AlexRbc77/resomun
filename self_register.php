<?php session_start(); include("database.php");?>

<?php
var_dump($_POST);
$participation = requestfromstring("INSERT INTO participant VALUES (default) RETURNING part_id")[0]['part_id'];
$delegation_choice = $_POST['delegation_choice'];
$user = $_POST['usr_id'];
list($conf_id, $commit_id) = explode("/", $_POST['conf_commit']);
requestfromstring("INSERT INTO participated_in(usr_id, conf_id, part_id, commit_id) VALUES (".$user.",".$conf_id.",".$participation.",".$commit_id.")");
requestfromstring("INSERT INTO represents(part_id, del_id) VALUES (".$participation.",".$delegation_choice.")");
requestfromstring("INSERT INTO has_role(role_id, part_id) VALUES (9,".$participation.")");

header("LOCATION: myresomun.php");

?>
