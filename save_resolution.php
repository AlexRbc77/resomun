<?php session_start(); include("database.php");?>
<?php

delete_empty_clauses();

//echo $_POST['action'];

$clause_numbers = $_POST["clause_numbers"]; //save the clauses before operating on them
if($clause_numbers != NULL) {
    for($i = 0; $i < count($clause_numbers); $i++) {
        $c = $clause_numbers[$i];
        ////echo "$i -> ($c) ".filter_clause($_POST["clause"][$i])."<br>";
        update_clause($c, $_POST["clause"][$i]);
        $subclauses = requestfromstring("SELECT sclause_id, sclause_contents FROM sclause NATURAL JOIN clause_contains WHERE clause_id=".$c);
        if(!empty($subclauses)) {
            $j = 0;
            foreach($subclauses as $sc) {
                ////echo level(1)."$j -> (".$sc['sclause_id'].") ".$_POST['sclause'][array_search($sc['sclause_id'], $_POST['sclause_numbers'])]."<br>";
                update_sclause($sc['sclause_id'], $_POST['sclause'][array_search($sc['sclause_id'], $_POST['sclause_numbers'])]);
                $j++;
                $subsubclauses = requestfromstring("SELECT ssclause_id, ssclause_contents FROM ssclause NATURAL JOIN sclause_contains WHERE sclause_id=".$sc['sclause_id']);
                if(!empty($subsubclauses)) {
                    $k = 0;
                    foreach($subsubclauses as $ssc) {
                        ////echo level(2)."$k -> (".$ssc['ssclause_id'].") ".$_POST['ssclause'][array_search($ssc['ssclause_id'], $_POST['ssclause_numbers'])]."<br>";
                        update_ssclause($ssc['ssclause_id'], $_POST['ssclause'][array_search($ssc['ssclause_id'], $_POST['ssclause_numbers'])]);
                        $k++;
                }
            }
            }
        }
    }
}



if($_POST['action'] == "Create Clause") {
    create_clause($_POST['reso_id']);
    gotolink("myresolution.php?reso_id=".$_POST['reso_id']."&committee=".$_POST['commit_id']);
} else 

if($_POST['action'] == "Create Preamb Clause") {
    create_preambclause($_POST['reso_id']);
    gotolink("myresolution.php?reso_id=".$_POST['reso_id']."&committee=".$_POST['commit_id']);
} else 

if($_POST['action'] == "Preview") {
    gotolink(""."view_resolution.php?reso_id=".$_POST['reso_id']."&committee=".$_POST['commit_id']);
} else 

if(substr($_POST['action'], 0, 16) == "Create subclause") {
    $clause_number = 0;
    sscanf($_POST['action'], "Create subclause (%d)", $clause_number);
    //echo "Creating subclause in clause ".$clause_number;
    create_subclause($clause_number);
    gotolink("myresolution.php?reso_id=".$_POST['reso_id']."&committee=".$_POST['commit_id']); //review add sub clauses mechanic
} else 

if(substr($_POST['action'], 0, 19) == "Create subsubclause") {
    $sclause_number = 0;
    sscanf($_POST['action'], "Create subsubclause (%d)", $sclause_number);
    //echo "<br>Creating subsubclause in subclause ".$sclause_number;
    create_subsubclause($sclause_number);
    gotolink("myresolution.php?reso_id=".$_POST['reso_id']."&committee=".$_POST['commit_id']);
} else {
    gotolink("myresolution.php?reso_id=".$_POST['reso_id']."&committee=".$_POST['commit_id']); //review add subsub clauses mechanic
}



?>