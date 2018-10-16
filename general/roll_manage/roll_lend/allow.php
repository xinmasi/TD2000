<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_sms1.php");

include_once("inc/header.inc.php");
?>

<body class="bodycolor">

<?
$CUR_TIME=date("Y-m-d H:i:s",time());

if ($ALLOW==3)
    $query_str=",RETURN_TIME='$CUR_TIME'";
else
    $query_str=",ALLOW_TIME='$CUR_TIME'";
$LEND_ID=intval($LEND_ID);
$query="update RMS_LEND set ALLOW='$ALLOW',OPERATOR = '".$_SESSION["LOGIN_USER_ID"]."' $query_str where LEND_ID='$LEND_ID'";
exequery(TD::conn(),$query);

if($ALLOW==1)
{
    $SMS_CONTENT=_("您申请借阅的档案，已被审批");  $REMIND_URL="/roll_manage/roll_lend/search.php";
    send_sms("",$_SESSION["LOGIN_USER_ID"],$LEND_USER_ID,37,$SMS_CONTENT,$REMIND_URL);
}
if($ALLOW==2)
{
    $SMS_CONTENT=_("您申请借阅的档案，未被审批");   $REMIND_URL="/roll_manage/roll_lend/search.php";
    send_sms("",$_SESSION["LOGIN_USER_ID"],$LEND_USER_ID,37,$SMS_CONTENT,$REMIND_URL);
}
if($ALLOW==3)
{
    $query = "select OPERATOR,FILE_ID, APPROVE from RMS_LEND where LEND_ID='$LEND_ID'";
    $cursor = exequery(TD::conn(),$query);
    if($ROW = mysql_fetch_array($cursor))
    {
        $OPERATOR = $ROW['OPERATOR'];
        $APPROVE = $ROW['APPROVE'];
        $FILE_ID = $ROW['FILE_ID'];
    }

    $query = "select FILE_CODE from RMS_FILE where FILE_ID='$FILE_ID'";
    $cursor = exequery(TD::conn(),$query);
    if($ROW = mysql_fetch_array($cursor))
        $FILE_CODE = $ROW['FILE_CODE'];
    $REMIND_URL="/roll_manage/roll_lend/confirm.php";
    if($OPERATOR!=''){
        $SMS_CONTENT=sprintf(_("%s向您归还档案<%s>"), $_SESSION["LOGIN_USER_NAME"], $FILE_CODE);
        send_sms("",$_SESSION["LOGIN_USER_ID"],$APPROVE,37,$SMS_CONTENT,$REMIND_URL);
    }
}

if ($ALLOW==3)
    header("location: search.php?connstatus=1");
else
    header("location: confirm.php?connstatus=1");
?>

</body>
</html>
