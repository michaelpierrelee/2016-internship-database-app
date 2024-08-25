<?php header('Content-Type: text/html; charset=utf-8');
require("../functions.php");
sec_session_start(); 
sec_session_check(2);

if (isset($_POST["data"])) {
	try {
		$db = connect();
		$req = $db -> prepare("DELETE FROM interns_internships WHERE `Promotion` = :promo");
		$req -> execute(array(
			"promo" => json_encode([$_POST["data"]])
		));
		$req -> closeCursor();
		echo "1";
	} catch (Exception $e) {
		echo "Error -- ";
		print_r($e);
	}
}