<?php
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility.php");
include_once("inc/check_type.php");
include_once("inc/utility_org.php");
include_once("inc/utility_project.php");
$PROJ_STATUS = intval($_GET['PROJ_STATUS']);
$LOGIN_USER_ID = $_SESSION['LOGIN_USER_ID'];
// $limit = $limit ? $limit : 9;
// $start = $start ? $start : 0;


//返回处理过的字符串   
function splitStr($str,$c)
{
	$str=addslashes($str);
	preg_match_all("/[".chr(0xa1)."-".chr(0xff)."]+/",$str,$arr);
	$arr1=$arr[0];
	$strcn=join($c,str_split(implode('',$arr1),2));
	
	preg_match_all("/[a-zA-Z0-9]+/",$str,$arren);
	$arr2=$arren[0];
	$stren=join($c,str_split(implode('',$arr2),1));
	$str=$strcn.$stren;
	return $str;
}


$fields = " p.PROJ_ID,p.PROJ_NUM , p.PROJ_NAME, p.PROJ_LEADER, p.PROJ_START_TIME, p.PROJ_END_TIME, p.PROJ_UPDATE_TIME, p.PROJ_PERCENT_COMPLETE, p.PROJ_LEVEL, p.NEW_CHANGE, p.PROJ_VIEWER, p.PROJ_TYPE, p.PROJ_OWNER, p.PROJ_MANAGER, p.PROJ_STATUS ";
$auth_sql = project_auth_sql();

$QUERY = "";

if(isset($find)){

	if(!isset($PROJ_NUM_M)){
		$PROJ_NUM = isset($PROJ_NUM)?" AND PROJ_NUM = '" . $PROJ_NUM ."'": "";
	}else{
		$PROJ_NUM = isset($PROJ_NUM)?" AND PROJ_NUM like '%" . splitStr($PROJ_NUM,'%') . "%'": "";
	}
	if(!isset($PROJ_NAME_M)){
		$PROJ_NAME = isset($PROJ_NAME)?" AND PROJ_NAME = '" . $PROJ_NAME ."'": "";
	}else{
		$PROJ_NAME = isset($PROJ_NAME)?" AND PROJ_NAME like '%" . splitStr($PROJ_NAME,'%') . "%'": "";
    }
    $PROJ_TYPE = isset($PROJ_TYPE)?" AND PROJ_TYPE = '" . $PROJ_TYPE ."'": "";
    $PROJ_START_TIME = isset($PROJ_START_TIME) ? " AND PROJ_START_TIME >= '" . $PROJ_START_TIME . "'":"";
    $PROJ_END_TIME = isset($PROJ_END_TIME)?" AND PROJ_END_TIME <= '" . $PROJ_END_TIME . "'":"";
    
    ////$PROJ_LEVEL = isset($PROJ_LEVEL)?" AND PROJ_LEVEL = '" . $PROJ_LEVEL ."'": "";
    if(isset($PROJ_LEVEL)){
        $PROJ_LEVEL = str_split($PROJ_LEVEL);
        $PROJ_LEVELS = "";
        //and (FIND_IN_SET('b',PROJ_LEVEL) || FIND_IN_SET('a',PROJ_LEVEL));
        foreach($PROJ_LEVEL as $KEY => $VAL){
            $PROJ_LEVELS .= " FIND_IN_SET('$VAL',PROJ_LEVEL) ||";
        }
        $PROJ_LEVELS = rtrim($PROJ_LEVELS,'||');
        $PROJ_LEVEL = " AND ( " . $PROJ_LEVELS . " ) ";
    }else{
        $PROJ_LEVEL = "";
    }
    
    
    $QUERY .= $PROJ_NUM . $PROJ_NAME . $PROJ_TYPE . $PROJ_LEVEL .$PROJ_START_TIME . $PROJ_END_TIME;
}
   

$a_proj_all = "select $fields FROM PROJ_PROJECT as p where PROJ_STATUS = '$PROJ_STATUS' $QUERY $auth_sql order by PROJ_START_TIME desc,PROJ_ID desc";
//9为临时位  当审批未通过所占用 除了<项目审批> 中不取状态9 其他地方均吧状态9作为状态1
if($PROJ_STATUS == 1)
	$a_proj_all = "select $fields FROM PROJ_PROJECT as p where (PROJ_STATUS = '$PROJ_STATUS' or PROJ_STATUS = '9') $QUERY $auth_sql order by PROJ_START_TIME desc,PROJ_ID desc";



//当前页
if(!isset($PAGE))
    $PAGE = 0;

//每页个数
$NUM = 28;

//当前个数
$START = $PAGE*$NUM;

//分页
$a_proj_all .= " limit $START,$NUM";


//设置权限 只有admin 审批人 查看人 所有者 可以看见项目。
$a_proj_cursor = exequery(TD::conn(), $a_proj_all);
$i_count = mysql_num_rows($a_proj_cursor);//计算数据库中一共有多少条记录
$a_new_array = array();//定义一个空的数组，不管有没有值
$cal_time = date("N");
while ($a_row = mysql_fetch_assoc($a_proj_cursor)) 
{
	//9为临时位  当审批未通过所占用 除了<项目审批> 中不取状态9 其他地方均吧状态9作为状态1
	if($a_row['PROJ_STATUS'] == 9)
		$a_row['PROJ_STATUS'] = 1;
    if($cal_time == 3 || $a_row['PROJ_PERCENT_COMPLETE']==0)
    {
        $i_proj_id = $a_row['PROJ_ID'];
        $I_day = 0;               //当前任务进行时间
        $total_task_time = 0;     //任务总时间（总天数）  
        $s_select_task = "select TASK_TIME,TASK_PERCENT_COMPLETE from proj_task where PROJ_ID='$i_proj_id'";
        $res_cursor_task = exequery(TD::conn(), $s_select_task);
		if(mysql_affected_rows())
		{
			while ($a_task = mysql_fetch_array($res_cursor_task))
			{
				$total_task_time += $a_task["TASK_TIME"];
				$I_day += $a_task["TASK_TIME"] * $a_task["TASK_PERCENT_COMPLETE"];//工期X进度==把百分数转换成天数来表现
			}
			if($total_task_time)
			{
				$total = $I_day / $total_task_time;  //总进度
			}
			
			$a_row['PROJ_PERCENT_COMPLETE'] = $i_proj_complete = (int)$total;

			$update_proj_complete = "update proj_project set PROJ_PERCENT_COMPLETE = '$i_proj_complete' where PROJ_ID = '$i_proj_id'";
			exequery(TD::conn(), $update_proj_complete);
		}
    }
    $sys_time = time();

    $a_row["PROJ_LEADER"] = rtrim(GetUserNameById($a_row["PROJ_LEADER"]),",");
    /**
     * XMYJ 项目预警
     * >0 超时xx天
     * 0 今天超时
     * -1 未超时 
     * 对于以结束的项目，计算应以实际与计划结束时间取差值
     */
    $a_row['XMYJ'] = "0";
    if($a_row['PROJ_STATUS'] == 3)
    {
        $YJ =strtotime($a_row["PROJ_ACT_END_TIME"]) -  strtotime($a_row["PROJ_END_TIME"]);
    }else
    {
        $YJ = round(($sys_time-strtotime($a_row["PROJ_END_TIME"]))/(3600*24));
    }
    
    if($YJ > 0 )
    {
        $a_row['XMYJ'] = $YJ -1;
    }else
    {
        $a_row['XMYJ'] = -1;
    }
    
    $a_new_array[] = $a_row;
}
$timeout_project = $doing_project = array();
foreach($a_new_array as $new_array)
{
    if($new_array['XMYJ'] != -1)
    {
        $timeout_project[] = $new_array;
    }else
    {
        $doing_project[] = $new_array;
    }
}
$a_new_array = array_merge($timeout_project,$doing_project);

$a_line = array('count' => $i_count, 'data' => $a_new_array);
$s_to_json = array_to_json( $a_line );
ob_clean();
echo $s_to_json;
