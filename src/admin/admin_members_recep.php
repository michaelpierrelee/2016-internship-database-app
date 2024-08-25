<?php header('Content-Type: text/html; charset=utf-8');
require("../functions.php");
sec_session_start(); 
sec_session_check(2);

if (isset($_POST["data"]) && !empty($_POST["data"]) && is_string($_POST["data"]) && $_POST["type"] == "add") {
	$sent = $_POST["data"];

	//unserialize
	$sent = explode("&", $sent);
	$name = ""; $user_pswd = ""; $lv = "";
	foreach ($sent as $value) {
		$v = explode("=", $value);
		if($v[0] == "name") $name = parse_data($v[1]);
		elseif($v[0] == "pswd") $user_pswd = hash('sha512', parse_data($v[1]));
		elseif($v[0] == "level") $lv = intval(parse_data($v[1]));
	}

	//send to db
	$db = connect();
	$req = $db -> prepare("INSERT INTO interns_members VALUES(0, :name, :pswd, :lv)");
	$req -> execute(array(
		"name" => $name,
		"pswd" => $user_pswd,
		"lv" => $lv
		));
	$req -> closeCursor();

	if ($req -> rowCount() == 1)
		echo "<p>User added: <strong>$name</strong></p>";
	else {
		echo "<p>Error for <strong>$name</strong></p>";
		echo "\nPDO::errorCode(): ", $req->errorCode();
		print_r($req->errorInfo());
	}

} elseif (isset($_POST["data"]) && !empty($_POST["data"]) && is_string($_POST["data"]) && $_POST["type"] == "remove") {
	$sent = $_POST["data"];

	//unserialize
	$sent = explode("&", $sent);
	$ids = array();
	foreach ($sent as $value) {
		$v = explode("=", $value);
		if ($v[1] == "on") $ids[] = hsc($v[0]);
	}

	//remove from db
	$db = connect();
	for ($i=0; $i < count($ids); $i++) { 
		$req = $db -> prepare("DELETE FROM interns_members WHERE id = :id");
		$req -> execute(array("id" => $ids[$i]));
	}
	$req -> closeCursor();

	if ($req -> rowCount() >= 1)
		echo "<p>User(s) removed</p>";
	else
		echo "<p>An error occured during the deletion</p>";
} else
	echo "<p>No data collected</p>";