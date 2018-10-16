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
        // window.opener.document.location.reload();
    }
</script>
<body class="bodycolor">
<?
$M_ID = $_POST['M_ID'];
$REJECT_CONTENT = $_POST[reject_content];
$CURRENT_TIME = date('Y-m-d H:i:s',time());
$query1="select SEND_NAME,APPROVE_NAME FROM MEETING where M_ID='$M_ID'";
$cursor= exequery(TD::conn(),$query1);
if($ROW=mysql_fetch_array($cursor))
{
    $SEND_NAME = $ROW["SEND_NAME"];
    $APPROVE_NAME = $ROW["APPROVE_NAME"];
}
$query="INSERT INTO MEETING_REFUSAL(MEETING_ID,R_CONTENT,R_TIME) VALUE('$M_ID','$REJECT_CONTENT','$CURRENT_TIME')";
$cursor=exequery(TD::conn(),$query);
if($cursor == "true")
{
    Message("",_("会议纪要驳回成功!"));
    $query="UPDATE MEETING SET SUMMARY_STATUS='3' WHERE M_ID='$M_ID'";
    $cursor=exequery(TD::conn(),$query);
    if($SMS_REMIND=="on")
    {
        $REMIND_URL1 = "/meeting/summary/summary.php?M_ID=$M_ID";
        send_sms("",$_SESSION["LOGIN_USER_ID"],$SEND_NAME,803,_("您的会议纪要被驳回！"),$REMIND_URL1,$M_ID);
    }
    if($SMS2_REMIND=="on")
        send_mobile_sms_user("",$_SESSION["LOGIN_USER_ID"],$SEND_NAME,_("您的会议纪要被驳回！"),803);

}
?>
<center><input type="button" value="<?=_("关闭")?>" class="BigButton" onClick="close_this_new();"></center>
</body>
</html>
