<?php

# ==================================
# Filter: Tags
# ==================================

$filter_tag_sql = "SELECT TagName, COUNT(snippet.SnippetID) as count, snippettag.TagID as TID
FROM snippet
INNER JOIN snippettag ON (snippet.SnippetID = snippettag.SnippetID)
INNER JOIN tags ON (tags.TagID = snippettag.TagID)
GROUP BY snippettag.TagID";

$filter_tag_result = mysql_query($filter_tag_sql) or die ($filter_tag_sql. '-error' .mysql_error());

# ==================================
# Filter: Program
# ==================================

$filter_program_sql = "SELECT ProgramName as pName, COUNT(snippetID) as count, students.ProgramID as PID
FROM snippet
INNER JOIN students ON (snippet.SID = students.SID)
INNER JOIN program ON (students.ProgramID = program.ProgramID)
GROUP BY students.ProgramID";
$filter_program_result = mysql_query($filter_program_sql) or die ($filter_program_sql. '-error' .mysql_error());



# ==================================
# Filter: Grad Year
# ==================================

$filter_gradyear_sql = "SELECT students.GradYearID as GYID, GradYear as year, COUNT(snippetID) as count
FROM snippet
INNER JOIN students ON (snippet.SID = students.SID)
INNER JOIN gradyear ON (students.GradYearID = gradyear.GradYearID)
GROUP BY students.GradYearID
ORDER BY GradYear DESC";

$filter_gradyear_result = mysql_query($filter_gradyear_sql) or die ($filter_gradyear_sql. '-error' .mysql_error());

# ==================================
# Snippet Stream
# ==================================

$snippet_stream_sql = "SELECT DISTINCT students.SID, snippet.SnippetID as snippetID, SnippetTitle, SnippetDescription as descr, SnippetURL, SnippetLikes, SnippetAddedDate, SnippetPictureFile, SnippetLikes, SnippetThumbnailFile as thumb, StudentFirstName as fname, StudentLastName as lname, StudentPicture as spic, ProgramName as pName, SchoolName, GradYear, students.GradYearID as GYID, students.ProgramID as PID, CreativeTitle
FROM snippet
LEFT JOIN snippettag ON (snippet.SnippetID = snippettag.SnippetID)
INNER JOIN students ON (snippet.SID = students.SID)
INNER JOIN program ON (students.ProgramID = program.ProgramID)
INNER JOIN school ON (students.SchoolID = school.SchoolID)
INNER JOIN gradyear ON (students.GradYearID = gradyear.GradYearID)
INNER JOIN creativetitle ON (students.CreativeTitleID = creativetitle.CreativeTitleID)
ORDER BY snippet.SnippetID DESC";

$snippet_stream_result = mysql_query($snippet_stream_sql) or die ($snippet_stream_sql. '-error' .mysql_error());

?>