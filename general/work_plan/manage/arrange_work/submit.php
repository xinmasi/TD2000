<?
include_once("inc/auth.inc.php");
include_once("inc/utility_sms1.php");
include_once("inc/utility_all.php");

if($CAL_ID=="")
    $WIN_TITLE=_("�½���������");
else
    $WIN_TITLE=_("�༭��������");

$HTML_PAGE_TITLE = $WIN_TITLE;
include_once("inc/header.inc.php");
?>




<body class="bodycolor">

<?
//------------------- ���� -----------------------
if($END_HOUR<$CAL_HOUR || ($END_HOUR==$CAL_HOUR && $END_MIN<$CAL_MIN))
{
    Message(_("����"),_("��ʼʱ�����ڽ���ʱ�䣡"));
    Button_Back();
    exit;
}

$CAL_ID=intval($CAL_ID);
if($CAL_ID!="")
{
    $query="select * from CALENDAR where CAL_ID='$CAL_ID'";
    $cursor= exequery(TD::conn(),$query);
    if($ROW=mysql_fetch_array($cursor))
    {
        $CAL_TIME1=$ROW["CAL_TIME"];
        $CONTENT1=$ROW["CONTENT"];
        $SMS_CONTENT1=csubstr($CONTENT1,0,100);
        $SMS_CONTENT2=$CONTENT1;
    }

    delete_remind_sms(5, $_SESSION["LOGIN_USER_ID"], $SMS_CONTENT, $CAL_TIME1);

    $query="delete from SMS2 where FROM_ID='".$_SESSION["LOGIN_USER_ID"]."' and SEND_TIME='$CAL_TIME1' and CONTENT like '%$SMS_CONTENT2%'";
    exequery(TD::conn(),$query);
}

$CAL_TIME=$CAL_YEAR."-".$CAL_MON."-".$CAL_DAY." ".$CAL_HOUR.":".$CAL_MIN.":00";
$END_TIME=$CAL_YEAR."-".$CAL_MON."-".$CAL_DAY." ".$END_HOUR.":".$END_MIN.":00";

if($CAL_ID=="")
    $query="insert into CALENDAR(USER_ID,CAL_TIME,END_TIME,CAL_TYPE,CONTENT,MANAGER_ID,OVER_STATUS,FROM_MODULE) values ('$USER_ID','".strtotime($CAL_TIME)."','".strtotime($END_TIME)."','1','$CONTENT','".$_SESSION["LOGIN_USER_ID"]."','0','3')";
else
    $query="update CALENDAR set CAL_TIME='".strtotime($CAL_TIME)."',END_TIME='".strtotime($END_TIME)."',CONTENT='$CONTENT' where CAL_ID='$CAL_ID'";
exequery(TD::conn(),$query);
if($CAL_ID=="")
    $CAL_ID=mysql_insert_id();;

//------- �������� --------
$USER_NAME=$_SESSION["LOGIN_USER_NAME"];

if($SMS_REMIND=="on")
{
    $MSG = sprintf(_("%sΪ�������µĹ�����"),$USER_NAME);
    $SMS_CONTENT=$MSG."\n"._("���ݣ�").csubstr($CONTENT,0,100);
    send_sms($CAL_TIME,$_SESSION["LOGIN_USER_ID"],$USER_ID,5,$SMS_CONTENT,$REMIND_URL);
    send_sms($CUR_TIME,$_SESSION["LOGIN_USER_ID"],$USER_ID,5,$SMS_CONTENT,$REMIND_URL);
}

if($SMS2_REMIND=="on")
{
    $MSG1 = sprintf(_("%sΪ�������µĹ�����"),$USER_NAME);
    $SMS_CONTENT=_("OA�ճ̰���:").$MSG1._("���ݣ�").$CONTENT;
    send_mobile_sms_user($CAL_TIME,$_SESSION["LOGIN_USER_ID"],$USER_ID,$SMS_CONTENT,5);
    send_mobile_sms_user($CUR_TIME,$_SESSION["LOGIN_USER_ID"],$USER_ID,$SMS_CONTENT,5);
}

Message("",_("����ɹ�"));
Button_Back();
?>
<script Language="JavaScript">
    window.opener.location.reload();
</script>
</body>
</html>
