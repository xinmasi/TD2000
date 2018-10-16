<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("É¾³ý");
include_once("inc/header.inc.php");
?>



<body class="bodycolor">

<?
$ROLL_ID=intval($ROLL_ID);
$query="update RMS_FILE set ROLL_ID=0 where ROLL_ID='$ROLL_ID'";
exequery(TD::conn(),$query);
$ROLL_ID=intval($ROLL_ID);
$query="delete from RMS_ROLL where ROLL_ID='$ROLL_ID'";
exequery(TD::conn(),$query);
header("location: index1.php?CUR_PAGE=$CUR_PAGE&connstatus=1");
?>

</body>
</html>
