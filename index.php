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
            <span class="icon-close" data-btn="close"></span>
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
            <div id="toggle" class="icon-close"></div>
        </div>
        <div id="filter_container">
            <div class="filter1 filter_content">
                <h2>Tags</h2>
                <ul class="filters">
                    <li><a href="#" data-filter="*">all snippets</a></li>
                    <?php
                    $sql = "SELECT TagName, COUNT(snippet.SnippetID) as count, snippettag.TagID as TID
                            FROM snippet
                            INNER JOIN snippettag ON (snippet.SnippetID = snippettag.SnippetID)
                            INNER JOIN tags ON (tags.TagID = snippettag.TagID)
                            GROUP BY snippettag.TagID";
                            
                    $result = mysql_query($sql) or die ($sql. '-error' .mysql_error());
                    while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
                       echo '<li><a href="#" class="tags" data-filter=".tag'.$row['TID'].'">' .$row['TagName'].'<span>'. $row['count'] . '<span></a></li>';
                    }
                    ?>
                </ul>
            </div>
            <div class="filter2 filter_content">
                <h2>Program</h2>
                 <ul class="filters">
                     <li><a href="#" data-filter="*">all programs</a></li>
                   <?php
                   $sql = "SELECT ProgramName as pName, COUNT(snippetID) as count, students.ProgramID as PID
                            FROM snippet
                            INNER JOIN students ON (snippet.SID = students.SID)
                            INNER JOIN program ON (students.ProgramID = program.ProgramID)
                            GROUP BY students.ProgramID";
                            
                    $result = mysql_query($sql) or die ($sql. '-error' .mysql_error());
                    while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
                       echo '<li><a href="#" class="programs" data-filter=".program'.$row['PID'].'">' .$row['pName'].'<span>'. $row['count'] . '<span></a></li>';
                    }
                    ?>

                </ul>
            </div>
            <div class="filter3 filter_content">
                <h2>Grad Year</h2>
                 <ul class="filters">
                    <li><a href="#" data-filter="*">all years</a></li>
                    <?php
                    $sql = "SELECT students.GradYearID as GYID, GradYear as year, COUNT(snippetID) as count
                            FROM snippet
                            INNER JOIN students ON (snippet.SID = students.SID)
                            INNER JOIN gradyear ON (students.GradYearID = gradyear.GradYearID)
                            GROUP BY students.GradYearID
                            ORDER BY GradYear DESC";
                            
                    $result = mysql_query($sql) or die ($sql. '-error' .mysql_error());
                    while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
                       echo '<li><a href="#" class="gradyear" data-filter=".gradyear'.$row['GYID'].'">' .$row['year'].'<span>'. $row['count'] . '<span></a></li>';
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
        $sql = "SELECT DISTINCT students.SID, snippet.SnippetID as snippetID, SnippetTitle, SnippetDescription as descr, SnippetURL, SnippetLikes, SnippetAddedDate, SnippetPictureFile, SnippetLikes, SnippetThumbnailFile as thumb, StudentFirstName as fname, StudentLastName as lname, StudentPicture as spic, ProgramName as pName, SchoolName, GradYear, students.GradYearID as GYID, students.ProgramID as PID, CreativeTitle
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
            $sql2 = "SELECT TagID FROM snippettag WHERE SnippetID=".$row['snippetID'];
            $result2 = mysql_query($sql2) or die ($sql2. '-error' .mysql_error());

            $classString = '';
            while ($row2 = mysql_fetch_array($result2, MYSQL_ASSOC)) {
                $classString .= 'tag'.$row2['TagID'].' ';
            }
            $classString .= ' program'.$row['PID'];
            $classString .= ' gradyear'.$row['GYID'];
            echo '<div class="snippet_item '.$classString.'">
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
     


     
    <?php require_once 'footer.php'; ?>

    <script src="js/isotope.min.js"></script>
    <script src="js/cookies.min.js"></script>
    <script>
          
    $(function(){

        

        if (Cookies.get('hideForWeek')) {
            $('#marketing').show(); // TODO: Remove to hide marketing bar
        } else {
            $('#marketing').show();
        }

        // cache container
        var $container = $('#snippet_stream_container');
        // initialize isotope
        $container.isotope({
            itemSelector : ".snippet_item"
        });

        // filter items when filter link is clicked
        $('.filters a').click(function(){
          var selector = $(this).attr('data-filter');
          $container.isotope({ filter: selector });
          return false;
        });

        divClose("[data-btn='close']", '#marketing');

    });


// Make masonry work with centered layout
    $.Isotope.prototype._getCenteredMasonryColumns = function() {
        this.width = this.element.width();

        var parentWidth = this.element.parent().width();

                  // i.e. options.masonry && options.masonry.columnWidth
        var colW = this.options.masonry && this.options.masonry.columnWidth ||
                  // or use the size of the first item
                  this.$filteredAtoms.outerWidth(true) ||
                  // if there's no items, use size of container
                  parentWidth;

        var cols = Math.floor( parentWidth / colW );
        cols = Math.max( cols, 1 );

        // i.e. this.masonry.cols = ....
        this.masonry.cols = cols;
        // i.e. this.masonry.columnWidth = ...
        this.masonry.columnWidth = colW;
        };

        $.Isotope.prototype._masonryReset = function() {
        // layout-specific props
        this.masonry = {};
        // FIXME shouldn't have to call this again
        this._getCenteredMasonryColumns();
        var i = this.masonry.cols;
        this.masonry.colYs = [];
        while (i--) {
        this.masonry.colYs.push( 0 );
        }
        };

        $.Isotope.prototype._masonryResizeChanged = function() {
        var prevColCount = this.masonry.cols;
        // get updated colCount
        this._getCenteredMasonryColumns();
        return ( this.masonry.cols !== prevColCount );
        };

        $.Isotope.prototype._masonryGetContainerSize = function() {
        var unusedCols = 0,
        i = this.masonry.cols;
        // count unused columns
        while ( --i ) {
        if ( this.masonry.colYs[i] !== 0 ) {
        break;
        }
        unusedCols++;
        }
    
        return {
          height : Math.max.apply( Math, this.masonry.colYs ),
          // fit container to columns that have been used;
          width : (this.masonry.cols - unusedCols) * this.masonry.columnWidth
        };
  };

    function divClose(trigger, target) {
        $(trigger).click(function() {
            $(target).slideUp();
            Cookies.set('hideForWeek', true, { expires: 604800 });
            
        });
    }

    </script>

    <?php require_once 'foot.php'; ?>
