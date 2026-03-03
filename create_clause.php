<?php session_start(); include("database.php");?>
<?php


function create_clause() {
    $clause_id = requestfromstring("INSERT INTO clause VALUES(default) RETURNING clause_id")[0]['clause_id'];
    requestfromstring("INSERT INTO reso_contains(resolution_id, clause_id) VALUES(".$reso_id.",".$clause_id.")");
}


?>