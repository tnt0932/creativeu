<?php
    session_start();
    if (!isset($_GET['id'])) {
        if (isset($_SESSION['logged_in_user'])) {
            header ('Location: profile.php?id='.$_SESSION['SID']);
        } else {
            header ('Location: index.php?error=selectuser');
        }
        
    }
    //use this database
    require_once 'includes/MySQL.php'; // the mysql classes
    require_once 'includes/db-local.php'; // connects the database here

    $db = new MySQL($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['database']);// and here

    if (isset($_GET['id'])) {
        $sql = "SELECT SID FROM students";
        $result = mysql_query($sql) or die ($sql. '-error' .mysql_error());
        $idexists = false;
        while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
            if ($_GET['id'] == $row['SID']) { $idexists = true; }
        };
        if (!$idexists) { header ('Location: index.php?nouser=true');}
    }
    if (isset($_GET['addlike'])) {
        if (!isset($_COOKIE[$_GET['addlike']])){
       
            setcookie($_GET['addlike'],$_GET['addlike'], time()+2630000);
            $sql = "SELECT SnippetLikes FROM snippet WHERE SnippetID=".$_GET['addlike'];
            $result = mysql_query($sql) or die ($sql. '-error' .mysql_error());
            while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
                $likes = $row['SnippetLikes'];
            };
            $sql = "UPDATE snippet SET SnippetLikes=$likes+1 WHERE SnippetID=".$_GET['addlike'];
            $result = mysql_query($sql) or die ($sql. '-error' .mysql_error());
        }
    }
    if (isset($_GET['del'])) {
        $sql = "DELETE FROM snippet WHERE SnippetID=".$_GET['del'];
        $result = mysql_query($sql) or die ($sql. '-error' .mysql_error());
        $sql = "DELETE FROM snippettag WHERE SnippetID=".$_GET['del'];
        $result = mysql_query($sql) or die ($sql. '-error' .mysql_error());
        echo '<span class="alert">The snippet has been deleted!</span>';
    }
    if (isset($_GET['deleteprofile'])) {
        $sql = "DELETE FROM students WHERE SID=".$_GET['id'];
        $result = mysql_query($sql) or die ($sql. '-error' .mysql_error());
        $sql = "DELETE FROM snippet WHERE SID=".$_GET['id'];
        $result = mysql_query($sql) or die ($sql. '-error' .mysql_error());
        header('Location: index.php?deleteduser=true');
    }
    
    require_once 'head.php';
    
    $sql = "SELECT SID, StudentFirstName as fname, StudentLastName as lname, StudentPicture as pic, ProgramName as program, SchoolName as school, GradYear as gradyear, StudentFacebook as facebook, StudentTwitter as twitter, StudentLinkedIn as linkedin, StudentWebsite as website, StudentDescription as description, CreativeTitleID as ctid, StudentPublicEmail FROM students 
        INNER JOIN program ON students.ProgramID=program.ProgramID
        INNER JOIN school ON students.SchoolID=school.SchoolID
        INNER JOIN gradyear ON students.GradYearID=gradyear.GradYearID
        WHERE SID=".$_GET['id'];
    $result = $db->query($sql);
    if ($result->size() == 1) { // was one record returned?
        while ($row = $result->fetch()) {
            $studentID = $row['SID'];
            $fname = $row['fname'];
            $lname = $row['lname'];
            $pic = $row['pic'];
            $school = $row['school'];
            $program = $row['program'];
            $gradyear = $row['gradyear'];
            $twitter = $row['twitter'];
            $facebook = $row['facebook'];
            $linkedin = $row['linkedin'];
            $ctid = $row['ctid'];
            $desc = $row['description'];
            $url = $row['website'];
            $StudentPublicEmail = $row['StudentPublicEmail'];
        }
    }
    
    if (isset($_POST['profile_submit'])) {
        $SID = $_SESSION['SID'];
        
        
        $upload_failure_warning = '';
        $allowed_extensions = array('jpg', 'jpeg', 'gif', 'png');
        
        $uploaded_picture = basename($_FILES['profile_image']['name']);
        
        $ext = substr($uploaded_picture, strrpos($uploaded_picture, '.') + 1);
        
        if (in_array($ext, $allowed_extensions)) {
            // it's a legal profile picure file
            $target_path_picture = $_SERVER['DOCUMENT_ROOT'] . URL_ROOT . 'img/student_uploads/'.$SID.'/profile_image';
            if (!is_dir($target_path_picture)) {
            // if there isnt a folder for the user, creates one
                mkdir($target_path_picture, 0755, true);
            }
            // now, upload the image
            $uploaded_picture = str_replace( ' ', '_', $uploaded_picture);


            // edit a profile picture and success
            if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $target_path_picture . '/' . $uploaded_picture)) {
                $sql = "UPDATE students SET StudentPicture='$uploaded_picture' WHERE SID=".$SID;
                $result = $db->query($sql);
            } else {
            // edit a profile picture and fails
                $upload_failure_warning = '. However, the image failed to upload. You may want to re-attempt the upload.';
            }
        
        // it's not a valid file
        } else {
            $upload_failure_warning = '. That type of file is not allowed. You can only upload .jpg, .jpeg, .gif and .png files.';
        }
        
        // load session with student profile pic
        $_SESSION['studentpic'] = $uploaded_picture;
    }
?>
<!-- ==================================
            MODIFIES THE DATABASE
==================================-->

<?php
        
    if (isset($_POST['profile_submit'])) { // USER HAS JUST SUBMITTED THE FORM
        $fname = mysql_real_escape_string(trim($_POST['fname']));
        $lname = mysql_real_escape_string(trim($_POST['lname']));
        $facebook = mysql_real_escape_string(trim($_POST['facebook']));
        $twitter = mysql_real_escape_string(trim($_POST['twitter']));
        $linkedin = mysql_real_escape_string(trim($_POST['linkedin']));
        $student_description = mysql_real_escape_string(trim($_POST['student_description']));
        $StudentPublicEmail = mysql_real_escape_string(trim($_POST['StudentPublicEmail']));
        $portfolio_website = mysql_real_escape_string(trim($_POST['portfolio_website']));
        $creativeTitleID = mysql_real_escape_string(trim($_POST['creative_title_select']));

        
                    
        $sql = "UPDATE students SET
                    StudentFacebook='$facebook',
                    StudentTwitter='$twitter',
                    StudentLinkedin='$linkedin',
                    StudentDescription='$student_description',
                    StudentPublicEmail='$StudentPublicEmail',
                    StudentWebsite='$portfolio_website',
                    StudentFirstName='$fname',
                    StudentLastName='$lname',
                    CreativeTitleID=$creativeTitleID
                WHERE SID=".$studentID;
        
        $result = $db->query($sql);
        
        echo"<span class='alert'>Hey, $fname! You've successfully updated your profile!</span>";
        
    }   
?>

    
<?php require_once 'header.php'; ?>

<div id="container">
<!-- ==================================
               Welcome Alert
     ==================================-->
     <?php if (isset($_SESSION['SID']) && ($_SESSION['SID'] == $_GET['id'])) { ?>
    <!-- logged in user msg -->
    <aside id="welcome_alert" class="col span_12_of_12">
        <h1 class="welcome-alert">Welcome to your profile.</h1><br><h2>Here you can see and edit your personal information or your snippets.You can complete your profile now and add your first portfolio piece, or skip it and come back later.</h2>
        <?php  if (($_SESSION['SID'] == $_GET['id']) && !isset($_GET['edit'])) {
              ?>
        <a href="<?php echo $_SERVER['REQUEST_URI'].'&edit=true';?>" class="btn-blue">Edit Profile</a>
        <?php } ?>
    </aside>
    <?php } 
    if (isset($_SESSION['admin_logged_in_user']) && !isset($_GET['edit'])) { ?>
        <a href="<?php echo $_SERVER['REQUEST_URI'].'&edit=true';?>" class="btn-blue">Edit Profile</a><br><br>
         <a href="#" id="del_profile_btn" class="btn-pink">Delete Profile</a>
        <p id="del_profile_confirm">Are you sure? This will delete the user and all of their snippets. <a href="<?php echo $_SERVER['REQUEST_URI'].'&deleteprofile=true';?>">Yes, Delete Profile</a></p>
    <?php } ?>
    
    <div id="content" class="section group">
        <form action="<?php echo $_SERVER['PHP_SELF'].'?id='.$studentID; ?>" method="post" enctype="multipart/form-data"><!-- start form -->
        <!-- ==================================
                         Left Column
             ==================================-->
             
            
            
            <div id="profileleft">
            <section class="col span_3_of_12">
                <?php if ($pic == NULL) { ?>
                <img src="img/default_profile.jpg" width="250" height="250" alt="Profile Image">
                <?php } else {
                echo '<img src="img/student_uploads/'.$studentID.'/profile_image/'.$pic.'" alt="'.$fname.' '.$lname.'\'s Profile Picture" width="250" height="250">';
                } ?>
                <br>
                
                <?php if (isset($_GET['edit'])) { ?>
                <input type="file" name="profile_image" id="student_profile_img"><br>
                <?php } ?>
                
                <div id="profile-social-media">

                <!-- FACEBOOK -->
                <div class="profile-social-media">
                <label id="student_facebook_label">
                <img src="img/icon-profilefb.png">
                
                <?php if (!isset($_GET['edit'])) { ?>
                <a href="<?php echo $facebook ?>"><?php echo $facebook ?></a>
                <?php } else { ?>
                <input type="text" name="facebook" id="student_facebook" class="profile-txtarea" placeholder="Enter your Facebook profile URL" value="<?php echo $facebook ?>" <?php if (!isset($_GET['edit'])) {  echo 'disabled'; }?>>
                <?php } ?>
                
                </label><br>
                </div>
                
                <!-- TWITTER -->
                <div class="profile-social-media">
                <label id="student_twitter_label">
                <img src="img/icon-profiletwitter.png">
                <?php if (!isset($_GET['edit'])) { ?>
                <a href="http://twitter.com/<?php echo $twitter ?>">@<?php echo $twitter ?></a>
                <?php } else { ?>
                <input type="text" name="twitter" id="student_twitter" class="profile-txtarea" placeholder="Enter your Twitter handle (ex. @creativeu)" value="<?php echo $twitter ?>" <?php if (!isset($_GET['edit'])) {  echo 'disabled'; }?>>
                <?php } ?>
                </label><br>
                </div>
                
                <div class="profile-social-media">
                <label id="student_linkedin_label">
                <img src="img/icon-profilelinkedin.png">
                <?php if (!isset($_GET['edit'])) { ?>
                <a href="<?php echo $linkedin ?>"><?php echo $fname.' '.$lname ?></a>
                <?php } else { ?>
                <input type="text" name="linkedin" id="student_linkedin" class="profile-txtarea" placeholder="Enter your LinkedIn URL" value="<?php echo $linkedin ?>" <?php if (!isset($_GET['edit'])) {  echo 'disabled'; }?>>
                <?php } ?>
                </label>
                </div>
                </div>
                <input type="hidden" name="SID" value="<?php echo $studentID ?>"> 
                
            </section>
            </div>
            
            
        <!-- ==================================
                       Right Column
             ==================================-->
			<div id="profileright">
             <section class="col span_9_of_12">  
            <!-- start of student info -->
                 <h1 id="profile-name"><?php echo $fname.' '.$lname ?></h1>
            <hr class="breaker"> 
                 <!-- <h3 id="profile-creative">[creative title]</h3> -->
                 <?php if (isset($_GET['edit'])) { ?>
                    <input type="text" name="fname" id="fname" class="profile-txtarea" value="<?php echo $fname ?>" <?php if (!isset($_GET['edit'])) {  echo 'disabled'; }?>>
                 <?php } ?>
                 <?php if (isset($_GET['edit'])) { ?> 
                    <input type="text" name="lname" id="lname" class="profile-txtarea" value="<?php echo $lname ?>" <?php if (!isset($_GET['edit'])) {  echo 'disabled'; }?>>
                 <?php } ?>

                 <?php if (!isset($_GET['edit'])) { 
                    $sql = "SELECT CreativeTitle, CreativeTitleID FROM creativetitle";
                    $result = mysql_query($sql) or die ($sql. '-error' .mysql_error());
                    while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
                       if ($row['CreativeTitleID'] == $ctid) {
                           echo '<h4 class="bold">'. $row['CreativeTitle'] . '</h4>';
                       } 
                    }
                 ?>
                 <?php } else { ?>
                 <select name="creative_title_select" <?php if (!isset($_GET['edit'])) {  echo 'disabled'; }?>>
                 <?php
                    $sql = "SELECT CreativeTitle, CreativeTitleID FROM creativetitle";
                            
                    $result = mysql_query($sql) or die ($sql. '-error' .mysql_error());
                    while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
                       if ($row['CreativeTitleID'] == $ctid) {
                           echo '<option value="'.$row['CreativeTitleID'].'" selected>'. $row['CreativeTitle'] . '</option>';
                       } else {
                           echo '<option value="'.$row['CreativeTitleID'].'">'. $row['CreativeTitle'] . '</option>';
                       }
                    }
                 ?>
                </select>
                <?php } ?>
                
                
                 <h4 id="profile-school"><?php echo $program .' at '.$school . ', '.$gradyear?></h4>
                 
                 <!-- <div id="total_student_likes"><img src="http://placehold.it/30x30" width="30" height="30"></div> -->
                <?php if (!isset($_GET['edit'])) { ?>
                <p><?php echo $desc ?></p>
                <?php } else { ?>
                
                <textarea name="student_description" id="student_description" class="profile-txtarea" placeholder="Tell the world about yourself in 140 characters or less!" <?php if (!isset($_GET['edit'])) {  echo 'disabled'; }?>><?php echo $desc ?></textarea>
                <?php } ?>
                <?php if (isset($_GET['edit'])) {?>
                <p id="chars_left_txt">You have <span id="charsLeft"></span> chars left.</p>
                <?php } ?>
                <br>
                
                <label id="student_public_email_label">
                    <img src="img/icon-email.png">
                    <?php if (!isset($_GET['edit'])) { ?>
                <a href="<?php echo $StudentPublicEmail ?>"><?php echo $StudentPublicEmail ?></a>
                <?php } else { ?>
                    <input type="text" name="StudentPublicEmail" id="StudentPublicEmail" class="profile-txtarea" placeholder="Enter a public email" value="<?php echo $StudentPublicEmail ?>" <?php if (!isset($_GET['edit'])) {  echo 'disabled'; }?>>
                <?php } ?>
                 </label><br>
                 
                 
                 <!-- student website -->
                 <label id="student_portfolio_website_label">
                    <img src="img/icon-website.png">
                </label>
                    <?php if (!isset($_GET['edit'])) { ?>
                    <a href="<?php echo $url ?>"><?php echo $url ?></a>
                    <?php } else { ?>
                     <input type="text" name="portfolio_website" id="student_portfolio_website" class="profile-txtarea" placeholder="Enter your portfolio website url" value="<?php echo $url ?>">
                    <?php } ?>
                 </label>
			<hr class="breaker">
             
             
             <!-- ==================================
                       Real snippet stream
             ==================================-->

             <section id="snippet_stream_container">
     
        <?php
        /*
require_once('paginator.class.php');
    
        $sql = "SELECT * FROM snippet";
        $resSql = mysql_query($sql) or die ($sql. '-error' .mysql_error());
        $db_count = mysql_num_rows($resSql);
        $pages = new Paginator;
        $pages->items_total = $db_count;
        $pages->items_per_page = 20;
        $pages->mid_range = 7;
        $pages->paginate(); //echo $pages->display_pages();
*/

        $sql = "SELECT DISTINCT students.SID, snippet.SnippetID as snippetID, SnippetDescription as descr, SnippetLikes, SnippetThumbnailFile as thumb, SnippetPictureFile
                FROM snippet
                INNER JOIN students ON (snippet.SID = students.SID)
                WHERE students.SID = $studentID
                ORDER BY snippet.SnippetID DESC";
                
        $result = mysql_query($sql) or die ($sql. '-error' .mysql_error());
        $db_count2 = mysql_num_rows($result);
        $mycounter = 0;
        $delete_link = '';
        
        
        while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
            if (isset($_SESSION['admin_logged_in_user']) || isset($_SESSION['SID']) && (($_SESSION['SID'] == $_GET['id']))) {
                $delete_link = '<a href="profile.php?id='.$row['SID'].'&del='.$row['snippetID'].'" class="delete_btn"></a>';
            }
           echo '<div class="snippet_item">
               <a href="#" id="snippet_view_trigger'.$mycounter.'">
                   <img class="snippet_image_stream"  src="img/student_uploads/'.$row['SID'].'/'.$row['snippetID'].'/snippet_thumb/'.$row['thumb'].'" width="230" height="230" alt="'.$row['descr'].'">
               </a>
               <a href="profile.php?id='.$row['SID'].'&addlike='.$row['snippetID'].'" class="heart">'.$row['SnippetLikes'].'</a>'.
               $delete_link.'
               </div>
           ';
           $mycounter++;
        }
        /*
echo '<div id="pagination" style="visibility:hidden">';
        echo $pages->display_pages();
        echo '</div>';
*/
        //echo "Page $pages->current_page of $pages->num_pages";
        //echo $pages->display_jump_menu();
        //echo $pages->display_items_per_page();
        ?>

        <!-- ==================================
                       bottom bar + cancel button
             ==================================-->
             
             </section><!-- /right column -->
             </div>
             <?php if (isset($_GET['edit'])) { ?>
             <section id="profile_form_submit_footer">
             
                 <input type="submit" name="profile_submit" value="submit"> <a href="#">Cancel</a>
             </section>
             <?php } ?>
             
             
             
        </form>
    </div>
</div>

                
</section>

<?php require_once 'footer.php'; ?>

<!-- ==================================
            Scripts
==================================-->

<script src="js/jquery.limit.js" type="text/javascript"></script>
<script type="text/javascript">

    $('#del_profile_btn').click(function() {
        $('#del_profile_confirm').show();
    });
    $('#student_description').limit('140', '#charsLeft');

</script>

<?php require_once 'foot.php'; ?>