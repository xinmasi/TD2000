<?php
include_once("inc/auth.inc.php");

/*
*	get_no.php
*	自动申请任务编号
*	zfc    2014-1-24
*/

$PARENT_ID = intval($PARENT_ID);
$PROJ_ID = intval($PROJ_ID);

if(!empty($PARENT_ID)){

	//选择父级 TASK_NO
	$QUERY = "SELECT TASK_NO FROM PROJ_TASK WHERE TASK_ID = '$PARENT_ID' AND PROJ_ID = '$PROJ_ID'";
	$CUR = exequery(TD::conn(),$QUERY);
	$ROW = mysql_fetch_array($CUR);
	$TASK_NO = $ROW['TASK_NO'] . '.';

	//生成子级编号
	$QUERY = "SELECT TASK_NO FROM PROJ_TASK WHERE PROJ_ID = '$PROJ_ID' AND PARENT_TASK = '$PARENT_ID'";
	$CUR = exequery(TD::conn(),$QUERY);
	$ROW = mysql_num_rows($CUR) + 1;

}else{
	
	$query = "SELECT 1 from PROJ_TASK where PROJ_ID='$PROJ_ID' AND PARENT_TASK = '0'";
    $cursor= exequery(TD::conn(),$query);
    $ROW = mysql_num_rows($cursor) + 1;
	
}

echo json_encode(array('no'=>$TASK_NO.$ROW));

?>