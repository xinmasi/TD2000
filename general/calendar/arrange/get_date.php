<?
include_once("inc/auth.inc.php");
include_once("inc/utility_org.php");
include_once("inc/utility_all.php");
ob_end_clean();
if($DATE_TYPE==1)
{
 $TIME_DIFF="+1 days";
}
if($DATE_TYPE==2)
{  
	$CAL_TIME=$WEEK_BEGIN;
	$TIME_DIFF="+1 weeks";
}
if($DATE_TYPE==3)
{  
	$CAL_TIME=$MONTH_BEGIN;
	$TIME_DIFF="+1 months";
}
if($CAL_TIME=='' || $CAL_TIME=="undefined")
   $CAL_TIME=date("Y-m-d H:i:00",time());
else
   $CAL_TIME=date("Y-m-d H:i:00",$CAL_TIME);
if($TIME_DIFF=='' || $TIME_DIFF=="undefined")
   $TIME_DIFF="+1 hours";
$END_TIME=date("Y-m-d H:i:59",strtotime($TIME_DIFF,strtotime($CAL_TIME))-1);
$CAL_TIME_D=substr($CAL_TIME,0,11);
if(substr($CAL_TIME,11)=="00:00:00")
   $CAL_TIME=$CAL_TIME_D.substr(date("Y-m-d H:i:s"),11);
$TIME_STR=$CAL_TIME.",".$END_TIME;
echo $TIME_STR;
?>
