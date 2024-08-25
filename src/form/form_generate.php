<?php  header('Content-Type: text/html; charset=utf-8');
require("../functions.php");
require("form_element.php");

$fields = array();
//get back fields
$bdd = connect();
$req = $bdd -> query("SELECT * FROM interns_fields ORDER BY position");
while ($data = $req -> fetch()) {
	$name = hsc($data["name"]);
	$step = hsc($data["step"]);
	$dets = hsc($data["details"]);
	$elmt = create_element($name, $dets);
	$fields[$step][] = array(
		"name" => $name,
		"elmt" => $elmt[0],
		"ex" => $elmt[1],
		"dupli" => $elmt[2]
		);
}
$req -> closeCursor();
//sort fields
ksort($fields);
?>

<div id="form_tabs">
	<!--menus from jQueryUI-->
	<ul>
	<?php
	foreach (array_keys($fields) as $k) {
		$k_nospace = str_replace(" ", "_", $k);
		echo "<li><a href='#$k_nospace'>$k</a></li>";
	}
	?>
	</ul>
	<!--forms from PureCSS-->
	<?php
	$i = 0;
	foreach ($fields as $k_step => $step) {
		$i++;
		$k_nospace = str_replace(" ", "_", $k_step);
		//create the form
		echo "<div id='$k_nospace'><form class='pure-form pure-form-aligned' id='form_$k_nospace' >";
		//fill with elements
		foreach ($step as $f) {
			$name = $f["name"];
			$name_nospace = str_replace(" ", "_", $name);
			$elmt = $f["elmt"];
			$ex = $f["ex"];
			//add a button to copy the fied
			if ($f["dupli"])
				$add = "<button class='pure-button new_field' target='$name_nospace'><i class='fa fa-plus'></i> New field</button>";
			else
				$add = "";
			//display
			echo "
				<div id='div_$name_nospace' class='pure-control-group'>
					<span class='copy_$name_nospace'><label for='$name' class='lab_form'>$name</label>
					$elmt</span>
					<span>$add</span>
					<span class='examples'>Ex: $ex</span>
				</div>
				";
		}
		//if it's the last step, add the button to send the forms
		if ($i == count($fields))
			echo "<button class='pure-button pure-button-primary' id='send'><i class='fa fa-paper-plane'></i> Send</button>";
		//end the current form
		echo "</form></div>";
	}
	?>
	<div id="msg"></div>
</div>
