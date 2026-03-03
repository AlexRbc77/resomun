<?php

function requestfromstring($string) {
    try {
        $database      = "pgsql:host=localhost;dbname=resomun";
        $postgres      = new PDO($database, "postgres", "f22raptor");
        $request       = $postgres->prepare($string);
		$request->execute();
		//echo $string.";<br>";
        return $request->fetchAll();
    }
    catch (PDOException $e) {
        echo $e;
        return Array();
    }
}

function array_to_table($array) {
	$keys = array_keys($array[0]);
	echo "<br><table>";

	//Write all keys
	echo "<tr>";
	$i = 0;
	foreach($keys as $k) {
		if($i % 2 == 0) {
			echo "<td>".$k."</td>";
		}
		$i++;
	}

	echo "</tr>";
	$i = 0;
	foreach($array as $a) {
		echo "<tr>";
		foreach($a as $b) {
			if($i % 2 == 0) {
				echo "<td>".$b."</td>";
			}
			$i++;
		}
		echo "</tr>";
	}
	echo "</table>";
}

function button_link($link, $message="LINK") {
	echo "<a href='".$link."' style='text-align:center;'> <button> ".$message."</button></a>";
}

function level($n) {
	assert($n >= 0);
	$result = "";
	for($i = 0; $i < $n; $i++) {
		$result .= "&#9;";
	}
	return $result;
}


function red($message) {
	echo "<p style='color:red;'>".$message."</p>";
}

function green($message) {
	echo "<p style='color:green;'>".$message."</p>";
}

function alert($message) {
	echo "<script> alert('".$message."'); </script>";
}

function form_get_link($page, $link_name, $var, $name) {
	echo "<form action='".$page."' method='get'> <input type='number' hidden=true name='".$name."' value=".$var."><input type='submit' value='".$link_name."'></form>";
}

function load_resolution($reso_id) {
	echo "<ol style=\"list-style:upper-roman;\">";
	$preambs = requestfromstring("SELECT clause_id, clause_contents, operative FROM clause NATURAL JOIN reso_contains WHERE operative=FALSE AND resolution_id = ".$reso_id." ORDER BY clause.clause_id ASC");
	foreach($preambs as $clause) {
		echo "<li><br><i>".level(1)."<textarea class='clause' rows='8' name='clause[]'>".$clause['clause_contents']."</textarea><input type='number' hidden=true name=clause_numbers[] value='".$clause['clause_id']."'></i></li><br>";
	} 
	echo "</ol>";
	echo "<input value='Create Preamb Clause' type='submit' name='action'>";
	echo "<ol style=\"list-style:decimal;\">";
	$clauses = requestfromstring("SELECT clause_id, clause_contents, operative FROM clause NATURAL JOIN reso_contains WHERE operative=TRUE AND resolution_id = ".$reso_id." ORDER BY clause.clause_id ASC");
	foreach($clauses as $clause) {
		echo "<li><br>".level(1)."<textarea class='clause' rows='8' name='clause[]'>".$clause['clause_contents']."</textarea><input type='number' hidden=true name=clause_numbers[] value='".$clause['clause_id']."'></li><br>";
		$subclauses = requestfromstring("SELECT clause_id, sclause_id, sclause_contents FROM sclause NATURAL JOIN clause_contains WHERE clause_id=".$clause['clause_id']);
		if(!empty($subclauses)) {
			echo "<ol style=\"list-style:lower-alpha;\">";
			foreach($subclauses as $sclause) {
				echo "<li><br>".level(2)."<textarea class='sclause' rows='8' name='sclause[]'>".$sclause['sclause_contents']."</textarea><input type='number' hidden=true name=sclause_numbers[] value='".$sclause['sclause_id']."'></li><br>";
				$subsubclauses = requestfromstring("SELECT sclause_id, ssclause_id, ssclause_contents FROM ssclause NATURAL JOIN sclause_contains WHERE sclause_id=".$sclause['sclause_id']);
				if(!empty($subclauses)) {
					echo "<ol style=\"list-style:lower-roman;\">";
					foreach($subsubclauses as $ssclause) {
						echo "<li><br>".level(3)."<textarea class='ssclause' rows='8' name='ssclause[]'>".$ssclause['ssclause_contents']."</textarea><input type='number' hidden=true name=ssclause_numbers[] value='".$ssclause['ssclause_id']."'></li><br>";
					}
				echo "</ol>";
				echo "<input value='Create subsubclause (".$sclause['sclause_id'].")' type='submit' name='action'>";
				}
			}
			echo "</ol>";
		}
		echo "<input value='Create subclause (".$clause['clause_id'].")' type='submit' name='action'>";
	}
	echo "</ol>";
}

function create_clause_link($reso_id, $committee) {
	button_link("create_clause.php?reso_id=".$reso_id."&commit_id=".$committee, "Create Clause");
}

function filter_clause($clause_contents) {
	$c = $clause_contents;
	for($i = 0; $i < strlen($clause_contents); $i++) {
		if($clause_contents[$i] == "’") {
			$c = substr_replace($clause_contents, "'", -$i-1, 0);
		}
	}
	for($i = 0; $i < strlen($clause_contents); $i++) {
		if($clause_contents[$i] == "'") {
			$c = substr_replace($clause_contents, "'", $i, 0);
		}
	}
	return $c;
}

function update_clause($clause_number, $clause_contents) {
	requestfromstring("UPDATE clause SET clause_contents='".filter_clause($clause_contents)."' WHERE clause_id=".$clause_number);
}

function previous() {
	header('Location: '.$_SERVER['HTTP_REFERER']);
}


function create_clause($reso_id) {
    $clause_id = requestfromstring("INSERT INTO clause VALUES (default) RETURNING clause_id")[0]['clause_id'];
	requestfromstring("INSERT INTO reso_contains(resolution_id, clause_id) VALUES(".$reso_id.",".$clause_id.")");
	return $clause_id;
}

function create_preambclause($reso_id) {
    $clause_id = requestfromstring("INSERT INTO clause VALUES(default) RETURNING clause_id")[0]['clause_id'];
	requestfromstring("INSERT INTO reso_contains(resolution_id, clause_id) VALUES(".$reso_id.",".$clause_id.")");
	requestfromstring("UPDATE clause SET operative=FALSE WHERE clause_id=".$clause_id);
}

function create_subclause($clause_id) {
	$sclause_id = requestfromstring("INSERT INTO sclause VALUES(default) RETURNING sclause_id")[0]['sclause_id'];
	requestfromstring("INSERT INTO clause_contains(clause_id, sclause_id) VALUES(".$clause_id.",".$sclause_id.")");
	return $sclause_id;
}

function create_subsubclause($sclause_id) {
	$ssclause_id = requestfromstring("INSERT INTO ssclause VALUES(default) RETURNING ssclause_id")[0]['ssclause_id'];
	requestfromstring("INSERT INTO sclause_contains(sclause_id, ssclause_id) VALUES(".$sclause_id.",".$ssclause_id.")");
	return $ssclause_id;
}

function update_ssclause($ssclause_id, $ssclause_contents) {
	requestfromstring("UPDATE ssclause SET ssclause_contents='".filter_clause($ssclause_contents)."' WHERE ssclause_id=".$ssclause_id);
}

function update_sclause($sclause_number, $sclause_contents) {
	requestfromstring("UPDATE sclause SET sclause_contents='".filter_clause($sclause_contents)."' WHERE sclause_id=".$sclause_number);
}

function get_country_from_part_number($part_number) {
	return requestfromstring("SELECT country FROM represents, delegation WHERE represents.del_id = delegation.del_id AND represents.part_id=".$part_number)[0]['country'];
}

function get_signatories($reso_number) {
	$main_sub = requestfromstring("SELECT main_sub_id FROM main_sub WHERE resolution_id=".$reso_number)[0]['main_sub_id'];
	return requestfromstring("SELECT part_id, signature FROM signed WHERE reso_id=".$reso_number." AND part_id !=".$main_sub);
}

function get_name_from_part_id($part_id) {
	$name = requestfromstring("SELECT firstname, lastname FROM usr NATURAL JOIN participated_in WHERE part_id=".$part_id)[0];
	return "{$name['firstname']} {$name['lastname']}";
}

function get_signatures($reso_number) {
	$result = [];
	$i = 0;
	foreach(get_signatories($reso_number) as $signature) {
		$result[$i] = [];
		$result[$i]['name'] = get_name_from_part_id($signature['part_id']);
		$result[$i]['country'] = get_country_from_part_number($signature['part_id']);
		$result[$i]['signature'] = $signature['signature'];
		$i++;
	}
	
	return $result;
}

function signatories_string($signatories) {
	$result = [];
	for($i = 0; $i < count($signatories); $i++) {
		array_push($result,$signatories[$i]['country']);
	}
	sort($result);
	$result = implode(", ", $result);
	$result .= " (".count($signatories).")";
	return $result;
}

function format_clause($clause, $clause_type) {
	$result = shell_exec("python3 mark_words.py \"".$clause."\" '".$clause_type."'");
	if($clause_type == 'o') {
		return rtrim("<b>".explode("<b>", $result)[1]);
	} else {
		return rtrim("<u>".explode("<u>", $result)[1]);
	}
}

function signature_div($signature) {
	$result = "<div width='25%' style='float:left;overflow:auto; display:block; margin-right:1.5%;margin-left: 2%;' class='sign_div'><center>";
	$result .= "<p style='font-size: 25px;'><u>".get_country_from_part_number($signature['part_id'])."</u></p>";
	$result .= "<p style='font-size: 15px;'>(".get_name_from_part_id($signature['part_id']).")</p>";
	$result .= "<img style='background-color:white;' ";
	$result .= $signature['signature'] != NULL ? "src='".$signature['signature']."' width='400px' height='400px' " : "src='resomun_logo.png' width='25%'";
	$result .= " alt='signature'></center>";
	$result .= "</div>";
	return $result;
}

function main_sub_signature_div($signature) {
	$result = "<div style='clear:left;display:block;overflow:auto;' max-width:'100%' class='main_sign_div'><center>";
	$result .= "<p style='font-size: 25px;'><u>Main submitter: ".get_country_from_part_number($signature['part_id'])."</u></p>";
	$result .= "<p style='font-size: 15px;'>(".get_name_from_part_id($signature['part_id']).")</p>";
	$result .= "<img style='background-color:white;' ";
	$result .= $signature['signature'] != NULL ? "src='".$signature['signature']."'" : "src='resomun_logo.png' width='25%'";
	$result .= " alt='signature'></center>";
	$result .= "</div><br>";
	return $result;
}


function string_resolution($reso_id) {
	$result = "";
	$result .= "<ol style=\"list-style:upper-roman;\">";
	$preambs = requestfromstring("SELECT clause_id, clause_contents, operative FROM clause NATURAL JOIN reso_contains WHERE operative=FALSE AND resolution_id = ".$reso_id." ORDER BY clause.clause_id ASC");
	foreach($preambs as $clause) {
		$result .= level(1)."<li><i>"."<p class='preambclause'>".format_clause($clause['clause_contents'], 'p').",</p></i></li><br>";
	} 
	$result .= "</ol>";
	$result .= "<ol style=\"list-style:decimal;\">";
	$clauses = requestfromstring("SELECT clause_id, clause_contents, operative FROM clause NATURAL JOIN reso_contains WHERE operative=TRUE AND resolution_id = ".$reso_id." ORDER BY clause.clause_id ASC");
	foreach($clauses as $clause) {
		$result .= level(1)."<li>"."<p class='clause'>".format_clause($clause['clause_contents'], 'o');
		$subclauses = requestfromstring("SELECT clause_id, sclause_id, sclause_contents FROM sclause NATURAL JOIN clause_contains WHERE clause_id=".$clause['clause_id']);
		if(!empty($subclauses)) {
			$result .= ":</p></li>";
			$result .= "<ol style=\"list-style:lower-alpha;\" class='clause'>";
			$j=0;
			foreach($subclauses as $sclause) {
				$result .= level(2)."<li>"."<p class='sclause'>".$sclause['sclause_contents'];
				
				$subsubclauses = requestfromstring("SELECT sclause_id, ssclause_id, ssclause_contents FROM ssclause NATURAL JOIN sclause_contains WHERE sclause_id=".$sclause['sclause_id']);
				if(!empty($subsubclauses)) {
					$i=0;
					$c=count($subsubclauses);
					$result .= ":</p></li>";
					$result .= "<ol style=\"list-style:lower-roman;\">";
					foreach($subsubclauses as $ssclause) {
						$result .= level(3)."<li>"."<p class='ssclause'>".$ssclause['ssclause_contents'];
						$result .= $i == $c-1 &&  $j == count($subclauses) - 1 ?  ";</p></li>" :	"</p></li>";
						$i++;
					}
				$result .= "</ol>";
				} else {
					$result .= $j == count($subclauses) - 1 ? ";</p></li>" : "</p></li>";
				}
				$j++;
			}
			$result .= "</ol>";
		} else {
			$result .= ";</p></li>";
		}
		$result .= "<br>";
	}
	$result .= "</ol>";
	return $result;
}

function is_conf_officer($conf_id, $part_id) {
	$conference_officers = requestfromstring("SELECT part_id FROM has_role WHERE role_id <= 5
	INTERSECT
	SELECT part_id FROM participated_in WHERE conf_id = ".$conf_id);
	foreach($conference_officers as $co) {
		if($co['part_id'] == $part_id) {
			return 1;
		}
	}
	return 0;
}

function gotolink($link) {
	echo "<script> window.location = '".$link."';</script>";
}

function get_main_sub_country($reso_id) {
	$country = requestfromstring("SELECT country from delegation, main_sub, represents, resolution WHERE represents.part_id = main_sub.main_sub_id AND main_sub.resolution_id = resolution.reso_id AND represents.del_id = delegation.del_id AND reso_id=".$reso_id)[0]['country'];
	return $country;
}

function search_resolutions($search, $committee) {
	$search_string = "SELECT DISTINCT * FROM resolution, main_sub WHERE reso_id=resolution_id
	AND reso_title LIKE '%".$search."%' AND commit_id=".$committee;
	$resolutions = requestfromstring($search_string);
	if(empty($resolutions)) {
		return red("No resolutions exist with a title like %".$search."%");
	} else {
		echo "<table>";
		foreach($resolutions as $reso) {
			$edit = !$reso['edit'] ? "on" : "off";
			$status = $reso['reso_status'];
			echo "<tr><td>";
			echo $reso['edit'] ? "<span style='color:green;'>" : "<span style='color:red;'>";
			echo $reso['reso_title']." by the delegate of ".get_main_sub_country($reso['reso_id'])." (".count(get_signatories($reso['reso_id']))." signatures)";
			echo "</span>";
			echo "</td><td><a href='spectate_resolution.php?resocode=ResoMUN:".$reso['main_sub_id'].";".$reso['reso_id']."!".$reso['commit_id']."'> <button> View resolution </button> </a></td> <td> <a href='toggle_resolution.php?commit_id=".$committee."&reso_id=".$reso['reso_id']."'> <button id='reso_$edit'> Turn editing $edit </button> </a></td><td>$status</td> </tr>";
			
		}
		echo "</table>";
	}
}

function search_resolutions_conference($search, $conference) {
	$search_string = "SELECT * FROM committee_of, committee WHERE commit_id = committee_id
	AND commit_title NOT LIKE 'OFFICERS_%'
	AND conference_id=".$conference;
	$committees = requestfromstring($search_string);
	foreach($committees as $commit) {
		echo "<h4>".$commit['commit_title']."</h4>";
		search_resolutions($search, $commit['commit_id']);
	}
}

function delete_empty_clauses() {
	requestfromstring("DELETE FROM clause WHERE clause_contents=''");
	requestfromstring("DELETE FROM sclause WHERE sclause_contents=''");
	requestfromstring("DELETE FROM ssclause WHERE ssclause_contents=''");
}

function is_delegate_of_committee($part_id, $committee) {
	$delegates = requestfromstring("SELECT part_id FROM participated_in WHERE commit_id=".$committee." 
	INTERSECT
	SELECT part_id FROM has_role WHERE role_id=9 AND part_id=".$part_id);
	foreach($delegates as $d) {
		if($d['part_id'] == $part_id) {
			return true;
		}
	}
	return false;
}

function roman($number) {
    $map = array('M' => 1000, 'CM' => 900, 'D' => 500, 'CD' => 400, 'C' => 100, 'XC' => 90, 'L' => 50, 'XL' => 40, 'X' => 10, 'IX' => 9, 'V' => 5, 'IV' => 4, 'I' => 1);
    $returnValue = '';
    while ($number > 0) {
        foreach ($map as $roman => $int) {
            if($number >= $int) {
                $number -= $int;
                $returnValue .= $roman;
                break;
            }
        }
    }
    return strtolower($returnValue);
}


function get_clause_numbers($reso_id) {
	$result = [];
	$clause_numbers = requestfromstring("SELECT clause.clause_id FROM reso_contains, clause WHERE clause.clause_id=reso_contains.clause_id AND operative='true' AND resolution_id=".$reso_id); //save the clauses before operating on them
	if($clause_numbers != NULL) {
    for($i = 0; $i < count($clause_numbers); $i++) {
		$c = $clause_numbers[$i]['clause_id'];
		$a = $i+1;
        array_push($result, array($a,$c));
        $subclauses = requestfromstring("SELECT sclause_id, sclause_contents FROM sclause NATURAL JOIN clause_contains WHERE clause_id=".$c);
        if(!empty($subclauses)) {
            $j = 1;
            foreach($subclauses as $sc) {
				array_push($result, array("$a.".chr($j+96), $sc['sclause_id']));
                $subsubclauses = requestfromstring("SELECT ssclause_id, ssclause_contents FROM ssclause NATURAL JOIN sclause_contains WHERE sclause_id=".$sc['sclause_id']);
                if(!empty($subsubclauses)) {
                    $k = 1;
                    foreach($subsubclauses as $ssc) {
						array_push($result, array("$a.".chr($j+96).".".roman($k), $ssc['ssclause_id']));
                        $k++;
                	}
				}
				$j++;
            }
        }
    }
	}
	return $result;
}

function decimal($roman) {
	$romans = array(
		'M' => 1000,
		'CM' => 900,
		'D' => 500,
		'CD' => 400,
		'C' => 100,
		'XC' => 90,
		'L' => 50,
		'XL' => 40,
		'X' => 10,
		'IX' => 9,
		'V' => 5,
		'IV' => 4,
		'I' => 1,
	);
	
	$result = 0;
	
	foreach ($romans as $key => $value) {
		while (strpos($roman, $key) === 0) {
			$result += $value;
			$roman = substr($roman, strlen($key));
		}
	}
	return $result;
}


function get_clause_contents($number_string) {
	$number = explode(";", $number_string)[1];
	$code = explode(";", $number_string)[0];
	if(count(explode(".", $code)) == 1) {
		$contents = requestfromstring("SELECT clause_contents FROM clause WHERE clause_id=".$number)[0]['clause_contents'];
	} else if(count(explode(".", $code)) == 2) {
		$contents = requestfromstring("SELECT sclause_contents FROM sclause WHERE sclause_id=".$number)[0]['sclause_contents'];
	} else {
		$contents = requestfromstring("SELECT ssclause_contents FROM ssclause WHERE ssclause_id=".$number)[0]['ssclause_contents'];
	}
	return array($code, $contents);
}

function email_from_partid($part_id) {
	$email = requestfromstring("SELECT email FROM usr NATURAL JOIN participated_in WHERE part_id=".$part_id)[0]['email'];
	return $email;
}

function get_chair_emails($committee) {
	$chair_emails = [];
	$chairs = requestfromstring("SELECT part_id FROM has_role NATURAL JOIN participated_in WHERE role_id=5 AND commit_id=".$committee);
	foreach($chairs as $chair) {
		array_push($chair_emails, email_from_partid($chair['part_id']));
	}
	return $chair_emails;
}


?>