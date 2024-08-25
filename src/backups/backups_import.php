<?php header('Content-Type: text/html; charset=utf-8');
require("../functions.php");
sec_session_start(); 
sec_session_check(2);
require("backups_fct.php");

if(isset($_POST["timestamp"])) {
	$files = glob('save/*'); // get all file names
	$get = explode("=", $_POST["timestamp"])[1];
	$i = 0;
	foreach($files as $file){ // iterate files
		if(is_file($file) && strpos($file, $get) !== FALSE) {
			//get back name of tables
			$a = backup_import($file, TRUE);
			if($a) $i++;
		}
	}
	if ($i == 2) echo "1";
	elseif ($i == 0) echo "0";
	else echo "-1";
}
?>