<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("É¾³ýÐ½³êÏîÄ¿");
include_once("inc/header.inc.php");
?>




<body class="bodycolor">

<?
$ITEM_ID = intval($ITEM_ID);
$query="delete from SAL_ITEM where ITEM_ID='$ITEM_ID'";
exequery(TD::conn(),$query);

header("location: index.php?connstatus=1");
?>
</body>
</html>
