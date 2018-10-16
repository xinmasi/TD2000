<?php
include_once("../inc/auth.php");
include_once("../inc/utility_all.php");
header('Content-Type:text/html; charset=GBK');//使用gb2312编码，使中文不会变成乱码
$backValue=$_POST['trans_data'];
$backValue[6]=substr($backValue[6], 0,10);
$backValue[6]=date('Y-m-d',$backValue[6]);
$backValue[7]=substr($backValue[7], 0,10);
$backValue[7]=date('Y-m-d',$backValue[7]);

 $query=" insert into TD_OA.proj_task 
	(TASK_ID, 
	PROJ_ID, 
	TASK_NO, 
	TASK_NAME, 
	TASK_DESCRIPTION, 
	TASK_USER, 
	TASK_MILESTONE, 
	TASK_START_TIME, 
	TASK_END_TIME, 
	TASK_ACT_END_TIME, 
	TASK_TIME, 
	TASK_LEVEL, 
	PRE_TASK, 
	TASK_PERCENT_COMPLETE, 
	REMARK, 
	FLOW_ID_STR, 
	RUN_ID_STR, 
	TASK_STATUS, 
	TAST_CONSTRAIN, 
	PARENT_TASK
	)
	values
	('TASK_ID', 
	'PROJ_ID', 
	'$backValue[0]', 
	'$backValue[1]', 
	'$backValue[8]', 
	'TASK_USER', 
	'TASK_MILESTONE', 
	'$backValue[6]', 
	'$backValue[7]', 
	'TASK_ACT_END_TIME', 
	'$backValue[11]', 
	'TASK_LEVEL', 
	'PRE_TASK', 
	'TASK_PERCENT_COMPLETE', 
	'$backValue[9]', 
	'$backValue[10]', 
	'RUN_ID_STR', 
	'$backValue[5]', 
	'TAST_CONSTRAIN', 
	'PARENT_TASK'
	)";
exequery($connection,$query);
 
 ?>