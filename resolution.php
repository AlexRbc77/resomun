<?php
include("database.php");

$r = create_resolution("Test resolution");
$reso_id = $r[0][0];
add_new_clause($reso_id, "THIS IS A TEST CLAUSE BY ELMO1");
add_new_clause($reso_id, "THIS IS A TEST CLAUSE BY ELMO2");
add_new_clause($reso_id, "THIS IS A TEST CLAUSE BY ELMO3");
add_new_clause($reso_id, "THIS IS A TEST CLAUSE BY ELMO4");
add_new_clause($reso_id, "THIS IS A TEST CLAUSE BY ELMO5");
add_new_clause($reso_id, "THIS IS A TEST CLAUSE BY ELMO6");
add_new_clause($reso_id, "THIS IS A TEST CLAUSE BY ELMO7");
add_new_clause($reso_id, "THIS IS A TEST CLAUSE BY ELMO8");
display_resolution($reso_id);


?>