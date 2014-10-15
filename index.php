<?php
session_start();

require_once 'connect.ini.php';
require_once 'queries/index-queries.php';

# ==================================
# Likes
# ==================================
 
// if (isset($_GET['addlike'])) {
//     if (!isset($_COOKIE[$_GET['addlike']])){
   
//         setcookie($_GET['addlike'],$_GET['addlike'], time()+2630000);
//         $sql = "SELECT SnippetLikes FROM snippet WHERE SnippetID=".$_GET['addlike'];
//         $result = mysql_query($sql) or die ($sql. '-error' .mysql_error());
//         while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
//             $likes = $row['SnippetLikes'];
//         };
//         $sql = "UPDATE snippet SET SnippetLikes=$likes+1 WHERE SnippetID=".$_GET['addlike'];
//         $result = mysql_query($sql) or die ($sql. '-error' .mysql_error());
//     }
// }

# ==================================
# Head
# ==================================

require_once 'head.php';

# ==================================
# Header
# ==================================
    
require_once 'header.php';
    
# ==================================
# Marketing
# ==================================
 
if (!isset($_SESSION['logged_in_user'] /* || $_SESSION['admin_logged_in_user'] */)) { ?>

<aside id="marketing">
    <span class="icon-close" data-btn="close"></span>
    <h2><span class="blue">Creative</span> <span class="pink">U</span> is the new academic network for creative students in Canada</h2>
    <br>
    <h3>Create an account &amp; share your work to the world!<br>
    Let us convince you</h3>
    <a href="about.php" class="btn-pink btn-marketing">Get to Know Us</a>   
</aside>

<?php } else { } ?>
    
    
<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~>
Filters :::::::::::::::::::::::::::::
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
    <section id="filter_wrap">

        <div id="filter_bar">
            <h3>Filter your stream</h3>
        </div>

        <div id="filter_container">

            <div class="filter_content">
                <h2>Tags</h2>
                <ul class="filters">
                    <li class="remove-filters" ><a href="#"data-filter="*">Remove all filters</a></li>
                    <?php
                    # SQL ==============================
                    # Filter: Tags
                    # ==================================
                    
                    while ($row = mysql_fetch_array($filter_tag_result, MYSQL_ASSOC)) {
                       echo '<li><a href="#" class="tags" data-filter=".tag'.$row['TID'].'">' .$row['TagName'].'<span>'. $row['count'] . '<span></a></li>';
                    } ?>
                </ul>
            </div>

            <div class="filter_content">
                <h2>Program</h2>
                 <ul class="filters">
                    <?php
                    # SQL ==============================
                    # Filter: Program
                    # ==================================
                    while ($row = mysql_fetch_array($filter_program_result, MYSQL_ASSOC)) {
                       echo '<li><a href="#" class="programs" data-filter=".program'.$row['PID'].'">' .$row['pName'].'<span>'. $row['count'] . '<span></a></li>';
                    } ?>

                </ul>
            </div>

            <div class="filter_content">
                <h2>Grad Year</h2>
                 <ul class="filters">
                    <?php
                    # SQL ==============================
                    # Filter: Grad Year
                    # ==================================
                    while ($row = mysql_fetch_array($filter_gradyear_result, MYSQL_ASSOC)) {
                       echo '<li><a href="#" class="gradyear" data-filter=".gradyear'.$row['GYID'].'">' .$row['year'].'<span>'. $row['count'] . '<span></a></li>';
                    } ?>
                </ul>
            </div>
            <div id="filter_float_clear"></div>
        </div>
        <div id="filter_reveal"><img src="img/tab-reveal.png" class="tab-reveal"></div>
     </section>
     
<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~>
Snippet Stream ::::::::::::::::::::::
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->
     
     <section id="snippet_stream_container">
     
        <?php
        
        $mycounter = 0;

        # SQL ==============================
        # Snippet Stream
        # ==================================
        while ($row = mysql_fetch_array($snippet_stream_result, MYSQL_ASSOC)) {
            $snippetlikes = $row['SnippetLikes'];
            $studentPic = "<img src='img/default_profile.jpg' width='50' height='50' alt='Profile Image' class='snippet_user_image'>";

            if ($row['spic'] != NULL) { 
                $studentPic = '<img src="img/student_uploads/'.$row['SID'].'/profile_image/'.$row['spic'].'" alt="'.$row['fname'].' '.$row['lname'].'\'s Profile Picture" width="50" height="50" class="snippet_user_image">';
            }

            $classString = ' program'.$row['PID'].' gradyear'.$row['GYID'];

            $snippet_tags_sql = "SELECT TagID FROM snippettag WHERE SnippetID=".$row['snippetID'];
            $snippet_tags_result = mysql_query($snippet_tags_sql) or die ($snippet_tags_sql. '-error' .mysql_error());

            while ($row2 = mysql_fetch_array($snippet_tags_result, MYSQL_ASSOC)) {
                $classString .= ' tag'.$row2['TagID'];
            }
            
            echo '<div class="snippet_item '.$classString.'">
               <a href="#" id="snippet_view_trigger'.$mycounter.'">
                   <img class="snippet_image_stream" src="img/student_uploads/'.$row['SID'].'/'.$row['snippetID'].'/snippet_thumb/'.$row['thumb'].'" width="230" height="230" alt="'.$row['descr'].'">
               </a>
               '.$studentPic.'
               <h2><a href="profile.php?id='.$row['SID'].'">'.$row['fname']. ' ' .$row['lname'].'</a></h2>
               <h4>'.$row['pName'].' '.$row['GradYear'].'</h4>
               <h4>'.$row['SchoolName'].'</h4>
               <a href="#" class="heart" data-snippetid="'.$row['snippetID'].'">'.$row['SnippetLikes'].'</a>
               </div>
           ';
           $mycounter++;
        }
        echo '</div>';
        ?>
        
    </section>
    <form id="addlike" action="utility/addlike.php" method="post">
        <input type="hidden" id="addLikeToSnippet" name="addLikeToSnippet" value="">
    </form>
     


     
    <?php require_once 'footer.php'; ?>

<!--~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~>
JS ::::::::::::::::::::::::::::::::::
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~-->

    <script src="js/isotope.min.js"></script>
    <script src="js/cookies.min.js"></script>
    <script>
          
    $(function(){

        if (Cookies.get('hideForWeek')) {
            //$('#marketing').show(); // TODO: Remove to hide marketing bar
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
          $('.filter_content').find('li').removeClass('active');
          $(this).parent().addClass('active');
          return false;
        });

        divClose("[data-btn='close']", '#marketing');

        // this is the id of the submit button
        $("[data-snippetid]").click(function() {
            var heart = $(this);
            var snippetID = heart.data('snippetid');
            $("#addLikeToSnippet").val(snippetID);
            var url = "utility/addlike.php"; // the script where you handle the form input.

            $.ajax({
                type: "POST",
                url: url,
                data: $("#addlike").serialize(), // serializes the form's elements.
                success: function(data) {
                    heart.text(parseInt(heart.text(), 10)+1);
                    console.log('Oh yeaahhh!');
                }
            });

            return false; // avoid to execute the actual submit of the form.
        });

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
            Cookies.set('hideForWeek', true, { expires: 86400 });
        });
    }

    </script>

    <?php require_once 'foot.php'; ?>
