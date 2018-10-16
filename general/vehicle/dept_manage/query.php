<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/check_type.php");
include_once("inc/flow_hook.php");

function check_car($VU_ID,$V_ID,$VU_START,$VU_END)
{
   $query="select VU_START,VU_END,VU_ID from vehicle_usage where VU_ID!='$VU_ID' and V_ID='$V_ID' and (VU_STATUS in('0','1','2') and DMER_STATUS='1' or VU_STATUS in('1','2') and DMER_STATUS='0') and SHOW_FLAG='1'";
   $cursor=exequery(TD::conn(),$query);
   $COUNT=0;
   while($ROW=mysql_fetch_array($cursor))
   {
     $VU_START1=$ROW["VU_START"];
     $VU_END1=$ROW["VU_END"];
     if(($VU_START1 >= $VU_START and $VU_END1 <= $VU_END) or ($VU_START1 < $VU_START and $VU_END1 > $VU_START) or ($VU_START1 < $VU_END and $VU_END1>$VU_END) or ($VU_START1 < $VU_START and $VU_END1 > $VU_END))
     {
     	  $COUNT++;
        $VU_IDD = $ROW["VU_ID"];
        break;
     }
   }
   $VU_ID = $VU_IDD;
   if($COUNT >= 1)
      return $VU_ID;
   else
      return "#";
}

$HTML_PAGE_TITLE = _("车辆使用管理");
include_once("inc/header.inc.php");
?>


<script Language=JavaScript>
function dept_reason(VU_ID)
{
   URL="dept_reason.php?VU_ID=" + VU_ID + "&DMER_STATUS=" + 3;
   window.open(URL,"dept_reason","height=500,width=600,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=200,top=60,resizable=yes");
}
</script>

<body class="bodycolor">

<?
if($DMER_STATUS==0)
{
    $DMER_STATUS_DESC=_("待批申请");
}
elseif($DMER_STATUS==1)
{
    $DMER_STATUS_DESC=_("已准申请");
}
elseif($DMER_STATUS==3)
{
    $DMER_STATUS_DESC=_("未准申请");
}
if($_SESSION["LOGIN_USER_ID"] != "admin" && $_SESSION["LOGIN_USER_PRIV"] != "1"){
    //非管理员权限
    $WHERE = " ((DEPT_MANAGER='".$_SESSION["LOGIN_USER_ID"]."' and SHOW_FLAG='1') or DEPT_MANAGER='".$_SESSION["LOGIN_USER_ID"]."' or DEPT_MANAGER='".$_SESSION["LOGIN_USER_ID"]."') ";
}else{
    $WHERE = " SHOW_FLAG='1' ";
}
$query = "SELECT count(*) from VEHICLE_USAGE where ".$WHERE." and DMER_STATUS='$DMER_STATUS'";
$cursor= exequery(TD::conn(),$query);
$VU_COUNT=0;
if($ROW=mysql_fetch_array($cursor))
   $VU_COUNT=$ROW[0];

if($VU_COUNT==0)
{
?>
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/vehicle.gif" width="24" height="24"><span class="big3"> <?=$DMER_STATUS_DESC?></span>
    </td>
  </tr>
</table>

<?
   Message("",_("无").$DMER_STATUS_DESC);
   exit;;
}
?>

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/vehicle.gif" width="24" height="24"><span class="big3"> <?=$DMER_STATUS_DESC?></span>
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
      <td nowrap align="center"><?=_("开始时间")?></td>
      <td nowrap align="center"><?=_("结束时间")?></td>
<?
      if($DMER_STATUS==0)
      {
?>
     <td nowrap align="center"><?=_("预约状态")?></td>
<?
     }
?>
      <td nowrap align="center"><?=_("操作")?></td>
    </tr>
<?
//============================ 显示已发布公告 =======================================
$query = "SELECT * from VEHICLE_USAGE where ".$WHERE." and DMER_STATUS='$DMER_STATUS' order by VU_START";
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
   $VU_START =$ROW["VU_START"];
   $VU_END=$ROW["VU_END"];
   $VU_MILEAGE=$ROW["VU_MILEAGE"];
   $VU_DEPT=$ROW["VU_DEPT"];
   $DMER_STATUS=$ROW["DMER_STATUS"];
   $VU_STATUS=$ROW["VU_STATUS"];

   $query_name = "SELECT USER_NAME from USER where USER_ID = '$VU_USER'";
   $cursor_name= exequery(TD::conn(),$query_name);
   		if($ROW_NAME=mysql_fetch_array($cursor_name)){
      		$VU_USER = $ROW_NAME["USER_NAME"] != ""?$ROW_NAME["USER_NAME"]:$VU_USER;
   		}
   if($VU_START=="0000-00-00 00:00:00")
      $VU_START="";
   if($VU_END=="0000-00-00 00:00:00")
      $VU_END="";

   if($VU_COUNT%2==1)
      $TableLine="TableLine1";
   else
      $TableLine="TableLine2";

   $query = "SELECT * from VEHICLE where V_ID='$V_ID'";
   $cursor2= exequery(TD::conn(),$query);
   if($ROW2=mysql_fetch_array($cursor2))
      $V_NUM=$ROW2["V_NUM"];
?>
    <tr class="<?=$TableLine?>">
      <td nowrap align="center"><a href="javascript:;" onClick="window.open('../vehicle_detail.php?V_ID=<?=$V_ID?>','','height=360,width=500,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=280,top=160,resizable=yes');"><?=$V_NUM?></a></td>
      <td nowrap align="center"><?=$VU_USER?></td>
      <td nowrap align="center"><?=$VU_START?></a></td>
      <td nowrap align="center"><?=$VU_END?></a></td>
<?
   if($DMER_STATUS==0)
   {
?>
           <td nowrap align="center">
<?
      $SS=substr(check_car($VU_ID,$V_ID,$VU_START,$VU_END), 0, 1);
      if(!is_number($SS))
         echo _("无冲突");
      else
      {
?>
        <a href="javascript:;" onClick="window.open('usage_detail.php?VU_ID=<?=check_car($VU_ID,$V_ID,$VU_START,$VU_END)?>','','height=350,width=450,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=300,top=150,resizable=yes');"><font color="red"><?=_("预约冲突")?></font></a>
<?
      }
?>
           </td>
<?
      }
?>
      <td nowrap align="center">
      <a href="javascript:;" onClick="window.open('../usage_detail.php?VU_ID=<?=$VU_ID?>','','height=380,width=400,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,top=200,left=280,resizable=yes');"><?=_("详细信息")?></a>&nbsp;
<?
   if($DMER_STATUS==0)
   {
   	  echo _("<a href=\"../new.php?VU_ID=$VU_ID&DMER_STATUS=$DMER_STATUS\">修改</a>&nbsp;");
      if(!is_number($SS))
      echo _("<a href='checkup.php?VU_ID=$VU_ID&DMER_STATUS=1'> 批准</a>&nbsp;");
?>
      <a href="javascript:dept_reason('<?=$VU_ID?>');"> <?=_("不准")?></a>&nbsp;
<?
   }
   elseif($DMER_STATUS==1)
   {
   	  if($VU_STATUS==0)
         echo _("<a href='checkup.php?VU_ID=$VU_ID&DMER_STATUS=0'> 撤销</a>&nbsp;");
   }
   elseif($DMER_STATUS==3)
   {
      echo _("<a href='checkup.php?VU_ID=$VU_ID&DMER_STATUS=1'> 批准</a>&nbsp;");
   }
?>
      </td>
    </tr>
<?
}
?>

</table>
</body>
</html>
