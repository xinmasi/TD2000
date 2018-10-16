<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");

$HTML_PAGE_TITLE = _("Ա���ػ���ѯ");
include_once("inc/header.inc.php");
?>


<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/theme/<?=$_SESSION["LOGIN_THEME"]?>/bbs.css">
<link rel="stylesheet"type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/js/validation/validationEngine.jquery.min.css">
<script type="text/javascript" src="/inc/js_lang.php"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/validation/jquery.validationEngine.min.js" type="text/javascript" charset="utf-8"></script>

<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>
<script> 
jQuery(document).ready(function(){      
    jQuery("#form1").validationEngine({promptPosition:"centerRight"});
});  
</script>
<script>
function do_export()
{
   if(document.form1.CARE_FEES1.value!="" && document.form1.CARE_FEES2.value!="" && document.form1.CARE_FEES1.value > document.form1.CARE_FEES2.value)
   { 
      alert("<?=_("�ػ���֧��ʼ���ò��ܴ��ڽ������ã�")?>");
      return (false);
   }
  document.form1.action='export.php';
  document.form1.submit();
}
function do_search()
{
   if(document.form1.CARE_FEES1.value!="" && document.form1.CARE_FEES2.value!="" && document.form1.CARE_FEES1.value > document.form1.CARE_FEES2.value)
   { 
      alert("<?=_("�ػ���֧��ʼ���ò��ܴ��ڽ������ã�")?>");
      return (false);
   }
  document.form1.action='search.php';
  document.form1.submit();
}
</script>

<body class="bodycolor">

<table border="0" width="80%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img align="absMiddle" src="<?=MYOA_STATIC_SERVER?>/static/images/menu/infofind.gif"><span class="big3"> <?=_("Ա���ػ���ѯ")?></span></td>
  </tr>
</table>
  <form enctype="multipart/form-data" action="search.php"  method="post" name="form1"  id="form1">
<table class="TableBlock" width="450" align="center">

    <tr>
      <td nowrap class="TableData"> <?=_("���ͣ�")?></td>
      <td class="TableData"> 
        <select name="CARE_TYPE" class="BigSelect">
          <option value="" selected><?=_("��������")?></option>
          <?=hrms_code_list("HR_STAFF_CARE",$CARE_TYPE);?>
        </select>&nbsp;
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData" width="100"> <?=_("���ػ�Ա����")?></td>
      <td class="TableData">
      	<input type="text" name="BY_CARE_NAME" size="13" class="BigStatic" readonly value="<?=$BY_CARE_NAME?>">&nbsp;
        <input type="hidden" name="BY_CARE_STAFFS" value="<?=$BY_CARE_STAFFS?>">
        <a href="javascript:;" class="orgAdd" onClick="SelectUserSingle('27','','BY_CARE_STAFFS', 'BY_CARE_NAME','1')"><?=_("ѡ��")?></a>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("�ػ����ڣ�")?></td>
      <td class="TableData">
        <input type="text" name="CARE_DATE1" size="10" maxlength="10" class="BigInput" id="start_time" value="<?=$CARE_DATE1?>" onClick="WdatePicker()"/>
        <?=_("��")?>
        <input type="text" name="CARE_DATE2" size="10" maxlength="10" class="BigInput" value="<?=$CARE_DATE2?>" onClick="WdatePicker({minDate:'#F{$dp.$D(\'start_time\')}'})"/>        
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData" width="100"> <?=_("�ػ���֧���ã�")?></td>
      <td class="TableData">
         <INPUT type="text"name="CARE_FEES1" class="BigInput validate[custom[money]]" data-prompt-position="centerRight:0,-6" size="10">
         <?=_("��")?>
         <INPUT type="text"name="CARE_FEES2" class="BigInput validate[custom[money]]" data-prompt-position="centerRight:0,-6" size="10">         
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData" width="100"> <?=_("�����ˣ�")?></td>
      <td class="TableData">
        <input type="hidden" name="PARTICIPANTS" value="">
        <textarea cols=27 name="PARTICIPANTS_NAME" rows=2 class="BigStatic" wrap="yes" readonly></textarea>
        <a href="javascript:;" class="orgAdd" onClick="SelectUser('27','','PARTICIPANTS', 'PARTICIPANTS_NAME','1')"><?=_("���")?></a>
        <a href="javascript:;" class="orgClear" onClick="ClearUser('PARTICIPANTS', 'PARTICIPANTS_NAME')"><?=_("���")?></a>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData" width="100"> <?=_("�ػ����ݣ�")?></td>
      <td class="TableData">
        <input type="text" name="CARE_CONTENT" size="28" maxlength="200" class="BigInput" value="">
      </td>
    </tr>
    <tr align="center" class="TableControl">
      <td colspan="2" nowrap>
        <input type="button" value="<?=_("��ѯ")?>" class="BigButton" onclick="do_search()">&nbsp;&nbsp;
        <input type="button" value="<?=_("����")?>" class="BigButton" onclick="do_export()">&nbsp;&nbsp;
      </td>
    </tr>
  </table>
</form>

</body>
</html>