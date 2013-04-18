<?php

require_once('includes/MySQL.php');
require_once('includes/db-local.php');

$db = new MySQL($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['database']);

$all_valid = $email_valid = $password_valid = $fname_valid = $lname_valid = $school_valid = $program_valid = $gradyear_valid = true;

if (isset($_POST['registration_submit'])) {

    require_once 'EmailAddressValidator.php';
    $validator = new EmailAddressValidator;
    
    $email = mysql_real_escape_string(trim($_POST['registration_email']));
    if (preg_match("/\r/",$email) || preg_match("/\n/",$email)){ die(); }
    
    $password = mysql_real_escape_string(trim($_POST['registration_password']));
    if (preg_match("/\r/",$password) || preg_match("/\n/",$password)){ die(); }
    
    $salt = mysql_real_escape_string(trim($_POST['registration_salt']));
    if (preg_match("/\r/",$salt) || preg_match("/\n/",$salt)){ die(); }
    
    $fname = mysql_real_escape_string(trim($_POST['registration_firstname']));
    if (preg_match("/\r/",$fname) || preg_match("/\n/",$fname)){ die(); }
    
    $lname = mysql_real_escape_string(trim($_POST['registration_lastname']));
    if (preg_match("/\r/",$lname) || preg_match("/\n/",$lname)){ die(); }
    
    $title = mysql_real_escape_string(trim($_POST['registration_creative_title']));
    if (preg_match("/\r/",$title) || preg_match("/\n/",$title)){ die(); }
    
    $school = mysql_real_escape_string(trim($_POST['registration_school']));
    if (preg_match("/\r/",$school) || preg_match("/\n/",$school)){ die(); }
    
    $program = mysql_real_escape_string(trim($_POST['registration_program']));
    if (preg_match("/\r/",$program) || preg_match("/\n/",$program)){ die(); }
    
    $gradyear = mysql_real_escape_string(trim($_POST['registration_gradyear']));
    if (preg_match("/\r/",$gradyear) || preg_match("/\n/",$gradyear)){ die(); }
    
/*
    $salt = dechex(mt_rand(0, 2147483647)) . dechex(mt_rand(0, 2147483647));
    $hashed_password = hash('sha256', $password . $salt); 
    for($round = 0; $round < 65535; $round++) { 
        $hashed_password = hash('sha256', $hashed_password . $salt); 
    }
*/
    
    
    if ($fname == '') { 
        $all_valid = $fname_valid = false; 
        header('Location: '. $_POST['from_page'].'?error=fname');
    }
    if ($lname == '') { 
        $all_valid = $lname_valid = false; 
        header('Location: '. $_POST['from_page'].'?error=lname');
    }
    if ($school == 0) {
        $all_valid = $school_valid = false;
        header('Location: '. $_POST['from_page'].'?error=school');
    }
    if ($program == 0) {
        $all_valid = $program_valid = false;
        header('Location: '. $_POST['from_page'].'?error=program');
    }
    if ($gradyear == 0) {
        $all_valid = $gradyear_valid = false;
        header('Location: '. $_POST['from_page'].'?error=gradyear');
    }
    
    
/*
    if ($password == '') { 
        $all_valid = $password_valid = false; 
        header('Location: '. $_POST['from_page'].'?error=password');
    }
    if (!$validator->check_email_address($email)) {
        $all_valid = $email_valid = false;
        header('Location: '. $_POST['from_page'].'?error=email');
    }
*/
    
    if ($all_valid) {
        $insertQ = "INSERT INTO students (StudentFirstName, StudentLastName, StudentEmail, StudentPassword, StudentSalt, GradYearID, SchoolID, ProgramID, CreativeTitleID) VALUES ('$fname', '$lname', '$email', '$password', '$salt', '$gradyear', '$school', '$program', '$title')";
        $result = mysql_query($insertQ) or die("$insertQ - ".mysql_error());
        header('Location: '. $_POST['from_page'].'?registered=true');
    }
}


?>