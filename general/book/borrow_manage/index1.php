<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("Ա����ѵͳ��");
include_once("inc/header.inc.php");
?>
<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>


<body class="bodycolor">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small" align="center">
<tr>
  <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/statistic.gif" align="absmiddle" width="22" height="18"><span class="big3"> <?=_("�Կγ�ͳ����")?></span></td>
</tr>
</table><br>

<table class="TableBlock"  width="400" align="center">
<form enctype="multipart/form-data" action="stat_persons.php"  method="post" name="form1">
 <tr class="TableLine1">
   <td nowrap><?=_("��ѵ�γ̱�ţ�")?></td>
   <td nowrap><INPUT type=text name="TRAIN_ID" size="12" class="BigInput"></td>
 </tr>
 <tr class="TableLine1">
   <td nowrap><?=_("��ѵ���")?></td>
      <td class="TableData">
	    <select name="TRAIN_TYPE" class="BigSelect">
   	    	 <option value="" <? if($TRAIN_TYPE=="") echo "selected";?>><?=_("��ѡ��")?></option>
<?
$query = "SELECT * from TRAIN_TTYPE";
$cursor= exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
   $TYPE_ID=$ROW["TYPE_ID"];
   $TYPE_NAME=$ROW["TYPE_NAME"];
?>
           <option value="<?=$TYPE_ID?>" <? if($TRAIN_TYPE==$TYPE_ID) echo "selected";?>><?=$TYPE_NAME?></option>
<?
}
?>
       </select>      
      </td>
 </tr>
 <tr>
    <td nowrap class="TableData"> <?=_("���ţ�")?></td>
    <td class="TableData">
      <input type="hidden" name="VU_DEPT">
      <input type="text" name="VU_DEPT_FIELD_DESC" class="BigStatic" size="17" maxlength="100" readonly>
      <a href="javascript:;" class="orgAdd" onClick="SelectDeptSingle('','VU_DEPT', 'VU_DEPT_FIELD_DESC')"><?=_("���")?></a>
    </td> 
 </tr>
 <tr>
    <td nowrap class="TableData"><?=_("����ʱ�䣺")?></td>
    <td class="TableData" colspan="3">
      <input type="text" name="BEGIN_TIME1" size="10" class="BigInput" onClick="WdatePicker()">
  
      <?=_("��")?> <input type="text" name="BEGIN_TIME2" size="10" class="BigInput" onClick="WdatePicker()">
   
    </td>
  </tr>
  <tr class="TableLine1">
   <td nowrap width="12%"><?=_("Ա����Ϊ")?></td>
   <td nowrap>
     <INPUT type="radio" name="STAT_SHOU" value="1" size="15" id="STAT_SHOU1" checked><label for="STAT_SHOU1"><?=_("�μ�")?></label>
     <INPUT type="radio" name="STAT_SHOU" value="2" size="15" id="STAT_SHOU2"><label for="STAT_SHOU2"><?=_("δ�μ�")?></label>
     <INPUT type="radio" name="STAT_SHOU" value="3" size="15" id="STAT_SHOU3"><label for="STAT_SHOU3"><?=_("����")?></label>
   </td>
  </tr>
  <tr class="TableControl">
   <td colspan="9" align="center"><input type="submit" value="<?=_("ȷ��")?>" class="BigButton"></td>
  </tr>
</form>
</table>
</body>
</html>