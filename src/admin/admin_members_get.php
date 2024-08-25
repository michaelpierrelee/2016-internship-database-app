<?php header('Content-Type: text/html; charset=utf-8');
require("../functions.php");
sec_session_start(); 
sec_session_check(2);

$bdd = connect();
$resp = $bdd -> query('SELECT * FROM interns_members');

$nb_admin = 0;
$data = [];
while ($d = $resp -> fetch()) {
	if (hsc($d["level"]) == 2) $nb_admin++;
	$data[] = array($d["name"], $d["level"], $d["id"]);
}

foreach ($data as $d) {
	$name = hsc($d[0]);
	$lv = hsc($d[1]);
	$id = $d[2];
	if ($nb_admin == 1 && $lv == 2) {
		echo
			"<tr><td>" . $name .
			"</td><td>" . $lv .
			"</td><td>" .
			"</td></tr>";
	} else {
		echo
			"<tr><td>" . $name .
			"</td><td>" . $lv .
			"</td><td><input type='checkbox' name='$id' id='$id' />" .
			"</td></tr>";
	}
}

$resp -> closeCursor();
?>