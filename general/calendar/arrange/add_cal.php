<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_email.php");
include_once("inc/utility_calendar.php");
mysql_set_charset(MYOA_DB_CHARSET, TD::conn());
ob_end_clean();

$content = $_GET["content"];
$starttime = $_GET["starttime"];
$caltype = $_GET["caltype"];
$content = td_iconv($content, "utf-8", MYOA_CHARSET);

$endtime = $_GET["endtime"];
$allday = $_GET["alldayflag"];
$repeat = $_GET["repeat"];
$sms_remind = $_GET["smsremind"];
$sms2_remind = $_GET["sms2remind"];
$before_day = intval($_GET["beforeday"]);
$before_hour = intval($_GET["beforehour"]);
$before_min = intval($_GET["beforemin"]);
$owner = $_GET["owner"];
$taker = $_GET["taker"];
$owner = td_iconv($owner, "utf-8", MYOA_CHARSET);
$taker = td_iconv($taker, "utf-8", MYOA_CHARSET);
$remin_type = $_GET["remintype"];
$remind_date = $_GET["reminddate"];
$remind_time = $_GET["remindtime"];
$cal_color = $_GET["calcolor"];
$cal_id = $_GET["cal_id"];
$op = $_GET["op"];
$overstatus = $_GET["overstatus"];

$get_repeat = $_GET["get_repeat"];

$calendar_starttime = $_GET["calendar_starttime"];
$calendar_endtime = $_GET["calendar_endtime"];
$cur_time = date("Y-m-d H:i:s",time());
ob_start();
if($op=="add")
{
    if($repeat!="1")
    {
        $before_remind = $before_day."|".$before_hour."|".$before_min;
        if($before_day || $before_hour || $before_min)
        {
            $s_remind_time = date("Y-m-d H:i:s",strtotime("- $before_day days - $before_hour hours - $before_min minutes",$starttime));
            if($s_remind_time < $cur_time)
            {
                $s_remind_time = $cur_time;
            }
        }
        
        $cal_array = array(
        'USER_ID' => $_SESSION["LOGIN_USER_ID"],
        'CAL_TIME' => $starttime,
        'CAL_TYPE' => $caltype,
        'CONTENT' => $content,
        'END_TIME' => $endtime,
        'BEFORE_REMAIND' => $before_remind,
        'REMIND_TIME' => $s_remind_time,
        'ADD_TIME' => $cur_time,
        'OVER_STATUS' => '0',
        'OWNER' => $owner,
        'TAKER' => $taker,
        'ALLDAY' => $allday,
        'CAL_LEVEL' => $cal_color
        );
        $result = add_calendar($cal_array,$sms_remind,$sms2_remind);
    }
    else
    {
        if($endtime)
        {
            $enddate = strtotime(date("Y-m-d",$endtime));
            $end_time = date("H:i:s",$endtime);
        }
        else
        {
            $enddate = 0;
            $end_time = '';
        }
        $startdate = strtotime(date("Y-m-d",$starttime));
        $start_time = date("H:i:s",$starttime);
        $cur_time1 = date("Y-m-d",time());
        $remind_time1 = $cur_time1." ".$remind_time;
        $remind_time = date("H:i:s",strtotime($remind_time1));
        $cal_array = array(
        'USER_ID' => $_SESSION["LOGIN_USER_ID"],
        'BEGIN_TIME' => $startdate,
        'END_TIME' => $enddate,
        'TYPE' => $remin_type,
        'REMIND_DATE' => $remind_date,
        'REMIND_TIME' => $remind_time,
        'CONTENT' => $content,
        'SMS_REMIND' => $sms_remind,
        'SMS2_REMIND' => $sms2_remind,
        'CAL_TYPE' => $caltype,
        'ADD_TIME' => $cur_time,
        'TAKER' => $taker,
        'BEGIN_TIME_TIME' => $start_time,
        'END_TIME_TIME' => $end_time ,
        'ALLDAY' => $allday  
        );
        
       $result = add_affair($cal_array,"");
    }
}
else if($op=="edit")
{
    if($repeat!="1")
    {
        $before_remind = $before_day."|".$before_hour."|".$before_min;
        if($before_day || $before_hour || $before_min)
        {
            $s_remind_time = date("Y-m-d H:i:s",strtotime("- $before_day days - $before_hour hours - $before_min minutes",$starttime));
            if($s_remind_time < $cur_time)
            {
                $s_remind_time = $cur_time;
            }
        }
        
        $cal_array = array(
        'USER_ID' => $_SESSION["LOGIN_USER_ID"],
        'CAL_TIME' => $starttime,
        'CAL_TYPE' => $caltype,
        'CONTENT' => $content,
        'END_TIME' => $endtime,
        'BEFORE_REMAIND' => $before_remind,
        'REMIND_TIME' => $s_remind_time,
        'ADD_TIME' => $cur_time,
        'OVER_STATUS' => $overstatus,
        'OWNER' => $owner,
        'TAKER' => $taker,
        'ALLDAY' => $allday,
        'CAL_LEVEL' => $cal_color
        );
        $result = update_calendar($cal_array,$cal_id,$sms_remind,$sms2_remind,$get_repeat);
    }
    else
    {
        if($endtime)
        {
            $enddate = strtotime(date("Y-m-d",$endtime));
            $end_time = date("H:i:s",$endtime);
        }
        else
        {
            $enddate = 0;
            $end_time = '';
        }
        $startdate = strtotime(date("Y-m-d",$starttime));
        $start_time = date("H:i:s",$starttime);
        $cur_time1 = date("Y-m-d",time());
        $remind_time1 = $cur_time1." ".$remind_time;
        $remind_time = date("H:i:s",strtotime($remind_time1));
        $cal_array = array(
        'USER_ID' => $_SESSION["LOGIN_USER_ID"],
        'BEGIN_TIME' => $startdate,
        'END_TIME' => $enddate,
        'TYPE' => $remin_type,
        'REMIND_DATE' => $remind_date,
        'REMIND_TIME' => $remind_time,
        'CONTENT' => $content,
        'SMS_REMIND' => $sms_remind,
        'SMS2_REMIND' => $sms2_remind,
        'CAL_TYPE' => $caltype,
        'ADD_TIME' => $cur_time,
        'TAKER' => $taker,
        'BEGIN_TIME_TIME' => $start_time,
        'END_TIME_TIME' => $end_time ,
        'ALLDAY' => $allday  
        );
        
       $result = update_affair($cal_array,$cal_id,$get_repeat);
    }
}

$result2 = array();
$RET_ARRAY = array();
if($repeat!="1")
{
    if($op=="edit")
    {
        $calid = $result["cal_id"];
        $query = "select URL,MANAGER_ID,OWNER,TAKER,USER_ID from CALENDAR where CAL_ID='$calid'";
        $cursor = exequery(TD::conn(),$query);
        if($ROW=mysql_fetch_array($cursor))
        {
            $URL = $ROW["URL"];
            $MANAGER_ID = $ROW["MANAGER_ID"];
            $OWNER = $ROW["OWNER"];
            $TAKER = $ROW["TAKER"];
            $CREATOR = $ROW["USER_ID"];            
        }
        if(($CREATOR==$_SESSION["LOGIN_USER_ID"] && $MANAGER_ID=="") || $MANAGER_ID==$_SESSION["LOGIN_USER_ID"] || $_SESSION["LOGIN_USER_PRIV"]==1 || find_id($OWNER,$_SESSION["LOGIN_USER_ID"]))
        {
            $EDIT_FLAG=1;
            $DELE_FLAG=1;
            $EDITABLE = true;
        }
        else
        {
            $EDIT_FLAG=0;
            $DELE_FLAG=0;
            $EDITABLE = false;
        }
    }
    else
    {
        $URL = "";
        $EDIT_FLAG=1;
        $DELE_FLAG=1;
        $EDITABLE = true;
    }
    $endtime1 = date("Y-m-d H:i:s",$endtime);
    $starttime1 = date("Y-m-d H:i:s",$starttime);
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
        "id" => $result['cal_id'], //主ID
        "title"=> $cal_array['CONTENT'],
		"start"=> $starttime,
		"end"=> $endtime,
		"allDay"=> $allday,
		"className"=> $cal_color,
		"state"=> $STATUS,
		"urls" => $URL,
		"originalTitle"	=> $cal_array['CONTENT'],
		"overstatus" => $cal_array['OVER_STATUS'],
		"edit" => $EDIT_FLAG,
		"dele" => $DELE_FLAG,
		"editable" => $EDITABLE
    );	
        
    $RET_ARRAY = array(
        'status' => $result['status'],
        'events' => array($result2)
    );
}
else
{
    $cal_array['CAL_ID'] = $result['cal_id'];
    $result2 = get_aff_list_data($cal_array, $calendar_starttime, $calendar_endtime);
    $RET_ARRAY = array(
        'status' => $result['status'],
        'events' => $result2
    );
}

ob_clean();
echo retJson($RET_ARRAY);
?>