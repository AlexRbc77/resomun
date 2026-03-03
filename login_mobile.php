<?php include('database.php'); session_start();?>
<?php
$post_info = json_decode(file_get_contents("php://input"), true);
$email = $post_info['email'];
$sponsor = $post_info['sponsor'];

$request = "SELECT * FROM usr WHERE email='".$email."' AND password = md5('".$sponsor."')";

$r = requestfromstring($request);

if(empty($r)) {
	echo "ERROR";
} else {
	$profile = $r[0];
    $info = array(
        'user_id' => $profile['usr_id'],
        'firstname' => $profile['firstname'],
        'lastname' => $profile['lastname']
    );
    echo json_encode($info);
}


?>