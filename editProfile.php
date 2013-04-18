<?php
		
		if (isset($_POST['submit'])) { // USER HAS JUST SUBMITTED THE FORM
		
		$SID = mysql_real_escape_string(trim($_POST['SID']));
		$facebook = mysql_real_escape_string(trim($_POST['facebook']));
		$twitter = mysql_real_escape_string(trim($_POST['twitter']));
		$linkedin = mysql_real_escape_string(trim($_POST['linkedin']));
		$student_description = mysql_real_escape_string(trim($_POST['student_description']));
		$StudentPublicEmail = mysql_real_escape_string(trim($_POST['StudentPublicEmail']));
		$portfolio_website = mysql_real_escape_string(trim($_POST['portfolio_website']));
		
		
		if (is_numeric($id)) {

		
			//use this database
			require_once 'includes/MySQL.php'; // the mysql classes
			require_once 'includes/db-local.php'; // connects the database here
	
			$db = new MySQL($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['database']);// and here

			
			$sql = "UPDATE students SET
									facebook ='$facebook',
									twitter='$twitter',
									linkedin='$linkedin',
									student_description='$student_description',
									StudentPublicEmail='$StudentPublicEmail',
									portfolio_website = '$portfolio_website',
									fname = '$fname',
									lname = '$lname' 
					WHERE SID=$SID";
			
			$result = $db->query($sql);
			
			echo"<h3>Great! $fname $lname, your profile has been updated.</h3>";

		}
			
		} else { // NO FORM SUBMISSION
			if (isset($_GET['id'])) { //THERE'S AN INCOMING ID	
				
				if (is_numeric($_GET['id'])) { // makes sure there is no malicious input here
					$sql = 'SELECT * FROM students WHERE id=' . mysql_real_escape_string(trim($_GET['SID']));
					
						require_once '../includes/MySQL.php';
						require_once '../includes/db-local.php';
						
						$db = new MySQL($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['database']);
						
						$result = $db ->query($sql);
						
						if ($result->size() == 1) {
							// ONLY GOT ONE RECORD BACK!
							while ($row = $result->fetch()) { // -> allows to link and object($result) to a method(fetch)
								edit_form($row);
							}
							
							
						} else { // 0 RECORDS, OR MORE THAN 1
							echo '<h3>Sorry, no user was found with that ID. Want to <a href="../address_book.php">try again</a>?</h3>';
						}
						
				} else {
					echo '<h3>Sorry, no user was found with that ID. Want to <a href="../address_book.php">try again</a>?</h3>';
				}
				
			} else { //	NO INCOMING ID IN QUERY STRING
				echo '<h3>Please choose a user to edit: <a href="../address_book.php">Address Book</a></h3>';
			}
		}

		//	DEAL WITH PAGE REQUEST BY LOGGED-IN USER HERE --> is the user logged in? YES
	
?>