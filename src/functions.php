<?php

function connect() {
	try {
		$db = new PDO('mysql:host=localhost;dbname=testDB;charset=utf8', 'testUser', '1234');
        // activate debugging
        $db -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $db;
	} catch (Exception $e) {
        die('Erreur : ' . $e->getMessage());
	}
}

function hsc($str) {
	return htmlspecialchars($str, ENT_QUOTES, "UTF-8");
}

function name_fields() {
	$db = connect();
	//select the fields and return them in the good order
	$req = $db -> query("SHOW COLUMNS FROM interns_internships");
	$col_in = array();
	while ($c = $req -> fetch()) $col_in[] = $c["Field"];

	//get back the name of columns from table interns_fields
	$req = $db -> query("SELECT name FROM interns_fields");
	$col_fi = array();
	while ($c = $req -> fetch()) $col_fi[] = hsc($c["name"]);

	//remove columns of interns_internships which are not in interns_fields
	$fields = array_intersect($col_in, $col_fi);
	$fields = array_values($fields); //reset keys to start from 0

	$req -> closeCursor();
	return $fields;
}

function parse_data($data) {
	$data = str_replace(".", "%§", $data);
	$parsed = array();
	parse_str($data, $parsed);
	$parsed = array_keys($parsed)[0];
	$parsed = str_replace("+", " ", $parsed);
	$parsed = hsc(str_replace("_", " ", $parsed));
	return str_replace("%§", ".", $parsed);
}

/******************SECURE AREA FUNCTIONS***************************/
//http://fr.wikihow.com/cr%C3%A9er-un-script-de-connexion-s%C3%A9curis%C3%A9e-avec-PHP-et-MySQL

// accès à la session
function sec_session_start() {
    $session_name = 'sec_session_id';   // Attribue un nom de session
    $secure = False;
    // Cette variable empêche Javascript d’accéder à l’id de session
    $httponly = true;
    // Force la session à n’utiliser que les cookies
    if (ini_set('session.use_only_cookies', 1) === FALSE) {
        header("Location: ../error.php?err=Could not initiate a safe session (ini_set)");
        exit();
    }
    // Récupère les paramètres actuels de cookies
    $cookieParams = session_get_cookie_params();
    session_set_cookie_params($cookieParams["lifetime"],
        $cookieParams["path"], 
        $cookieParams["domain"], 
        $secure,
        $httponly);
    // Donne à la session le nom configuré plus haut
    session_name($session_name);
    session_start();            // Démarre la session PHP 
    //session_regenerate_id();    // Génère une nouvelle session et efface la précédente
}

// Connexion
function login($name, $given_pswd) {
	//get back user data
	$db = connect();
	$req = $db -> prepare("SELECT * FROM interns_members WHERE name = :name LIMIT 1");
	$req -> execute(array("name" => $name));
	$data = $req -> fetch();
	$db_pswd = $data["pswd"];
	$user_id = $data["id"];
	$user_name = $data["name"];
	$user_lv = $data["level"];
	//hash the user password
	$given_pswd = hash('sha512', $given_pswd);
	//return
	if ($given_pswd == $db_pswd) {
		// Le mot de passe est correct!
        // Récupère la chaîne user-agent de l’utilisateur
        $user_browser = $_SERVER['HTTP_USER_AGENT'];
        // Protection XSS car nous pourrions conserver cette valeur
        $user_id = preg_replace("/[^0-9]+/", "", $user_id);
        $_SESSION['user_id'] = $user_id;
        // Protection XSS car nous pourrions conserver cette valeur
        $user_name = preg_replace("/[^a-zA-Z0-9_\-]+/", "", $user_name);
        $_SESSION['user_name'] = $user_name;
        $_SESSION['login_string'] = hash('sha512', $db_pswd . $user_browser);
        // Protection XSS car nous pourrions conserver cette valeur
        $user_lv = preg_replace("/[^a-zA-Z0-9_\-]+/", "", $user_lv);
        $_SESSION['user_lv'] = $user_lv;
        // Ouverture de session réussie.
		return True;
	}
	else
		return False;
}

// Vérifiez le statut de la session.
function login_check($mandatory_lv) { 
    // Vérifie que toutes les variables de session sont mises en place
    if (isset($_SESSION['user_id'],  $_SESSION['user_name'], $_SESSION['login_string'], $_SESSION['user_lv'])) {
 
        $user_id = $_SESSION['user_id'];
        $login_string = $_SESSION['login_string'];
        $user_name = $_SESSION['user_name'];
        $user_lv = $_SESSION['user_lv'];
 
        // Récupère la chaîne user-agent de l’utilisateur
        $user_browser = $_SERVER['HTTP_USER_AGENT'];

 		$db = connect();
 		$req = $db -> prepare("SELECT pswd, level FROM interns_members WHERE id = :id LIMIT 1");
 		$req -> execute(array("id" => $user_id));
 		$data = $req -> fetch();
 		$password = $data["pswd"];
 		$level = $data["level"];
 		$req -> closeCursor();
 
        if ($req -> rowCount() == 1) {
            // Si l’utilisateur existe
            $login_check = hash('sha512', $password . $user_browser);

            if ($login_check == $login_string && $user_lv == $level && $user_lv >= $mandatory_lv)
                return True; //Connecté
    	}
	}
	return False; //Non connecté
}

function sec_session_check($lv) {
	if(!login_check($lv)) { 
		echo "<p>You are not connected or you don't have the authorization to call this content.</p>";
		die();
	}
}

/** 
* Converts bytes into human readable file size. 
* 
* @param string $bytes 
* @return string human readable file size (2,87 Мб)
* @author Mogilev Arseny 
*/ 
function fileSizeConvert($bytes)
{
    $bytes = floatval($bytes);
        $arBytes = array(
            0 => array(
                "UNIT" => "TB",
                "VALUE" => pow(1024, 4)
            ),
            1 => array(
                "UNIT" => "GB",
                "VALUE" => pow(1024, 3)
            ),
            2 => array(
                "UNIT" => "MB",
                "VALUE" => pow(1024, 2)
            ),
            3 => array(
                "UNIT" => "KB",
                "VALUE" => 1024
            ),
            4 => array(
                "UNIT" => "B",
                "VALUE" => 1
            ),
        );

    foreach($arBytes as $arItem)
    {
        if($bytes >= $arItem["VALUE"])
        {
            $result = $bytes / $arItem["VALUE"];
            $result = strval(round($result, 2))." ".$arItem["UNIT"];
            break;
        }
    }
    return $result;
}