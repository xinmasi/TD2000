<?
include_once("inc/auth.inc.php");
include_once("inc/utility.php");
include_once("inc/utility_sms1.php");

$HTML_PAGE_TITLE = _("´úÏú¼Ù");
include_once("inc/header.inc.php");
?>




<body class="bodycolor">

<?
$CUR_TIME=date("Y-m-d H:i:s",time());
$SELECTED_STR = td_trim($SELECTED_STR);
if($SELECTED_STR != "")
{
   $query="update ATTENDANCE_OVERTIME set STATUS='1',ALLOW='3',CONFIRM_TIME='$CUR_TIME' where OVERTIME_ID in ($SELECTED_STR)";
   exequery(TD::conn(),$query);
}
if($OVERTIME_ID != "")
{
   $query="update ATTENDANCE_OVERTIME set STATUS='1',ALLOW='3',CONFIRM_TIME='$CUR_TIME' where OVERTIME_ID = '$OVERTIME_ID'";
   exequery(TD::conn(),$query);
}

header("location: ./overtime_back.php?connstatus=1");
?>

</body>
</html>
