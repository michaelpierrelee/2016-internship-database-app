<?php header('Content-Type: text/html; charset=utf-8');
require("../functions.php");
sec_session_start(); 

if(login_check(2))
	echo "1";
else
	echo "0";

?>