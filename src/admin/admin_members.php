<?php header('Content-Type: text/html; charset=utf-8');

?>

<h2>Management of members</h2>
<p><button class='pure-button' id='menu'><i class='fa fa-home'></i> Go to the menu</button></p>
<p id="msg"></p>
<form id="form_new_mb" class="pure-form pure-form-aligned"><fieldset>
	<legend>Add a new member</legend>
	<div class="pure-control-group">
		<label for="name">Name:</label>
		<input type="text" id="name" name="name" required />
	</div>

	<div class="pure-control-group">
		<label for="pswd">Password:</label>
		<input type="text" id="pswd" name="pswd" required />
	</div>
	<div class="pure-control-group">
		<label for="level">Authorization level:</label>
		<input type="text" id="level" name="level" value="1" required />
	</div>
	<div class="pure-control-group">
		<button class='pure-button pure-button-primary' id='add_mb'><i class='fa fa-paper-plane'></i> Send</button>
	</div>
</fieldset></form>

<p><em>Authorization level: 1 = normal level, 2 = administration level.</em></p>

<h3>Existing members:</h3>
<form id="form_mbs" class="pure-form">
	<table id="form_mbs_table" class="pure-table">
		<thead>
	        <tr>
	            <th>Name</th>
	            <th>Authorization level</th>
	            <th>Select</th>
	        </tr>
	    </thead>
	    <tbody id="exist_mb"></tbody>
	</table>
	<p>
		<button type="submit" id="remove_mb" class="pure-button">Remove members</button>
	</p>
</form>