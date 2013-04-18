<?php 
session_start();	
require_once 'head.php'; 
?>

<?php 
    require_once 'header.php';
    require_once('includes/MySQL.php');
    require_once('includes/db-local.php');
    
    
    $db = new MySQL($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['database']);

	if (!isset($_SESSION['admin_logged_in_user'])) {
?>
<div class="adminform">
<form action="adminlogin.php" method="post">
<label for='u'>Admin Email:</label><br><input id='u' type="text" size="26" name="AdminEmail"><br>
<label for='p'>Password:</label><br><input id='p' type="password" size="26" name="AdminPassword"><br>
<input type="hidden" name="from_page" value="<?php echo $_SERVER['SCRIPT_NAME']; ?>">
<input type="submit" value="Log in">
</form>
</div>

<?php
	}else {
?>


<div id="container">
    
    <div id="content" class="section group">
          
        <section class="col span_12_of_12">
        <!-- ==================================
                       Admin Info
             ==================================-->

             		<div>
						<h1><?php echo $_SESSION['admin_logged_in_user']; ?>'s Dashboard</h1>
                     </div>


                 <!-- ==================================
                      Programs (Add)
                 ==================================-->

            <div class="addform">
                <form method="post" id="add-contact-form" >
                <fieldset>
                <div class="row">
                <h3>Update Programs</h3><br>
                <input type="text" id="addprogram" name="ProgramName" size="80" maxlength="80"><input type="submit" id="submit" name="programsubmit" value="Add" class="btn-blue">
                </div>
                </fieldset>
                </form>

                <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
<?php
        		$sql = "SELECT ProgramName, ProgramID FROM program";
                            
        		$result = mysql_query($sql) or die ($sql. '-error' .mysql_error());
                
                while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
                echo '<li>
                          <div class="editfield">
                          <input type="hidden" name="ProgramIDs[]" value="'.$row['ProgramID'].'">
                          <input type="text" size="30" name="ProgramNames[]" value="'.$row['ProgramName'].'">
                          </div>
                      </li>';


            }   
?>
            <input type="submit" name="editprogramsubmit" value="Save Changes">     
            <hr class="breaker">
            </div>

                 <!-- ==================================
                      Schools (Add)
                 ==================================-->
            <div class="addform">
                <form method="post" id="add-contact-form" >
                <fieldset>
                <div class="row">
                <h3>Update Schools</h3><br>
                <input type="text" id="addschool" name="SchoolName" size="80" maxlength="80"><input type="submit" id="submit" name="schoolsubmit" value="Add" class="btn-blue">
                </div>
                </fieldset>
                </form>     

                <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
<?php
        		$sql = "SELECT SchoolName, SchoolID FROM school";
                            
        		$result = mysql_query($sql) or die ($sql. '-error' .mysql_error());
                
                while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
                echo '<li>
                          <div class="editfield">
                          <input type="hidden" name="SchoolIDs[]" value="'.$row['SchoolID'].'">
                          <input type="text" size="50" name="SchoolNames[]" value="'.$row['SchoolName'].'">
                          </div>
                      </li>';


            }
?>
            <input type="submit" name="editschoolsubmit" value="Save Changes">     
            <hr class="breaker">
            </div>

                 <!-- ==================================
                      Grad Year (Add)
                 ==================================-->
            <div class="addform">
                <form method="post" id="add-contact-form" >
                <fieldset>
                <div class="row">
                <h3>Update Grad Years</h3><br>
                <input type="text" id="addgyear" name="GradYear" size="80" maxlength="80"><input type="submit" id="submit" name="gyearsubmit" value="Add" class="btn-blue">
                </div>
                </fieldset>
                </form>

                <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
<?php
        		$sql = "SELECT GradYear, GradYearID FROM gradyear";
                            
        		$result = mysql_query($sql) or die ($sql. '-error' .mysql_error());
                
                while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
                echo '<li>
                          <div class="editfield">
                          <input type="hidden" name="GradYearIDs[]" value="'.$row['GradYearID'].'">
                          <input type="text" size="5" name="GradYears[]" value="'.$row['GradYear'].'">
                          </div>
                      </li>';


            }
?>
            <input type="submit" name="editgyearsubmit" value="Save Changes">
            </form>
            <hr class="breaker">
            </div>



                 <!-- ==================================
                      Creative Role (Add)
                 ==================================-->
            <div class="addform">
                <form method="post" id="add-contact-form" >
                <fieldset>
                <div class="row">
                <h3>Update Creative Roles</h3><br>
                <input type="text" id="addgcrole" name="CreativeTitle" size="80" maxlength="80"><input type="submit" id="submit" name="crolesubmit" value="Add" class="btn-blue">
                </div>
                </fieldset>
                </form>
                
                <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
<?php
        		$sql = "SELECT CreativeTitle, CreativeTitleID FROM creativetitle";
                            
        		$result = mysql_query($sql) or die ($sql. '-error' .mysql_error());
                
                while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
                echo '<li>
                          <div class="editfield">
                          <input type="hidden" name="CreativeTitleIDs[]" value="'.$row['CreativeTitleID'].'">
                          <input type="text" size="30" name="CreativeTitles[]" value="'.$row['CreativeTitle'].'">
                          </div>
                      </li>';


            }
?>
			<input type="submit" name="editctitlesubmit" value="Save Changes">
            </form>
            <hr class="breaker">
            </div>

                 <!-- ==================================
                      Tags (Add)
                 ==================================-->
            <div class="addform">
                <form method="post" id="add-contact-form" >
                <fieldset>
                <div class="row">
                <h3>Update Tags</h3><br>
                <input type="text" id="addtag" name="TagName" size="80" maxlength="80"><input type="submit" id="submit" name="tagsubmit" value="Add" class="btn-blue">
                </div>
                </fieldset>
                </form>                </div>
                
            <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
<?php
                $sql = "SELECT TagName, TagID FROM tags";
                                
                $result = mysql_query($sql) or die ($sql. '-error' .mysql_error());
                
                while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
                echo '<li>
                          <div class="editfield">
                          <input type="hidden" name="TagIDs[]" value="'.$row['TagID'].'">
                          <input type="text" size="30" name="TagNames[]" value="'.$row['TagName'].'">
                          </div>
                      </li>';
                }
?>
            <input type="submit" name="edittagsubmit" value="Save Changes">
            </form>
            <hr class="breaker">
            </div>

                
             </section> 


    </div>
</div>

<?php 
		//======================================
		// 		EDITING DATA IN DATABASE
		//======================================

		//================PROGRAM================

if( isset($_POST['editprogramsubmit']) && is_array($_POST['ProgramNames'] )) {

  foreach($_POST['ProgramNames'] as $key=>$name){

    $ProgramID= $_POST['ProgramIDs'][$key];


    $sql='UPDATE program SET ProgramName="'.$name.'" WHERE ProgramID="'.$ProgramID.'" limit 1';
    // execute the query
    $result = mysql_query($sql) or die ($sql. '-error' .mysql_error());
  }

} 

		//================SCHOOL================

if( isset($_POST['editschoolsubmit']) && is_array($_POST['SchoolNames'] )) {

  foreach($_POST['SchoolNames'] as $key=>$name){

    $SchoolID= $_POST['SchoolIDs'][$key];


    $sql='UPDATE school SET SchoolName="'.$name.'" WHERE SchoolID="'.$SchoolID.'" limit 1';
    // execute the query
    $result = mysql_query($sql) or die ($sql. '-error' .mysql_error());
  }

} 

		//===============GRAD YEAR==============

if( isset($_POST['editgyearsubmit']) && is_array($_POST['GradYears'] )) {

  foreach($_POST['GradYears'] as $key=>$name){

    $GradYearID= $_POST['GradYearIDs'][$key];


    $sql='UPDATE gradyear SET GradYear="'.$name.'" WHERE GradYearID="'.$GradYearID.'" limit 1';
    // execute the query
    $result = mysql_query($sql) or die ($sql. '-error' .mysql_error());
  }

} 

		//=============CREATIVE TITLE============

if( isset($_POST['editctitlesubmit']) && is_array($_POST['CreativeTitles'] )) {

  foreach($_POST['CreativeTitles'] as $key=>$name){

    $CreativeTitleID= $_POST['CreativeTitleIDs'][$key];


    $sql='UPDATE creativetitle SET CreativeTitle="'.$name.'" WHERE CreativeTitleID="'.$CreativeTitleID.'" limit 1';
    // execute the query
    $result = mysql_query($sql) or die ($sql. '-error' .mysql_error());
  }

} 


		//=================TAGS=================

if( isset($_POST['edittagsubmit']) && is_array($_POST['TagNames'] )) {

  foreach($_POST['TagNames'] as $key=>$name){

    $tagId= $_POST['TagIDs'][$key];

    // validate and clean up sql injections from $tagId and $name

    $sql='UPDATE tags SET TagName="'.$name.'" WHERE TagID="'.$tagId.'" limit 1';
    // execute the query
    $result = mysql_query($sql) or die ($sql. '-error' .mysql_error());
  }

} 
?>



<?php 	
		//======================================
		// 		ADDING CONTENT TO DATABASE
		//======================================

		//=================PROGRAM=================

if (isset($_POST['programsubmit'])) {
        $ProgramName = $_POST['ProgramName'];

        $sql = "INSERT INTO program (ProgramName) VALUES ('$ProgramName')";
            
        $db = new MySQL($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['database']);
        
        $result = $db->query($sql); 
        $new_id = $result->insertID();
        
        };


		//=================SCHOOL=================
        
if (isset($_POST['schoolsubmit'])) {
        $School = $_POST['SchoolName'];

        $sql = "INSERT INTO school (SchoolName) VALUES ('$School')";
            
        $db = new MySQL($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['database']);
        
        $result = $db->query($sql); 
        $new_id = $result->insertID();
        
        };

		//=================GRAD YEAR=================	

if (isset($_POST['gyearsubmit'])) {
        $GradYear = $_POST['GradYear'];

        $sql = "INSERT INTO gradyear (GradYear) VALUES ('$GradYear')";
            
        $db = new MySQL($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['database']);
        
        $result = $db->query($sql); 
        $new_id = $result->insertID();
        
        };

		//==============CREATIVE TITLE===============

if (isset($_POST['crolesubmit'])) {
        $CreativeTitle = $_POST['CreativeTitle'];

        $sql = "INSERT INTO creativetitle (CreativeTitle) VALUES ('$CreativeTitle')";
            
        $db = new MySQL($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['database']);
        
        $result = $db->query($sql); 
        $new_id = $result->insertID();
        
        };
        
		//=================TAGS=================        
        
if (isset($_POST['tagsubmit'])) {
        $TagName = $_POST['TagName'];

        $sql = "INSERT INTO tags (TagName) VALUES ('$TagName')";
            
        $db = new MySQL($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['database']);
        
        $result = $db->query($sql); 
        $new_id = $result->insertID();
        
        };


}// end of else statement from admin login form
?>


<?php require_once 'footer.php'; ?>

<?php require_once 'foot.php'; ?>