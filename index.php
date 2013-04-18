<?php
session_start(); 
require_once('includes/MySQL.php');
require_once('includes/db-local.php');
$db = new MySQL($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['database']);
 
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

 ?>
<?php require_once 'head.php'; ?>
<?php
    $showfilterpanel = "<script type='text/javascript'>\$(function() {\$('#filter_container').show(); });</script>";
    $where = '';
    
    
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
    if (isset($_GET['all'])) {
        $where = '';
        echo $showfilterpanel;
    }
    
?>
<?php
    require_once('paginator.class.php');
    
    $sql = "SELECT * FROM snippet";
    $resSql = mysql_query($sql) or die ($sql. '-error' .mysql_error());
    $db_count = mysql_num_rows($resSql);
    $pages = new Paginator;
    $pages->items_total = $db_count;
    $pages->items_per_page = 20;
    $pages->mid_range = 7;
    $pages->paginate(); //echo $pages->display_pages();
?>
<!-- ==================================
                  Header
     ==================================-->
    
    <?php require_once 'header.php'; ?>
    
<!-- ==================================
                 Marketing
     ==================================-->
    
    <?php   
    if (!isset($_SESSION['logged_in_user'] /* || $_SESSION['admin_logged_in_user'] */)) {
    ?>
    <aside id="marketing">
            <h2><span class="blue">Creative</span> <span class="pink">U</span> is the new academic network for creative students in Canada</h2><br>
            <h3>Create an account &amp; share your work to the world!<br>
            Let us convince you</h3>
            <a href="about.php" class="btn-pink btn-marketing">Get to Know Us</a>
        
    </aside>
    <?php
    } else {
	?> <?php } ?>
    
    
    
<!-- ==================================
                  Filters
     ==================================-->
     <section id="filter_wrap">
        <div id="filter_bar">
            <h3>Filter your stream</h3>
        </div>
        <div id="filter_container">
            <ul id="filter_tabs">
                <li class="filter1"><a href="#" class="filter1 filter_tab">Tags</a></li>
                <li class="filter2"><a href="#" class="filter2 filter_tab">Program</a></li>
                <li class="filter3"><a href="#" class="filter3 filter_tab">Grad Year</a></li>
            </ul>
            <div class="filter1 filter_content">
                <ul>
                    <li><a href="index.php?all=true">all snippets</a></li>
                    <?php
                    $sql = "SELECT TagName, COUNT(snippet.SnippetID) as count, snippettag.TagID as TID
                            FROM snippet
                            INNER JOIN snippettag ON (snippet.SnippetID = snippettag.SnippetID)
                            INNER JOIN tags ON (tags.TagID = snippettag.TagID)
                            GROUP BY snippettag.TagID";
                            
                    $result = mysql_query($sql) or die ($sql. '-error' .mysql_error());
                    while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
                       echo '<li><a href="index.php?tag='. $row['TID'] .'">' .$row['TagName'].'<span>'. $row['count'] . '<span></a></li>';
                    }
                    ?>
                </ul>
            </div>
            <div class="filter2 filter_content">
                 <ul>
                     <li>all programs</li>
                   <?php
                   $sql = "SELECT ProgramName as pName, COUNT(snippetID) as count, students.ProgramID as PID
                            FROM snippet
                            INNER JOIN students ON (snippet.SID = students.SID)
                            INNER JOIN program ON (students.ProgramID = program.ProgramID)
                            GROUP BY students.ProgramID";
                            
                    $result = mysql_query($sql) or die ($sql. '-error' .mysql_error());
                    while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
                       echo '<li><a href="index.php?program='. $row['PID'] .'">' .$row['pName'].'<span>'. $row['count'] . '<span></a></li>';
                    }
                    ?>

                </ul>
            </div>
            <div class="filter3 filter_content">
                 <ul>
                    <li>all years</li>
                    <?php
                    $sql = "SELECT students.GradYearID as GYID, GradYear as year, COUNT(snippetID) as count
                            FROM snippet
                            INNER JOIN students ON (snippet.SID = students.SID)
                            INNER JOIN gradyear ON (students.GradYearID = gradyear.GradYearID)
                            GROUP BY students.GradYearID
                            ORDER BY GradYear DESC";
                            
                    $result = mysql_query($sql) or die ($sql. '-error' .mysql_error());
                    while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
                       echo '<li><a href="index.php?gradyear='. $row['GYID'] .'">' .$row['year'].'<span>'. $row['count'] . '<span></a></li>';
                    }
                    ?>
                </ul>
            </div>
            <div id="filter_float_clear"></div>
        </div>
        <div id="filter_reveal"><img src="img/tab-reveal.png" class="tab-reveal"></div>
     </section>
     
<!-- ==================================
               Snippet Stream
     ==================================-->
     
     <section id="snippet_stream_container">
     
        <?php
        $sql = "SELECT DISTINCT students.SID, snippet.SnippetID as snippetID, SnippetTitle, SnippetDescription as descr, SnippetURL, SnippetLikes, SnippetAddedDate, SnippetPictureFile, SnippetLikes, SnippetThumbnailFile as thumb, StudentFirstName as fname, StudentLastName as lname, StudentPicture as spic, ProgramName as pName, SchoolName, GradYear, CreativeTitle
                FROM snippet
                LEFT JOIN snippettag ON (snippet.SnippetID = snippettag.SnippetID)
                INNER JOIN students ON (snippet.SID = students.SID)
                INNER JOIN program ON (students.ProgramID = program.ProgramID)
                INNER JOIN school ON (students.SchoolID = school.SchoolID)
                INNER JOIN gradyear ON (students.GradYearID = gradyear.GradYearID)
                INNER JOIN creativetitle ON (students.CreativeTitleID = creativetitle.CreativeTitleID)
                $where
                ORDER BY snippet.SnippetID DESC
                $pages->limit";
                
        $result = mysql_query($sql) or die ($sql. '-error' .mysql_error());
        $mycounter = 0;
        
        while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
            $snippetlikes = $row['SnippetLikes'];
            $studentPic = "<img src='img/default_profile.jpg' width='50' height='50' alt='Profile Image' class='snippet_user_image'>";
            if ($row['spic'] != NULL) { 
                $studentPic = '<img src="img/student_uploads/'.$row['SID'].'/profile_image/'.$row['spic'].'" alt="'.$row['fname'].' '.$row['lname'].'\'s Profile Picture" width="50" height="50" class="snippet_user_image">';
            } 
           echo '<div class="snippet_item">
               <a href="#" id="snippet_view_trigger'.$mycounter.'">
                   <img class="snippet_image_stream" src="img/student_uploads/'.$row['SID'].'/'.$row['snippetID'].'/snippet_thumb/'.$row['thumb'].'" width="230" height="230" alt="'.$row['descr'].'">
               </a>
               '.$studentPic.'
               <h2><a href="profile.php?id='.$row['SID'].'">'.$row['fname']. ' ' .$row['lname'].'</a></h2>
               <h3 class="creativeTitleIndex">'.$row['CreativeTitle'].'</h3>
               <h4>'.$row['pName'].' '.$row['GradYear'].'</h4>
               <h4>'.$row['SchoolName'].'</h4>
               <a href="index.php?addlike='.$row['snippetID'].'" class="heart">'.$row['SnippetLikes'].'</a>
               </div>
           ';
           $mycounter++;
        }
        echo '<div id="pagination" style="visibility:hidden">';
        echo $pages->display_pages();
        echo '</div>';
        //echo "Page $pages->current_page of $pages->num_pages";
        //echo $pages->display_jump_menu();
        //echo $pages->display_items_per_page();
        ?>
        
     </section>
     
     
<script>
      
$(function(){
        
        var $container = $('#snippet_stream_container');
        
        $container.imagesLoaded(function(){
          $container.masonry({
            itemSelector: '.snippet_item',
            isFitWidth: true
          });
        });
        
        $container.infinitescroll({
          navSelector  : '#pagination',    // selector for the paged navigation 
          nextSelector : '#paginate_next',  // selector for the NEXT link (to page 2)
          itemSelector : '.snippet_item',     // selector for all items you'll retrieve
          loading: {
              finished: undefined,
              finishedMsg: 'No more pages to load.',
              img: 'http://i.imgur.com/6RMhx.gif'
            }
          },
          // trigger Masonry as a callback
          function( newElements ) {
            // hide new items while they are loading
            var $newElems = $( newElements ).css({ opacity: 0 });
            // ensure that images load before adding to masonry layout
            $newElems.imagesLoaded(function(){
              // show elems now they're ready
              $newElems.animate({ opacity: 1 });
              $container.masonry( 'appended', $newElems, true ); 
            });
          }
        );
        
      });

    </script>

     
    <?php require_once 'footer.php'; ?>

    <?php require_once 'foot.php'; ?>
