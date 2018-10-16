<?
include_once("inc/auth.inc.php");
include_once("inc/utility_org.php");
$HTML_PAGE_TITLE = _("车辆查询");
include_once("inc/header.inc.php");
?>
<?
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
$WHERE_STR="";
if($VU_STATUS==0)
{
   $VU_STATUS_DESC=_("待批申请");
   //$WHERE_STR = " and DMER_STATUS!='3'";
  $WHERE_STR=" VU_STATUS='$VU_STATUS' and DMER_STATUS!='3'";
}
elseif($VU_STATUS==1)
{
   $VU_STATUS_DESC=_("已准申请");
   $WHERE_STR="  VU_STATUS='$VU_STATUS'";
}
elseif($VU_STATUS==2)
{
   $VU_STATUS_DESC=_("使用中车辆");
   $WHERE_STR="  VU_STATUS='$VU_STATUS'";
}
elseif($VU_STATUS==3)
{
  $VU_STATUS_DESC=_("未准申请");
  $WHERE_STR="  VU_STATUS='$VU_STATUS' or DMER_STATUS='3'";
}
elseif($VU_STATUS==4)
{
  $VU_STATUS_DESC=_("使用结束车辆");
  $WHERE_STR="  VU_STATUS='$VU_STATUS'";
}
?>

<body class="bodycolor">

<?
if($_SESSION["MYOA_IS_GROUP"]=="1" && $_SESSION["LOGIN_USER_PRIV_TYPE"]!="1")
{
    $dept_id = get_manage_dept_ids($_SESSION['LOGIN_UID']);
    if($dept_id)
    {
        $dept_str = $dept_id;
    }
    else
    {
        $dept_str = $_SESSION["LOGIN_DEPT_ID"];
    }
    $UID = rtrim(GetUidByOther('','',$dept_str),",");
    $user_id = rtrim(GetUserIDByUid($UID),",");
    if($user_id!='')
    {
        $query = "SELECT count(*) from VEHICLE_USAGE where ".$WHERE_STR." and find_in_set(VU_USER,'".$user_id."')";
    }
    else
    {
        $query = "SELECT count(*) from VEHICLE_USAGE where ".$WHERE_STR." and VU_USER='".$_SESSION['LOGIN_USER_ID']."'";
    }
}
else
{
    $query = "SELECT count(*) from VEHICLE_USAGE where ".$WHERE_STR;
}
$cursor= exequery(TD::conn(),$query);
$VU_COUNT=0;
if($ROW=mysql_fetch_array($cursor))
   $VU_COUNT=$ROW[0];

if($VU_COUNT==0)
{
?>
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/vehicle.gif" width="24" height="24"><span class="big3"> <?=$VU_STATUS_DESC?></span>
    </td>
  </tr>
</table>
<?
   Message("",_("无").$VU_STATUS_DESC);
   exit();
}
?>

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/vehicle.gif" width="24" height="24"><span class="big3"> <?=$VU_STATUS_DESC?></span>
    </td>
<?
		$MSG = sprintf(_("共%s条车辆记录"),"<span class='big4'>&nbsp;".$VU_COUNT."</span>&nbsp;");
?>
    <td valign="bottom" class="small1"><?=$MSG?>
    </td>
    </tr>
</table>

<table class="TableList" width="95%" align="center">
  <tr class="TableHeader">
      <td nowrap align="center"><?=_("车牌号")?></td>
      <td nowrap align="center"><?=_("用车人")?></td>
      <td align="center"><?=_("事由")?></td>
      <td align="center"><?=_("目的地")?></td>      
      <td width="120" align="center"><?=_("开始时间")?></td>
      <td width="120" align="center"><?=_("结束时间")?></td>
      <td align="center"><?=_("备注")?></td>
      <td nowrap align="center"><?=_("操作")?></td>
    </tr>

<?
//============================ 显示已发布公告 =======================================
if($_SESSION["MYOA_IS_GROUP"]=="1" && $_SESSION["LOGIN_USER_PRIV_TYPE"]!="1")
{
    $dept_id = get_manage_dept_ids($_SESSION['LOGIN_UID']);
    if($dept_id)
    {
        $dept_str = $dept_id;
    }
    else
    {
        $dept_str = $_SESSION["LOGIN_DEPT_ID"];
    }
    $UID = rtrim(GetUidByOther('','',$dept_str),",");
    $user_id = rtrim(GetUserIDByUid($UID),",");
    if($user_id!='')
    {
        $query = "SELECT * from VEHICLE_USAGE where ".$WHERE_STR." and find_in_set(VU_USER,'".$user_id."') order by VU_START";
    }
    else
    {
        $query = "SELECT * from VEHICLE_USAGE where ".$WHERE_STR." and VU_USER='".$_SESSION['LOGIN']."' order by VU_START";
    }
}
else
{
    $query = "SELECT * from VEHICLE_USAGE where ".$WHERE_STR." order by VU_START";
}
$cursor= exequery(TD::conn(),$query);
$VU_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
   $VU_COUNT++;

   $VU_ID=$ROW["VU_ID"];
   $V_ID=$ROW["V_ID"];
   $VU_PROPOSER=$ROW["VU_PROPOSER"];
   $VU_REQUEST_DATE=$ROW["VU_REQUEST_DATE"];
   $VU_USER=$ROW["VU_USER"];
   $VU_REASON=$ROW["VU_REASON"];
   $VU_START =$ROW["VU_START"];
   $VU_END=$ROW["VU_END"];
   $VU_MILEAGE=$ROW["VU_MILEAGE"];
   $VU_DEPT=$ROW["VU_DEPT"];
   $VU_STATUS=$ROW["VU_STATUS"];
   $VU_REMARK=$ROW["VU_REMARK"];
   $VU_DESTINATION=$ROW["VU_DESTINATION"];
   if($VU_START=="0000-00-00 00:00:00")
      $VU_START="";
   if($VU_END=="0000-00-00 00:00:00")
      $VU_END="";

   if($VU_COUNT%2==1)
      $TableLine="TableLine1";
   else
      $TableLine="TableLine2";
  
    $query_name = "SELECT USER_NAME from USER where USER_ID = '$VU_USER'";
   	$cursor_name= exequery(TD::conn(),$query_name);
   		if($ROW_NAME=mysql_fetch_array($cursor_name)){
      		//$VU_USER_ID = $ROW_NAME["USER_NAME"] != ""?$VU_USER:"";
      		$VU_USER = $ROW_NAME["USER_NAME"] != ""?$ROW_NAME["USER_NAME"]:$VU_USER;
   		}
   $query = "SELECT * from VEHICLE where V_ID='$V_ID'";
   $cursor2= exequery(TD::conn(),$query);
   if($ROW2=mysql_fetch_array($cursor2))
      $V_NUM=$ROW2["V_NUM"];
?>
    <tr class="<?=$TableLine?>">
      <td nowrap align="center"><a href="javascript:;" onClick="window.open('../vehicle_detail.php?V_ID=<?=$V_ID?>','','height=360,width=500,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=280,top=160,resizable=yes');"><?=$V_NUM?></a></td>
      <td nowrap align="center"><?=$VU_USER?></td>
      <td align="center"><?=$VU_REASON?></td>
      <td align="center"><?=$VU_DESTINATION?></td>      
      <td width="120" nowrap align="center"><?=$VU_START?></td>
      <td width="120" nowrap align="center"><?=$VU_END?></td>
      <td align="center"><?=$VU_REMARK?></td>
      <td nowrap align="center">
      <a href="javascript:;" onClick="window.open('../usage_detail.php?VU_ID=<?=$VU_ID?>','','height=380,width=400,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,top=200,left=280,resizable=yes');"><?=_("详细信息")?></a>
      </td>
    </tr>
<?
}
?>
</table>
</body>

</html>
