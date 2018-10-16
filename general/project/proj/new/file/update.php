<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("ÐÞ¸ÄÄ¿Â¼");
include_once("inc/header.inc.php");
?>




<body class="bodycolor">

<?
$query="update PROJ_FILE_SORT set SORT_NO='$SORT_NO',SORT_NAME='$SORT_NAME' where SORT_ID='$SORT_ID'";
exequery(TD::conn(),$query);
header("location: index.php?PROJ_ID=".$PROJ_ID);
?>

</body>
</html>
