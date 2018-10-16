<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
include_once("inc/utility_all.php");
function get_ann($USER_ID)
{

  $CUR_DATE=date("Y-m-d",time());
  $query = "SELECT LEAVE_TYPE from HR_STAFF_INFO where USER_ID='$USER_ID'";
  $cursor= exequery(TD::conn(),$query);
  if($ROW=mysql_fetch_array($cursor))
  	 $LEAVE_TYPE1=$ROW["LEAVE_TYPE"];//年休假总计

    //获取SYS_PARA数据库的年休假开始时间和结束时间20131014
    $query="select * from SYS_PARA where PARA_NAME='ANNUAL_BEGIN_TIME'";
    $cursor=exequery(TD::conn(),$query);
    if($ROW=mysql_fetch_array($cursor))
       $ANNUAL_BEGIN_TIME=$ROW["PARA_VALUE"];
    $query="select * from SYS_PARA where PARA_NAME='ANNUAL_END_TIME'";
    $cursor=exequery(TD::conn(),$query);
    if($ROW=mysql_fetch_array($cursor))
       $ANNUAL_END_TIME=$ROW["PARA_VALUE"];

    $CUR_YEAR = date("Y",time());
    $CUR_M = date("m",time());
    $ANNUAL_BEGIN_TIME_ARRAY=explode('-',$ANNUAL_BEGIN_TIME);
    if($CUR_M<$ANNUAL_BEGIN_TIME_ARRAY[1])
    {
        $CUR_YEAR1=$CUR_YEAR-1;
        $BEGIN_TIME = $CUR_YEAR1."$ANNUAL_BEGIN_TIME";
        $END_TIME = $CUR_YEAR."$ANNUAL_END_TIME";
    }
    else
    {
        $BEGIN_TIME = $CUR_YEAR."$ANNUAL_BEGIN_TIME";
        $END_TIME = $CUR_YEAR."$ANNUAL_END_TIME";
    }
    //$BEGIN_TIME=substr($CUR_DATE,0,4)."-01-01 00:00:01";
    //$END_TIME=substr($CUR_DATE,0,4)."-12-30 23:59:59";
    //如果格式为-01-01 00:00:01，则年数加1

  $query = "SELECT * from ATTEND_LEAVE where USER_ID='$USER_ID' and (ALLOW='1' or ALLOW='3' or ALLOW='0') and LEAVE_DATE1 >='$BEGIN_TIME' and LEAVE_DATE1 <='$END_TIME'";
  $cursor= exequery(TD::conn(),$query);
  $LEAVE_DAYS=0;
  $ANNUAL_LEAVE_DAYS=0;
  while($ROW=mysql_fetch_array($cursor))
  {
     $LEAVE_DATE1=$ROW["LEAVE_DATE1"];
     $LEAVE_DATE2=$ROW["LEAVE_DATE2"];
     $ANNUAL_LEAVE=$ROW["ANNUAL_LEAVE"];

     $DAY_DIFF= DateDiff_("d",$LEAVE_DATE1,$LEAVE_DATE2);

     $LEAVE_DAYS+=$DAY_DIFF;
     $LEAVE_DAYS=number_format($LEAVE_DAYS, 1, '.', ' ');
     $ANNUAL_LEAVE_DAYS+=$ANNUAL_LEAVE;
     $ANNUAL_LEAVE_DAYS=number_format($ANNUAL_LEAVE_DAYS, 1, '.', ' ');
  }

  $ANNUAL_LEAVE_LEFT=number_format(($LEAVE_TYPE1-$ANNUAL_LEAVE_DAYS), 1, '.', ' ');
  if($ANNUAL_LEAVE_LEFT < 0)
     $ANNUAL_LEAVE_LEFT=0;

  return $ANNUAL_LEAVE_LEFT;
}
?>