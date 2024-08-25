<?php header('Content-Type: text/html; charset=utf-8');
require("../functions.php");
sec_session_start(); 
sec_session_check(2);

if( isset($_POST["id"]) && !empty($_POST["id"]) && is_string($_POST["id"])) {
	$id = hsc($_POST["id"]);
	$db = connect();
	$req = $db -> prepare("UPDATE interns_internships SET validated = 0 WHERE id = :id");
	$req -> execute(array("id" => $id));
	$req -> closeCursor();
}

?>