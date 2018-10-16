<?
include_once("inc/auth.inc.php");
include_once("inc/utility_file.php");
include_once("inc/utility_all.php");
include_once("inc/utility_sms2.php");
include_once("inc/utility_sms1.php");

$HTML_PAGE_TITLE = _("添加批注");
include_once("inc/header.inc.php");
?>

<body class="bodycolor">

<?
//--------- 上传附件 ----------
//echo $ATTACHMENT;
if(count($_FILES)>1)
{
    $ATTACHMENTS=upload();

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

$query="insert into WORK_DETAIL (PLAN_ID,WRITE_TIME,PROGRESS,TYPE_FLAG,WRITER,ATTACHMENT_ID,ATTACHMENT_NAME) values ('$PLAN_ID','$WRITE_TIME','$PROGRESS','1','".$_SESSION["LOGIN_USER_ID"]."','$ATTACHMENT_ID','$ATTACHMENT_NAME')";
exequery(TD::conn(),$query);

$query = "SELECT PARTICIPATOR,NAME from WORK_PLAN  where PLAN_ID='$PLAN_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
    $PARTICIPATOR=$ROW["PARTICIPATOR"];
    $NAME=$ROW["NAME"];

    $MY_ARRAY=explode(",",$PARTICIPATOR);
    $ARRAY_COUNT=sizeof($MY_ARRAY);
    if($MY_ARRAY[$ARRAY_COUNT-1]=="")
        $ARRAY_COUNT--;
    for($I=0;$I<$ARRAY_COUNT;$I++)
    {
        if($MY_ARRAY[$I]!=$_SESSION["LOGIN_USER_ID"])
            $USER_ID_STR.=$MY_ARRAY[$I].",";
    }

}

$SMS_CONTENT=sprintf(_(" %s对%s进行了批注，请查看。"),$_SESSION["LOGIN_USER_NAME"],$NAME);

$REMIND_URL="1:work_plan/show/opinion_detail.php?PLAN_ID=".$PLAN_ID;

if($SMS_REMIND=="on")
    send_sms($CUR_TIME,$_SESSION["LOGIN_USER_ID"],$USER_ID_STR,12,$SMS_CONTENT,$REMIND_URL,$PLAN_ID);

if($SMS2_REMIND=="on")
    send_mobile_sms_user($CUR_TIME,$_SESSION["LOGIN_USER_ID"],$USER_ID_STR,$SMS_CONTENT,12);

header("location: add_opinion.php?PLAN_ID=$PLAN_ID");
?>

</body>
</html>