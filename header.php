<?php
    //ALERTS
    $invalid_email = '<span class="alert">THAT EMAIL DOESN\'T EXIST IN OUR SYSTEM!</span>';
    $invalid_user = '<span class="alert">WRONG EMAIL / PASSWORD COMBO</span>';
    $invalid_password = '<span class="alert">PLEASE ENTER A PASSWORD</span>';
    $existing_email = '<span class="alert">THAT EMAIL ALREADY EXISTS IN OUR SYSTEM!</span>';
    $accountdeleted = '<span class="alert">YOUR ACCOUNT HAS BEEN DELETED</span>';
    $registered = '<span class="alert">REGISTRATION SUCCESSFUL!</span>';
    $deleteduser = '<span class="alert">The user and all of their snippets have been successfully deleted!</span>';
    $nouser = '<span class="alert">Sorry, there are no users with that ID!</span>';
    
    if (isset($_GET['error']) && $_GET['error'] == 'invalid_email') {
        echo $invalid_email;
    }
    if (isset($_GET['error']) && $_GET['error'] == 'invalid_user') {
        echo $invalid_user;
    }
    if (isset($_GET['deleteaccount']) && $_GET['deleteaccount']==true) {
        echo $accountdeleted;
    }
    if (isset($_GET['registered']) && $_GET['registered']==true) {
        echo $registered;
    }
    if (isset($_GET['deleteduser']) && $_GET['deleteduser']==true) {
        echo $deleteduser;
    }
    if (isset($_GET['nouser']) && $_GET['nouser']==true) {
        echo $nouser;
    }
    
?>

<?php
    if (isset($_POST['registration_2_trigger'])) {
        require_once 'EmailAddressValidator.php';
        $validator = new EmailAddressValidator;
        // store stage 1 of the registration process. (used in hidden inputs in the registration modal)
        $email =  $_POST['register-email'];
        $password = $_POST['register-password']; //TODO: Salt password before storing in variable.
        $salt = dechex(mt_rand(0, 2147483647)) . dechex(mt_rand(0, 2147483647));
        $hashed_password = hash('sha256', $password . $salt); 
        for($round = 0; $round < 65535; $round++) { 
            $hashed_password = hash('sha256', $hashed_password . $salt); 
        }
        
        $_SESSION['registration_email'] = $email;
        $_SESSION['registration_password'] = $hashed_password;
        $_SESSION['registration_salt'] = $salt;
        
        $all_valid = $email_valid = true;
        /* CHECK TO SEE IF THE ENTERED EMAIL ALREADY EXISTS IN OUR SYSTEM. IF SO, RETURN AN ERROR */
        $selectQ = 'SELECT StudentEmail FROM students';
        //run the select query
        $results = mysql_query($selectQ) or die($selectQ. ' - error' .mysql_error());
        //check to see if the chosen username already exists in the system
        while ($result = mysql_fetch_array($results, MYSQL_ASSOC)) {
            if ($email == $result['StudentEmail']) {
                $all_valid = $email_valid = false;
                echo $existing_email;
            }
            if ($password == '') { 
                $all_valid = $password_valid = false; 
                echo $invalid_password;
            }
            if (!$validator->check_email_address($email)) {
                $all_valid = $email_valid = false;
                echo $invalid_email;
            }
        }
        
        // IF EMAIL/PASSWORD IS VALID, THEN LOAD/LAUNCH MODAL
        if ($all_valid) {
?>
        <script type="text/javascript">
        $(document).ready(function() {
            $('#registration_modal').lightbox_me({
                centered: true, 
            });
        });
        </script>
<?php } } ?>


<!-- Login dropdown script -->
<script type="text/javascript">
$(document).ready(function(){

    nav_dropdown('#login-trigger', '#login-content');
    nav_dropdown('#register-trigger', '#register-content')
    nav_dropdown('#nav-links-trigger', '#nav-links-content');
    password_strength('#register-password');
    
});
</script>

<header>
    <a href="index.php"><img src="img/logo-creativeu.png" id="logo" width="90" height="41" alt="creativeu logo" id="logo"></a>
     <nav>

  <!-- ================================================
  				STATEMENT FOR LOGGED IN ADMIN
  	   ================================================-->   
         <?php
             if (isset($_SESSION['admin_logged_in_user'])) {
         ?>
            <ul>
                <li><a href="#"><?php echo $_SESSION['admin_logged_in_user'] ?></a></li>
                
                <li>
                    <a href="#"><p id="nav-links-trigger">[gear icon]</p></a>
                    <div id="nav-links-content">
                        <ul>
                            <li><a href="admin.php">Dashboard</a></li><br>
                            <li><a href="logout.php">Log out</a></li>
                        </ul>
                    </div>
                </li>
            </ul>
<?php
} else {
?>
  <!-- ================================================
  				STATEMENT FOR LOGGED IN USER
  	   ================================================-->   
         <?php
             if (!isset($_SESSION['logged_in_user'])) {
         ?>
        <ul>
        
            <!-- ========================================
                    LOGIN
            ==========================================-->
            <li id="login">
                <a href="#" id="login-trigger">Log in</a>
                <div id="login-content">
                    <form action="login.php" method="post">
                        <fieldset id="login-inputs">
                            <input id="login-email" type="email" name="login-email" placeholder="your email address" required>
                            <input id="login-password" type="password" name="login-password" placeholder="password" required>
                        </fieldset>
                        <fieldset id="login-actions">
                            <input id ="login-submit" type="submit" name="login-submit" value="Log in" class="btn-pink">                     
                        </fieldset>
                        <a href="#" id="forgot_password_trigger">Forgot password?</a>
                    </form>
                </div>
            </li>
            
            <!-- ========================================
                    Registration
            ==========================================-->
            <li id="register"><a href="#" id="register-trigger">Register</a>
                <div id="register-content">
                    <p>Are you Canadian student? Register for a free account to display your work!</p>
                    <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
                        <input type="hidden" name="from_page" value="<?php echo $_SERVER['SCRIPT_NAME']; ?>">
                        <fieldset id="register-inputs">
                            <input id="register-email" type="email" name="register-email" placeholder="your email address" required>
                            <input id="register-password" type="password" name="register-password" placeholder="password" class="strength" required>
                            <input type="submit" name="registration_2_trigger" id ="registration_2_trigger" value="Continue" class="btn-pink">
                        </fieldset>
                    </form>
                </div>
            </li>
            <li><a href="about.php">About Us</a></li>
            
            <?php } else { ?>
            
            
            <!-- ========================================
                    User is logged in
            ==========================================-->
            
            <ul>
                <li><a href="#" id="add_snippet_modal_trigger" class="btn-pink" style="display: inline;">Add Snippet</a></li>
                <li><a href="profile.php?id=<?php echo $_SESSION['SID'] ?>"><?php echo $_SESSION['logged_in_user'] ?></a></li>
                
                <li>
                    <a href="#"><p id="nav-links-trigger">[gear icon]</p></a>
                    <div id="nav-links-content">
                        <ul>
                            <li><a href="accountsettings.php">Settings</a></li><br>
                            <li><a href="about.php">About us</a></li><br>
                            <li><a href="logout.php">Log out</a></li>
                        </ul>
                    </div>
                </li>
            </ul>
            <?php } }?>
        </ul>
    </nav>
</header>