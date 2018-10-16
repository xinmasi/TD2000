<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");

$HTML_PAGE_TITLE = _("�Ͷ�������Ϣ��ѯ");
include_once("inc/header.inc.php");
?>


<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/theme/<?=$_SESSION["LOGIN_THEME"]?>/bbs.css">
<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>
<script Language="JavaScript">
function CheckForm()
{
   
 if(document.form1.ISSUE_DATE1.value!="" && document.form1.ISSUE_DATE1.value!="" && document.form1.ISSUE_DATE1.value > document.form1.ISSUE_DATE2.value)
   { 
      alert("<?=_("��֤���ڵĽ�����ѯʱ�䲻��С�ڷ�֤���ڵĿ�ʼ��ѯʱ�䣡")?>");
      return (false);
   }
 if(document.form1.EXPIRE_DATE1.value!="" && document.form1.EXPIRE_DATE2.value!="" && document.form1.EXPIRE_DATE1.value > document.form1.EXPIRE_DATE2.value)
   { 
      alert("<?=_("֤�鵽�����ڵĽ�����ѯʱ�䲻��С��֤�鵽�����ڵĿ�ʼ��ѯʱ�䣡")?>");
      return (false);
   }
 return (true);
}
function do_export()
{
  document.form1.action='export.php';
  document.form1.submit();
}
function do_search()
{
  document.form1.action='search.php';
  document.form1.submit();
}
</script>

<body class="bodycolor">

<table border="0" width="80%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img align="absMiddle" src="<?=MYOA_STATIC_SERVER?>/static/images/menu/infofind.gif"><span class="big3"> <?=_("�Ͷ�������Ϣ��ѯ")?></span></td>
  </tr>
</table>
<br>
<form enctype="multipart/form-data" action="#"  method="post" name="form1" onsubmit="return CheckForm();" >
 <table class="TableBlock" width="450" align="center">
    <tr>
      <td nowrap class="TableData"><?=_("��λԱ����")?></td>
      <td class="TableData">
        <input type="text" name="STAFF_NAME1" size="15" class="BigStatic" readonly value="">&nbsp;
        <input type="hidden" name="STAFF_NAME" value="<?=$STAFF_NAME?>">
        <a href="javascript:;" class="orgAdd" onClick="SelectUserSingle('27','','STAFF_NAME', 'STAFF_NAME1','1')"><?=_("ѡ��")?></a>
      </td>
    </tr>
    </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("��֤���ڣ�")?></td>
      <td class="TableData">
        <input type="text" name="ISSUE_DATE1" size="10" maxlength="10" class="BigInput" id="provide_start_time" value="<?=$ISSUE_DATE1?>" onClick="WdatePicker()"/>
        <?=_("��")?>
        <input type="text" name="ISSUE_DATE2" size="10" maxlength="10" class="BigInput" value="<?=$ISSUE_DATE2?>" onClick="WdatePicker({minDate:'#F{$dp.$D(\'provide_start_time\')}'})"/>       
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("�������ڣ�")?></td>
      <td class="TableData">
        <input type="text" name="EXPIRE_DATE1" size="10" maxlength="10" class="BigInput" id="expire_start_time" value="<?=$EXPIRE_DATE1?>" onClick="WdatePicker()"/>
        <?=_("��")?>
        <input type="text" name="EXPIRE_DATE2" size="10" maxlength="10" class="BigInput" value="<?=$EXPIRE_DATE2?>" onClick="WdatePicker({minDate:'#F{$dp.$D(\'expire_start_time\')}'})"/>        
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData" width="100"> <?=_("�������ƣ�")?></td>
      <td class="TableData">
        <input type="text" name="ABILITY_NAME" size="25" maxlength="200" class="BigInput" value="<?=$ABILITY_NAME?>">
      </td>
   </tr>
    <tr>
      <td nowrap class="TableData"><?=_("��֤����/��λ��")?></td>
      <td class="TableData">
         <input type="text" name="ISSUING_AUTHORITY" size="25" maxlength="200" class="BigInput" value="<?=$ISSUING_AUTHORITY?>">
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