<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
include_once("inc/chinese_date.php");
ob_clean();

//include_once("inc/check_components.php");
//checkComponents::isExitApp('MOBILE_ATTENDANCE');
$uid = $_GET['user_id'] ? GetUidByUserID($_GET['user_id']) : $_SESSION['LOGIN_UID'];
$user_id = $_GET['user_id'] ? $_GET['user_id'] : $_SESSION['LOGIN_USER_ID'];
if(!$start)
    exit;

    
if(substr($start, -2) != "01")
{
    $timestamp = strtotime($start) + 10 * 24 * 3600;
}
else
{
    $timestamp = strtotime($start);
}

$start_date = date("Y-m-01 00:00:00", $timestamp);
$end_date = date("Y-m-t 23:59:59", $timestamp);
$total_day = intval(date("t", $timestamp));
$dskey = date("Y-m-01", $timestamp);
$dekey = date("Y-m-t", $timestamp);


/*$HOLIDAY = "";
$SOME_DATE = date("Y-m-d", time());
$WEEK = date("w",strtotime($SOME_DATE));
$query = "select * from ATTEND_HOLIDAY where BEGIN_DATE <='$SOME_DATE' and END_DATE>='$SOME_DATE'";
$cursor = exequery(TD::conn(),$query);
if($ROW = mysql_fetch_array($cursor)){
    $HOLIDAY = "<font color='#008000'>"._("节假日")."</font>";
}else{
    if(find_id($GENERAL, $WEEK))
        $HOLIDAY = "<font color='#008000'>"._("公休日")."</font>";
}*/

 //--- 查询上下班登记记录 ---
$CUR_DATE = date("Y-m-d",time());
$data = array();
$mids = '';
$query = "SELECT * from ATTEND_DUTY where USER_ID='$user_id' and REGISTER_TIME > '".$start_date."' and REGISTER_TIME <= '".$end_date."' and IS_MOBILE_DUTY = '1' ORDER BY REGISTER_TIME DESC";
$cursor= exequery(TD::conn(),$query,true);
while($ROW=mysql_fetch_array($cursor))
{
    // if($ROW['ATTEND_MOBILE_ID']!= 0)
    //     $mids.= $ROW['ATTEND_MOBILE_ID'].",";

    $data[date("Y-m-d", strtotime($ROW['REGISTER_TIME']))][$ROW['REGISTER_TYPE']] = $ROW;
}

//查询登记地点
// $location_data = array();
// if($mids!="")
// {
//     $mids = rtrim($mids, ",");
//     $query = "SELECT * from ATTEND_MOBILE where M_ID in ($mids)";
//     $cursor= exequery(TD::conn(),$query,true);
//     while($ROW=mysql_fetch_array($cursor))
//     {
//         $location_data[$ROW['M_ID']] = $ROW['M_LOCATION'];
//     }
// }

//最终数据
$output = array();
//--- 查询一般签到登记记录 ---
$start = strtotime($start_date);
$end = strtotime($end_date);
$query2 = "select M_ID,M_TIME,M_LOCATION,M_ISFOOT from ATTEND_MOBILE where M_ISFOOT = 2 and M_TIME >= '$start' and M_TIME <= '$end' and M_UID = '$uid'";
$cursor2 = exequery(TD::conn(),$query2);
while($row2 = mysql_fetch_array($cursor2))
{
    $id = $row2['M_ID'];
    $m_time = $row2['M_TIME'];
    $m_isfoot = $row2['M_ISFOOT'];
    //$data[date("Y-m-d", $row2['M_TIME'])][0] = $row2;
    if($m_time!="")
        {
            $output[] = array(
                'id' => $id,
                'title' => '外勤打卡',
                'type' => 0,
                'color' => "#51a351",
                'start' => date('Y-m-d H:i:s',$m_time),
                'date' => date('Y-m-d',$m_time),
                'm_isfoot' => $m_isfoot
            );
        }
}
//file_put_contents('1.txt', var_export($output, true)."\r\n".$query2);
for($J=1; $J <= $total_day; $J++)
{
    $current_day = date("Y-m-d", (strtotime($dskey) + ($J-1)*24*3600));
    if($current_day > $dekey)
        continue;

    //查询当天是否有排班
    $sql = "SELECT duty_type FROM user_duty WHERE uid = '$uid' AND duty_date = '$current_day'";
    $cursor= exequery(TD::conn(),$sql);
    if($row=mysql_fetch_array($cursor))
    {
        $user_duty = $row[0];
    }
    if(empty($user_duty))
    {
        $query="select * from attend_holiday where BEGIN_DATE <='$current_day' and END_DATE>='$current_day'";
        $cursor= exequery(TD::conn(),$query);
        if($ROW=mysql_fetch_array($cursor))
        {
            $HOLIDAY = "<font color='#008000'>"._("公休日")."</font>";
        }
        else
        {
            $HOLIDAY = "<font color='#008000'>"._("未安排班次")."</font>";
        }
    }

    $sql1 = "SELECT * FROM attend_config WHERE DUTY_TYPE = '$user_duty' and DUTY_TYPE!='99'";
    $cursor1= exequery(TD::conn(),$sql1);
    if($row1=mysql_fetch_array($cursor1))
    {
        $DUTY_NAME    = $row1["DUTY_NAME"];
        $GENERAL      = $row1["GENERAL"];
        $DUTY_TYPE_ARR = array();
        for($I=1;$I<=6;$I++)
        {
            $cn = $I%2==0?"2":"1";
            if($row1["DUTY_TIME".$I]!="")
                $DUTY_TYPE_ARR[$I]=array( "DUTY_TIME" => $row1["DUTY_TIME".$I] ,"DUTY_TYPE" => $cn);
        }
    }

    for($I=1;$I<=6;$I++)
    {
        if($data[$current_day][$I]["DUTY_TIME"]!="")
        {
            $DUTY_TIME = $data[$current_day][$I]["DUTY_TIME"];
        }else
        {
            $DUTY_TIME = $DUTY_TYPE_ARR[$I]['DUTY_TIME'];
        }

        if($DUTY_TIME=="")
            continue;

        $DUTY_TYPE = $I%2==0?"2":"1";

        $ttime = "";
        $color = "#fff";
        $type = 0;

        //判断值
        $REGISTER_TIME_ORG = $REGISTER_TIME = $data[$current_day][$I]["REGISTER_TIME"];

        if($REGISTER_TIME!="")
        {
            $REGISTER_TIME = strtok($REGISTER_TIME," ");
            $REGISTER_TIME = strtok(" ");

            $color = "#51a351";
            $type = 1;

            if($data[$current_day][$I]["DUTY_TIME"]!="")
            {
                //早退
                $str1 = this_compare_time($REGISTER_TIME,$DUTY_TIME,$data[$current_day][$I]['TIME_EARLY'],1);
                //迟到
                $str2 = this_compare_time($REGISTER_TIME,$DUTY_TIME,$data[$current_day][$I]['TIME_LATE'],0);
            }else
            {
                if($DUTY_TYPE=="1" && compare_time($REGISTER_TIME,$DUTY_TIME)==1)
                    $str2 = 1;
                if($DUTY_TYPE=="2" && compare_time($REGISTER_TIME,$DUTY_TIME)==-1)
                    $str1 = -1;
            }

            if($HOLIDAY=="" && $DUTY_TYPE=="1" && $str2==1)
            {
                $ttime = " [迟到]";
                $type = 2;
                $color = "#bd362f";
            }

            if($HOLIDAY=="" && $DUTY_TYPE=="2" && $str1==-1)
            {
                $ttime = " [早退]";
                $type = 3;
                $color = "#f89406";
            }
        }
        $DUTY_INTERVAL_BEFORE="DUTY_INTERVAL_BEFORE".$DUTY_TYPE;
        $DUTY_INTERVAL_AFTER="DUTY_INTERVAL_AFTER".$DUTY_TYPE;

        if($DUTY_TYPE=="1")
            $DUTY_TYPE_NAME="上班签到";
        else
            $DUTY_TYPE_NAME="下班签退";

        if($REGISTER_TIME!="")
        {
            $output[] = array(
                'id' => $data[$current_day][$I]['ATTEND_MOBILE_ID'],
                'title' => $DUTY_TYPE_NAME.$ttime,
                'type' => $type,
                'color' => $color,
                'start' => $REGISTER_TIME_ORG,
                'date' => $current_day,
                'm_isfoot' => 0
            );
        }

    }
}

echo json_encode(td_iconv($output, MYOA_CHARSET, 'utf-8'));

?>