<?php  header('Content-Type: text/html; charset=utf-8');
require("../functions.php");

sec_session_start(); // Notre façon personnalisée de démarrer la session PHP
 
if (isset($_POST['data'])) {
    $sent = $_POST["data"];

    //unserialize
    $sent = explode("&", $sent);
    $user_name = ""; $user_pswd = "";
    foreach ($sent as $value) {
        $v = explode("=", $value);
        if($v[0] == "name") $user_name = parse_data($v[1]);
        elseif($v[0] == "pswd") $user_pswd = parse_data($v[1]);
    }

    if (login($user_name, $user_pswd))
        echo "connected";
    else
        echo "Connection aborted. Wrong login or password.";
} else
    echo 'Invalid Request';