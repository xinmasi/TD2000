<?php
include_once("../inc/auth.php");
include_once("../inc/utility_all.php");
header('Content-Type:text/html; charset=GBK');//使用gb2312编码，使中文不会变成乱码
$query="select 	TASK_ID, 
	PROJ_ID, 
	TASK_NO as id, 
	TASK_NAME as name, 
	TASK_DESCRIPTION as description, 
	TASK_USER as zxr, 
	TASK_MILESTONE, 
	TASK_START_TIME as start, 
	TASK_END_TIME as end, 
	TASK_ACT_END_TIME, 
	TASK_TIME as duration, 
	TASK_LEVEL, 
	PRE_TASK, 
	TASK_PERCENT_COMPLETE as progress, 
	REMARK as bz, 
	FLOW_ID_STR as glgzl, 
	RUN_ID_STR, 
	TASK_STATUS as status, 
	TAST_CONSTRAIN as depends, 
	PARENT_TASK	 
	from 
	TD_OA.proj_task" ;	 
	$taskResult=array();	
 $cursor=exequery($connection,$query);  
while($row=mysql_fetch_assoc($cursor)){
$row['start']=strtotime($row['start'])+'000';
$row['end']=strtotime($row['end'])+'000';
$taskResult[]=$row;
}
?>