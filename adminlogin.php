<?php

	$AdminEmail = $_POST['AdminEmail'];
	$AdminPassword = $_POST['AdminPassword'];

	$valid_user = false;
	
	require_once 'includes/MySQL.php';
	require_once 'includes/db-local.php';
	
	$db = new MySQL($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['database']);

	$sql = "SELECT * FROM admin WHERE AdminEmail='$AdminEmail' and AdminPassword='$AdminPassword'";
	
	$result = $db->query($sql);	//Run the Query
	if ($result->size() == 1) { // was one record returned?
		while ($row = $result->fetch()) {

			if ($AdminEmail == $row['AdminEmail'] && $AdminPassword == $row['AdminPassword']) {
				$valid_user = true;
				session_start();
				$_SESSION['admin_logged_in_user'] = $row['AdminName'];
				header('Location: admin.php');
			}
		}
}		

	if (!$valid_user) {
		header('Location: admin.php?error=user');	
	}
?>