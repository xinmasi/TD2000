<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");

$HTML_PAGE_TITLE = _("ְ��������ѯ");
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
    <td class="Big"><img align="absMiddle" src="<?=MYOA_STATIC_SERVER?>/static/images/menu/infofind.gif"><span class="big3"> <?=_("ְ��������ѯ")?></span></td>
  </tr>
</table>
<form enctype="multipart/form-data" action="#"  method="post" name="form1">
 <table class="TableBlock" width="450" align="center">
    <tr>
      <td nowrap class="TableData"><?=_("��������")?></td>
      <td class="TableData">
        <input type="text" name="BY_EVALU_NAME" size="15" class="BigInput" value="<?=substr(GetUserNameById($BY_EVALU_STAFFS),0,-1)?>">&nbsp;
        <input type="hidden" name="BY_EVALU_STAFFS" value="<?=$BY_EVALU_STAFFS?>">
        <a href="javascript:;" class="orgAdd" onClick="SelectUserSingle('27','','BY_EVALU_STAFFS', 'BY_EVALU_NAME','1')"><?=_("ѡ��")?></a>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"><?=_("��׼�ˣ�")?></td>
      <td class="TableData">
        <input type="text" name="APPROVE_PERSON_NAME" size="15" class="BigInput" value="<?=substr(GetUserNameById($APPROVE_PERSON),0,-1)?>">&nbsp;
        <input type="hidden" name="APPROVE_PERSON" value="<?=$APPROVE_PERSON?>">
        <a href="javascript:;" class="orgAdd" onClick="SelectUserSingle('27','','APPROVE_PERSON', 'APPROVE_PERSON_NAME')"><?=_("ѡ��")?></a>
      </td>
    </tr>
    <tr>
    	 <td nowrap class="TableData"> <?=_("��ȡְ�ƣ�")?></td>
      <td class="TableData">
        <INPUT type="text"name="POST_NAME" class=BigInput size="15" value="<?=$POST_NAME?>">
      </td>
      </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("��ȡ��ʽ��")?></td>
      <td class="TableData">
        <select name="GET_METHOD" class="BigSelect">
          <option value="" selected><?=_("��������")?>&nbsp;&nbsp;&nbsp;</option>
          <?=hrms_code_list("HR_STAFF_TITLE_EVALUATION",$GET_METHOD);?>
        </select>&nbsp;
      </td> 
    </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("�걨ʱ�䣺")?></td>
      <td class="TableData">
        <input type="text" id="start_time1" name="REPORT_TIME1" size="10" maxlength="10" class="BigInput" value="<?=$REPORT_TIME1?>" onClick="WdatePicker()"/>
        <?=_("��")?>
        <input type="text" name="REPORT_TIME2" size="10" maxlength="10" class="BigInput" value="<?=$REPORT_TIME2?>" onClick="WdatePicker({minDate:'#F{$dp.$D(\'start_time1\')}'})"
/>    
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("��ȡʱ�䣺")?></td>
      <td class="TableData">
        <input type="text" id="start_time2" name="RECEIVE_TIME1" size="10" maxlength="10" class="BigInput" value="<?=$RECEIVE_TIME1?>" onClick="WdatePicker()"/>
        <?=_("��")?>
        <input type="text" name="RECEIVE_TIME2" size="10" maxlength="10" class="BigInput" value="<?=$RECEIVE_TIME2?>" onClick="WdatePicker({minDate:'#F{$dp.$D(\'start_time2\')}'})"
/>      
      </td>
    </tr>
    <tr>
       <td nowrap class="TableData"> <?=_("Ƹ��ְ��")?></td>
      <td class="TableData">
        <INPUT type="text"name="EMPLOY_POST" class=BigInput size="15" value="<?=$EMPLOY_POST?>">
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("Ƹ�õ�λ��")?></td>
      <td class="TableData">
        <INPUT type="text"name="EMPLOY_COMPANY" class=BigInput size="15" value="<?=$EMPLOY_COMPANY?>">
      </td> 
    </tr>
    <tr>
      <td nowrap class="TableData" width="100"> <?=_("�������飺")?></td>
      <td class="TableData">
        <input type="text" name="REMARK" size="25" maxlength="200" class="BigInput" value="">
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