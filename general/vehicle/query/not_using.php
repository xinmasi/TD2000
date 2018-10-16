<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("未使用车辆");
include_once("inc/header.inc.php");
?>



<body class="bodycolor">

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/vehicle.gif" HEIGHT="20"><span class="big3"> <?=_("未使用车辆")?></span>
    </td>
  </tr>
</table>
<?
//============================  =======================================
$query = "select OPERATOR_NAME from VEHICLE_OPERATOR where OPERATOR_ID='1'";
$cursor=exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
   $MANAGERS=$ROW["OPERATOR_NAME"];

if(find_id($MANAGERS,$_SESSION["LOGIN_USER_ID"]) || $_SESSION["LOGIN_USER_PRIV"]=="1")//或者是admin
    $query = "SELECT * from VEHICLE where USEING_FLAG='0' order by V_NUM";
else
    $query = "SELECT * from VEHICLE where USEING_FLAG='0' and (DEPT_RANGE = 'ALL_DEPT' or find_in_set('" .$_SESSION["LOGIN_DEPT_ID"]. "',DEPT_RANGE) or find_in_set('" .$_SESSION["LOGIN_USER_ID"]. "',USER_RANGE) or DEPT_RANGE='' and USER_RANGE='') order by V_NUM";
    
$cursor= exequery(TD::conn(),$query);
$V_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
   $V_COUNT++;

   $V_ID=$ROW["V_ID"];
   $V_MODEL=$ROW["V_MODEL"];
   $V_NUM=$ROW["V_NUM"];
   $V_DRIVER=$ROW["V_DRIVER"];
   $V_TYPE=$ROW["V_TYPE"];

   $V_TYPE_DESC=get_code_name($V_TYPE,"VEHICLE_TYPE");

   if($V_COUNT==1)
   {
?>
<table class="TableList" width="95%" align="center">
  <tr class="TableHeader">
      <td nowrap align="center"><?=_("厂牌型号")?></td>
      <td nowrap align="center"><?=_("车牌号")?></td>
      <td nowrap align="center"><?=_("司机")?></td>
      <td nowrap align="center"><?=_("类型")?></td>
      <td nowrap align="center"><?=_("操作")?></td>
    </tr>
<?
   }
?>
    <tr class="TableData">
      <td nowrap align="center"><?=$V_MODEL?></td>
      <td nowrap align="center"><?=$V_NUM?></td>
      <td nowrap align="center"><?=$V_DRIVER?></td>
      <td nowrap align="center"><?=$V_TYPE_DESC?></td>
      <td nowrap align="center" width="25%">
      <a href="javascript:;" onClick="window.open('../vehicle_detail.php?V_ID=<?=$V_ID?>','','height=360,width=500,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=250,left=280,top=160,resizable=yes');"><?=_("详细信息")?></a>&nbsp;
      <a href="javascript:;" onClick="window.open('../order_detail.php?V_ID=<?=$V_ID?>','','height=400,width=700,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=250,top=170,resizable=yes');"><?=_("预定情况")?></a>&nbsp;
      </td>
    </tr>
<?
}
if($V_COUNT>0)
{
?>
  </table>
<?
}
else
  Message("",_("无未使用的车辆"));
?>

</body>

</html>
