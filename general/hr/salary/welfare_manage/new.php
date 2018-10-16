<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("�½�Ա��������Ϣ");
include_once("inc/header.inc.php");
?>

<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>

<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>
<script Language="JavaScript">
function CheckForm()
{
	if(document.form1.STAFF_NAME.value=="")
   { 
      alert("<?=_("��ѡ��λԱ����")?>");
      return (false);
   }
  if(document.form1.WELFARE_ITEM.value=="")
   { 
      alert("<?=_("������Ŀ����Ϊ�գ�")?>");
      return (false);
   }
  if(document.form1.WELFARE_MONTH.value=="")
   { 
      alert("<?=_("�����·ݲ���Ϊ�գ�")?>");
      return (false);
   }
   var pattern = /^\d+(?=\.{0,1}\d+$|$)/
   if(!pattern.exec(document.form1.WELFARE_PAYMENT.value))
   {
      alert("<?=_("�������ӦΪ���ڵ���0�����֣�")?>");
      return (false);
   }
   if(document.form1.WELFARE_PAYMENT.value > 999999.99)
   {
       alert("<?=_("�������ӦС��999999.99��")?>");
      return (false);
   }
 return (true);
}
</script>	


<body class="bodycolor">
<?
$CUR_DATE=date("Y-m-d",time());
$CUR_MONTH=substr(date("Y-m-d",time()),0,-3);
?>
<table border="0" width="90%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_new.gif" align="absmiddle"><span class="big3"> <?=_("�½�Ա��������Ϣ")?></span>&nbsp;&nbsp;
    </td>
  </tr>
</table>
<br>
<form enctype="multipart/form-data" action="add.php"  method="post" name="form1" onsubmit="return CheckForm();">
<table class="TableBlock" width="50%" align="center">
	<tr>
		<td nowrap class="TableData"><?=_("��λԱ����")?></td>
      <td class="TableData">
        <input type="text" name="STAFF_NAME1" size="15" class="BigStatic" readonly value="<?=$STAFF_NAME1?>">&nbsp;
        <input type="hidden" name="STAFF_NAME" value="<?=$STAFF_NAME?>">
        <a href="javascript:;" class="orgAdd" onClick="SelectUserSingle('27','','STAFF_NAME', 'STAFF_NAME1')"><?=_("ѡ��")?></a>
      </td>
 	  <td nowrap class="TableData"><?=_("������Ŀ��")?></td>
      <td class="TableData" colspan=3>
      	<select name="WELFARE_ITEM" style="background: white;" title="<?=_("������Ŀ���ڡ�������Դ���á�->��HRMS�������á�ģ�����á�")?>">
          <option value=""><?=_("������Ŀ")?>&nbsp&nbsp&nbsp&nbsp&nbsp;</option>
          <?=hrms_code_list("HR_WELFARE_MANAGE","")?>
        </select> 
    </td>
	</tr>
	<tr>
    <td nowrap class="TableData"><?=_("�������ڣ�")?></td>
     <td class="TableData">
       <input type="text" name="PAYMENT_DATE" size="15" maxlength="10" class="BigInput" value="<?=$PAYMENT_DATE?>" onClick="WdatePicker()"/>
     </td>
    <td nowrap class="TableData"><?=_("�����·ݣ�")?></td>
     <td class="TableData">
       <input name="WELFARE_MONTH" size="15" class="BigInput" value="<?=$CUR_MONTH?>" onClick="WdatePicker({dateFmt:'yyyy-MM'})">
     </td>
  </tr>
	<tr>
    <td nowrap class="TableData"><?=_("������")?></td>
     <td class="TableData">
       <INPUT type="text" name="WELFARE_PAYMENT" class=BigInput size="15" value="">&nbsp;<?=_("Ԫ")?>
     </td>
    <td nowrap class="TableData"><?=_("�Ƿ���˰��")?></td>
     <td class="TableData">
       <select name="TAX_AFFAIRS" style="background: white;" title="">
          <option value="" ><?=_("�Ƿ���˰")?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>
          <option value="0"><?=_("��")?></option>
          <option value="1"><?=_("��")?></option>
        </select>
     </td>
  </tr>
	<tr>
    <td nowrap class="TableData"><?=_("������Ʒ��")?></td>
     <td class="TableData" colspan=3>
       <textarea name="FREE_GIFT" cols="70" rows="5" class="BigInput" value=""></textarea>
     </td>
  </tr>
  <tr>
    <td nowrap class="TableData"><?=_("��ע��")?></td>
     <td class="TableData" colspan=3>
       <textarea name="REMARK" cols="70" rows="6" class="BigInput" value=""></textarea>
     </td>
  </tr> 
	<tr align="center" class="TableControl">
    <td colspan=4 nowrap>
       <input type="submit" value="<?=_("����")?>" class="BigButton">
     </td>
  </tr>
</table>
</form>
</body>	
</html>