
<!-- ==========Forgot Password=========-->

<div id="forgot_password_modal">
    <h3>Forgot your password?</h3>
    <p>Don't panic! Send us an email and get you a temporary password!</p>
    <a href="mailto:creativeuca@gmail.com?subject=forgot%20password">Send Message</a>
    <!--
<form>
        <input type="email" name="forgot_password_email" placeholder="enter your email address">
        <input type="submit" name="forgot_password_submit" value="Send">
    </form>
-->
    <a href="#" onclick="$('#forgot_password_modal').trigger('close');">Cancel</a>
</div>

<!-- ==========Registration Step 2=========-->

<div id="registration_modal">
    <h3>Registration</h3>
    <p>This info will help us to make sure that your work is in the right place!</p>
    
    <form action="register.php" method="post">
        <fieldset>
            <legend>Personal</legend>
            <br>
            <?php  if (isset($_POST['registration_2_trigger'])) { ?>
            <input type="hidden" name="from_page" value="<?php echo $_SERVER['SCRIPT_NAME']; ?>">
            <input type="hidden" name="registration_email" value="<?php echo $_SESSION['registration_email']; ?>">
            <input type="hidden" name="registration_password" value="<?php echo $_SESSION['registration_password']; ?>">
            <input type="hidden" name="registration_salt" value="<?php echo $_SESSION['registration_salt']; ?>">
            <?php } ?>
            <span class="firstlabel"><label>First Name<input type="text" name="registration_firstname" required></label></span><br>
            <span><label>Last Name<input type="text" name="registration_lastname" required></label></span><br>
            <span><select name="registration_creative_title" id="registration_creative_title_select">
                <option value="0">Select your title</option>
                <?php
                   $sql = "SELECT CreativeTitle, CreativeTitleID FROM creativetitle";
                            
                    $result = mysql_query($sql) or die ($sql. '-error' .mysql_error());
                    while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
                       echo '<option value="'.$row['CreativeTitleID'].'">'. $row['CreativeTitle'] . '</option>';
                    }
                ?>
            </select></span>
        </fieldset>
        <fieldset>
            <legend>Academic</legend>
            <br>
            <span><select name="registration_school" id="registration_school_select" class="selectElem">
                <option value="0">Select your school</option>
                <?php
                   $sql = "SELECT SchoolName, SchoolID FROM school";
                            
                    $result = mysql_query($sql) or die ($sql. '-error' .mysql_error());
                    while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
                       echo '<option value="'.$row['SchoolID'].'">'. $row['SchoolName'] . '</option>';
                    }
                ?>
            </select></span><br>
            
            <input type="hidden" value="0" id="school_value">
            
            <span><select name="registration_program" id="registration_program_select" class="selectElem">
            
                <option value="0">Select your program</option>
                <?php
                   $sql = "SELECT ProgramName as pName, ProgramID FROM program";
                            
                    $result = mysql_query($sql) or die ($sql. '-error' .mysql_error());
                    while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
                       echo '<option value="'.$row['ProgramID'].'">'. $row['pName'] . '</option>';
                    }
                ?>
            </select></span><br>
            
            <span><select name="registration_gradyear" id="registration_gradyear_select" class="selectElem">
                <option value="0">Select your grad year</option>
                <?php
                    $sql = "SELECT GradYear, GradYearID FROM gradyear";
                            
                    $result = mysql_query($sql) or die ($sql. '-error' .mysql_error());
                    while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
                       echo '<option value="'.$row['GradYearID'].'">'. $row['GradYear'] . '</option>';
                    }
                ?>
            </select></span><br>
        </fieldset>
        <p>If your school isn't on the list, please <a href="#">send us an email</a> we'll look at adding it!</p><br>
        <a href="#" onclick="$('#registration_modal').trigger('close');">Cancel</a>
        <input type="submit" name="registration_submit" id="registration_submit" value="Done" class="btn-pink">
    </form>
</div>

<!-- ==========Add a snippet=========-->

<div id="add_snippet_modal">
<?php 
    $SID = $_SESSION['SID'];
    
    //randnumber/intcaptcha used to see if form was submitted with refresh or submit button
    if (isset($_POST['add_snippet_submit']) && $_POST['randnumber']==$_SESSION['intcaptcha']) {
        $sTitle = mysql_real_escape_string(trim($_POST['snippet_title']));
        $sDescription = mysql_real_escape_string(trim($_POST['snippet_description']));
        $sURL = mysql_real_escape_string(trim($_POST['snippet_url']));
        //$tags = $_POST['snippet_tag_chk'];
        
        $valid = true;
        if ($sTitle == '') {    //  was the username empty?
            $valid = false;
        }
        
        if (!$valid) {
                exit;
            } else {
            $sql = "INSERT INTO snippet (
                                   SnippetTitle,
                                   SnippetDescription,
                                   SnippetURL,
                                   SID
                               ) VALUES (
                                   '$sTitle',
                                   '$sDescription',
                                   '$sURL',
                                   '$SID')";


            $result = $db->query($sql);
            $new_id = $result->insertID();
            
            $upload_failure_warning = '';
            $allowed_extensions = array('jpg', 'jpeg', 'gif', 'png');
            
            $uploaded_picture = basename($_FILES['snippet_image']['name']);
            $uploaded_thumb = basename($_FILES['snippet_thumb']['name']);
            
            $ext_pic = substr($uploaded_picture, strrpos($uploaded_picture, '.') + 1);
            $ext_thumb = substr($uploaded_thumb, strrpos($uploaded_thumb, '.') + 1);
            
            if (in_array($ext_pic, $allowed_extensions) && in_array($ext_thumb, $allowed_extensions)) {
                //it's a legal file
                $target_path_picture = $_SERVER['DOCUMENT_ROOT'] . URL_ROOT . 'img/student_uploads/'.$SID.'/'.$new_id.'/snippet_image';
                $target_path_thumb = $_SERVER['DOCUMENT_ROOT'] . URL_ROOT . 'img/student_uploads/'.$SID.'/'.$new_id.'/snippet_thumb';
                mkdir($target_path_picture, 0755, true);
                mkdir($target_path_thumb, 0755, true);
                $uploaded_picture = str_replace( ' ', '_', $uploaded_picture);
                $uploaded_thumb = str_replace( ' ', '_', $uploaded_thumb);
                
                if (move_uploaded_file($_FILES['snippet_image']['tmp_name'], $target_path_picture . '/' . $uploaded_picture)) {
                    $sql = "UPDATE snippet SET SnippetPictureFile='$uploaded_picture' WHERE SnippetID=$new_id";
                    $result = $db->query($sql);
                } else {
                    $upload_failure_warning = '. However, the image failed to upload. You may want to re-attempt the upload.';
                }
                if (move_uploaded_file($_FILES['snippet_thumb']['tmp_name'], $target_path_thumb . '/' . $uploaded_thumb)) {
                    $sql = "UPDATE snippet SET SnippetThumbnailFile='$uploaded_thumb' WHERE SnippetID=$new_id";
                    $result = $db->query($sql);
                } else {
                    $upload_failure_warning = '. However, the thumbnail failed to upload. You may want to re-attempt the upload.';
                }
                
            } else {
                $upload_failure_warning = '. That type of file is not allowed - upload was stopped.';
            }
            if (isset($_POST['snippet_tag_chk'])) {
                for ($i=0;$i<count($_POST['snippet_tag_chk']);$i++) {
                    $sql = "INSERT INTO snippettag (SnippetID,TagID) VALUES ($new_id, ".$_POST['snippet_tag_chk'][$i].")";
                    $result = $db->query($sql);
                }
            } else {
                echo '<h3>Tags not set</h3>';
            }
            //header('Location: index.php');
            //  output the results message, including the contents of $upload_failure_warning
                echo "<h3>You've successfully uploaded a snippet called: $sTitle</h3>";
        }
    }
    // make random number and store is session variable. Used when stopping form from submitting on refresh.
    $randNo = md5(mt_rand(0,10));
    $_SESSION['intcaptcha']= $randNo;
?>
	<h1>Add Snippet</h1>
    <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" enctype="multipart/form-data">
        <label for="snippet_image">Choose the full-sized snippet image (600px x 400px)<input type="file" name="snippet_image" required></label><br>
        <label for="snippet_thumb">Choose the snippet thumbnail (300px x 300px)<input type="file" name="snippet_thumb" required></label><br>
        <!-- TODO: add in thumbnail field -->
        <span><label>Snippet Title<input type="text" name="snippet_title" required></label></span><br>
        <span><label>Snippet Description<textarea name="snippet_description"></textarea></label></span><br>
        <span><label>Snippet Link<input type="url" name="snippet_url" placeholder="Is there a URL where people can find out more about this project?"></label></span><br>
        <?php
            $sql = "SELECT TagID, TagName FROM tags";
            $result = $db->query($sql); // run the query
            while ($row = $result->fetch()) {
                echo "<input class='checkbox' type='checkbox' name='snippet_tag_chk[]' value=".$row['TagID'].">".$row['TagName'];
            }
        ?>
        <br>
        <!-- used as part of stopping the form from resubmitting on page refresh -->
        <input type="hidden" name="randnumber" value="<?php echo $_SESSION['intcaptcha']?>">
        <input type="submit" name="add_snippet_submit" value="Snip it!" class="btn-pink">
    </form>
</div>

<!-- ==========Snippet view=========-->
<!-- This will grab the information from the database and it will populate the html created in the index.php -->


<div id="snippet_view">

    <?php
    //use this database
    require_once 'includes/MySQL.php'; // the mysql classes
    require_once 'includes/db-local.php'; // connects the database here
    
    $db = new MySQL($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['database']);// and here
    $where = '';
    if (($_SERVER['PHP_SELF'] == '/profile.php') && isset($_GET['id'])) {
        $where = 'WHERE students.SID='.$_GET['id'];
    }
    if (isset($_GET['tag'])) {
        $where = 'WHERE snippettag.TagID='.$_GET['tag'];
        echo $showfilterpanel;
    }
    if (isset($_GET['program'])) {
        $where = 'WHERE students.ProgramID='.$_GET['program'];
        echo $showfilterpanel;
    }
    if (isset($_GET['gradyear'])) {
        $where = 'WHERE students.GradYearID='.$_GET['gradyear'];
        echo $showfilterpanel;
    }
    ?>
    
<?php 

    // grab the selected info fromt this tables in the database
    $sql = "SELECT DISTINCT students.SID as SID, StudentFirstName as fname, StudentLastname as lname, snippet.SnippetID as snippetID, SnippetLikes, SnippetPictureFile as pic, SnippetTitle as title, SnippetURL as URL, ProgramName as pName, GradYear, SchoolName, SnippetDescription as descr
    FROM snippet
    INNER JOIN students ON (students.SID = snippet.SID)
    LEFT JOIN program ON (program.ProgramID = students.ProgramID)
    LEFT JOIN school ON (school.SchoolID = students.SchoolID)
    LEFT JOIN gradyear ON (gradyear.GradYearID = students.GradYearID)
    INNER JOIN snippettag ON (snippettag.SnippetID = snippet.SnippetID)
    $where
    ORDER BY snippet.SnippetID DESC";
    
    
    //loop it in a while  
    //userView.php?viewID='.$data["SnippetID"].'     
    $result = mysql_query($sql) or die ($sql. '-error' .mysql_error());
    $db_count2 = mysql_num_rows($result);
    $counter = 0;
    while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
        echo '<div class="snippet_view_container" id="sn'.$counter.'">
                <h1>'.$row['title'].'</h1><br>
                <img src="img/student_uploads/'.$row['SID'].'/'.$row['snippetID'].'/snippet_image/'.$row['pic'].'"  alt="'.$row['descr'].'"><br>
                <div class="snippet_view_bottom"><p>'.$row['descr'].'</p><br>
                <p>Link to Snippet: <a href="'.$row['URL'].'"name="snippet_url">'.$row['title'].'</a></p><br>
                <div id="snippet_user_data">
                    <h2><a href="profile.php?id='.$row['SID'].'">'.$row['fname']. ' ' .$row['lname'].'</a></h2><br>
                    <h3>'.$row['pName'].' '.$row['GradYear'].'</h3><br>
                    <h4>'.$row['SchoolName'].'</h4><br>
                    <a href="'.$_SERVER['PHP_SELF'].'?id='.$row['SID'].'&addlike='.$row['snippetID'].'" class="snippet_likes">'.$row['SnippetLikes'].'</a>
                </div>
                </div>
            </div>';
        $counter++;
    }
?>    
  
</div>
<!--
 <?php
    $sql2 = "SELECT SnippetID FROM snippet";
    $result = mysql_query($sql2) or die ($sql2. '-error' .mysql_error());
    
?> 
-->


<!-- ==========Triggers modals go here=========-->

<script type="text/javascript">

$(document).ready(function(){
    trigger_modals('#forgot_password_trigger', '#forgot_password_modal');
    trigger_modals('#add_snippet_modal_trigger', '#add_snippet_modal');
    
    for (var mycount = 0; mycount<<?php echo $db_count2; ?>; mycount++){
    
         snippetnum = "#snippet_view_trigger"+mycount;
         snippetcontent = "#sn"+mycount;
         trigger_modals(snippetnum, snippetcontent);

         
    }
    
    //trigger_modals('#snippet_view_trigger0','#snippet_view');
    //trigger_modals('#snippet_view_trigger1','#snippet_view');
    checkOptions();
});


$("select").change(checkOptions);

function checkOptions() {
  var okay = false;
  $("select").each(function(index, element) {
    if ( $(element).val() == "0" ) {
      okay = true;
    }
  });

  if (okay) {
    $("#registration_submit").attr("disabled","disabled");
  } else {
    $("#hidden-div").hide();
    $("#registration_submit").removeAttr("disabled");
  };
}





</script>