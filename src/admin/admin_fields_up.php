<?php header('Content-Type: text/html; charset=utf-8');
require("../functions.php");
sec_session_start(); 
sec_session_check(2);

$bdd = connect();
$resp = $bdd -> query('SELECT * FROM interns_fields ORDER BY step, position');
while ($data = $resp -> fetch())
{
	$name = hsc($data["name"]);
	$step = hsc($data["step"]);
	$details = hsc($data["details"]);
	$details = str_replace("#039;", "$", $details); //part to convert quotes
	$details = htmlspecialchars_decode($details); //part to convert quotes
	$details = str_replace("&$", "'", $details); //part to convert quotes
	$position = hsc($data["position"]);
	echo
		"<tr><td>" . $name .
		"</td><td>" . $step .
		"</td><td>" . $details .
		"</td><td>" . $position .
		"</td><td><input type='checkbox' name='$name' id='$name' />" .
		"</td><td><input type='radio' name='to_modify' value='$name' />" .
		"</td></tr>";
}
$resp -> closeCursor();
?>