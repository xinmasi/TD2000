<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("��׼ԭ��");
include_once("inc/header.inc.php");
?>




<body class="bodycolor">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/vehicle.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><span class="big3"> <?=_("��׼ԭ��")?></span>
    </td>
  </tr>
</table>
<br>
<?
$query="select OPERATOR_REASON from VEHICLE_USAGE where VU_ID='$VU_ID'";
$cursor=exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
   $OPERATOR_REASON=$ROW["OPERATOR_REASON"];
}
?>
<table class="TableBlock" width="450" align="center" >
  <form action="submit_reason.php"  method="post" name="form1">  
   <tr>
     <td nowrap class="TableContent"> <?=_("��׼ԭ��")?></td>
     <td class="TableData" colspan="1">
       <textarea name="OPERATOR_REASON" class="BigInput" cols="50" rows="16"><?=$OPERATOR_REASON?></textarea>
     </td>
   </tr> 
   <tr>
    <td nowrap  class="TableControl" colspan="2" align="center">
      <input type="hidden" value="<?=$VU_ID?>" name="VU_ID">
      <input type="hidden" value="<?=$VU_STATUS?>" name="VU_STATUS">
      <input type="submit" value="<?=_("ȷ��")?>" class="BigButton">&nbsp;&nbsp;
      <input type="button" class="BigButton" value="<?=_("�ر�")?>" onClick="window.close();" title="<?=_("�رմ���")?>">
    </td>
  </form>
</table>

</body>
</html>