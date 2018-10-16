<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");

$HTML_PAGE_TITLE = _("���µ�����Ϣ��ѯ");
include_once("inc/header.inc.php");
?>


<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/theme/<?=$_SESSION["LOGIN_THEME"]?>/bbs.css">
<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>
<script>
function do_export()
{
    if(document.form1.TRANSFER_DATE1.value!="" && document.form1.TRANSFER_DATE2.value!="" && document.form1.TRANSFER_DATE1.value > document.form1.TRANSFER_DATE2.value)
   { 
      alert("<?=_("������ʼ���ڲ��ܴ��ڵ����������ڣ�")?>");
      return (false);
   }
   if(document.form1.TRANSFER_EFFECTIVE_DATE1.value!="" && document.form1.TRANSFER_EFFECTIVE_DATE2.value!="" && document.form1.TRANSFER_EFFECTIVE_DATE1.value > document.form1.TRANSFER_EFFECTIVE_DATE2.value)
   { 
      alert("<?=_("������Ч��ʼ���ڲ��ܴ��ڵ����������ڣ�")?>");
      return (false);
   }
  document.form1.action='export.php';
  document.form1.submit();
}
function do_search()
{
  if(document.form1.TRANSFER_DATE1.value!="" && document.form1.TRANSFER_DATE2.value!="" && document.form1.TRANSFER_DATE1.value > document.form1.TRANSFER_DATE2.value)
   { 
      alert("<?=_("������ʼ���ڲ��ܴ��ڵ����������ڣ�")?>");
      return (false);
   }
   if(document.form1.TRANSFER_EFFECTIVE_DATE1.value!="" && document.form1.TRANSFER_EFFECTIVE_DATE2.value!="" && document.form1.TRANSFER_EFFECTIVE_DATE1.value > document.form1.TRANSFER_EFFECTIVE_DATE2.value)
   { 
      alert("<?=_("������Ч��ʼ���ڲ��ܴ��ڵ����������ڣ�")?>");
      return (false);
   }
  document.form1.action='search.php';
  document.form1.submit();
}
</script>

<body class="bodycolor">

<table border="0" width="80%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img align="absMiddle" src="<?=MYOA_STATIC_SERVER?>/static/images/menu/infofind.gif"><span class="big3"> <?=_("���µ�����Ϣ��ѯ")?></span></td>
  </tr>
</table>
<br>
<form enctype="multipart/form-data" action="search.php"  method="post" name="form1" >
 <table class="TableBlock" width="450" align="center">
    <tr>
      <td nowrap class="TableData"><?=_("������Ա��")?></td>
      <td class="TableData">
        <input type="text" name="TRANSFER_PERSON_NAME" size="15" class="BigInput" value="">&nbsp;
        <input type="hidden" name="TRANSFER_PERSON" value="<?=$TRANSFER_PERSON?>">
        <a href="javascript:;" class="orgAdd" onClick="SelectUserSingle('27','','TRANSFER_PERSON', 'TRANSFER_PERSON_NAME','1')"><?=_("ѡ��")?></a>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("�������ͣ�")?></td>
      <td class="TableData" >
        <select name="TRANSFER_TYPE" style="background: white;" title="<?=_("�������Ϳ��ڡ�������Դ���á�->��HRMS�������á�ģ�����á�")?>">
          <option value=""><?=_("��ѡ��")?></option>
          <?=hrms_code_list("HR_STAFF_TRANSFER","")?>
        </select>
      </td> 
    </tr> 
    </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("�������ڣ�")?></td>
      <td class="TableData">
        <input type="text" name="TRANSFER_DATE1" size="10" maxlength="10" class="BigInput" id="transfer_start_time" value="<?=$TRANSFER_DATE1?>" onClick="WdatePicker()"/>
        <?=_("��")?>
        <input type="text" name="TRANSFER_DATE2" size="10" maxlength="10" class="BigInput" value="<?=$TRANSFER_DATE2?>" onClick="WdatePicker({minDate:'#F{$dp.$D(\'transfer_start_time\')}'})"/>   
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("������Ч���ڣ�")?></td>
      <td class="TableData">
        <input type="text" name="TRANSFER_EFFECTIVE_DATE1" size="10" maxlength="10" id="start_time" class="BigInput" value="<?=$TRANSFER_EFFECTIVE_DATE1?>" onClick="WdatePicker()"/> 
        <?=_("��")?>
        <input type="text" name="TRANSFER_EFFECTIVE_DATE2" size="10" maxlength="10" class="BigInput" value="<?=$TRANSFER_EFFECTIVE_DATE2?>" onClick="WdatePicker({minDate:'#F{$dp.$D(\'start_time\')}'})"/>        
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData" width="100"> <?=_("����ԭ��")?></td>
      <td class="TableData">
        <input type="text" name="TRAN_REASON" size="25" maxlength="200" class="BigInput" value="<?=$TRAN_REASON?>">
      </td>
    </tr>
    <tr align="center" class="TableControl">
      <td colspan="2" nowrap>
        <input type="button" value="<?=_("��ѯ")?>" class="BigButton" onclick="do_search()">&nbsp;&nbsp;
        <input type="button" value="<?=_("����")?>" class="BigButton" onclick="do_export()">&nbsp;&nbsp;
      </td>
    </tr>
  </form>
 </table>


</body>
</html>