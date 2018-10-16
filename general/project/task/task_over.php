<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");
include_once("inc/utility_sms2.php");
include_once("inc/utility_sms1.php");

$CUR_DATE=date("Y-m-d",time());

$query = "update PROJ_TASK set TASK_STATUS = '1',TASK_ACT_END_TIME='$CUR_DATE' WHERE TASK_ID='$TASK_ID'";
exequery(TD::conn(),$query);
//检测是否为其他任务的前置任务
$query = "select PROJ_ID,TASK_ID,TASK_NAME,TAST_CONSTRAIN,TASK_USER,TASK_TIME,TASK_START_TIME from PROJ_TASK WHERE PRE_TASK='$TASK_ID'";
$curosr = exequery(TD::conn(),$query);
while($ROW = mysql_fetch_array($curosr))
{
    $PROJ_ID = $ROW['PROJ_ID'];
    $TASK_ID_NEW = $ROW['TASK_ID'];
    $TASK_NAME = $ROW['TASK_NAME'];
    $TASK_USER = $ROW['TASK_USER'];
    $TASK_START_TIME = $ROW['TASK_START_TIME'];

    if($ROW['TAST_CONSTRAIN'] == 1){
        //未开始的任务提前开始 拖后的延后执行 更新结束时间和开始时间
        $t = $ROW['TASK_TIME'];
        //计算理论结束时间 现开始时间+原执行天数 = 理论结束时间
        $TASK_ENT_TIME = date('Y-m-d',strtotime("+$t day",strtotime($CUR_DATE)));
        $query = "update PROJ_TASK set TASK_START_TIME='$CUR_DATE',TASK_END_TIME='$TASK_ENT_TIME' WHERE TASK_ID='$TASK_ID_NEW'";
        exequery(TD::conn(),$query);
        $USER_ID_STR = $TASK_USER;
        $USER_ID_STR .= "";
        //提醒
        $SMS_CONTENT=sprintf(_("任务[%s]的前置任务已结束,请您开始任务!"), $TASK_NAME);
        $REMIND_URL="1:project/proj/task/task_detail.php?PROJ_ID=".$PROJ_ID."&TASK_ID=".$TASK_ID;
        send_sms("",$_SESSION["LOGIN_USER_ID"],$USER_ID_STR,42,$SMS_CONTENT,$REMIND_URL,$PROJ_ID);
    }

}
//header("location:task_doing.php");
?>