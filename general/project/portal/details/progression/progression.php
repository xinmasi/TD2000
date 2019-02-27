<?php

/*
 *	获取友好任务关系json
 *	$i_proj_id
 *	return json
 *	zfc 2014-2-28
*/

include_once("inc/auth.inc.php");
include_once("inc/utility_org.php");
include_once("inc/utility.php");
include_once("inc/utility_all.php");

//逻辑
if(isset($by)){
	if($by == "DESC"){
		$BY = "ASC";
	}else{
		$BY = "DESC";
	}
}

$ORDER = isset($order) ? $order : "TASK_SORT";
function getTaskLog($TASK_ID,$LOG_SIZE=5)
{
    $return_str = ''    ;
    $QUERY = "SELECT LOG_ID,LOG_TIME,PERCENT,LOG_USER,LOG_CONTENT,ATTACHMENT_ID,ATTACHMENT_NAME from PROJ_TASK_LOG where TASK_ID='$TASK_ID' order by LOG_USER,LOG_TIME asc limit 0,$LOG_SIZE";
    $CUR = exequery(TD::conn(),$QUERY);
    $LOG_COUNT==0;
    while($ROW = mysql_fetch_array($CUR)){
        $LOG_COUNT++;
        $LOG_ID1=$ROW["LOG_ID"];
        $LOG_TIME1=$ROW["LOG_TIME"];
        $LOG_CONTENT1=$ROW["LOG_CONTENT"];
        $PERCENT1 =$ROW["PERCENT"];
        $LOG_USER1=$ROW["LOG_USER"];
        
        $ATTACHMENT_ID1=$ROW["ATTACHMENT_ID"];
        $ATTACHMENT_NAME1=$ROW["ATTACHMENT_NAME"];

        $query1 = "SELECT * from USER where USER_ID='$LOG_USER1'";
        $cursor1= exequery(TD::conn(),$query1);
        if($ROW1=mysql_fetch_array($cursor1))
            $USER_NAME=$ROW1["USER_NAME"];           
        $return_str .= "$USER_NAME  $LOG_CONTENT1  ($PERCENT1%) $LOG_TIME1 &#13&#10";
    } 
    if ($LOG_COUNT==0){  
      $return_str.='无人';
    }
     return $return_str ;
}

//关系组合
$QUERY = "SELECT TASK_ID,PARENT_TASK,TASK_NAME,TASK_NO,TASK_USER,PROJ_ID,TASK_PERCENT_COMPLETE,TASK_START_TIME,TASK_END_TIME,TASK_TIME FROM PROJ_TASK WHERE PROJ_ID = '$i_proj_id' ORDER BY $ORDER $BY";

$CUR = exequery(TD::conn(),$QUERY);
$datas = array();
while($ROW = mysql_fetch_array($CUR)){
	$datas[$ROW['TASK_ID']]['TASK_ID'] = $ROW['TASK_ID'];
	$datas[$ROW['TASK_ID']]['PARENT_TASK'] = $ROW['PARENT_TASK'];
	$datas[$ROW['TASK_ID']]['TASK_NAME'] = $ROW['TASK_NAME'];
	$datas[$ROW['TASK_ID']]['TASK_NO'] = $ROW['TASK_NO'];
	$datas[$ROW['TASK_ID']]['PROJ_ID'] = $ROW['PROJ_ID'];
	$datas[$ROW['TASK_ID']]['TASK_START_TIME'] = $ROW['TASK_START_TIME'];
	$datas[$ROW['TASK_ID']]['TASK_TIME'] = $ROW['TASK_TIME'];
	$datas[$ROW['TASK_ID']]['TASK_END_TIME'] = $ROW['TASK_END_TIME'];
	$datas[$ROW['TASK_ID']]['TASK_USER'] = td_trim(GetUserNameById($ROW['TASK_USER']));//及其影响速度
	$datas[$ROW['TASK_ID']]['TASK_PERCENT_COMPLETE'] = $ROW['TASK_PERCENT_COMPLETE'];
	$datas[$ROW['TASK_ID']]['TASK_LOG'] = getTaskLog($ROW['TASK_ID']);
}
//友好关系			
function tree($items) { 
	foreach ($items as $item) 
		$items[$item['PARENT_TASK']]['SON'][] = &$items[$item['TASK_ID']]; 
	return isset($items[0]['SON']) ? $items[0]['SON'] : array(); 
}
$datas = tree($datas);

//重构数组（转换1维数组）
// 1                 1
//	1.1              1.1
//  1.2     ===>     1.2
// 2                 2
//  2.1              2.1
//
$DATA = array();
$i = 0;
foreach($datas as $data){
	$DATA[$i++] = array_to_json($data);
	to($data,$i,$DATA);
	//一大类（级别）标识
	$DATA[$i++] = array_to_json(array('TASK_ID'=>'no'));	
}

function to($datas,$i,$DATA){
	if(isset($datas['SON'])){
		foreach($datas['SON'] as $data){
			$DATA[$i++] = array_to_json($data);
			to($data,$i,$DATA);			
		}
	}
}
ob_clean();
echo array_to_json($DATA);
?>