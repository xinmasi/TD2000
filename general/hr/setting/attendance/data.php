<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
include_once("inc/attendance/attend.setting.funcs.php");
ob_clean();

$attend = new AttendSetting();

//获取部门人员考勤信息
if($action == "get_deptuser")
{
    $data['dept_id']  = $dept_id;
    $data['page']     = $current;
    $data['month']    = $month;
    $data['pageSize'] = $pageSize;

    $date = $attend->get_deptinfo($data);
    echo $date;
    exit;
}

//保存
if($action == "save")
{
    $date = $attend->save($data);
    echo $date;
    exit;
}

if($action == "get_locations")
{
    $date = $attend->get_location_info();
    echo $date;
    exit;
}

if($action == "get_wifis")
{
    $date = $attend->get_wifi_info();
    echo $date;
    exit;
}

if($action == "save_position")
{
    $data['state'] = $state;
    if($state ==0)
    {
        $data['address']     = td_iconv($address, "utf-8", MYOA_CHARSET);//地点名
        $data['latitude']    = $latitude;
        $data['longitude']   = $longitude;
        $data['offset']      = $offset;
    }
    else
    {
        $data['name']          = td_iconv($name, "utf-8", MYOA_CHARSET);
        $data['macAddress']    = $macAddress;
    }

    $date = $attend->save_position($data);
    echo $date;
    exit;
}

if($action == "del_attend")
{
    $date = $attend->del_coordinate($id);
    echo $date;
    exit;
}
?>