<?
include_once("inc/auth.inc.php");
include_once("inc/check_type.php");
include_once("inc/utility_all.php");
include_once("inc/utility_sms1.php");
include_once("inc/utility_sms2.php");

$HTML_PAGE_TITLE = _("发布新闻");
include_once("inc/header.inc.php");
?>




<body class="bodycolor">

<?
$ROOM_ID=intval($ROOM_ID);
$query="update RMS_ROLL_ROOM set DEPT_ID='$DEPT_ID',VIEW_DEPT_ID='$TO_ID',ROOM_CODE='$ROOM_CODE',ROOM_NAME='$ROOM_NAME',REMARK='$REMARK',MANAGE_USER='$USER_ID' where ROOM_ID='$ROOM_ID'";
exequery(TD::conn(),$query);
if($OP==0)
   header("location: modify.php?ROOM_ID=$ROOM_ID&CUR_PAGE=$CUR_PAGE&connstatus=1");
else
   header("location: index1.php?CUR_PAGE=$CUR_PAGE&connstatus=1");
?>

</body>
</html>
