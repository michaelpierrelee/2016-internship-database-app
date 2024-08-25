<?php  header('Content-Type: text/html; charset=utf-8');
require("../functions.php");
sec_session_start(); 
sec_session_check(1);

$fields = array();
//get back fields
$bdd = connect();
$req = $bdd -> query("SELECT name, step FROM interns_fields ORDER BY position");
while ($data = $req -> fetch()) {
	$name = hsc($data["name"]);
	$step = hsc($data["step"]);
	$fields[$step][] = $name;
}
$req -> closeCursor();
//sort fields
ksort($fields);
echo json_encode($fields);
?>