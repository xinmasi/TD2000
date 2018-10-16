<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");
include_once("inc/utility_sms2.php");
include_once("inc/utility_sms1.php");

include_once ("inc/utility_project.php");
mysql_set_charset(MYOA_DB_CHARSET, TD::conn());
$proj_hook = project_hook("project_task_x1");

if($proj_hook == 1)
{
    $run_id = get_run_id("TASK_ID",$TASK_ID);
    if($run_id)
    {
        $num = count($run_id);
        $run_id = $run_id[($num >= 1)? $num - 1 : 0];
        if(!project_build($run_id))
        {
            include_once("inc/header.inc.php");
            Message(_("提示"),_("您的上一个任务审批流程还未结束，请等待流程结束！"));
            exit;
        }
    }
}


$HTML_PAGE_TITLE = _("添加工作日志");
include_once("inc/header.inc.php");
?>

<body class="bodycolor">

<?
$CUR_TIME=date("Y-m-d H:i:s",time());
$CUR_DATE=date("Y-m-d",time());
//--------- 上传附件 ----------
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

if($LOG_ID=="")
    $query="insert into PROJ_TASK_LOG (TASK_ID,LOG_TIME,LOG_CONTENT,PERCENT,LOG_TYPE,LOG_USER,ATTACHMENT_ID,ATTACHMENT_NAME) values ('$TASK_ID','$CUR_TIME','$LOG_CONTENT','$PERCENT','$LOG_TYPE','".$_SESSION["LOGIN_USER_ID"]."','$ATTACHMENT_ID','$ATTACHMENT_NAME')";
else
    $query="update PROJ_TASK_LOG set TASK_ID='$TASK_ID',LOG_CONTENT='$LOG_CONTENT',PERCENT='$PERCENT',LOG_TYPE='$LOG_TYPE',LOG_USER='".$_SESSION["LOGIN_USER_ID"]."',ATTACHMENT_ID='$ATTACHMENT_ID',ATTACHMENT_NAME='$ATTACHMENT_NAME' where LOG_ID='$LOG_ID'";
exequery(TD::conn(),$query);

//-- 更新任务总进度、任务状态 --
$query = "SELECT max(PERCENT) FROM PROJ_TASK_LOG WHERE TASK_ID='$TASK_ID'";
$cursor = exequery(TD::conn(),$query);
if($ROW = mysql_fetch_array($cursor))
{
    $PERCENT_MAX=$ROW["max(PERCENT)"];
    $query = "update PROJ_TASK SET TASK_PERCENT_COMPLETE='$PERCENT_MAX' WHERE TASK_ID='$TASK_ID'";
    exequery(TD::conn(),$query);
}

//--事务提醒--
$query = "SELECT PROJ_OWNER,PROJ_MANAGER,PROJ_VIEWER,PROJ_NAME from PROJ_PROJECT where PROJ_ID='$PROJ_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
    $PROJ_OWNER=$ROW["PROJ_OWNER"];
    $PROJ_MANAGER=$ROW["PROJ_MANAGER"];
    $PROJ_VIEWER=$ROW["PROJ_VIEWER"];
    $PROJ_NAME = $ROW["PROJ_NAME"];

    $USER_ID_STR=$PROJ_VIEWER;
    if(!find_id($USER_ID_STR,$PROJ_OWNER))
        $USER_ID_STR .= $PROJ_OWNER.",";
    if(!find_id($USER_ID_STR,$PROJ_MANAGER))
        $USER_ID_STR .= $PROJ_MANAGER.",";
    $PROJ_NAME = gbk_stripslashes($PROJ_NAME);
}

$SMS_CONTENT=_("[$PROJ_NAME]有新的进度日志，请查看。");

$REMIND_URL="1:project/proj/task/task_detail.php?PROJ_ID=".$PROJ_ID."&TASK_ID=".$TASK_ID;
if($SMS_REMIND=="on")
    send_sms($CUR_TIME,$_SESSION["LOGIN_USER_ID"],$USER_ID_STR,42,$SMS_CONTENT,$REMIND_URL,$PROJ_ID);

if($SMS2_REMIND=="on")
    send_mobile_sms_user($CUR_TIME,$_SESSION["LOGIN_USER_ID"],$USER_ID_STR,$SMS_CONTENT,42);

if($PERCENT_MAX == 100 && $proj_hook == 1){

    echo '<script>';
    echo 'window.open("task_run_flow.php?PROJ_ID='.$PROJ_ID.'&TASK_ID='.$TASK_ID.'","","status=0,toolbar=no,menubar=no,width="+(screen.availWidth-12)+",height="+(screen.availHeight-38)+",location=no,scrollbars=yes,resizable=yes,left=0,top=0");';
    echo '</script>';

}else{
    header("location: detail.php?PROJ_ID=$PROJ_ID&TASK_ID=$TASK_ID");
}
?>
</body>
</html>