<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");


$HTML_PAGE_TITLE = _("�豸��ѯ");
include_once("inc/header.inc.php");
?>




<body class="bodycolor">
<table border="0" width="90%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img align="absMiddle" src="<?=MYOA_STATIC_SERVER?>/static/images/menu/infofind.gif"><span class="big3"> <?=_("�豸��ѯ")?></span>
    </td>
  </tr>
</table>

<table class="TableBlock" width="550" align="center">
  <form enctype="multipart/form-data" method="post" name="form1" action="search.php">
    <tr>
      <td nowrap class="TableData" width="100"> <?=_("�豸��ţ�")?></td>
      <td class="TableData">
        <input type="text" name="EQUIPMENT_NO" maxlength="100" class="BigInput">
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData" width="100"> <?=_("�豸���ƣ�")?></td>
      <td class="TableData">
        <input type="text" name="EQUIPMENT_NAME" maxlength="100" class="BigInput">
      </td>
    </tr> 
    <tr>
      <td nowrap class="TableData" width="100"> <?=_("�豸״̬��")?></td>
      <td class="TableData">
        <select name="EQUIPMENT_STATUS">
       	  <option value="1"><?=_("����")?></option>
       	  <option value="0"><?=_("������")?></option>       	 
        </select>
      </td>
    </tr>  
    <tr>
      <td nowrap class="TableData" width="100"> <?=_("�����ң�")?></td>
      <td class="TableData">
    	<select name="MR_ID" class="BigSelect">
    		<option value=""></option>
<?
$query = "SELECT MR_ID,MR_NAME from MEETING_ROOM";
$cursor= exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
  $MR_ID=$ROW["MR_ID"];
  $MR_NAME=$ROW["MR_NAME"];
?>
      <option value="<?=$MR_ID?>"><?=$MR_NAME?></option>
<?
}
?>    
      </select>
      </td>
    </tr> 
    <tr>
      <td nowrap class="TableData" width="100"> <?=_("�豸������")?></td>
      <td class="TableData">
        <input type="text" name="REMARK" maxlength="100" class="BigInput">
      </td>
    </tr>   
    <tr align="center" class="TableControl">
      <td colspan="2" nowrap>
        <input type="submit" value="<?=_("ȷ��")?>" class="BigButton">
      </td>
    </tr>
  </table>
</form>

</body>
</html>           