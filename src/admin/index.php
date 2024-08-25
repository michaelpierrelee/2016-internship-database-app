<?php header('Content-Type: text/html; charset=utf-8');
require("../functions.php");
sec_session_start(); 
sec_session_check(2);