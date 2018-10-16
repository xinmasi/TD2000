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
   $query="update ATTEND_LEAVE set STATUS='2',ALLOW='3',DESTROY_TIME='$CUR_TIME' where LEAVE_ID in ($SELECTED_STR)";
   exequery(TD::conn(),$query);
}
if($LEAVE_ID != "")
{
   $query="update ATTEND_LEAVE set STATUS='2',ALLOW='3',DESTROY_TIME='$CUR_TIME' where LEAVE_ID = '$LEAVE_ID'";
   exequery(TD::conn(),$query);
}

header("location: ./leave_back.php?connstatus=1");
?>

</body>
</html>
