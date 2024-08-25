<?php header('Content-Type: text/html; charset=utf-8');
require("../functions.php");
sec_session_start(); 
sec_session_check(2);
require("backups_fct.php");

$a = backup_table(["interns_internships", "interns_fields"]);
if ($a) echo "1";
else echo "0";

?>