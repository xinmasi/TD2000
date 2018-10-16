<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("É¾³ý»ý·ÖÏî");
include_once("inc/header.inc.php");
?>




<body class="bodycolor">

<?
$query="delete from integral_item where ITEM_ID='$ITEM_ID'";
exequery(TD::conn(),$query);

header("location:no_link.php");
?>

</body>
</html>
