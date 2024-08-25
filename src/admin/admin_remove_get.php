<?php header('Content-Type: text/html; charset=utf-8');
require("../functions.php");
sec_session_start(); 
sec_session_check(2);

$db = connect();
$req = $db -> query("SELECT `Promotion` FROM interns_internships  group by `Promotion` having count(*) >= 1");

$promo = "";
while ($d = $req -> fetch()) {
	$date = json_decode($d["Promotion"])[0];
	$promo .= "<option value='" . $date . "'>" . $date . "</option>";
}

?>

<form class='pure-form' id="form_remove_promo">
	<label for='chosen_promo'>Chose a promotion:</label>
	<select id='chosen_promo' name='chosen_promo'><?php echo $promo; ?></select>
	<button type="submit" id="delete_promo" class="pure-button pure-button-primary pure-button-active"><i class="fa fa-trash"></i> Delete</button>
</form>