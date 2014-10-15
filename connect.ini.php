<?php

require_once('includes/MySQL.php');
require_once('includes/db-local.php');
//require_once('includes/db-live.php');
$db = new MySQL($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['database']);

?>