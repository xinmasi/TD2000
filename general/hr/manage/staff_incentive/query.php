<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");

$HTML_PAGE_TITLE = _("������Ϣ��ѯ");
include_once("inc/header.inc.php");
?>


<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/theme/<?=$_SESSION["LOGIN_THEME"]?>/bbs.css">
<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>
<script Language="JavaScript">
function CheckForm()
{
   
 if(document.form1.INCENTIVE_TIME1.value!="" && document.form1.INCENTIVE_TIME2.value!="" && document.form1.INCENTIVE_TIME1.value > document.form1.INCENTIVE_TIME2.value)
   { 
      alert("<?=_("�������ڵĽ�����ѯʱ�䲻��С�ڽ������ڵĿ�ʼ��ѯʱ�䣡")?>");
      return (false);
   }
 return (true);
}

function do_export()
{
	CheckForm();
  document.form1.action='export.php';
  document.form1.submit();
}
function do_search()
{
	CheckForm();
  document.form1.action='search.php';
  document.form1.submit();
}
</script>

<body class="bodycolor">

<table border="0" width="80%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img align="absMiddle" src="<?=MYOA_STATIC_SERVER?>/static/images/menu/infofind.gif"><span class="big3"> <?=_("������Ϣ��ѯ")?></span></td>
  </tr>
</table>
<br>
<form enctype="multipart/form-data" action="#"  method="post" name="form1" >
 <table class="TableBlock" width="450" align="center">
    <tr>
      <td nowrap class="TableData"><?=_("��λԱ����")?></td>
      <td class="TableData">
        <input type="text" name="STAFF_NAME1" size="10" class="BigStatic" readonly value="">&nbsp;
        <input type="hidden" name="STAFF_NAME" value="<?=$STAFF_NAME?>">
        <a href="javascript:;" class="orgAdd" onClick="SelectUserSingle('27','','STAFF_NAME', 'STAFF_NAME1','1')"><?=_("ѡ��")?></a>
      </td>
    </tr>
        <tr>
      <td nowrap class="TableData"> <?=_("�������ڣ�")?></td>
      <td class="TableData">
        <input type="text" name="INCENTIVE_TIME1" size="10" maxlength="10" class="BigInput" id="start_time" value="<?=$INCENTIVE_TIME1?>" onClick="WdatePicker()"/>
        <?=_("��")?>
        <input type="text" name="INCENTIVE_TIME2" size="10" maxlength="10" class="BigInput" value="<?=$INCENTIVE_TIME2?>" onClick="WdatePicker({minDate:'#F{$dp.$D(\'start_time\')}'})"/>     
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData" width="100"> <?=_("������Ŀ��")?></td>
      <td class="TableData" >
        <select name="INCENTIVE_ITEM" style="background: white;" title="<?=_("������Ŀ���ƿ��ڡ�������Դ���á�->��HRMS�������á�ģ�����á�")?>">
          <option value=""><?=_("��ѡ��")?></option>
          <?=hrms_code_list("HR_STAFF_INCENTIVE1","")?>
        </select>
      </td> 
   </tr>
    </tr>
    <tr>
      <td nowrap class="TableData"><?=_("�������ԣ�")?></td>
      <td class="TableData">
        <select name="INCENTIVE_TYPE" >
          <option value=""><?=_("��ѡ��")?></option>
          <?=hrms_code_list("INCENTIVE_TYPE",$INCENTIVE_TYPE)?>
        </select>
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