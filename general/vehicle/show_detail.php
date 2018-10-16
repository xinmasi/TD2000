<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");

include_once("inc/header.inc.php");
?>




<body class="bodycolor" leftmargin="0">

<?
if($V_ID=="")
   exit;

$query = "SELECT * from VEHICLE where V_ID='$V_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
  $V_MODEL=$ROW["V_MODEL"];
  $V_NUM=$ROW["V_NUM"];
  $V_DRIVER=$ROW["V_DRIVER"];
  $V_PHONE_NO=$ROW["V_PHONE_NO"];
  $V_TYPE=$ROW["V_TYPE"];
  $V_DATE=$ROW["V_DATE"];
  $V_PRICE =$ROW["V_PRICE"];
  $V_ENGINE_NUM=$ROW["V_ENGINE_NUM"];
  $V_STATUS=$ROW["V_STATUS"];
  $V_REMARK=$ROW["V_REMARK"];
  $ATTACHMENT_ID=$ROW["ATTACHMENT_ID"];
  $ATTACHMENT_NAME=$ROW["ATTACHMENT_NAME"];
  $V_SEATING=$ROW["V_SEATING"];    //座位数

  if($V_DATE=="0000-00-00")
     $V_DATE="";

  $V_TYPE_DESC=get_code_name($V_TYPE,"VEHICLE_TYPE");

  if($V_STATUS==0)
     $V_STATUS_DESC= "<font color=\"#00AA00\"><b>"._("正常")."</b></font>";
  elseif($VU_STATUS==1)
     $V_STATUS_DESC=_("损坏");
  elseif($V_STATUS==2)
     $V_STATUS_DESC=_("维修中");
  elseif($V_STATUS==3)
    $V_STATUS_DESC=_("报废");

  $query = "SELECT count(*) from VEHICLE_USAGE where V_ID='$V_ID' and VU_STATUS!='4'";
  $cursor= exequery(TD::conn(),$query);
  if($ROW=mysql_fetch_array($cursor))
     $VU_COUNT=$ROW[0];
  if($VU_COUNT>0){
     $MSG = sprintf(_("共%d条预定信息"),$VU_COUNT);
     $ORDER_DETAIL="<a href=\"javascript:;\" onClick=\"window.open('order_detail.php?V_ID=".$V_ID."','','height=400,width=700,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=250,top=170,resizable=yes');\" title=\""._("点击查看详情")."\">".$MSG."</a>";
  }
  else
     $ORDER_DETAIL=_("无预定信息");
?>
<table class="TableBlock" width="100%">
  <tr class="TableLine2">
      <td nowrap align="left" width="80" class="TableData"><?=_("型号：")?></td>
      <td nowrap align="left" class="TableData" width="70%"><?=$V_MODEL?></td>
      <td class="TableData" rowspan="6">
<?
   if($ATTACHMENT_NAME=="")
      echo "<center>"._("暂无照片")."</center>";
   else
{
	$URL_ARRAY = attach_url($ATTACHMENT_ID,$ATTACHMENT_NAME);
?>
	<a href="<?=$URL_ARRAY["view"]?>" title="<?=_("点击查看放大图片")?>" target="_blank"><img src="<?=$URL_ARRAY["view"]?>" width='250' border=1 alt="<?=_("文件名：")?><?=$ATTACHMENT_NAME?>"></a>
<?
}
?>
      </td>
  </tr>
  <tr class="TableLine1">
      <td nowrap align="left" width="80" class="TableData"><?=_("车牌号：")?></td>
      <td nowrap align="left" class="TableData"><?=$V_NUM?></td>
  </tr>
  <tr class="TableLine1">
      <td nowrap align="left" width="80" class="TableData"><?=_("座位数：")?></td>
      <td nowrap align="left" class="TableData"><?=$V_SEATING?></td>
  </tr>
  <tr class="TableLine1">
      <td nowrap align="left" width="80" class="TableData"><?=_("司机：")?></td>
      <td nowrap align="left" class="TableData"><?=td_trim($V_DRIVER)?></td>
  </tr>
  <tr class="TableLine1">
      <td nowrap align="left" width="80" class="TableData"><?=_("手机号码：")?></td>
      <td nowrap align="left" class="TableData"><?=td_trim($V_PHONE_NO)?></td>
  </tr>
  <tr class="TableLine1">
      <td nowrap align="left" width="80" class="TableData"><?=_("车辆类型：")?></td>
      <td nowrap align="left" class="TableData"><?=$V_TYPE_DESC?></td>
  </tr>
  <tr class="TableLine1">
      <td nowrap align="left" width="80" class="TableData"><?=_("预定情况：")?></td>
      <td nowrap align="left" class="TableData"><?=$ORDER_DETAIL?>
         &nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:;" onClick="window.open('all_order_detail.php','','height=400,width=700,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=250,top=170,resizable=yes');" title="<?=_("所有车辆预定情况")?>"><?=_("更多……")?></a>
      </td>
  </tr>
  <tr class="TableLine1">
      <td nowrap align="left" width="80" class="TableData"><?=_("当前状态：")?></td>
      <td nowrap align="left" class="TableData" colspan="2"><?=$V_STATUS_DESC?></td>
  </tr>
  <tr class="TableLine1">
      <td nowrap align="left" width="80" class="TableData"><?=_("备注：")?></td>
      <td align="left" class="TableData" colspan="2"><?=$V_REMARK?></td>
  </tr>
</table>
<?
}
?>

</body>

</html>
