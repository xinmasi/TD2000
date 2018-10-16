<? 
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_email.php");
include_once("inc/utility_calendar.php");
mysql_set_charset(MYOA_DB_CHARSET, TD::conn());
ob_end_clean();

$cal_id = intval($_GET["id"]);
$op = $_GET["op"];
$over_status = $_GET["over_status"];
$type = $_GET["type"];
$starttime = $_GET["starttime"];
$endtime = $_GET["endtime"];
$result2 = array();
$RET_ARRAY = array();
$cur_time = date("Y-m-d H:i:s",time());

if($op=="del")
{
    if($type=="calendar")
    {
        $result = delete_calendar($cal_id);
        $RET_ARRAY = array(
        'status' => $result,
        'events' => array()
        );
    }
    else if($type=="affair")
    {
        $result = delete_affair($cal_id);
        $RET_ARRAY = array(
        'status' => $result,
        'events' => array()
        );
    }
    else if($type=="task")
    {
        $result = delete_task($cal_id);
       $RET_ARRAY = array(
        'status' => $result,
        'events' => array()
        );

    }
}
else if($op=="change")
{
    $result = calendar_change_state($cal_id,$over_status); 
    $query = "select * from CALENDAR where CAL_ID='$cal_id'";
    $cursor = exequery(TD::conn(),$query);
    if($ROW=mysql_fetch_array($cursor))
    {
        $starttime = date("Y-m-d H:i:s",$ROW["CAL_TIME"]);
        $endtime = date("Y-m-d H:i:s",$ROW["END_TIME"]);
        $allday = $ROW["allday"]=="1" ? true : false;
        $overstatus = $ROW["OVER_STATUS"];
        $creator = $ROW["USER_ID"];
        $manager_id = $ROW["MANAGER_ID"];
        $owner = $ROW["OWNER"];
        if(($creator == $_SESSION["LOGIN_USER_ID"] && $manager_id=="") || $_SESSION["LOGIN_USER_PRIV"]==1 || $manager_id == $_SESSION["LOGIN_USER_ID"] || find_id($owner,$_SESSION["LOGIN_USER_ID"]))
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
        if($overstatus==0)
        {
            if(compare_time($cur_time,$endtime)>0)
            {
                $STATUS_COLOR="#FF0000";
                $STATUS=_("已超时");
            }
            else if(compare_time($cur_time,$starttime)<0)
            {
                $STATUS_COLOR="#0000FF";
                $STATUS=_("未开始");
            }
            else
            {
                $STATUS_COLOR="#0000FF";
                $STATUS=_("进行中");
            }
        }
        $result2[] = array(
            //"view"=> $view,
            "type"=>"calendar",
            "id" => $ROW['CAL_ID'], //主ID
            "title"=> $ROW['CONTENT'],
    		"start"=> $starttime,
    		"end"=> $endtime,
    		"allDay"=> $allday,
    		"className"=> 'fc-event-color'.$ROW["CAL_LEVEL"],
    		"state"=> $STATUS,
    		"urls" => "",
    		"originalTitle"	=> $ROW['CONTENT'],
    		"overstatus" => $overstatus,
    		"edit" => $edit_flag,
    		"dele" => $dele_flag,
    		"editable" => $edittable
        );	
    }
    $RET_ARRAY = array(
        'status' => $result,
        'events' => $result2
        );   
}
else if($op=="setup")
{
    $result = set_time($starttime,$endtime);
    $RET_ARRAY = array(
    'status' => $result,
    'events' => array()
    );
}
//echo $result;
echo retJson($RET_ARRAY);

?>