<?
include_once("inc/conn.php");

function check_holiday($DAY)
{
    $IS_HOLIDAY=0;
    $query="select * from ATTEND_HOLIDAY where BEGIN_DATE <='$DAY' and END_DATE>='$DAY'";
    $cursor= exequery(TD::conn(),$query);
    if($ROW=mysql_fetch_array($cursor))
        $IS_HOLIDAY=1;
    return $IS_HOLIDAY;
}
function check_holiday1($DAY,$GENERAL)
{
    $IS_HOLIDAY1=0;
    $WEEK=date("w",strtotime($DAY));
    if(find_id($GENERAL,$WEEK))
        $IS_HOLIDAY1=1;
    return $IS_HOLIDAY1;
}
function check_evection($USER_ID,$DAY)
{
    $IS_EVECTION=0;
    $query="select * from ATTEND_EVECTION where USER_ID='$USER_ID' and ALLOW='1' and to_days(EVECTION_DATE1)<=to_days('$DAY') and to_days(EVECTION_DATE2)>=to_days('$DAY')";
    $cursor= exequery(TD::conn(),$query);
    if($ROW=mysql_fetch_array($cursor))
        $IS_EVECTION=1;
    return $IS_EVECTION;
}

function check_leave($USER_ID,$DAY,$DUTY_TIME)
{
    $IS_LEAVE=0;
    $query="select * from ATTEND_LEAVE where USER_ID='$USER_ID' and (ALLOW='1' or ALLOW='3') and LEAVE_DATE1<='$DAY $DUTY_TIME' and LEAVE_DATE2>='$DAY $DUTY_TIME'";
    $cursor= exequery(TD::conn(),$query);
    if($ROW=mysql_fetch_array($cursor))
    {
        $IS_LEAVE=1;
        $LEAVE_TYPE2=$ROW["LEAVE_TYPE2"];
        $LEAVE_TYPE2_STR=get_hrms_code_name($LEAVE_TYPE2,"ATTEND_LEAVE");
    }
    if($LEAVE_TYPE2_STR!="")
        return $LEAVE_TYPE2_STR;
    else
        return $IS_LEAVE;
}
function check_out($USER_ID,$DAY,$DUTY_TIME)
{
    $DUTY_TIME = date("H:s:i",strtotime($DUTY_TIME));
    $IS_OUT=0;
    $query="select * from ATTEND_OUT where USER_ID='$USER_ID' and ALLOW='1' and to_days(SUBMIT_TIME)=to_days('$DAY') and OUT_TIME1<='".substr($DUTY_TIME,0,strrpos($DUTY_TIME,":"))."' and OUT_TIME2>='".substr($DUTY_TIME,0,strrpos($DUTY_TIME,":"))."'";
    $cursor= exequery(TD::conn(),$query);
    if($ROW=mysql_fetch_array($cursor))
        $IS_OUT=1;
    return $IS_OUT;
}
function get_default_type($USER_ID)
{
    $DUTY_TYPE="";
    $query="select DUTY_TYPE from USER_EXT where USER_ID='$USER_ID'";
    $cursor=exequery(TD::conn(),$query);
    if($ROW=mysql_fetch_array($cursor))
        $DUTY_TYPE=$ROW["DUTY_TYPE"];
    return $DUTY_TYPE;
}
?>