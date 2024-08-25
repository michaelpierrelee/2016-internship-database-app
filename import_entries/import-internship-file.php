<?php  header('Content-Type: text/html; charset=utf-8');
require("functions.php");
set_time_limit(0);
$data = array();

$handle = @fopen("internship-entries-to-import.tsv", "r");
if ($handle) {
    while (($buffer = fgets($handle, 4096)) !== false) {
    	$line = str_replace("\n", "", $buffer);
        $data[] = explode("\t", $line);
    }
    if (!feof($handle)) {
        echo "Error: unexpected fgets() fail\n";
    }
    fclose($handle);

}
//print_r($data[2]);



$db = connect();
echo "<table style='border-collapse:collapse'>";
for ($i = 0; $i < count($data); $i++) {
	$d = $data[$i];
	/*$e = $i + 1;
	echo "<tr><td style='border-style: solid'>$e</td>";
	for ($j=0; $j < count($d); $j++) { 
		echo "<td style='border-style: solid'>" . $d[$j] . "</td>";
	}
	echo "</tr>";*/
	$query = "INSERT INTO interns_internships(`id`, `submit date`, `validated`, `First Name`, `Last Name`, `Promotion`, `Formation`, `Email`, `Sector`, `Organization`, `Team`, `Country`, `City`, `Website`, `Supervisor`, `Contacts`, `Position`, `ESBS Year`, `Duration`, `Domains`, `Subject`, `Techniques`, `Comments`, `Monthly Salary`, `Financial Comments`, `Satisfaction`)
		VALUES(0, '2015-01-09', 0, :first, :last, :a, :b, :c, :d, :e, :f, :g, :h, :i, :j, :k, :l, :m, :n, :o, :p, :q, :r, :s, :t, :u)";
	$req = $db -> prepare($query);
	$req -> execute(array(
			"first" => json_encode([ucfirst(strtolower($d[1]))]),
			"last" => json_encode([ucfirst(strtolower($d[0]))]),
			"a" => json_encode([$d[2]]),
			"b" => json_encode(["Biotech"]),
			"c" => json_encode([$d[3]]),
			"d" => json_encode([ucfirst($d[4])]),
			"e" => json_encode([$d[5]]),
			"f" => json_encode([$d[7]]),
			"g" => json_encode([$d[8]]),
			"h" => json_encode([$d[9]]),
			"i" => json_encode([$d[6]]),
			"j" => json_encode([$d[10]]),
			"k" => json_encode([$d[12]]),
			"l" => json_encode([$d[11]]),
			"m" => json_encode([$d[15]]),
			"n" => json_encode([$d[13]]),
			"o" => json_encode([$d[16], $d[17], $d[18], $d[19], $d[20]]),
			"p" => json_encode([$d[21]]),
			"q" => json_encode([$d[16], $d[17], $d[18], $d[19], $d[20]]),
			"r" => json_encode([$d[22]]),
			"s" => json_encode([$d[23]]),
			"t" => json_encode([""]),
			"u" => json_encode([""])
		));

	echo $i -1 . " " . $d[0] . "<br>";
}
echo "</table>";
$req -> closeCursor();

/*$db = connect();
$req = $db -> query($query);
$req -> closeCursor();*/




