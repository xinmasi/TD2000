<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
ob_end_clean();

$filter = json_decode(stripslashes($filter),true);
if(is_array($filter))
{
   foreach($filter as $key => $array)
   {
      if($array['property'] && $array['value'])
         $$array['property'] = $array['value'];
   }
}

if($DEBUG)
{
	print_r($filter);
	exit;
}
function level_desc($level)
{
   switch($level)
   {
      case "0": return _("次要");
      case "1": return _("一般");
      case "2": return _("重要");
      case "3": return _("非常重要");
   }
}
/**
 * 获取前置任务的“任务状态”字段 by dq 090629
 */
function getPreTaskStatus($TASK_ID)
{
   $PRE_TASK_STATUS = "0";
   
   $query = "select TASK_STATUS from PROJ_TASK where TASK_ID='$TASK_ID'";
   $cursor = exequery(TD::conn(), $query);
   if ($ROW = mysql_fetch_array($cursor))
   {
      $PRE_TASK_STATUS = $ROW["TASK_STATUS"];
   }
   
   return $PRE_TASK_STATUS;
}
//if($act=="count")
   $query_count = "select count(*) from PROJ_TASK LEFT JOIN PROJ_PROJECT ON (PROJ_TASK.PROJ_ID=PROJ_PROJECT.PROJ_ID) WHERE PROJ_TASK.TASK_USER='".$_SESSION["LOGIN_USER_ID"]."' and PROJ_PROJECT.PROJ_STATUS ='2'";// AND PROJ_PROJECT.PROJ_STATUS IN (2,3)
//else
   $query = "select PROJ_PROJECT.PROJ_NAME,PROJ_PROJECT.PROJ_END_TIME,PROJ_PROJECT.PROJ_ACT_END_TIME,PROJ_PROJECT.PROJ_ID,PROJ_TYPE,PROJ_NUM,PROJ_STATUS,TASK_ID,TASK_NAME,PRE_TASK,TASK_START_TIME,TASK_END_TIME,TASK_ACT_END_TIME,TASK_TIME,TASK_STATUS,TASK_LEVEL 
   		from PROJ_TASK LEFT OUTER JOIN PROJ_PROJECT ON (PROJ_TASK.PROJ_ID=PROJ_PROJECT.PROJ_ID) WHERE PROJ_TASK.TASK_USER='".$_SESSION["LOGIN_USER_ID"]."'  and PROJ_PROJECT.PROJ_STATUS ='2'";//AND PROJ_PROJECT.PROJ_STATUS in(2,3)

if($PROJ_ID)
   $query_str .= " AND PROJ_TASK.PROJ_ID='$PROJ_ID'";

if($HIDEFIN == 'false' || empty($HIDEFIN)){
   $query_str .= " AND PROJ_TASK.TASK_STATUS='0'";
}
if($RANGE!=0)
{
  $WEEK_START=date("Y-m-d",strtotime("-".date("w",time())."days"));
  $WEEK_END=date("Y-m-d",strtotime("+6 days",strtotime($WEEK_START)));
  $MONTH_START=date("Y-m",time())."-01";
  $MONTH_END=date("Y-m",time())."-".date("t");
  $TODAY=date("Y-m-d");
  
  switch ($RANGE)
  {
  	//本周任务
  	case 1:
  	  $query_str .=" AND TASK_END_TIME<='$WEEK_END' && TASK_END_TIME>='$WEEK_START'";
  	  break;
  	//本月任务
    case 2:
  	  $query_str .=" AND TASK_END_TIME<='$MONTH_END' && TASK_END_TIME>='$MONTH_START'";
  	  break;
  	//未来任务
  	case 3:
  	  $query_str .=" AND TASK_START_TIME>='$TODAY'";
  	  break;
  	default:
  	  break;
  }
}

//if($act=="count")
//{
$query_count = $query_count.$query_str;
 $cursor = exequery(TD::conn(),$query_count);
 if($ROW=mysql_fetch_array($cursor))
    $TASK_COUNT=$ROW[0];
// echo $TASK_COUNT;
// exit;
//}



$query_str .= " ORDER BY TASK_START_TIME ASC";
//$limit = $endrecord-$start+1;
$limit = $limit ? $limit : 10;
$start = $start ? $start :  0;
$query_str1 = " limit $start,$limit";
$query_data = $query.$query_str.$query_str1;

$COUNT = 0;
$cursor = exequery(TD::conn(), $query_data);

if($page>1 && !mysql_affected_rows()>0)
{
	for($i=0;$i<$page;$i++)
	{
		$page = $page-1;
		$start = $start-$limit;
		$query_str1 = " limit $start,$limit";
		$query_data = $query.$query_str.$query_str1;
		$cursor = exequery(TD::conn(), $query_data);
		if(mysql_affected_rows()>0)
		{
			break;
		}	
	}
}
while($ROW = mysql_fetch_array($cursor))
{
   $TASK_ARRAY = array();
	 $TASK_ID = $ROW["TASK_ID"];
	 $PROJ_ID=$ROW["PROJ_ID"];
	 $PROJ_STATUS = $ROW["PROJ_STATUS"];
	 $PROJ_NAME = $ROW["PROJ_NAME"];
	 $PROJ_END_TIME = $ROW["PROJ_END_TIME"];
	 $PROJ_ACT_END_TIME = $ROW["PROJ_ACT_END_TIME"];
	 $PROJ_NUM = $ROW["PROJ_NUM"];
	 $PROJ_TYPE = $ROW["PROJ_TYPE"];
	 $TASK_NAME = $ROW["TASK_NAME"];
	 $PRE_TASK = $ROW["PRE_TASK"];
	 $TASK_START_TIME = $ROW["TASK_START_TIME"];
	 $TASK_END_TIME = $ROW["TASK_END_TIME"];
	 $TASK_ACT_END_TIME = $ROW["TASK_ACT_END_TIME"];
	 if(strtotime($TASK_ACT_END_TIME)>0)
	   $TASK_END_TIME = $TASK_ACT_END_TIME;
	 $TASK_TIME = $ROW["TASK_TIME"];
	 $TASK_LEVEL = $ROW["TASK_LEVEL"];//任务等级 by lp 110110
	 $TASK_STATUS = $ROW["TASK_STATUS"];//任务状态 by dq 090628
if(strtotime($PROJ_ACT_END_TIME)>0)
   $PROJ_END_TIME = $PROJ_ACT_END_TIME;
    $PRE_TASK_STATUS = getPreTaskStatus($PRE_TASK);
    $PRE_TASK_FLAG = false;
    if ($PRE_TASK != "" && $PRE_TASK_STATUS == "1" || $PRE_TASK == "" || $PRE_TASK == "0" )
       $PRE_TASK_FLAG = true;

	 $COUNT++;

$TASK_ARRAY['TASK_ID'] = $TASK_ID;
$TASK_ARRAY['PROJ_NUM'] = $PROJ_NUM;
$TASK_ARRAY['PROJ_NAME'] = '<a href="#" title="' . $PROJ_NAME . '" onclick=projDetail("'.$PROJ_ID.'")>'.$PROJ_NAME.'</a>';
//$TASK_ARRAY['PROJ_NAME'] = $PROJ_NAME;
$TASK_ARRAY['TASK_NAME'] = '<a href="#" title="' . $TASK_NAME . '" onclick=taskDetail("'.$TASK_ID.'")>'.$TASK_NAME.'</a>';
//$TASK_ARRAY['TASK_NAME'] = $TASK_NAME;
$TASK_ARRAY['TASK_LEVEL'] = '<span class="CalLevel'.(4-$TASK_LEVEL).'" title="'.level_desc($TASK_LEVEL).'">'.level_desc($TASK_LEVEL).'</span>';
//$TASK_ARRAY['TASK_LEVEL'] = $TASK_LEVEL;
$TASK_ARRAY['TASK_START_TIME'] = $TASK_START_TIME;
$TASK_ARRAY['TASK_TIME'] = $TASK_TIME._("工作日");
$TASK_ARRAY['TASK_END_TIME'] = $TASK_END_TIME;
$TASK_ARRAY['PROJ_END_TIME'] = $PROJ_END_TIME;
if($TASK_STATUS=="1")
{
   $TASK_ARRAY['TASK_STATUS'] = '<font color=red>'._("已结束").'</font>';
}else{
   if($TASK_START_TIME > date("Y-m-d",time()))
      $TASK_ARRAY['TASK_STATUS'] = _("未到开始时间");
   else if($PRE_TASK_FLAG==false)
      $TASK_ARRAY['TASK_STATUS'] =  _("前置任务尚未结束");
   else 
      $TASK_ARRAY['TASK_STATUS'] = '<font color=blue>'._("执行中").'</font>';
}
//$TASK_ARRAY['TASK_STATUS'] = $TASK_STATUS;
$action = '';
if($TASK_STATUS=="0" && $TASK_START_TIME <= date("Y-m-d",time()) && $PRE_TASK_FLAG==true)
{
   $action.= '<a href="javascript:edit_task('. $PROJ_ID . ',' . $TASK_ID . ');" >'._("办理").'</a>&nbsp;&nbsp;';
   $action.= '<a href="javascript:check_task_finished('. $PROJ_ID . ',' . $TASK_ID . ');" >'._("结束").'</a>';
}
else
{
   if($TASK_STATUS=="1")
   {
      //echo '<a href="javascript:;" onclick="view_task_detail('.$PROJ_ID.','.$TASK_ID.')">任务明细</a>&nbsp;';
      //$action.= '<font color=red>'._("已结束").'</font>';
      if($PROJ_STATUS=="2")
         $action.= '<a href="###" onclick=resume_task("'.$TASK_ID.'")>'._("恢复执行").'</a>&nbsp;';
      else  
         $action.= '-&nbsp;';
   }
  // else if($TASK_START_TIME > date("Y-m-d",time()))
   //   $action.= _("未到开始时间");
  // else if($PRE_TASK_FLAG==false)
   //   $action.= _("前置任务尚未结束");
}
$TASK_ARRAY['action'] = $action;//$action;
$RET_ARRAY[] = $TASK_ARRAY;
}

ob_clean();
echo array_to_json(array('results'=> $TASK_COUNT,'datastr'=> $RET_ARRAY));