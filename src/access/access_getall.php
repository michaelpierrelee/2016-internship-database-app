<?php header('Content-Type: text/html; charset=utf-8');
require("../functions.php");
sec_session_start(); 
sec_session_check(1);

//GET BACK DATA
//select the fields
$fields = name_fields();
$fields[] = "id";

$start = 0;
if (isset($_POST["start"]))
	$start = $_POST["start"];

//select the oldest internship which are validated
$db = connect();
$req = $db -> query("SELECT * FROM interns_internships WHERE validated=1 ORDER BY `submit date` DESC, `Promotion` DESC  LIMIT " . $start . ", 100");
$internships = array();
while ($data = $req -> fetch()) {
	foreach ($data as $key => $value) {
		if ((in_array($key, $fields)) && $key != "0") {
			/*if (is_string($value))
				$value = str_replace(";", ".", $value);*/
			$internships[][$key] = json_decode($value);
		}
	}
}

//select the number of validated internships
$req = $db ->query("SELECT COUNT(*) FROM interns_internships WHERE validated=1");
$total = $req -> fetch()[0];

//select details to know if the field has to be displayed in the filters
$req = $db ->query("SELECT name, details FROM interns_fields");
$filters = array();
while ($data = $req -> fetch()) {
	$name = $data["name"];
	//get back value of details "selective" and "negative"
	$filters[$name] = array("selective" => "false", "negative" => "false");
	$details = explode(";", $data["details"]);
	$d = 0;
	$i = 0;
	while ($d != 2 && $i < count($details)) {
		$parsed = explode("=", $details[$i]);
		if ($parsed[0] == "selective" && $parsed[1] == "true") {
			$filters[$name]["selective"] = "true";
			$d++;
		} elseif ($parsed[0] == "negative" && $parsed[1] == "true") {
			$filters[$name]["negative"] = "true";
			$d++;
		}
		$i++;
	}
}

$req -> closeCursor();

//return json
echo json_encode(array(
	"internships" => $internships,
	"field names" => $fields,
	"total" => $total,
	"filters" => $filters
));
?>