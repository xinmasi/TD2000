<?
include_once("inc/auth.inc.php");
include_once("inc/utility_file.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
include_once("inc/utility_sms1.php");
include_once("inc/header.inc.php");

?>
<script type="text/javascript" src="/static/js/utility.js"></script>
<script>

    function close_this_new()
    {
        TJF_window_close();
        //window.opener.document.location.reload();
    }
</script>
<body class="bodycolor">
<?
$savetype = $_POST['savetype'];
if($savetype == "save"  || $savetype == "publish")
{
    $SUMMARY = $_POST['SUMMARY'];
    $M_ID = $_POST['M_ID'];
    if(count($_FILES)>1)
    {
        $ATTACHMENTS=upload();
        $CONTENT=ReplaceImageSrc($CONTENT, $ATTACHMENTS);
        $ATTACHMENT_ID=$ATTACHMENT_ID_OLD.$ATTACHMENTS["ID"];
        $ATTACHMENT_NAME=$ATTACHMENT_NAME_OLD.$ATTACHMENTS["NAME"];
    }
    else
    {
        $ATTACHMENT_ID=$ATTACHMENT_ID_OLD;
        $ATTACHMENT_NAME=$ATTACHMENT_NAME_OLD;
    }

    $ATTACHMENT_ID.=copy_sel_attach($ATTACH_NAME,$ATTACH_DIR,$DISK_ID);
    $ATTACHMENT_NAME.=$ATTACH_NAME;

    $C = preg_match('/<img.*?\ssrc=\\\"\/inc\/attach.php\?(.*)MODULE=upload_temp/i',$SUMMARY);
    $SUMMARY = replace_attach_url($SUMMARY);
    if($C==1)
    {
        $ATTACHMENT_ID=move_attach($ATTACHMENT_ID,$ATTACHMENT_NAME,"","",true).",";
    }

    $M_ID=intval($M_ID);

    $query="update MEETING set SUMMARY='$SUMMARY',READ_PEOPLE_ID='$COPY_TO_ID',ATTACHMENT_ID1='$ATTACHMENT_ID',ATTACHMENT_NAME1='$ATTACHMENT_NAME',M_FACT_ATTENDEE='$M_FACT_ATTENDEE_ID' where M_ID='$M_ID'";
    exequery(TD::conn(),$query);
    //0 不需要审核 1 需要审核
    if($savetype == "publish"){
        $query  = "select M_TYPE from MEETING where M_ID='$M_ID'";
        $cursor = exequery(TD::conn(),$query);
        if($ROW = mysql_fetch_array($cursor))
        {
            $M_TYPE = $ROW["M_TYPE"];
        }
        $SUMMARY_APPROVE_ARRAY = get_sys_para("SUMMARY_APPROVE",false);
        //视频会议且会议纪要免审批
        if($SUMMARY_APPROVE_ARRAY["SUMMARY_APPROVE"] == "0" && $M_TYPE == "1"){
            //给查阅人员发送事务提醒以及电子邮件提醒
            include_once("../manage/meeting_funcs.class.php");
            $Meeting = new MeetingFuncs($M_ID);
            $Meeting->EmailToAllowUserIds($M_ID,$_SESSION["LOGIN_USER_ID"]);
            header("location:summary_approval.php?M_ID=$M_ID&SMS_REMIND=on");
            exit;
        }
    }
    Message("",_("会议纪要保存成功!"));
    ?>
    <center><input type="button" value="<?=_("关闭")?>" class="BigButton" onClick="window.close();"></center>
    <?
    exit;
}
if($savetype == "approve")
{
    if(count($_FILES)>1)
    {
        $ATTACHMENTS=upload();
        $CONTENT=ReplaceImageSrc($CONTENT, $ATTACHMENTS);
        $ATTACHMENT_ID=$ATTACHMENT_ID_OLD.$ATTACHMENTS["ID"];
        $ATTACHMENT_NAME=$ATTACHMENT_NAME_OLD.$ATTACHMENTS["NAME"];
    }
    else
    {
        $ATTACHMENT_ID=$ATTACHMENT_ID_OLD;
        $ATTACHMENT_NAME=$ATTACHMENT_NAME_OLD;
    }

    $ATTACHMENT_ID.=copy_sel_attach($ATTACH_NAME,$ATTACH_DIR,$DISK_ID);
    $ATTACHMENT_NAME.=$ATTACH_NAME;

    $C = preg_match('/<img.*?\ssrc=\\\"\/inc\/attach.php\?(.*)MODULE=upload_temp/i',$SUMMARY);
    $SUMMARY = replace_attach_url($SUMMARY);
    if($C==1)
    {
        $ATTACHMENT_ID=move_attach($ATTACHMENT_ID,$ATTACHMENT_NAME,"","",true).",";
    }
    $M_ID = intval($M_ID);
    $SEND_NAME = $_SESSION["LOGIN_USER_ID"];
    $query="update MEETING set SUMMARY='$SUMMARY',READ_PEOPLE_ID='$COPY_TO_ID',ATTACHMENT_ID1='$ATTACHMENT_ID',ATTACHMENT_NAME1='$ATTACHMENT_NAME',M_FACT_ATTENDEE='$M_FACT_ATTENDEE_ID',SUMMARY_STATUS='1',APPROVE_NAME='$APPROVE_NAME',SEND_NAME='$SEND_NAME' where M_ID='$M_ID'";
    exequery(TD::conn(),$query);

    $query1="select M_NAME,APPROVE_NAME FROM MEETING where M_ID='$M_ID'";
    $cursor= exequery(TD::conn(),$query1);
    if($ROW=mysql_fetch_array($cursor))
    {
        $M_NAME=$ROW["M_NAME"];
        $APPROVE_NAME = $ROW["APPROVE_NAME"];
    }
    $query = "select M_ATTENDEE from MEETING where M_ID='$M_ID'";
    $cursor= exequery(TD::conn(),$query);
    if($ROW=mysql_fetch_array($cursor))
    {
        $M_ATTENDEE=td_trim($ROW["M_ATTENDEE"]);
    }


    $M_ATTENDEE_ARRAY = explode(',',$M_ATTENDEE);
    foreach($M_ATTENDEE_ARRAY as $value)
    {
        if(find_id($COPY_TO_ID,$value))
            continue;
        else
            $COPY_TO_ID.= $value.",";
    }

    $COPY_TO_ID = td_trim($COPY_TO_ID);
    if($SMS_REMIND=="on")
    {
        $REMIND_URL1 = "/meeting/query/meeting_minutes_approval.php?M_ID=$M_ID";
        send_sms("",$_SESSION["LOGIN_USER_ID"],$APPROVE_NAME,802,sprintf(_("%s的会议纪要请审批！"),$M_NAME),$REMIND_URL1,$M_ID);
    }
    if($SMS2_REMIND=="on")
        send_mobile_sms_user("",$_SESSION["LOGIN_USER_ID"],$APPROVE_NAME,sprintf(_("%s的会议纪要请审批！"),$M_NAME),802);

    Message("",_("会议纪要提交成功!"));
}
?>

<center><input type="button" value="<?=_("关闭")?>" class="BigButton" onclick="close_this_new();" title="<?=_("关闭窗口")?>" /></center>


</body>
</html>