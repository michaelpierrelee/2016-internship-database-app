<?php header('Content-Type: text/html; charset=utf-8');
require("../functions.php");
?>

<h2>Manage backups of the database</h2>
<p><a class='pure-button' id="menu">
	<i class='fa fa-home'></i>
	Go to the menu
</a></p>

<div id="msg"></div>

<p style="text-align:center"><a class='pure-button pure-button-primary pure-button-active' id="export"><i class='fa fa-archive'></i> <strong>Make a backup</strong></a></p>

<form id="import" class="pure-form">
	<p>Execute a backup will remove tables of internships (_internships and _fields) and will restore the database according to the version of the backup.
	<br /><strong>Be careful!</strong><br />
	<em>Backups are saved in the directory indi/backups/save and the sql commands to recreate the tables interns_ in indi/backups/save/original.</em></p>
	<table id="table_import" class="pure-table pure-table-horizontal pure-table-striped" style="width: 100%">
		<thead>
	        <tr>
	            <th>Date backup</th>
	            <th>Size</th>
	            <th>Restore internships</th>
	        </tr>
	    </thead>
	    <tbody id="backups"></tbody>
	</table>
	<p>
		<button type="submit" id="restore" class="pure-button pure-button-primary pure-button-active"><i class="fa fa-cloud-upload"></i> Import backup</button>
		<button type="submit" id="delete" class="pure-button pure-button"><i class='fa fa-trash'></i> Delete backup</button>
	</p>
</form>