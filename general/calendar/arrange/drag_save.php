<?
include_once("inc/auth.inc.php");
include_once("inc/utility_email.php");
include_once("inc/utility_calendar.php");

while(list($KEY, $VALUE) = each($_POST))
{
    $$KEY = trim($VALUE);
}
$cur_time = date("Y-m-d H:i:s",time());
if($type == 'calendar')
{
    $result2 = array();
    $CAL_ARRAY = array('CAL_TIME'=> $start_time,'END_TIME'=> $end_time);
    $a_result = update_calendar($CAL_ARRAY,$cal_id,0,0,false);
    $query = "select * from CALENDAR where CAL_ID='$cal_id'";
    $cursor = exequery(TD::conn(),$query);
    if($ROW=mysql_fetch_array($cursor))
    {
        $cal_time = $ROW["CAL_TIME"];
        $end_time = $ROW["END_TIME"];
        $cal_color = $ROW["CAL_LEVEL"];
        $allday = $ROW["ALLDAY"];
        $URL = $ROW["URL"];
        $creator = $ROW["USER_ID"];
        $manage_id = $ROW["MANAGER_ID"];
        $taker = $ROW["TAKER"];
        $owner = $ROW["OWNER"];               
    }
    //是否有编辑、删除权限
    if(($creator == $_SESSION["LOGIN_USER_ID"] && $manage_id=="") || $manage_id==$_SESSION["LOGIN_USER_ID"] || $_SESSION["LOGIN_USER_PRIV"]==1 || find_id($owner,$_SESSION["LOGIN_USER_ID"]))
    {
        $edit_flag = 1;
        $dele_flag = 1;
        $edittable = true;
    }
    else
    {
        $edit_flag = 0;
        $dele_flag = 0; 
        $edittable = false;
    }
    $endtime1 = date("Y-m-d H:i:s",$end_time);
    $starttime1 = date("Y-m-d H:i:s",$cal_time);
	if(compare_time($cur_time,$endtime1)>0)
	{
		$STATUS_COLOR="#FF0000";
		$STATUS=_("已超时");
	}
	else if(compare_time($cur_time,$starttime1)<0)
	{
		$STATUS_COLOR="#0000FF";
		$STATUS=_("未开始");
	}
	else
	{
		$STATUS_COLOR="#0000FF";
		$STATUS=_("进行中");
	}
	
	if($allday)
	{
	    $allday = true;
	}
	else
	{
	    $allday = false;
	}
	$cal_color = 'fc-event-color'.$cal_color;
	$result2 = array(
        //"view"=> $view,
        "type"=>"calendar",
        "id" => $cal_id, //主ID
        "title"=> $ROW['CONTENT'],
		"start"=> $cal_time,
		"end"=> $end_time,
		"allDay"=> $allday,
		"className"=> $cal_color,
		"state"=> $STATUS,
		"urls" => $URL,
		"originalTitle"	=> $ROW['CONTENT'],
		"overstatus" => $ROW['OVER_STATUS'],
		"edit" => $edit_flag,
		"dele" => $dele_flag,
		"editable" => $edittable
    );
    $RET_ARRAY = array(
        'status' => $a_result['status'],
        'events' => array($result2)
    );	   
}
echo retJson($RET_ARRAY);
//echo $a_result['status'];
?>