<?php  header('Content-Type: text/html; charset=utf-8');
require("../functions.php");

sec_session_start();
 
// Détruisez les variables de session 
$_SESSION = array();
 
// Retournez les paramètres de session 
$params = session_get_cookie_params();
 
// Effacez le cookie. 
setcookie(session_name(),
        '', time() - 42000, 
        $params["path"], 
        $params["domain"], 
        $params["secure"], 
        $params["httponly"]);
 
// Détruisez la session 
session_destroy();
?>