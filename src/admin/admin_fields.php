<?php header('Content-Type: text/html; charset=utf-8');
require("../functions.php");
?>

<h2>Add, remove or rename field to the database</h2>
<p><a class='pure-button' id="menu">
	<i class='fa fa-home'></i>
	Go to the menu
</a></p>

<div id="load_field"></div>

<p> Don't modify the name of the field 'Promotion', it is used to sort the database.<br />
	<strong>To add a new field, a knowledge in basic html is required (or see http://www.w3schools.com/html/html_form_elements.asp).</strong><br />
	For details, each detail must be separated by a ; such as "element=input;type=text"<br />
	The first detail must be the type of the field: input, textarea, select...<br />
	If the first detail is "input", the second must be the attribute 'type' of the input such as "text", "number" and so on.<br />
	If the first detail is "select" or "datalist", the possible choices must be defined such as: "element=select;choices=a/b/c" where the choices are separated by a /<br />
	Or 'choices' can be "from file" or "from database". 1st case: define the name of the file such as "choices=from file;file=countries.txt"<br />
	For the 2nd case: define the name of the column in the table interns_internships (= the name of the field) where the values will be get back such as "choices=from database;column=Domain(s)"<br />
	Other attributes can be added: "maxlength=10", "min=5"... If they don't have a value, specify only true such as "required=true".<br />
	Define for each field "ex=an example for the field".<br />
	Use negative=true to use a field as a negative filter in the database interface and selective=true as a selective filter.<br />
	Finally, if the user can add several same fields, define "new fields=true", such as for the field of techniques.
</p>

<h3>Existing fields:</h3>
<form id="form_exist" class="pure-form">
	<table id="table_form_exist" class="pure-table pure-table-horizontal pure-table-striped">
		<col class="f" />
		<col class="s" />
		<col class="d" />
		<col class="p" />
		<col class="r" />
		<col class="m" />
		<thead>
	        <tr>
	            <th>Field name</th>
	            <th>Step</th>
	            <th>Details</th>
	            <th>Position</th>
	            <th>Remove</th>
	            <th>Modify</th>
	        </tr>
	    </thead>
	    <tbody id="exist"></tbody>
	</table>
	<p>
		<button type="submit" id="submit_remove" class="pure-button">Remove selected fields</button>
		<button type="submit" id="submit_modify" class="pure-button">Modify the selected field</button>
	</p>
</form>