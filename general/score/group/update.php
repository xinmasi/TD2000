<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/check_type.php");

$HTML_PAGE_TITLE = _("�½�����");
include_once("inc/header.inc.php");
?>




<body class="bodycolor">

<?
//------------------- ���� -----------------------
if($DIARY=="on")
  $GROUP_REFER=$GROUP_REFER."DIARY,";
if($CALENDAR=="on")
  $GROUP_REFER=$GROUP_REFER."CALENDAR,";  
$query="update SCORE_GROUP set GROUP_NAME='$GROUP_NAME',GROUP_DESC ='$GROUP_DESC',GROUP_REFER='$GROUP_REFER',TO_ID='$TO_ID',USER_ID='$USER_ID',PRIV_ID='$PRIV_ID' where GROUP_ID='$GROUP_ID'";
exequery(TD::conn(),$query);

header("location: index.php?CUR_PAGE=$CUR_PAGE&connstatus=1");

?>

</body>
</html>
