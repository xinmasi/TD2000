<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");
include_once("inc/utility_sms2.php");
include_once("inc/utility_sms1.php");

$HTML_PAGE_TITLE = _("工作计划");
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

if($AUTO_PERSON=="")
    $query="insert into WORK_PERSON(PLAN_ID,PUSER_ID,PBEGEI_DATE,PEND_DATE,PPLAN_CONTENT,PUSE_RESOURCE,ATTACHMENT_ID,ATTACHMENT_NAME) values ('$PLAN_ID','$USER_ID','$PBEGEI_DATE','$PEND_DATE','$PPLAN_CONTENT','$PUSE_RESOURCE','$ATTACHMENT_ID','$ATTACHMENT_NAME')";
else
    $query="update WORK_PERSON set PLAN_ID='$PLAN_ID',PUSER_ID='$USER_ID',PBEGEI_DATE='$PBEGEI_DATE',PEND_DATE='$PEND_DATE',PPLAN_CONTENT='$PPLAN_CONTENT',PUSE_RESOURCE='$PUSE_RESOURCE',ATTACHMENT_ID='$ATTACHMENT_ID',ATTACHMENT_NAME='$ATTACHMENT_NAME' where AUTO_PERSON='$AUTO_PERSON'";
exequery(TD::conn(),$query);

if($SMS_REMIND=="on" || $SMS2_REMIND=="on")
{
    $SEND_TIME = trim($PBEGEI_DATE)." 09:00:00";
    $SMS_CONTENT=$PPLAN_CONTENT;
    if(strlen($PPLAN_CONTENT) > 50)
        $SMS_CONTENT=csubstr($PPLAN_CONTENT,0,50)."...";
    if(strlen($NAME) > 30)
        $NAME=csubstr($NAME,0,30)."...";

    $REMIND_URL="1:work_plan/show/plan_resource.php?USER_ID=$USER_ID&PLAN_ID=$PLAN_ID&USER_NAME=".urlencode($USER_NAME)."&NAME=".urlencode($NAME)."&URL_BEGIN_DATE=$URL_BEGIN_DATE&URL_END_DATE=$URL_END_DATE";
}

if($SMS_REMIND=="on")
    send_sms($SEND_TIME,$_SESSION["LOGIN_USER_ID"],$USER_ID,12,$SMS_CONTENT,$REMIND_URL,$PLAN_ID);

if($SMS2_REMIND=="on")
    send_mobile_sms_user($SEND_TIME,$_SESSION["LOGIN_USER_ID"],$USER_ID,$SMS_CONTENT,12);

$USER_NAME=urlencode($USER_NAME);
$NAME=urlencode($NAME);

header("location: plan_resource.php?PLAN_ID=$PLAN_ID&USER_ID=$USER_ID&USER_NAME=$USER_NAME&NAME=$NAME&URL_BEGIN_DATE=$URL_BEGIN_DATE&URL_END_DATE=$URL_END_DATE");
?>
</body>
</html>