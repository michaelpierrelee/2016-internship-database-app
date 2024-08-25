<?php
function fwrite_stream($fp, $string) {
    for ($written = 0; $written < strlen($string); $written += $fwrite) {
        $fwrite = fwrite($fp, substr($string, $written));
        if ($fwrite === false) {
            return $written;
        }
    }
    return $written;
}

function backup_table($tables) {
	/*SAVE THE STRUCTURE AND THE DATA OF A TABLE INTO A FILE*/
	$time = time();
	foreach ($tables as $table) {
		$db = connect();
		$data = $table . "\n";

		//get back the structure of the table
		$req = $db -> query("SHOW CREATE TABLE " . $table);
		$structure = $req -> fetch()["Create Table"];
		$structure = str_replace("\n", "", $structure);
		$data .= $structure . "\n";

		//get back name of columns
		$req = $db -> query("DESCRIBE " . $table);
		$fetch = $req -> fetchAll();
		foreach ($fetch as $i => $d) {
			$data .= $d["Field"];
			if ($i < count($fetch) - 1)
				$data .= "\t";
		}
		$data .= "\n";

		//get back data from the table
		$req = $db -> query("SELECT * FROM " . $table);
		$rows = $db -> query("SELECT COUNT(*) FROM " . $table) -> fetch()[0];
		$i = 0;
		while ($d = $req -> fetch(PDO::FETCH_NUM)) {
			foreach ($d as $key => $value) {
				$value = str_replace("\n", "", $value);
				$value = str_replace("\t", "", $value);
				$data .= $value;
				if ($key < count($d) - 1)
					$data .= "\t";
			}
			if ($i < $rows - 1)
				$data .= "\n";
			$i++;
		}
		$req -> closeCursor();

		//save into the file
		$fp = fopen('save/db-backup-' . $table . '-' . $time . '.txt', 'w');
		fwrite_stream($fp, $data);
	}
	return TRUE;
}

function backup_import($file, $recreate) {
	/*RECREATE THE TABLE SAVED IN $file IF $recreate = true AND ADD DATA*/
	$db = connect();
	$handle = fopen($file, "r");
	if ($handle) {
		$i = 0;
		$id_column = false; //if there is an id column
	    while (!feof($handle)) {
	        $buffer = fgets($handle, 4096);
	        //buffer operations
	        $buffer = str_replace("\n", "", $buffer);
	        if ($i == 0){
	        	//Verify if the table already exists
	        	$table = $buffer;
	        	if ($recreate) {
					try {
						//table found
				        $req = $db -> query("SELECT 1 FROM $table LIMIT 1");
				        //delete the table
				        $req = $db -> query("DROP TABLE $table");
				    } catch (Exception $e) {
				        //table not found
				    }
			    } else {
			    	//empty the table
			    	$req = $db -> query("TRUNCATE $table");
			    }
			} elseif ($i == 1) {
	        	//create the table
			    $req = $db -> query($buffer);
			    //reset autoincrement
	    		$req = $db -> query("ALTER TABLE $table AUTO_INCREMENT = 1");
		    } elseif ($i == 2) {
		    	//get back columns
		    	$columns = explode("\t", $buffer);
		    	//if there is an id column
			    if(in_array("id", $columns))
			    	$id_column = true;
	        } else {
	        	$buffer = explode("\t", $buffer);
	        	$query = "INSERT INTO ". $table ." VALUES(";
        		$query_arr = array();
        		$j = 0;

	        	//manage the case where the id field has to be empty
	        	if ($id_column) { //to use the autoincrement of mysql to give an id
	        		$j = 1;
	        		$query .= "0";
	        		if (count($buffer) > 1) $query .= ", ";
	        	}
				
				//prepare
				while ($j < count($buffer)) {
					//prepare the query
					$c = str_replace(' ', '_', $columns[$j]);
					$query .= " :$c";
					if ($j < count($columns) - 1)
						$query .= ", ";
					//prepare the array
					$query_arr[$c] = $buffer[$j];
					//increment
					$j++;
				}
				$query .= ")";
				//print_r($query_arr);
				//echo "<br><br>";

				//fill the table
				$req = $db -> prepare($query);
				$req -> execute($query_arr);
	        }
	        $i++;
	    }
	    $req -> closeCursor();
    }
    fclose($handle);
    return TRUE;
}