<?php
    //session_start();
    define('URL_ROOT', '/');
?>
<!DOCTYPE html>
<html>
<head>
	<title>Creative U</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<!--[if lt IE 9]>
	<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
	<link rel="stylesheet" href="stylesheets/css/html5reset-1.6.1.css">
	<link rel="stylesheet" href="stylesheets/css/screen.css">
	<script src="http://code.jquery.com/jquery-1.8.3.min.js"></script>
	<script src="js/scripts.js"></script>
	<!-- <script src="js/jquery.masonry.min.js"></script> --><!--TODO: Switch to Masonry.min.js -->
	<script src="js/jquery.lightbox_me.js"></script>
	<script src="js/jquery.complexify.js"></script>
	<script src="js/jquery.infinitescroll.min.js"></script>
	<link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
<!-- 	<script src="js/jquery.ias.min.js"></script> -->
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-27737840-2']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>

<!-- Piwik --> 
<!-- <script type="text/javascript">
var pkBaseURL = (("https:" == document.location.protocol) ? "https://localhost:8888/piwik/" : "http://localhost:8888/piwik/");
document.write(unescape("%3Cscript src='" + pkBaseURL + "piwik.js' type='text/javascript'%3E%3C/script%3E"));
</script><script type="text/javascript">
try {
var piwikTracker = Piwik.getTracker(pkBaseURL + "piwik.php", 1);
piwikTracker.trackPageView();
piwikTracker.enableLinkTracking();
} catch( err ) {}
</script><noscript><p><img src="http://localhost:8888/piwik/piwik.php?idsite=1" style="border:0" alt="" /></p></noscript> -->
<!-- End Piwik Tracking Code -->

</head>
<body>
<?php
require_once('includes/MySQL.php');
require_once('includes/db-local.php');
$db = new MySQL($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['database']);
?>