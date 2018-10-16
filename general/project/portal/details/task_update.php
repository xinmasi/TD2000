<?php
include_once("../inc/auth.php");
include_once("../inc/utility_all.php");
header('Content-Type:text/html; charset=GBK');//使用gb2312编码，使中文不会变成乱码
$backValue=$_POST['trans_data'];
$backValue[6]=substr($backValue[6], 0,10);
$backValue[6]=date('Y-m-d',$backValue[6]);
$backValue[7]=substr($backValue[7], 0,10);
$backValue[7]=date('Y-m-d',$backValue[7]);
 $query="
update TD_OA.proj_task 
	set
	TASK_ID = 'TASK_ID' , 
	PROJ_ID = 'PROJ_ID' , 
	TASK_NO = 'TASK_NO' , 
	TASK_NAME = 'TASK_NAME' , 
	TASK_DESCRIPTION = 'TASK_DESCRIPTION' , 
	TASK_USER = 'TASK_USER' , 
	TASK_MILESTONE = 'TASK_MILESTONE' , 
	TASK_START_TIME = 'TASK_START_TIME' , 
	TASK_END_TIME = 'TASK_END_TIME' , 
	TASK_ACT_END_TIME = 'TASK_ACT_END_TIME' , 
	TASK_TIME = 'TASK_TIME', 
	TASK_LEVEL = 'TASK_LEVEL' , 
	PRE_TASK = 'PRE_TASK' , 
	TASK_PERCENT_COMPLETE = 'TASK_PERCENT_COMPLETE' , 
	REMARK = 'REMARK' , 
	FLOW_ID_STR = 'FLOW_ID_STR' , 
	RUN_ID_STR = 'RUN_ID_STR' , 
	TASK_STATUS = 'TASK_STATUS' , 
	TAST_CONSTRAIN = 'TAST_CONSTRAIN' , 
	PARENT_TASK = 'PARENT_TASK'
	
	where
	TASK_ID = 'TASK_ID' ;

";
exequery($connection,$query);
 
 ?>