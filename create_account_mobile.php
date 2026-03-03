<?php include('database.php'); session_start();?>
<?php
$post_info = json_decode(file_get_contents("php://input"), true);
$s = "INSERT INTO usr(firstname, lastname, age, email, password)
VALUES ('".$post_info['fname']."','".$post_info['lname']."',".$post_info['age'].",'".$post_info['email']."',md5('".$post_info['birthday']."')) RETURNING usr_id";

$result = requestfromstring($s)[0]['usr_id'];

if($result == NULL) {
    echo "ERROR";
} else {
    echo "Thank you for creating a new account";
}
?>