<?php header('Content-Type: text/html; charset=utf-8');
require("../functions.php");
sec_session_start(); 
sec_session_check(2);

//count the number of fields to validate
$db = connect();
$req = $db -> query("SELECT COUNT(validated) FROM interns_internships WHERE validated=0");
$ct = intval($req -> fetchColumn());

//select the fields
$fields = name_fields();

//select the oldest internship which are not validated
$req = $db -> query("SELECT * FROM interns_internships WHERE validated=0 ORDER BY `submit date` LIMIT 1");
$data = $req -> fetch();
$intern = array();
$id_db = 0;
$date = "";
foreach ($data as $key => $value) {
	if (in_array($key, $fields) && $key != "0") {
		$intern[$key] = json_decode($value);
	} elseif ($key == "id") {
		$id_db = $value;
	} elseif ($key == "submit date") {
		$date = $value;
	}
}

//return the intern into json
$req -> closeCursor();
echo json_encode(array("count" => $ct, "internship" => $intern, "id_db" => $id_db, "date" => $date));
?>