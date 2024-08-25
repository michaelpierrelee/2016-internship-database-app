<?php header('Content-Type: text/html; charset=utf-8');
require("../functions.php");

echo "<h2>Validation of internships</h2>";
echo "<p><button class='pure-button' id='menu'><i class='fa fa-home'></i> Go to the menu</button></p>";
echo "<div id='info'><p>Number of internships to validate: <span id='nb_interns'>0</span>.</p>";
echo "<p>Current internship id=<span id='current'>0</span>, submitted the <span id='date'></span>.</p></div>";
echo "<div id='val_form'></div>";

?>