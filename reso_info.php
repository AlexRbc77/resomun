<html>
<?php include("database.php");?>
<?php 
$users = requestfromstring("SELECT COUNT(*) FROM usr")[0][0];
$conferences = requestfromstring("SELECT COUNT(*) FROM conference")[0][0];
$committees = requestfromstring("SELECT COUNT(*) FROM committee")[0][0];
$resolutions = requestfromstring("SELECT COUNT(*) FROM resolution")[0][0];

echo $users.",".$conferences.",".$committees.",".$resolutions;
?>

</html>