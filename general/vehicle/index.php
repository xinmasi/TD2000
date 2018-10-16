<?
include_once("inc/auth.inc.php");

$CUR_TIME=date("Y-m-d H:i:s",time());
//-----自动使用----------
$query = "SELECT * from VEHICLE_USAGE where VU_STATUS=1";
$cursor= exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
   $VU_ID3=$ROW["VU_ID"];
   $V_ID3=$ROW["V_ID"];
   
   $query2="SELECT USEING_FLAG from VEHICLE where V_ID='$V_ID3'";
   $cursor3=exequery(TD::conn(),$query2);
   if ($ROW3=mysql_fetch_array($cursor3))
   {
   	$USEING_FLAG2=$ROW3["USEING_FLAG"];
   }
   
   $VU_START3=$ROW["VU_START"];
   if($CUR_TIME>=$VU_START3 && $USEING_FLAG2=='0')
   {
     	exequery(TD::conn(),"UPDATE VEHICLE_USAGE set VU_STATUS= '2' where VU_ID='$VU_ID3'");
     	exequery(TD::conn(),"UPDATE VEHICLE set USEING_FLAG= '1' where V_ID='$V_ID3'");
   }
}

$query="SELECT * FROM VEHICLE_USAGE WHERE VU_STATUS='2' OR VU_STATUS='1'";
$cursor=exequery(TD::conn(), $query);
while ($ROW=mysql_fetch_array($cursor))
{
	$VU_ID3=$ROW["VU_ID"];
   $V_ID3=$ROW["V_ID"];
   $IS_BACK=$ROW["IS_BACK"];
   $VU_END_TIME=$ROW["VU_END"];
   $VU_STATUS1=$ROW["VU_STATUS"];
   if ($IS_BACK==0 && $VU_END_TIME<=$CUR_TIME && $VU_STATUS1=='2')
   {

      exequery(TD::conn(),"UPDATE VEHICLE_USAGE set VU_STATUS= '4', IS_BACK=2 where VU_ID='$VU_ID3'");
     	exequery(TD::conn(),"UPDATE VEHICLE set USEING_FLAG= '0' where V_ID='$V_ID3'");
   }
   
   $sql1="select distinct(V_ID) from VEHICLE_USAGE where V_ID='$V_ID3' and VU_STATUS='2' ";  
   $cursor_sql=exequery(TD::conn(), $sql1);
   if (mysql_num_rows($cursor_sql)==0)
   {
     	$sql2="update VEHICLE set USEING_FLAG=0 where V_ID='$V_ID3'";
   	exequery(TD::conn(), $sql2);
   }
   
}

//-----自动回收----------
/*$query = "SELECT * from VEHICLE_USAGE where VU_STATUS=2";
$cursor= exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
   $VU_ID3=$ROW["VU_ID"];
   $V_ID3=$ROW["V_ID"];
   $VU_END3=$ROW["VU_END"];
   if($CUR_TIME>=$VU_END3)
   {
     	exequery(TD::conn(),"UPDATE VEHICLE_USAGE set VU_STATUS= '4' where VU_ID='$VU_ID3'");
     	exequery(TD::conn(),"UPDATE VEHICLE set USEING_FLAG= '0' where V_ID='$V_ID3'");
   }
}*/

$HTML_PAGE_TITLE = _("车辆使用申请");
include_once("menu_top.php");
?>
