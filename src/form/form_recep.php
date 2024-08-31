<?php header('Content-Type: text/html; charset=utf-8');
require("../functions.php");

sec_session_start(); 
sec_session_check(1);

//receive data from the form to add a new internship thanks to AJAX
if( isset($_POST["data"]) && !empty($_POST["data"]) && is_string($_POST["data"])) {
	$raw_data = $_POST["data"];

	//convert data into an array
	$raw_data = explode(";", $raw_data);
	$data = array();
	foreach ($raw_data as $raw) {
		$exp = explode("&", $raw);
		foreach ($exp as $r) {
			$r = explode("=", $r);

			$key = parse_data($r[0]);

			if (!empty($r[1]))
				$value = parse_data($r[1]);
			else
				$value = "";

			if ($value != "")
				$data[$key][] = $value;
		}
	}

	//convert values for a key into a json table
	foreach ($data as $key => $array) {
		$array = array_unique($array); //remove redundancies
		$array = array_values($array); //reset keys to start from 0
		$data[$key] = json_encode($array); //convert to json
	}

	//get back the name of colums from interns_internships
	$fields = name_fields();

	//prepare the query and the array for the query
	$date = date('Y-m-d', time());
	$db = connect();
	$query = "INSERT INTO interns_internships VALUES(0, '$date', 0,";
	$query_arr = array();
	for($i = 0; $i < count($fields); $i++) {
		$c = str_replace(' ', '_', $fields[$i]);
		$query .= " :$c";
		if ($i < count($fields) - 1)
			$query .= ", ";
		
		if (array_key_exists($fields[$i], $data))
			$query_arr[$c] = $data[$fields[$i]];
		else
			$query_arr[$c] = NULL;
	}
	$query .= ")";

	/*echo "<br>" . $query . "<br>";
	var_dump($query_arr);
	echo '<br><br>';*/

	//send data into the database
	try {
		$req = $db -> prepare($query);
		$req -> execute($query_arr);
	} catch (PDOException $e) {
		echo "Fail in the save of data into the database: <br><br> $e <br /><br>";
		var_dump($_POST["data"]);
	}
	$req -> closeCursor();
	//error message
	if ($req -> rowCount() == 0) {
		echo "No entry was added into the database:<br />";
		var_dump($_POST["data"]);
	} elseif ($req -> rowCount() > 1) {
		echo "More than one entry were handled by the query (" . $req -> rowCount() . "):<br />";
		var_dump($_POST["data"]);
	}
	//right message
	else echo "1";

} elseif (isset($_POST["data"]) && !empty($_POST["data"])) {
	echo "Data get are not a string: ";
	var_dump($_POST["data"]);
} else
	echo "No data were sent.";
?>