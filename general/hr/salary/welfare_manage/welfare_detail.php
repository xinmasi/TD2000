<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");

$HTML_PAGE_TITLE = _("Ա��������ϸ��Ϣ");
include_once("inc/header.inc.php");
?>




<body class="bodycolor">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/meeting.gif" width="17" height="17"><span class="big3"><?=_("Ա��������ϸ��Ϣ")?></span><br>
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
    <td nowrap align="left" width="120" class="TableContent"><?=_("������")?></td>
    <td nowrap align="left" class="TableData" width="180"><?=$STAFF_NAME1?></td>
    <td nowrap align="left" width="120" class="TableContent"><?=_("������Ŀ��")?></td>
    <td nowrap align="left" class="TableData" width="180"><?=$WELFARE_ITEM?></td>
  </tr>
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("�����·ݣ�")?></td>
    <td nowrap align="left" class="TableData" width="180"><?=$WELFARE_MONTH?></td>
    <td nowrap align="left" width="120" class="TableContent"><?=_("�������ڣ�")?></td>
    <td align="left" class="TableData" width="180"><?=$PAYMENT_DATE=="0000-00-00"?"":$PAYMENT_DATE;?></td>
  </tr>
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("������")?></td>
    <td nowrap align="left" class="TableData" width="180"><?=$WELFARE_PAYMENT?></td>
    <td nowrap align="left" width="120" class="TableContent"><?=_("�Ƿ���˰��")?></td>
    <td nowrap align="left" class="TableData" width="180"><? if($TAX_AFFAIRS=="1") echo _("��");if($TAX_AFFAIRS=="0") echo _("��");?></td>
  </tr>
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("������Ʒ��")?></td>
    <td nowrap align="left" class="TableData" colspan="3"><?=$FREE_GIFT?></td>
  </tr>
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("��ע��")?></td>
    <td nowrap align="left" class="TableData" colspan="3"><?=$REMARK?></td>
  </tr>
  <tr>
    <td nowrap align="left" width="120" class="TableContent"><?=_("�Ǽ�ʱ�䣺")?></td>
    <td nowrap align="left" class="TableData" colspan="3"><?=$ADD_TIME?></td>
  </tr>
  <tr align="center" class="TableControl">
    <td colspan="4">
      <input type="button" value="<?=_("�ر�")?>" class="BigButton" onClick="window.close();" title="<?=_("�رմ���")?>">
    </td>
  </tr>
</table>
<?
}
else
  Message("",_("δ�ҵ���Ӧ��¼��"));
?>
</body>

</html>
