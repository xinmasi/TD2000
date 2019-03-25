<?php


function gbk2utf8($data){
	if(is_array($data)){
		return array_map('gbk2utf8', $data);
	}
	return iconv('gbk','utf-8',$data);
}

$query = "SELECT CODE_NO,CODE_NAME FROM SYS_CODE WHERE PARENT_NO='PROJ_TYPE'";
$cursor = exequery(TD::conn(), $query);
$typeMap = array();
$typeSqlArray = array();
$i = 0;
while($ROW = mysql_fetch_array($cursor)){
	$typeMap[$ROW['CODE_NO']] = $ROW['CODE_NAME'];
	$typeSqlArray[$i] = $ROW;
	$i++;
}

$dateType = $_REQUEST['dateType'];
$month = $_REQUEST['month'];
$projDeptName = $_REQUEST['projDeptName'];
$deptId = $_REQUEST['deptId'];
$leaderName = $_REQUEST['leaderName'];//项目负责人
$leaderId = $_REQUEST['leaderId'];

$queryMonth = "";

if(isset($dateType) && $dateType == "month"){
	if(!isset($month)  || $month == ""){
		$month = date("Y-m");
	}
	$start = $month."-01";
	$end = $month."-31";
	$queryMonth = " AND ( TASK_START_TIME <= '$end' AND TASK_END_TIME >= '$start'  )";
}else if (isset($dateType) && $dateType == "week"){
	if(!isset($month)  || $month == ""){
		$month = date("Y-m");
	}
	$start = $month."-01";
	$end = $month."-31";
	$queryMonth = " AND ( TASK_START_TIME <= '$end' AND TASK_END_TIME >= '$start'  )";
}else if (isset($dateType) && $dateType == "day"){
	if(!isset($month)  || $month == ""){
		$month = date("Y-m-d");
	}
	$queryMonth = " AND ( TASK_START_TIME <= '$month' AND TASK_END_TIME >= '$month'  )";
}



$queryDept = "";
if(isset($projDeptName)  && $projDeptName != ""){
	$deptArray = explode(",",$projDeptName);
	$queryDept .= " AND (";
	$i=1;
	foreach ($deptArray as $dept){
		if(isset($dept) && $dept != ""){
			$queryDept .= " d.DEPT_NAME LIKE '%$dept%' ";
		}
		$i++;
		if ($i < sizeof($deptArray))
			$queryDept .= " OR ";
	}
	$queryDept .= " ) ";
}

$queryLeader = "";
if(isset($leaderName)  && $leaderName != ""){
	$deptArray = explode(",",$leaderName);
	$queryLeader .= " AND (";
	$i=1;
	foreach ($deptArray as $dept){
		if(isset($dept) && $dept != ""){
			$queryLeader .= " u.USER_NAME LIKE '%$dept%' ";
		}
		$i++;
		if ($i < sizeof($deptArray))
			$queryLeader .= " OR ";
	}
	$queryLeader .= " ) ";
}


$query = "SELECT TASK_ID as id,PARENT_TASK as pId,CONCAT(TASK_NO,' ',TASK_NAME) as name,TASK_NO,TASK_USER,t.PROJ_ID,TASK_PERCENT_COMPLETE,TASK_START_TIME,TASK_END_TIME,TASK_TIME,p.PROJ_NAME,p.PROJ_TYPE,d.DEPT_ID,d.DEPT_NAME,u.USER_ID,u.USER_NAME
FROM PROJ_TASK AS t 
LEFT JOIN PROJ_PROJECT AS p  ON T.PROJ_ID = p.PROJ_ID
LEFT JOIN USER AS u ON u.USER_ID = p.PROJ_LEADER
LEFT JOIN DEPARTMENT AS d ON u.DEPT_ID = d.DEPT_ID
WHERE 1=1 $queryMonth $queryDept $queryLeader
ORDER BY p.PROJ_ID,t.TASK_SORT 
";
$cursor = exequery(TD::conn(), $query);
$date = array();
while($ROW = mysql_fetch_array($cursor)){
// 	{"CONTACT_USER":"xxx项目","SECTOR_NAME":"某某","CONTACT_PHONE":"18888888888","addFlag":true,"ORG_ID":1,"id":"o1","pId":"onull","open":true,"name":"3.编码落地","modFlag":true,"CORP_CAT":"港口-天然液化气,港口-液化石油气","TYPE":"org","delFlag":true},
	
	
	array_push($date,$ROW);
	
}
$jsonDate = json_encode(gbk2utf8($date));

?>
