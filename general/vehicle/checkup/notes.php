<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
$query="select VU_MILEAGE,VU_MILEAGE_TRUE,VU_PARKING_FEES from VEHICLE_USAGE where VU_ID='$VU_ID'";
$cursor=exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
   $VU_MILEAGE=$ROW["VU_MILEAGE"];
   $VU_MILEAGE_TRUE=$ROW["VU_MILEAGE_TRUE"];
   $VU_PARKING_FEES=$ROW["VU_PARKING_FEES"];
}
$MILEAGE = $VU_MILEAGE_TRUE ? $VU_MILEAGE_TRUE : $VU_MILEAGE;

$HTML_PAGE_TITLE = _("�������");
include_once("inc/header.inc.php");
?>



<body class="bodycolor">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/vehicle.gif" HEIGHT="20"><span class="big3"> <?=_("�������")?></span>
    </td>
  </tr>
</table>
<form enctype="multipart/form-data" action="notes_update.php" method="post" name="form1">
<table class="TableBlock" align="center" width="90%">
    <tr>
      <td nowrap class="TableContent" width="20%"><?=_("ʵ���������")?></td>
      <td class="TableData">
        <input type="text" name="VU_MILEAGE_TRUE" size="10" class="SmallInput" value="<?=$MILEAGE?>">&nbsp;<?=_("����")?></td>
	 </tr>
	 <tr>
      <td nowrap class="TableContent" width="20%"> <?=_("���ã�")?></td>
      <td class="TableData">
        <input type="text" name="VU_PARKING_FEES" size="10" class="SmallInput" value="<?=$VU_PARKING_FEES?>">&nbsp;<?=_("Ԫ(����ͣ����,���ٷ�,���ŷ�,���ͷѵ�)")?></td>	  	
    </tr>
	<tr class="TableControl">
      <td nowrap colspan="2" align="center">
		<input type="hidden" value="<?=$VU_ID?>" name="VU_ID">
        <input type="button" value="<?=_("����")?>" class="BigButton" onclick="submit();">&nbsp;&nbsp;
		<input type="button" value="<?=_("�ر�")?>" class="BigButton" onclick="window.close();	">

      </td>
    </tr>
</table>
</form>
</body>