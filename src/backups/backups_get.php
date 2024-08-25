<?php header('Content-Type: text/html; charset=utf-8');
require("../functions.php");
sec_session_start(); 
sec_session_check(2);

//get back files of the directory
$directory = "save";
$scanned_dir = array_values(array_diff(scandir($directory), array('..', '.', 'original', '.htaccess', '.htpasswd')));

//remove files which are not txt
$dir = [];
for ($i = 0; $i < count($scanned_dir); $i++) { 
	if(strpos($scanned_dir[$i], ".txt") === FALSE || explode("-", $scanned_dir[$i])[2] == "interns_fields") {
		unset($scanned_dir[$i]);
	}
}

//generate string
$string = "";
foreach ($scanned_dir as $value) {
	$size = fileSizeConvert(filesize("save/" . $value));
	$timestamp = explode(".", explode("-", $value)[3])[0];
	$string .= "
	<tr>
		<td style='word-wrap: break-word'>". date('d/m/Y G:H:i:s', $timestamp) ."</td>
		<td style='word-wrap: break-word'>". $size ."</td>
		<td><input id='$timestamp' type='radio' name='timestamp' value='$timestamp'></td>
	</tr>";
}
if ($string == "")
	$string = "<tr><td style='word-wrap: break-word'><strong>No backup.</strong> Please save one!</td><td></td><td></td></tr>";

echo $string;
?>