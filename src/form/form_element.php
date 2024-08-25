<?php

function add_attributes($form_element, $elmts, $attr_noval, $attr_val) {
	//add the attributes
	foreach ($elmts as $key => $value) {
		if (in_array($key, $attr_val))
			$form_element .= "$key='$value' ";
		elseif (in_array($key, $attr_noval) && $value === "true")
			$form_element .= $key . " ";
	}
	return $form_element;
}

function choices_fromFile($address) {
	$choices = array();
	$file = fopen($address, "r");
	while (!feof($file)) {
		$line = fgets($file);
  		if ($line === false)
    		throw new Exception("File read error");
		$choices[] = str_replace("\n", "", $line);
	}
	fclose($file);
	return $choices;
}

function choices_fromDB($column) {
	//query, select only the validated data
	$bdd = connect();
	$req = $bdd -> query("SELECT `$column`, validated FROM interns_internships WHERE validated = 1 ORDER BY `$column`");
	//get back choices
	$choices = array();
	while ($data = $req -> fetch()) {
		if (isset($data[$column])) {
			$c = json_decode($data[$column]);
			foreach ($c as $value)
				$choices[] = $value;
		}
	}
	$req -> closeCursor();
	return $choices;
}

function create_element($name, $details){
	$attr_noval = array( "autofocus"); //contains the attributes which have no value in the form elements
	$attr_val = array("required", "id", "type", "min", "max", "maxlength", "placeholder", "cols", "rows"); //contains the attributes which have a value in the form elements

	//explode the chain to basic elements
	$details = str_replace("#039;", "$", $details); //part to convert quotes
	$details = htmlspecialchars_decode($details); //part to convert quotes
	$details = str_replace("&$", "'", $details); //part to convert quotes
	$details = explode(";", $details);
	$elmts = array();
	foreach ($details as $d) {
		$e = explode("=", $d);
		$elmts[$e[0]] = $e[1];
	}
	$elmts["id"] = $name;

	//create a form element
	$form_element = "";
	if ($elmts["element"] == "input") {
		//create the input
		$form_element = "<input name='$name' ";
		//add attributes
		$form_element = add_attributes($form_element, $elmts, $attr_noval, $attr_val);
		//end the input
		$form_element .= " />";
	}
	else {
		//create the element
		$form_element = "";
		if ($elmts["element"] == "datalist")
			$form_element = "<input list='$name'>";
		$form_element .= "<" . $elmts["element"] . " name='$name' ";
		//add attributes
		$form_element = add_attributes($form_element, $elmts, $attr_noval, $attr_val);
		$form_element .= ">";
		if ($elmts["element"] == "select")
			$form_element .= "<option value='none'></option>";
		//add options
		if (isset($elmts["choices"]) && ($elmts["element"] == "select" || $elmts["element"] == "datalist")) {
			//get back choices
			if ($elmts["choices"] === "from file")
				$choices = choices_fromFile($elmts["file"]);
			elseif ($elmts["choices"] === "from database")
				$choices = choices_fromDB($elmts["column"]);
			else
				$choices = explode("/", $elmts["choices"]);
			//remove redundancies
			for ($i = 0; $i < count($choices) ; $i++) {
				$choices[$i] = ucfirst(strtolower($choices[$i]));
			}
			$choices = array_keys(array_flip($choices));
			sort($choices, SORT_NATURAL);
			//generation of options
			foreach ($choices as $c)
				$form_element .= "<option value='" . hsc($c) . "'>$c</option>";
		}
		//end the element
		$form_element .= "</" . $elmts["element"]. ">";
	}

	//create the example
	$example = $elmts["ex"];
	//define if it can have several same fields
	$duplicable = isset($elmts["new fields"]) && $elmts["new fields"] === "true";

	return array($form_element, $example, $duplicable);
}