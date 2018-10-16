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
function LoadWindow()
{
  URL="leave_name_select";
  loc_x=document.body.scrollLeft+event.clientX-event.offsetX-100;
  loc_y=document.body.scrollTop+event.clientY-event.offsetY+170;
  if(window.showModalDialog)
  {
	  window.showModalDialog(URL,self,"edge:raised;scroll:0;status:0;help:0;resizable:1;dialogWidth:320px;dialogHeight:245px;dialogTop:"+loc_y+"px;dialogLeft:"+loc_x+"px");
  }
  else
  {
	  window.open(URL,"parent","height=245,width=320,status=0,toolbar=no,menubar=no,location=no,scrollbars=yes,top="+loc_y+",left="+loc_x+",resizable=yes,modal=yes,dependent=yes,dialog=yes,minimizable=no",true);
  }
}
function load_name(leave_id, leave_name)
{
	document.form1.LEAVE_PERSON.value=leave_id;
	document.form1.LEAVE_PERSON_NAME.value=leave_name;
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
    <td class="Big"><img align="absMiddle" src="<?=MYOA_STATIC_SERVER?>/static/images/menu/infofind.gif"><span class="big3"> <?=_("Ա����ְ��Ϣ��ѯ")?></span></td>
  </tr>
</table>
<br>
<form enctype="multipart/form-data" action="#"  method="post" name="form1">
  <table class="TableBlock" width="450" align="center">
    <tr>
      <td nowrap class="TableData"><?=_("��ְ��Ա��")?></td>
      <td class="TableData">
        <input type="text" name="LEAVE_PERSON_NAME" size="15" class="BigInput" value="<?=$LEAVE_PERSON_NAME?>">&nbsp;
        <input type="hidden" name="LEAVE_PERSON" value="<?=$LEAVE_PERSON?>">
        <a href="javascript:;" class="orgAdd" onClick="LoadWindow()"><?=_("ѡ��")?></a>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("��ְ���ͣ�")?></td>
      <td class="TableData" >
        <select name="QUIT_TYPE" style="background: white;" title="<?=_("��ְ���Ϳ��ڡ�������Դ���á�->��HRMS�������á�ģ�����á�")?>">
          <option value=""><?=_("��ְ����")?>&nbsp&nbsp&nbsp&nbsp&nbsp;</option>
          <?=hrms_code_list("HR_STAFF_LEAVE","")?>
        </select>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("��ְ���ţ�")?></td>
      <td class="TableData" >
        <input type="hidden" name="LEAVE_DEPT" value="<?=$LEAVE_DEPT?>">
        <input type="text" name="LEAVE_DEPT_NAME" value="<?=td_trim(GetDeptNameById($LEAVE_DEPT))?>" class=BigStatic size=15 maxlength=100 readonly>
        <a href="javascript:;" class="orgAdd" onClick="SelectDeptSingle('','LEAVE_DEPT','LEAVE_DEPT_NAME')"><?=_("ѡ��")?></a>      	
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("����ְ���ڣ�")?></td>
      <td class="TableData">
        <input type="text" name="QUIT_TIME_PLAN1" size="10" maxlength="10" class="BigInput" id="leave_start_time" value="<?=$QUIT_TIME_PLAN1?>" onClick="WdatePicker()"/>
        <?=_("��")?>
        <input type="text" name="QUIT_TIME_PLAN2" size="10" maxlength="10" class="BigInput" value="<?=$QUIT_TIME_PLAN2?>" onClick="WdatePicker({minDate:'#F{$dp.$D(\'leave_start_time\')}'})"/>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("ʵ����ְ���ڣ�")?></td>
      <td class="TableData">
        <input type="text" name="QUIT_TIME_FACT1" size="10" maxlength="10" class="BigInput" id="reallLeave_start_time" value="<?=$QUIT_TIME_FACT1?>" onClick="WdatePicker()"/>
        <?=_("��")?>
        <input type="text" name="QUIT_TIME_FACT2" size="10" maxlength="10" class="BigInput" value="<?=$QUIT_TIME_FACT2?>" onClick="WdatePicker({minDate:'#F{$dp.$D(\'reallLeave_start_time\')}'})"/>      
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData" width="100"> <?=_("��ְԭ��")?></td>
      <td class="TableData">
        <input type="text" name="QUIT_REASON" size="25" maxlength="200" class="BigInput" value="<?=$QUIT_REASON?>">
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
        <input type="button" value="<?=_("��ѯ")?>" class="BigButton" onClick="do_search()">&nbsp;&nbsp;
        <input type="button" value="<?=_("����")?>" class="BigButton" onClick="do_export()">&nbsp;&nbsp;
      </td>
    </tr>
  </table>
</form>
 
</body>
</html>