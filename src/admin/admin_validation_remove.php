<?php header('Content-Type: text/html; charset=utf-8');
require("../functions.php");
sec_session_start(); 
sec_session_check(2);

if( isset($_POST["id"]) && !empty($_POST["id"]) && is_string($_POST["id"])) {
	$id = hsc($_POST["id"]);
	$db = connect();
	try {
		$req = $db -> prepare("DELETE FROM interns_internships WHERE id=:id");
		$req -> execute(array("id" => $id));
	} catch (PDOException $e) {
		echo "Fail in the save of data into the database: $e <br />";
		var_dump($_POST["data"]);
	}
	$req -> closeCursor();
	//error message
	if ($req -> rowCount() == 0) {
		echo "No entry was deleted from the database:<br />";
		var_dump($_POST["data"]);
	} elseif ($req -> rowCount() > 1) {
		echo "More than one entry were handled by the query (" . $req -> rowCount() . "):<br />";
		var_dump($_POST["data"]);
	}
	//right message
	else echo "1";

	$req -> closeCursor();
} else
	echo "No data were sent.";

?>