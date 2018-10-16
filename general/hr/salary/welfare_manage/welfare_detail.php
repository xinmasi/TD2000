<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");

$HTML_PAGE_TITLE = _("员工福利详细信息");
include_once("inc/header.inc.php");
?>




<body class="bodycolor">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/meeting.gif" width="17" height="17"><span class="big3"><?=_("员工福利详细信息")?></span><br>
    </td>
  </tr>
</table>
<?
$query = "SELECT * from  HR_WELFARE_MANAGE where WELFARE_ID='$WELFARE_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
   $STAFF_NAME=$ROW["STAFF_NAME"];
   $WELFARE_MONTH=$ROW["WELFARE_MONTH"];
   $PAYMENT_DATE=$ROW["PAYMENT_DATE"];
   $WELFARE_ITEM=$ROW["WELFARE_ITEM"];
   $WELFARE_PAYMENT=$ROW["WELFARE_PAYMENT"];
   $FREE_GIFT=$ROW["FREE_GIFT"];
   $TAX_AFFAIRS=$ROW["TAX_AFFAIRS"];
   $REMARK=$ROW["REMARK"];
   $ADD_TIME=$ROW["ADD_TIME"]; 
	 
	 $STAFF_NAME1=substr(GetUserNameById($STAFF_NAME),0,-1);
	 $WELFARE_ITEM=get_hrms_code_name($WELFARE_ITEM,"HR_WELFARE_MANAGE");
?>
<table class="TableBlock" width="90%" align="center">
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("姓名：")?></td>
    <td nowrap align="left" class="TableData" width="180"><?=$STAFF_NAME1?></td>
    <td nowrap align="left" width="120" class="TableContent"><?=_("福利项目：")?></td>
    <td nowrap align="left" class="TableData" width="180"><?=$WELFARE_ITEM?></td>
  </tr>
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("工资月份：")?></td>
    <td nowrap align="left" class="TableData" width="180"><?=$WELFARE_MONTH?></td>
    <td nowrap align="left" width="120" class="TableContent"><?=_("发放日期：")?></td>
    <td align="left" class="TableData" width="180"><?=$PAYMENT_DATE=="0000-00-00"?"":$PAYMENT_DATE;?></td>
  </tr>
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("福利金额：")?></td>
    <td nowrap align="left" class="TableData" width="180"><?=$WELFARE_PAYMENT?></td>
    <td nowrap align="left" width="120" class="TableContent"><?=_("是否纳税：")?></td>
    <td nowrap align="left" class="TableData" width="180"><? if($TAX_AFFAIRS=="1") echo _("是");if($TAX_AFFAIRS=="0") echo _("否");?></td>
  </tr>
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("发放物品：")?></td>
    <td nowrap align="left" class="TableData" colspan="3"><?=$FREE_GIFT?></td>
  </tr>
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("备注：")?></td>
    <td nowrap align="left" class="TableData" colspan="3"><?=$REMARK?></td>
  </tr>
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("登记时间：")?></td>
    <td nowrap align="left" class="TableData" colspan="3"><?=$ADD_TIME?></td>
  </tr>
  <tr align="center" class="TableControl">
    <td colspan="4">
      <input type="button" value="<?=_("关闭")?>" class="BigButton" onClick="window.close();" title="<?=_("关闭窗口")?>">
    </td>
  </tr>
</table>
<?
}
else
  Message("",_("未找到相应记录！"));
?>
</body>

</html>
