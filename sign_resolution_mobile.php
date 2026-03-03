<?php include('database.php'); session_start();?>
<?php

$post_info = json_decode(file_get_contents("php://input"), true);
$user_id = $post_info['user_id'];
list($resolution, $main_sub, $committee, $conference) = explode(";", $post_info['qr_input']);
$reso_id=0;
sscanf($resolution, "resolution=%d;", $reso_id);
$commit_id=0;
sscanf($committee, "committee=%d;", $commit_id);
$part_id = requestfromstring("SELECT part_id FROM participated_in WHERE commit_id=".$commit_id." AND usr_id=".$user_id)[0]['part_id'];
$is_delegate = requestfromstring("SELECT * FROM has_role NATURAL JOIN participated_in WHERE role_id=9 AND commit_id=".$commit_id." AND part_id=".$part_id)[0]['part_id'] != NULL;
$signed_resolution = requestfromstring("SELECT * FROM signed WHERE reso_id=".$reso_id." AND part_id=".$part_id)[0] != NULL;
$file_name = $post_info['signature'];
$already_signed = (requestfromstring("SELECT * FROM signed WHERE part_id=".$part_id." AND reso_id=".$reso_id)[0]['part_id']) != NULL;
$url = "resize_image.php";
$data = array('image' => $post_info['signature']);

$options = array(
    'http' => array(
        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
        'method'  => 'POST',
        'content' => http_build_query($data)
    )
);
$context  = stream_context_create($options);
$result = file_get_contents($url, false, $context);

sscanf($result, "src=\"%s\" hidden>", $file_name);

if($is_delegate && !$signed_resolution) {
    if($already_signed) {
        requestfromstring("UPDATE signed SET signature='".$file_name."' WHERE reso_id=".$reso_id." AND part_id=".$part_id);
    } else {
        requestfromstring("INSERT INTO signed(part_id, reso_id, signature) VALUES(".$part_id.",".$reso_id.", '".$file_name."')");
    }
    echo "Thank you for signing the resolution";
} else {
    echo "Error";
}



?>