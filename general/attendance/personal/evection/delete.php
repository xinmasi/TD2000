<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("É¾³ý");
include_once("inc/header.inc.php");
?>




<body class="bodycolor">

<?
$EVECTION_ID=intval($EVECTION_ID);
$query="delete from ATTEND_EVECTION where EVECTION_ID='$EVECTION_ID'";
exequery(TD::conn(),$query);
header("location: index.php?connstatus=1");
?>

</body>
</html>
