<?php header('Content-Type: text/html; charset=utf-8');
require("../functions.php");
sec_session_start(); 
sec_session_check(2);

function basic_form() {
	echo "
		<h3>Add a new field</h3>
		<form id='form_add' class='pure-form pure-form-stacked'>
			<fieldset>
			<label for='field'>Name of the new field (sensible to the casse):</label>
			<input type='text' name='field' id='field' max='250' placeholder='Last Name' required autofocus />

			<label for='step'>Complete name of the step:</label>
			<input type='text' name='step' id='field' max='250' placeholder='Step 1: Personal Data' required />

			<label for='details'>Details of the field:</label>
			<textarea name='details' id='details' placeholder='element=input;type=text' required rows=5 cols=50></textarea>

			<label for='pos'>Position:</label>
			<input type='number' name='pos' id='pos' required/>

			<button type='submit' id='submit_add' class='pure-button pure-button-primary'><i class='fa fa-paper-plane'></i> Send</button>
			</fieldset>
		</form>
	";
}


if(isset($_POST["data"]) && !empty($_POST["data"]) && is_string($_POST["data"])) {
	$type = hsc($_POST["type"]);
	$sent = $_POST["data"];

	if ($type == "add") { //add a new field 
		//unserialize
		$sent = explode("&", $sent);
		$name = ""; $step = ""; $details = ""; $position = 0;
		foreach ($sent as $value) {
			$v = explode("=", $value);
			if($v[0] == "field") { $name = parse_data($v[1]); }
			elseif($v[0] == "step") { $step = parse_data($v[1]); }
			elseif($v[0] == "details") { $details = parse_data($v[1]); }
			elseif($v[0] == "pos") { $position = parse_data($v[1]); }
		}

		if ($name != "") {
			//add a new field to the table interns_fields
			$bdd = connect();
			$req = $bdd -> prepare("INSERT INTO interns_fields VALUES(:name, :step, :details, :position)");
			$req -> execute(array(
				'name' => $name,
				'step' => $step,
				'details' => $details,
				'position' => $position
				));
			//create a new column into interns_internships
			$que = "ALTER TABLE interns_internships ADD `$name` TEXT NULL DEFAULT NULL";
			$req = $bdd -> query($que);
			//response
			echo "<p>Field added: <strong>$name</strong></p>";
			basic_form();
			$req -> closeCursor();	
		} else
			echo "<a class='pure-button' id='reload_add'><i class='fa fa-refresh'></i> Reload the add form</a></p>";

	} elseif ($type == "remove") { //remove a field
		//unserialize
		$sent = explode("&", $sent);
		$fields = array();
		foreach ($sent as $value) {
			$v = explode("=", $value);
			if ($v[1] == "on") $fields[] = parse_data($v[0]);
		}
		//deletion
		$bdd = connect();
		$resp = $bdd -> query('SELECT * FROM interns_fields');
		while ($data = $resp -> fetch()) {
			//search if a field from the database was tagged to be removed
			$name = hsc($data["name"]);
			if ( in_array($name, $fields) ) {
				$req = $bdd -> query("ALTER TABLE interns_internships DROP COLUMN `$name`");
				$name = "'" . $name . "'";
				$req = $bdd -> query("DELETE FROM interns_fields WHERE name=$name");
				//response
				echo "
					<p>Fields removed: <strong>$name</strong>
					<a class='pure-button' id='reload_add'><i class='fa fa-refresh'></i> Reload the add form</a></p>
					";
			}
		}
		$resp -> closeCursor();

	} elseif ($type == "update") { //effective update of the field
		//unserialize
		$sent = explode("&", $sent);
		$name = ""; $old_name = "";
		$step = ""; $details = ""; $position = "";
		foreach ($sent as $value) {
			$v = explode("=", $value);
			if($v[0] == "field") { $name = parse_data($v[1]); }
			elseif($v[0] == "old_name") { $old_name = parse_data($v[1]); }
			elseif($v[0] == "step") { $step = parse_data($v[1]); }
			elseif($v[0] == "details") { $details = parse_data($v[1]); }
			elseif($v[0] == "position") { $position = parse_data($v[1]); }
		}
		//update
		if ($name != "") {
			$bdd = connect();
			$req = $bdd -> query("ALTER TABLE interns_internships CHANGE `$old_name` `$name` TEXT");
			$req = $bdd -> prepare("UPDATE interns_fields SET name=:name, step=:step, details=:details, position=:position WHERE name=:oldname");
			$req -> execute(array(
				"name" => $name,
				"oldname" => $old_name,
				"step" => $step,
				"details" => $details,
				"position" => $position
				));
			//response
			echo "
				<p>Field updated: <strong>$old_name</strong> to <strong>$name</strong>
				<a class='pure-button' id='reload_add'><i class='fa fa-refresh'></i> Reload the add form</a></p>
				";
			$req -> closeCursor();
		}

	} elseif ($type == "modify") { //form to modify a field
		//unserialize
		$sent = explode("&", $sent);
		$name = "";
		foreach ($sent as $value) {
			$v = explode("=", $value);
			if($v[0] == "to_modify") $name = parse_data($v[1]);
		}
		//get back the tagged entry
		$bdd = connect();
		$resp = $bdd -> prepare("SELECT * FROM interns_fields WHERE name=:name");
		$resp -> execute(array("name" => $name));
		while ($data = $resp -> fetch()) {
			$step = $data["step"];
			$details = $data["details"];
			$position = $data["position"];
		}
		$resp -> closeCursor();
		echo "
			<h3>Update a field</h3>
			<form id='form_update' class='pure-form pure-form-stacked'>
				<label for='field'>Name of the field (sensible to the casse):</label>
				<input type='text' name='field' id='field' max='250' value='$name' required autofocus />

				<label for='step'>Name of the step:</label>
				<input type='text' name='step' id='field' max='250' value='$step' required />

				<label for='details'>Details of the field:</label>
				<textarea name='details' id='details' placeholder='element=input;type=text' required  rows=5 cols=50>$details</textarea>

				<label for='position'>Position of the step:</label>
				<input type='number' name='position' id='position' min=0 value='$position' required />

				<input type='hidden' name='old_name' id='old_name' value='$name' required />
				<button type='submit' id='submit_update' class='pure-button pure-button-primary'>Update</button>
				<a class='pure-button' id='reload_add'><i class='fa fa-refresh'></i> Reload the add form</a>
			</form>
		";
	}
	
} else
	basic_form();
?>