<?php
//�����һ��������������ʱ�����

include_once("inc/auth.inc.php");
include_once ("inc/utility_project.php");

$TASK_ID = intval($TASK_ID);

$proj_hook = project_hook("project_task_x1");
if($proj_hook == 1){
	$run_id = get_run_id("TASK_ID",$TASK_ID);
	if($run_id){
		$num = count($run_id);
		$run_id = $run_id[($num >= 1)? $num - 1 : 0];
		if(!project_build($run_id)){
			echo 0;
		}else{
			echo 1;
		}
	}else{
		echo 1;
	}
}else{
	echo 1;
}
?>