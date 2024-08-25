<?php header('Content-Type: text/html; charset=utf-8');
require("functions.php");
sec_session_start();
?>

<!DOCTYPE html>
<html class="fontawe">
	<head>
		<meta charset="utf-8" />
		<title>INDI</title>
		
		<meta name="viewport" content="width=device-width, initial-scale=1">
		
		<link rel="stylesheet" href="pure-release-0.6.0/pure-min.css">
		<link rel="stylesheet" href="pure-release-0.6.0/grids-responsive-min.css">
		<link rel="stylesheet" href="font-awesome/css/font-awesome.min.css">
		<link rel="stylesheet" href="jquery-ui-1.12.0.custom/jquery-ui.min.css">
		<link rel="stylesheet" href="interns.css">

		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	</head>
	<body>

		<script src="jquery-3.1.1.min.js"></script>
		<script src="jquery-ui-1.12.0.custom/jquery-ui.min.js"></script>
		<script src="index_script.js"></script>
		<script src="connection/connect_script.js"></script>
<!--
By Michaël Pierrelée, ESBS Promotion 2017.
-->

		<header>
			<h1 class="to_center main_title">I.N.D.I.</h1>
			<p class="to_center">
				<em>Internship Network Database Interface</em>
				<span id="add_button_deco"></span>
			</p>
		</header>
		
		<div id="load">
		<?php
			if (login_check(0)) {
		?>
			<script type="text/javascript">
				$( function() {
					$("#add_button_deco").html("<button class='pure-button' id='ask_deconnect'><i class='fa fa-sign-out'></i> Out</button>");
					$.getScript("menu/menu_script.js");
					load_php("menu/menu.php");
				});
			</script>
		<?php
			} else {
		?>
			<form id="connection" class="pure-form pure-form-aligned">
				<div class="pure-control-group">
					<p>With INDI, the ESBS students can look at details of internships saved by their mates and add their own internships.</p>
				</div>
				<div class="pure-control-group">
					<label for="name">Login:</label>
					<input type="text" id="name" name="name" required />
				</div>
				<div class="pure-control-group">
					<label for="pswd">Password:</label>
					<input type="text" id="pswd" name="pswd" required />
				</div>
				<div class="pure-control-group">
					<button class='pure-button pure-button-primary' id='ask_connect'><i class='fa fa-sign-in'></i> Connection</button>
				</div>
			</form>
			<div id="msg"></div>
		<?php
			}
		?>
			
		</div>

		<footer>Michaël Pierrelée - Licence Apache 2.0</footer>


		
	</body>
</html>