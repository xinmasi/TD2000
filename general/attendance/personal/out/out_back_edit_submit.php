<?
include_once("inc/auth.inc.php");
include_once("inc/check_type.php");
include_once("inc/utility_all.php");
include_once("inc/utility_sms1.php");

$HTML_PAGE_TITLE = _("修改外出记录");
include_once("inc/header.inc.php");
?>

<body class="bodycolor">

<?
$CUR_TIME=date("Y-m-d H:i:s",time());

//----------- 合法性校验 ---------
if($OUT_TIME1!="")
{
    $OUT_TIME11="1999-01-02 ".$OUT_TIME1.":02";
    $TIME_OK=is_date_time($OUT_TIME11);

    if(!$TIME_OK)
    {
        Message(_("错误"),_("时间有问题，请核实"));
        Button_Back();
        exit;
    }
}

if($OUT_TIME2!="")
{
    $OUT_TIME22="1999-01-02 ".$OUT_TIME2.":02";
    $TIME_OK=is_date_time($OUT_TIME22);

    if(!$TIME_OK)
    {
        Message(_("错误"),_("时间有问题，请核实"));
        Button_Back();
        exit;
    }
}

if(compare_date_time($OUT_TIME11,$OUT_TIME22)>=0)
{
    Message(_("错误"),_("外出结束时间应晚于外出开始时间"));
    Button_Back();
    exit;
}

$SUBMIT_TIME=$OUT_DATE." ".$OUT_TIME1;
$query="update ATTEND_OUT set SUBMIT_TIME='$SUBMIT_TIME',OUT_TYPE='$OUT_TYPE',OUT_TIME1='$OUT_TIME1',OUT_TIME2='$OUT_TIME2',STATUS='1' where OUT_ID='$OUT_ID'";
$cursor = exequery(TD::conn(),$query);

$query = "UPDATE calendar SET OVER_STATUS='1' where USER_ID='".$_SESSION["LOGIN_USER_ID"]."' and END_TIME='$END_TIME' and CAL_TYPE='1'";
exequery(TD::conn(),$query);


$SMS_CONTENT=$_SESSION["LOGIN_USER__NAME"]._("外出归来，请查看！");
$REMIND_URL="1:attendance/manage/records/out_edit.php?OUT_ID=".$OUT_ID;
send_sms("",$_SESSION["LOGIN_USER_ID"],$LEADER_ID,6,$SMS_CONTENT,$REMIND_URL,$OUT_ID);

if($cursor != false) {
    ?>
    <script>
        window.close();
        if(window.opener.location.href.indexOf("connstatus") < 0 ){
            window.opener.location.href = window.opener.location.href+"?connstatus=1";
        }else{
            window.opener.location.reload();
        }
    </script>
    <?
}
?>
</body>
</html>
