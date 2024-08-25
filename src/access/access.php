<?php header('Content-Type: text/html; charset=utf-8');
?>

<h2>Internship database</h1>
<p><button class='pure-button' id='menu'><i class='fa fa-home'></i> Go to the menu</button></p>
<p>
	<strong><span id='nb_ints'>?</span></strong> internships saved and validated, 
	<strong><span id='nb_ints_disp'>0</span></strong> in memory. 
	<button class='pure-button' style='font-size: 85%;' id='plus_ints'><i class='fa fa-plus'></i> Display more</button>
</p>
<p><em><strong>Tips:</strong><br />
	<span id="tips">
		<i class="fa fa-thumb-tack"></i> Click on a row to change its color.<br />
		<i class="fa fa-sort-amount-asc"></i> Click on a table head to change the order of the column.<br />
		<i class="fa fa-search-plus"></i> Click on a magnifier at the left to see details of the internship of the row.<br />
		<i class="fa fa-euro"></i> Monthly Salary in €.<br />
		<i class="fa fa-smile-o"></i> The satisfaction degree is on a scale from 1 (bad internship) to 5 (excellent).<br />
		<i class="fa fa-search"></i> Use the hotkey CRTL-F (or CMD-F on iOS) to find a key word on the page.<br />
		<i class="fa fa-exchange"></i> Choose a negative filter to remove all rows with it, choose a selective filter to only display rows with it.
	</span></em>
</p>
<div id='msg'></div>
<div id='choices'></div>
<div id='db'></div>