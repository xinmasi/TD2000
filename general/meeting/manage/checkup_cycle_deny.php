<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_sms1.php");

include_once("inc/header.inc.php");
?>

<body class="bodycolor">
<?
$M_STATUS=3;
$CHECK_STR= td_trim($CHECK_STR);
$CHECK_ARRAY = explode(",",$CHECK_STR);
foreach($CHECK_ARRAY as $key=> $value)
{
    $M_ID = $value;

    $query="update MEETING set M_STATUS='$M_STATUS',REASON='$REASON' where M_ID='$M_ID'";
    exequery(TD::conn(),$query);

    $query="select * from MEETING where M_ID='$M_ID'";
    $cursor=exequery(TD::conn(),$query);
    if($ROW=mysql_fetch_array($cursor))
    {
        $M_PROPOSER=$ROW["M_PROPOSER"];
        $M_ATTENDEE=$ROW["M_ATTENDEE"];
        $SMS_REMIND=$ROW["SMS_REMIND"];
        $SMS2_REMIND=$ROW["SMS2_REMIND"];
        $M_NAME=$ROW["M_NAME"];
        $M_ROOM=$ROW["M_ROOM"];
        $M_START2 = $M_START=$ROW["M_START"];
        $M_END=$ROW["M_END"];
        $RESEND_LONG=$ROW["RESEND_LONG"];
        $RESEND_LONG_FEN=$ROW["RESEND_LONG_FEN"];
        $RESEND_SEVERAL=$ROW["RESEND_SEVERAL"];

        if($RESEND_SEVERAL > 4)
            $RESEND_SEVERAL = 4;

    }
    $CONTENT=sprintf(_("您%s的会议申请未被批准，会议名称：%s。"),$M_START2,$M_NAME);
    //$CONTENT=_("您").$M_START2._("的会议申请未被批准！");

    $SYS_PARA_ARRAY=get_sys_para("SMS_REMIND");
    $PARA_VALUE=$SYS_PARA_ARRAY["SMS_REMIND"];
    $SMS_REMIND1=substr($PARA_VALUE,0,strpos($PARA_VALUE,"|"));
    $SMS2_REMIND1=substr($PARA_VALUE,strpos($PARA_VALUE,"|")+1);

    $REMIND_URL1="1:meeting/query/meeting_detail.php?M_ID=".$M_ID;

    send_sms("",$_SESSION["LOGIN_USER_ID"],$M_PROPOSER,"8_1",$CONTENT,$REMIND_URL1,$M_ID);
}
$query = "SELECT count(*) as num from MEETING where M_STATUS=0 and CYCLE=".$_GET['CYCLE']." and CYCLE_NO=".$_GET['CYCLE_NO'];
$cursor=exequery(TD::conn(),$query);
$num=mysql_fetch_array($cursor)['num'];
if($num == 0){
    header("location:manage1.php?M_STATUS=0");
}else{
    header("location:manage_cycle.php?M_STATUS=$M_STATUS&CYCLE=".$_GET['CYCLE']."&CYCLE_NO=".$_GET['CYCLE_NO']);
}
?>
</body>

</html>
