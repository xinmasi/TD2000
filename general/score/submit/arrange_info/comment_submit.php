<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_sms1.php");
include_once("inc/utility_sms2.php");

$HTML_PAGE_TITLE = _("日志修改");
include_once("inc/header.inc.php");
?>

<body class="bodycolor">

<?
$DIA_ID=intval($DIA_ID);
//------------------- 保存 -----------------------
$query = "SELECT * from DIARY where DIA_ID='$DIA_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
    $SUBJECT=$ROW["SUBJECT"];
    $CONTENT1=$ROW["CONTENT"];
    $DIA_DATE=$ROW["DIA_DATE"];
    $DIA_DATE=strtok($DIA_DATE," ");

    if($SUBJECT=="")
        $SUBJECT=csubstr(strip_tags($CONTENT1),0,50).(strlen($CONTENT1)>50?"...":"");
}

$SEND_TIME=date("Y-m-d H:i:s",time());
$COMMENT_ID=intval($COMMENT_ID);
if($COMMENT_ID!="")
    $query="update DIARY_COMMENT set SEND_TIME='$SEND_TIME',CONTENT='$CONTENT' where COMMENT_ID='$COMMENT_ID'";
else
    $query="insert into DIARY_COMMENT (DIA_ID,USER_ID,CONTENT,SEND_TIME) values($DIA_ID,'".$_SESSION["LOGIN_USER_ID"]."','$CONTENT','$SEND_TIME')";
exequery(TD::conn(),$query);

$MSG = sprintf(_("对您 %s 的工作日志“ %s ”进行了点评，请查看。"),$DIA_DATE,$SUBJECT);
$SMS_CONTENT=$_SESSION["LOGIN_USER_NAME"].$MSG;
$REMIND_URL="1:diary/show_diary.php?dia_id=".$DIA_ID;
if($SMS_REMIND=="on")
    send_sms("",$_SESSION["LOGIN_USER_ID"],$DIA_USER,13,$SMS_CONTENT,$REMIND_URL,$DIA_ID);

if($SMS2_REMIND=="on")
    send_mobile_sms_user("",$_SESSION["LOGIN_USER_ID"],$DIA_USER,$SMS_CONTENT,13);


Message("",_("保存成功"));
?>
<div align="center">
    <input type="button" class="BigButton" value="<?=_("返回")?>" onclick="location='user_diary.php?USER_ID=<?=$DIA_USER?>&connstatus=1'">
</div>
</body>
</html>
