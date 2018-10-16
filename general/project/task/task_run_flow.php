<?php

/*
*   项目任务流程发起模块
*    by   zfc   2014-1-21
*    可引用 可调用
*/
include_once("inc/auth.inc.php");
include_once("inc/flow_hook.php");
include_once("inc/utility_org.php");


$PROJ_ID = intval($PROJ_ID);
$TASK_ID = intval($TASK_ID);

$QUERY = "SELECT * FROM PROJ_TASK WHERE PROJ_ID = '$PROJ_ID' AND TASK_ID = '$TASK_ID'";
$CUR = exequery(TD::conn(),$QUERY); 
if($ROW = mysql_fetch_array($CUR)){

    $TASK_NO = $ROW['TASK_NO'];
    $PROJ_ID = $ROW['PROJ_ID'];
    $TASK_NAME = $ROW['TASK_NAME'];
    $TASK_USER = $ROW['TASK_USER'];
    $TASK_LEVEL = $ROW['TASK_LEVEL'];
    $TASK_PERCENT_COMPLETE = $ROW['TASK_PERCENT_COMPLETE'];
    $TASK_TIME = $ROW['TASK_TIME'];
    $TASK_START_TIME = $ROW['TASK_START_TIME'];
    $TASK_END_TIME = $ROW['TASK_END_TIME'];
    $TASK_DESCRIPTION = $ROW['TASK_DESCRIPTION'];
    $PRE_TASK = $ROW['PRE_TASK'];
    $PARENT_TASK = $ROW['PARENT_TASK'];
    $TASK_MILESTONE = $ROW['TASK_MILESTONE'];
    $REMARK = $ROW['REMARK'];

}

//任务级别数组
$ARR_TASK_LEVEL = array(
                        "次要",
                        "一般",
                        "重要",
                        "非常重要"
                    );


//处理数据
$TASK_USER = rtrim(GetUserNameById($TASK_USER),',');
$TASK_LEVEL = $ARR_TASK_LEVEL[$TASK_LEVEL];
$TASK_TIME = $TASK_TIME . _('天');
$TASK_PERCENT_COMPLETE = $ROW['TASK_PERCENT_COMPLETE'] . '%';

$QUERY = "SELECT TASK_NAME FROM PROJ_TASK WHERE TASK_ID = '$PRE_TASK'";
$CUR = exequery(TD::conn(),$QUERY); 
$ROW = mysql_fetch_array($CUR);
$PRE_TASK = $ROW['TASK_NAME'];

$QUERY = "SELECT TASK_NAME FROM PROJ_TASK WHERE  TASK_ID = '$PARENT_TASK'";
$CUR = exequery(TD::conn(),$QUERY); 
$ROW = mysql_fetch_array($CUR);
$PARENT_TASK = $ROW['TASK_NAME'];

if(empty($PRE_TASK))
    $PRE_TASK = _("无");
if(empty($PARENT_TASK))
    $PARENT_TASK = _("无");

$QUERY = "SELECT * FROM PROJ_TASK_LOG WHERE TASK_ID = '$TASK_ID' ORDER BY LOG_TIME DESC";
$CUR = exequery(TD::conn(),$QUERY);
$LOG_CONTENT = "";
while($ROW = mysql_fetch_array($CUR)){
    if(!empty($ROW['LOG_CONTENT']))
        $LOG_CONTENT .= _("进度 : ") . $ROW['PERCENT'] . '%\n' . _('描述 : ') . $ROW['LOG_CONTENT'] . '\n' . _('时间 : ') .  $ROW['LOG_TIME'] . '\n\r';
}

$QUERY = "SELECT PROJ_NAME FROM PROJ_PROJECT WHERE PROJ_ID = '$PROJ_ID'";
$CUR = exequery(TD::conn(),$QUERY);
$ROW1 = mysql_fetch_array($CUR);
$PROJ_NAME = $ROW1['PROJ_NAME'];

$data_array = array(
                    "KEY"=>"$TASK_ID",
                    "field"=>"TASK_ID",
                    "TASK_NO"=>"$TASK_NO",
                    "TASK_NAME"=>"$TASK_NAME",
                    "TASK_USER"=>"$TASK_USER",
                    "TASK_LEVEL"=>"$TASK_LEVEL",
                    "TASK_PERCENT_COMPLETE"=>"$TASK_PERCENT_COMPLETE",
                    "TASK_TIME"=>"$TASK_TIME",
                    "PRE_TASK"=>"$PRE_TASK",
                    "PARENT_TASK"=>"$PARENT_TASK",
                    "TASK_START_TIME"=>"$TASK_START_TIME",
                    "TASK_END_TIME"=>"$TASK_END_TIME",
                    "TASK_DESCRIPTION"=>"$TASK_DESCRIPTION",
                    "LOG_CONTENT"=>"$LOG_CONTENT",
                    "TASK_MILESTONE"=>"$TASK_MILESTONE",
                    "REMARK"=>"$REMARK",
					"PROJ_ID"=>"$PROJ_ID",
					"PROJ_NAME"=>"$PROJ_NAME"
                );
				
				
$status = 0;
$config = array("module" => "project_task_x1");
run_hook($data_array,$config);
?>