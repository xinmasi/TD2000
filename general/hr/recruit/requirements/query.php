<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");

$HTML_PAGE_TITLE = _("��Ƹ������Ϣ��ѯ");
include_once("inc/header.inc.php");
?>
<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/theme/<?=$_SESSION["LOGIN_THEME"]?>/bbs.css">
<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>
<script Language="JavaScript">
function exreport()
{
  document.form1.action='export.php';
  document.form1.submit();
}
function search()
{
  document.form1.action='search.php';
  document.form1.submit();
}
</script>

<body class="bodycolor">

<table border="0" width="80%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img align="absMiddle" src="<?=MYOA_STATIC_SERVER?>/static/images/menu/infofind.gif"><span class="big3"> <?=_("��Ƹ������Ϣ��ѯ")?></span></td>
  </tr>
</table>
<br>
<form enctype="multipart/form-data" action=""  method="post" name="form1">
 <table class="TableBlock" width="450" align="center">
    <tr>
      <td nowrap class="TableData"><?=_("�����ţ�")?></td>
      <td class="TableData">
         <INPUT type="text"name="REQU_NO" class=BigInput size="15" value="<?=$REQU_NO?>">
      </td>
    </tr>
    <tr>
    	<td nowrap class="TableData"><?=_("�����λ��")?></td>
      <td class="TableData">
        <INPUT type="text"name="REQU_JOB" class=BigInput size="15" value="<?=$REQU_JOB?>">
      </td>
   </tr>
   <tr>
    	<td nowrap class="TableData"><?=_("����������")?></td>
      <td class="TableData">
       <INPUT type="text"name="REQU_NUM" class=BigInput size="15" value="<?=$REQU_NUM?>">
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData" width="100"> <?=_("�����ţ�")?></td>
      <td class="TableData">
        <input type="hidden" name="REQU_DEPT" value="">
        <textarea cols=30 name=REQU_DEPT_NAME rows=2 class="BigStatic" wrap="yes" readonly></textarea>
        <a href="javascript:;" class="orgAdd" onClick="SelectDept('','REQU_DEPT', 'REQU_DEPT_NAME')"><?=_("���")?></a>
       <a href="javascript:;" class="orgClear" onClick="ClearUser('REQU_DEPT', 'REQU_DEPT_NAME')"><?=_("���")?></a>
      </td>
   </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("�ù����ڣ�")?></td>
      <td class="TableData">
        <input type="text" id="start_time" name="REQU_TIME1" size="10" maxlength="10" class="BigInput" value="<?=$REQU_TIME1?>" onClick="WdatePicker()"/>
        <?=_("��")?>
        <input type="text" name="REQU_TIME2" size="10" maxlength="10" class="BigInput" value="<?=$REQU_TIME2?>" onClick="WdatePicker({minDate:'#F{$dp.$D(\'start_time\')}'})"/>        
      </td>
    </tr>
    <tr align="center" class="TableControl">
      <td colspan="2" nowrap>
        <input type="submit" value="<?=_("��ѯ")?>" class="BigButton" onClick="search()">&nbsp;
        <input type="button" value="<?=_("����")?>" class="BigButton" onClick="exreport()">&nbsp;&nbsp;
      </td>
    </tr>
  </form>
 </table>


</body>
</html>