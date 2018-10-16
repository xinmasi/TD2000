<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");

$HTML_PAGE_TITLE = _("Ա����ְ��Ϣ��ѯ");
include_once("inc/header.inc.php");
?>


<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/theme/<?=$_SESSION["LOGIN_THEME"]?>/bbs.css">
<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>
<script>
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
    <td class="Big"><img align="absMiddle" src="<?=MYOA_STATIC_SERVER?>/static/images/menu/infofind.gif"><span class="big3"> <?=_("Ա����ְ��Ϣ��ѯ")?></span></td>
  </tr>
</table>
<br>
<form enctype="multipart/form-data" action="#"  method="post" name="form1">
 <table class="TableBlock" width="450" align="center">
    <tr>
      <td nowrap class="TableData"><?=_("��ְ��Ա��")?></td>
      <td class="TableData">
        <input type="text" name="REINSTATEMENT_PERSON_NAME" size="15" class="BigInput" value="">&nbsp;
        <input type="hidden" name="REINSTATEMENT_PERSON" value="<?=$LEAVE_PERSON?>">
        <a href="javascript:;" class="orgAdd" onClick="SelectUserSingle('27','','REINSTATEMENT_PERSON', 'REINSTATEMENT_PERSON_NAME')"><?=_("ѡ��")?></a>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("��ְ���ͣ�")?></td>
      <td class="TableData" >
        <select name="REAPPOINTMENT_TYPE" style="background: white;" title="<?=_("��ְ���Ϳ��ڡ�������Դ���á�->��HRMS�������á�ģ�����á�")?>">
          <option value=""><?=_("��ְ����")?>&nbsp&nbsp&nbsp&nbsp&nbsp;</option>
          <?=hrms_code_list("HR_STAFF_REINSTATEMENT","")?>
        </select>
      </td> 
    </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("�������ڣ�")?></td>
      <td class="TableData">
        <input type="text" id="start_time1" name="APPLICATION_DATE1" size="10" maxlength="10" class="BigInput" value="<?=$APPLICATION_DATE1?>" onClick="WdatePicker()"/>
        <?=_("��")?>
        <input type="text" name="APPLICATION_DATE2" size="10" maxlength="10" class="BigInput" value="<?=$APPLICATION_DATE2?>" onClick="WdatePicker({minDate:'#F{$dp.$D(\'start_time1\')}'})"/>      
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("ʵ�ʸ�ְ���ڣ�")?></td>
      <td class="TableData">
        <input type="text" id="start_time2" name="REAPPOINTMENT_TIME_FACT1" size="10" maxlength="10" class="BigInput" value="<?=$REAPPOINTMENT_TIME_FACT1?>" onClick="WdatePicker()"/>
        <?=_("��")?>
        <input type="text" name="REAPPOINTMENT_TIME_FACT2" size="10" maxlength="10" class="BigInput" value="<?=$REAPPOINTMENT_TIME_FACT2?>" onClick="WdatePicker({minDate:'#F{$dp.$D(\'start_time2\')}'})"/>     
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData" width="100"> <?=_("��ְ˵����")?></td>
      <td class="TableData">
        <input type="text" name="REAPPOINTMENT_STATE" size="25" maxlength="200" class="BigInput" value="<?=$REAPPOINTMENT_STATE?>">
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData" width="100"> <?=_("��ְ��������")?></td>
      <td class="TableData">
        <input type="text" name="MATERIALS_CONDITION" size="25" maxlength="200" class="BigInput" value="<?=$MATERIALS_CONDITION?>">
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