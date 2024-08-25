<?php header('Content-Type: text/html; charset=utf-8');
require("../functions.php");
sec_session_start(); ?>

<div id="menu_choices">
	<div id="form" class="a_menu" onClick=""><i class="fa fa-plus"></i><br />New internship</div>
	<div id="access" class="a_menu" onClick=""><i class="fa fa-database"></i><br />Internship database</div>
<?php
	if (login_check(2)) {
?>
	<div id="validation" class="a_menu" onClick=""><i class="fa fa-check"></i><br />Validate internships</div>
	<div id="fields" class="a_menu" onClick=""><i class="fa fa-bars"></i><br />Management of fields</div>
	<div id="save" class="a_menu" onClick=""><i class="fa fa-floppy-o"></i><br />Save of the database</div>
	<div id="remove" class="a_menu" onClick=""><i class="fa fa-trash-o"></i><br />Remove the oldest internships</div>
	<div id="members" class="a_menu" onClick=""><i class="fa fa-child"></i><br />Members and passwords</div>
<?php
	}
?>
</div>
<div id="msg"></div>