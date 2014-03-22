<?php
	$db = new mysqli( 'tunnel.pagodabox.com',  'frieda', '5bs4PZFO', 'shows', 3306);
	
	if(!$db) {
		// Show error if we cannot connect.
		echo 'ERROR: Could not connect to the database.';
	} else {
		// Is there a posted query string?
		if(isset($_POST['queryString'])) {
			$queryString = $db->real_escape_string($_POST['queryString']);
			
			// Is the string length greater than 0?
			
			if(strlen($queryString) >0) {
				$query = $db->query("SELECT * FROM shows WHERE name LIKE '$queryString%' OR secondary_name LIKE '$queryString%' OR third_name LIKE '$queryString%' LIMIT 10");
					if($query) {
					while ($result = $query->fetch_object()) {
							// Format the results, im using <li> for the list, you can change it.
							// The onClick function fills the textbox with the result.
						
							// YOU MUST CHANGE: $result->value to $result->your_colum
	         					echo '<li onClick="fill(\''.$result->name.'\');seasons(\''.$result->seasons.'\');">'.$result->name.'</li>';
	         				}

					} else {
						echo 'ERROR: There was a problem with the query.';
					}

			} else {

			} // There is a queryString.
		} else {
			echo 'You shall not pass!';
		}
	}
?>