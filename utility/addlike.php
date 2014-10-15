<?php 

require_once '../connect.ini.php';

if (isset($_POST['addLikeToSnippet'])) {

	$snippetID = $_POST['addLikeToSnippet'];


	if (!isset($_COOKIE['snippets'.$snippetID])){
		
		setcookie('snippets'.$snippetID,$snippetID, time()+2630000);
		$sql = "SELECT SnippetLikes FROM snippet WHERE SnippetID=".$snippetID;
		$result = mysql_query($sql) or die ($sql. '-error' .mysql_error());
		while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
		    $likes = $row['SnippetLikes'];
		};
		$sql = "UPDATE snippet SET SnippetLikes=$likes+1 WHERE SnippetID=".$snippetID;
		$result = mysql_query($sql) or die ($sql. '-error' .mysql_error());
	} else {
		 header('HTTP/1.1 401 Gotta be logged in to add snippets');
	}
}

?>