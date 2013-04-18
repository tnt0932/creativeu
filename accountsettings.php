<?php
    session_start(); 
    
    if (!isset($_SESSION['logged_in_user'])) {
        header ('Location: index.php');
    }
    //use this database
    require_once 'includes/MySQL.php'; // the mysql classes
    require_once 'includes/db-local.php'; // connects the database here

    $db = new MySQL($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['database']);// and here   
    //error vars
    $new_password_error = $new_email_error = $password_error_delete_account = $new_password = $password_match = $new_email = '';
    
    //hash password function
    function hashPassword($unhashed, $salt) {
        $hashed_password = hash('sha256', $unhashed . $salt);
        for ($i = 0; $i < 65535; $i++) {
            $hashed_password = hash('sha256', $hashed_password . $salt);
        }
        return $hashed_password;
    }
    
    // Change Email
    if (isset($_POST['change_email_submit'])) {
        $email_exists = false;
        
        $sql = "SELECT StudentEmail FROM students";
        $result = $db->query($sql);
        
        while ($row = $result->fetch()) {
            if ($_POST['email'] == $row['StudentEmail']) {
                $new_email_error = "<h3>That email address already exists in our system</h3><br>";
                $email_exists = true;
            }
        }
        if (!$email_exists) {
            $sql = "UPDATE students SET StudentEmail='".$_POST['email']."' WHERE SID=".$_SESSION['SID'];
            $result = $db->query($sql);
        }
    }
    
    // Change Password
    if (isset($_POST['change_password_submit'])) {
        $sql = "SELECT StudentPassword FROM students WHERE SID=".$_SESSION['SID'];
        $result = $db->query($sql);
        
        while ($row = $result->fetch()) {
            $password = $row['StudentPassword'];
        }

        if ($_POST['new_password_1'] != $_POST['new_password_2']) {
            $password_match = '<h3>Those passwords don\'t match!</h3><br>';
        } else {
            $hashed_password = hashPassword($_POST['current_password'], $_SESSION['salt']);
            if ($hashed_password == $password) {
                $hashed_password = hashPassword($_POST['new_password_1'], $_SESSION['salt']);
                $sql = "UPDATE students SET StudentPassword='$hashed_password' WHERE SID=".$_SESSION['SID'];
                $result = mysql_query($sql) or die ($sql. '-error' .mysql_error());
                $new_password = "<h4 style='color:#E81D77;'>Password successfully changed!</h4><br>";
            } else {
                $new_password = "<h4 style='color:#E81D77;'>Sorry, that password is incorrect.</h4><br>";
            }
        }
    }
    
    // Delete Account
    if (isset($_POST['delete_account_submit'])) {
        $hashed_password = hashPassword($_POST['current_password'], $_SESSION['salt']);
        if ($hashed_password == $_SESSION['password']) {
            $sql = "DELETE FROM students WHERE SID=".$_SESSION['SID'];
            $result = $db->query($sql);
            $sql = "DELETE FROM snippet WHERE SID=".$_SESSION['SID'];
            $result = $db->query($sql);
            header('Location: logout.php?deleteaccount=true');
        } else {
            $password_error_delete_account = "<h3>Sorry, that password is incorrect.</h3><br>";
        }

    }
?>
<?php require_once 'head.php'; ?>

<?php require_once 'header.php'; ?>
<div id="accountcontent">
<div id="container">
    
    <div id="content" class="section group">
    <!-- ==================================
                     Left Column
         ==================================-->
        <section class="col span_3_of_12">
        <?php $sql = "SELECT StudentPicture as pic, SID, StudentFirstName as fname, StudentLastName as lname FROM students
        WHERE SID=".$_SESSION['SID'];
        $result = $db->query($sql);
        if ($result->size() == 1) { // was one record returned?
            while ($row = $result->fetch()) {
                $pic = $row['pic'];
                $studentID = $row['SID'];
                $fname = $row['fname'];
                $lname = $row['lname'];
            }
        }

           if ($pic == NULL) { ?>
                <img src="img/default_profile.jpg" width="250" height="250" alt="Profile Image">
                <?php } else {
                echo '<img src="img/student_uploads/'.$studentID.'/profile_image/'.$pic.'" alt="'.$fname.' '.$lname.'\'s Profile Picture" width="250" height="250" class="profile_img">';
            } ?>
            <br>
        </section>
        
    <!-- ==================================
                   Right Column
         ==================================-->
         <section class="col span_9_of_12">
             <h1><?php echo $_SESSION['fname']?> <?php echo $_SESSION['lname']?></h1>
            <hr class="breaker">
             
             <!-- ==================================
                   Change Email
             ==================================-->
             <h3>Change your login email address</h3>
             <p>Edit your login email or create a new one</p>
             
             <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
                 <span><label>Login Email: <input type="email" name="email" value="<?php echo $_SESSION['studentemail']?>"></label></span><br>
                 <?php echo $new_email_error ?>
                 <input type="submit" name="change_email_submit" class="btn-pink sbutton">
             </form>
            <hr class="breaker">             
             <!-- ==================================
                   Change Password
             ==================================-->
             <h3>Change your password</h3>
             <p>Edit your password and create a new one</p>
             
             <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
                 <span><label>Current Password: <input type="password" name="current_password"></label></span><br>
                 <?php echo $new_password ?>
                 <span><label>New Password: <input type="password" name="new_password_1" id="new_password_1" class="strength"></label></span><br>
                 <span><label>Confirm New Password: <input type="password" name="new_password_2"></label></span><br>
                 <?php echo $password_match ?>
                 <input type="submit" name="change_password_submit" value="Save Changes" class="btn-pink sbutton">
             </form>
            <hr class="breaker">         
             <!-- ==================================
                   Delete Account
             ==================================-->
             <h3>Delete your account</h3>
             <p>Deleting your account will remove  all of your snippets, likes, and personal information. Proceed with caution!</p>
             
             <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
                 <span><label>Enter your current password: <input type="password" name="current_password"></label></span><br>
                 <?php echo $password_error_delete_account ?>
                 <a href="#" id="delete_account_trigger" class="btn-pink sbutton">Delete Account</a>
                 <p class="delete_account_hidden">Are you sure you want to delete your account? You'll lose your account and all of your snippets.</p>
                 <input type="submit" name="delete_account_submit" value="Delete Account" class="delete_account_hidden btn-blue sbutton">
             </form>
            <hr class="breaker">   
         </section><!-- /right column -->
    </div>
</div>
</div>

<script>
    $(document).ready(function() {
        password_strength('#new_password_1');
        $('#delete_account_trigger').click(function() {
            $('.delete_account_hidden').show(); 
        });
    });
</script>

<?php require_once 'footer.php'; ?>

<?php require_once 'foot.php'; ?>