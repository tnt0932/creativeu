<?php // set some variables..
   
    $all_valid = $username_valid = true;
    
    // do everything below, only if the form has been submitted
    if (isset($_POST['login-submit'])) {
        require_once('includes/MySQL.php');
        require_once('includes/db-local.php');
        
        $db = new MySQL($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['database']);
        // getting the post data from the form and assigning it to variables
        $email = mysql_real_escape_string(trim($_POST['login-email']));
        //if (preg_match("/\r/",$email) || preg_match("/\n/",$email)){ die(); }
        
        $password = mysql_real_escape_string(trim($_POST['login-password']));
        //if (preg_match("/\r/",$password) || preg_match("/\n/",$password)){ die(); }
        
        // set some validation variables
        $valid_user = $valid_email = false;
        //echo "<h1>$email, $password</h1>";
        
    
        $sql = "SELECT SID, StudentEmail, StudentFirstName as fname, StudentLastName as lname, StudentPassword, StudentSalt, StudentPicture, ProgramName as program, SchoolName as school, GradYear as gradyear, StudentFacebook as facebook, StudentTwitter as twitter, StudentLinkedIn as linkedin, StudentWebsite as website, StudentDescription as description, CreativeTitleID FROM students 
        INNER JOIN program ON students.ProgramID=program.ProgramID
        INNER JOIN school ON students.SchoolID=school.SchoolID
        INNER JOIN gradyear ON students.GradYearID=gradyear.GradYearID
        WHERE StudentEmail='$email'";
        
        $result = $db->query($sql); // run the query
        if ($result->size() == 1) { // was one record returned?
            while ($row = $result->fetch()) {
                $check_password = hash('sha256', $password . $row['StudentSalt']);
                for ($round = 0; $round < 65535; $round++) {
                    $check_password = hash('sha256', $check_password . $row['StudentSalt']);
                }
                if ($check_password == $row['StudentPassword']) {
                    $valid_user = true;
                    $valid_email = true;
                    session_start();
                    $_SESSION['logged_in_user'] = $row['fname'] . ' ' . $row['lname'];
                    $_SESSION['studentemail'] = $row['StudentEmail'];
                    $_SESSION['SID'] = $row['SID'];
                    $_SESSION['password'] = $row['StudentPassword'];
                    $_SESSION['salt'] = $row['StudentSalt'];
                    $_SESSION['fname'] = $row['fname'];
                    $_SESSION['lname'] = $row['lname'];
                    if ($row['StudentPicture'] != NULL) {
                        $_SESSION['studentpic'] = $row['StudentPicture'];
                    }
                    header('Location: index.php');
                }
                if ($row['StudentEmail'] == $email && $row['StudentPassword'] != $password) {
                    // set this var to true
                    session_start();
                    $_SESSION['valid_email'] = $email;
                    $valid_email = true;
                }
            }
        }
        
        // if these vars are not true, then these vars are false
        if (!$valid_user) { header('Location: index.php?error=invalid_user'); }
        if (!$valid_email) { header('Location: index.php?error=invalid_email'); }
    }
?>




