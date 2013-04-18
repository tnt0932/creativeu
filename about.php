<?php 
	session_start();
	require_once 'head.php'; ?>

<?php require_once 'header.php'; ?>

<div id="container">
    
    <div id="content" class="section group">
        <form>
        <!-- ==================================
                         Left Column
             ==================================-->

            <section class="col span_3_of_12">
            	 <!-- <a href='#'><img src="img/icon_aboutfb.png"></a> -->
                 <a href='http://twitter.com/creativeuca'><img src="img/icon_abouttw.png"></a>
                 <!-- <a href='#'><img src="img/icon_aboutin.png"></a> -->
            </section>
        <!-- ==================================
                       Right Column
             ==================================-->
             
             <section class="col span_9_of_12">
             	<div id="aboutright">
                 <h3><span class="blue">Creative</span> <span class="pink">U</span> is the new academic network for creative students in Canada. Create an account and show your work to the world! Meet the team that created this website!</h3>
            
                 <!-- ==================================
                       The Team
                 ==================================-->
                 <div class="section_group">
                     <div class="col span_3_of_12" class="test">
                         <img src="img/aboutus/lynn.png" width="200" height="200" alt="team photo">
                         <h4>Lynn Nguyen</h4>
                         <p>INTE 2013</p>
                         <p>Capilano University</p>
                         <p><img src="img/icon-profiletwitter.png"><a href="http://www.twitter.com/thehelloLYNN">@thehelloLYNN</a></p>
                     </div>
                     <div class="col span_3_of_12">
                         <img src="img/aboutus/brian.png" width="200" height="200" alt="team photo">
                         <h4>Brian Lui</h4>
                         <p>INTE 2013</p>
                         <p>Capilano University</p>
                         <p><img src="img/icon-profiletwitter.png"><a href="http://www.twitter.com/brianluii">@brianluii</a></p>
                     </div>
                     <div class="col span_3_of_12">
                         <img src="img/aboutus/troy.png" width="200" height="200" alt="team photo">
                         <h4>Troy Tucker</h4>
                         <p>INTE 2013</p>
                         <p>Capilano University</p>
                         <p><img src="img/icon-profiletwitter.png"><a href="http://www.twitter.com/troynoahtucker">@troynoahtucker</a></p>
                     </div>
                     <div class="col span_3_of_12">
                         <img src="img/aboutus/will.png" width="200" height="200" alt="team photo">
                         <h4>Will Balladares</h4>
                         <p>INTE 2013</p>
                         <p>Capilano University</p>
                         <p><img src="img/icon-profiletwitter.png"><a href="http://www.twitter.com/wnballadares">@wnballadares</a></p>
                     </div>
                 </div>
            <hr class="breaker">
  			</div>
             </section><!-- /right column -->
             
             
        </form>
    </div>

</div>

<script>
    $(document).ready(function() {
        password_strength('#new_password_1');
    });
</script>

<?php require_once 'footer.php'; ?>

<?php require_once 'foot.php'; ?>