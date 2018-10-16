<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

$CUR_DATE=date("Y-m-d",time());
$BEGIN_DATE = substr(date('Y-m-d',strtotime($CUR_DATE.' -3 month')),0,7)."-01";
$END_DATE = substr(date('Y-m-d',strtotime($CUR_DATE.' +12 month')),0,7)."-01";

$HTML_PAGE_TITLE = _("导入和导出");
include_once("inc/header.inc.php");
?>
<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>

<script Language="JavaScript">
function my_export()
{
	 var CONTENT_NAME="";

   if(document.form1.CALENDAR_CONTENT.checked==false && document.form1.AFFAIR_CONTENT.checked==false && document.form1.TASK_CONTENT.checked==false)
   {
      alert("<?=_("至少选择一种导出内容")?>");
      return;
   }
   if(document.form1.BEGIN_DATE.value > document.form1.END_DATE.value)
   { alert("<?=_("条件中结束日期要晚于开始日期")?>");
     return (false);
   }   
   
   if(document.form1.CALENDAR_CONTENT.checked==true)
      CONTENT_NAME += "<?=_("日程")?>";
   if(document.form1.AFFAIR_CONTENT.checked==true)
      CONTENT_NAME += "<?=_("周期性事务")?>";
   if(document.form1.TASK_CONTENT.checked==true)
      CONTENT_NAME += "<?=_("任务")?>";

   document.form1.CONTENT_NAME.value= CONTENT_NAME;
   if(document.form1.TO_OUTLOOK_OA.item(0).checked)
   //if(document.getElementById("OUTLOOK_OA").options[document.getElementById("OUTLOOK_OA").options.selectedIndex].value=="1")
      document.form1.action="export_csv.php";
   else
   	 document.form1.action="export_xml.php";

   document.form1.submit();
}

var file_name;
function CheckForm()
{
  if(document.form2.XML_FILE.value=="")
  { alert("<?=_("请选择要导入的文件！")?>");
     return (false);
  }

  if(document.form2.XML_FILE.value!="")
  {
     var file_temp=document.form2.XML_FILE.value;
     var Pos;
     Pos=file_temp.lastIndexOf("\\");
     file_name=file_temp.substring(Pos+1,file_temp.length);
     document.form2.FILE_NAME.value=file_name;
  }

   return (true);
}

function my_inport()
{
	if(CheckForm())
	{
     if(document.form2.FROM_OUTLOOK_OA.item(0).checked)
        document.form2.action="import_csv.php";
     else
        document.form2.action="import_xml.php";
     document.form2.submit();        
	}
}
</script>


<body class="bodycolor">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/inout.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><span class="big3"> <?=_("日程数据导出")?></span>
    </td>
  </tr>
</table>

<br>
 <form action="search.php"  method="post" name="form1">
 <table class="TableBlock" width="60%" align="center">
<?
if($_SESSION["LOGIN_USER_PRIV"]=="1")
{
?>
  <tr>
    <td nowrap class="TableData"><?=_("选择范围(部门)：")?></td>
    <td class="TableData">
      <input type="hidden" name="TO_ID" value="">
      <textarea cols=35 name="TO_NAME" rows=2 class="BigStatic" wrap="yes" readonly></textarea>
      <a href="javascript:;" class="orgAdd" onClick="SelectDept('')"><?=_("添加")?></a>
     <a href="javascript:;" class="orgClear" onClick="ClearUser()"><?=_("清空")?></a>
    </td>
  </tr>
  <tr>
    <td nowrap class="TableData"><?=_("选择范围(角色)：")?></td>
    <td class="TableData">
      <input type="hidden" name="PRIV_ID" value="">
      <textarea cols=35 name="PRIV_NAME" rows=2 class="BigStatic" wrap="yes" readonly></textarea>
      <a href="javascript:;" class="orgAdd" onClick="SelectPriv('','PRIV_ID', 'PRIV_NAME')"><?=_("添加")?></a>
      <a href="javascript:;" class="orgClear" onClick="ClearUser('PRIV_ID', 'PRIV_NAME')"><?=_("清空")?></a>
    </td>
  </tr>
  <tr>
    <td nowrap class="TableData"><?=_("选择范围(人员)：")?></td>
    <td class="TableData">
      <input type="hidden" name="TO_ID2" value="">
      <textarea cols=35 name="TO_NAME2" rows="2" class="BigStatic" wrap="yes" readonly></textarea>
      <a href="javascript:;" class="orgAdd" onClick="SelectUser('8','','TO_ID2', 'TO_NAME2')"><?=_("添加")?></a>
      <a href="javascript:;" class="orgClear" onClick="ClearUser('TO_ID2', 'TO_NAME2')"><?=_("清空")?></a>
    </td>
  </tr>
<?
}
?>
    <tr>
      <td nowrap class="TableData" width="100"> <?=_("日期：")?></td>
      <td class="TableData">
        <input type="text" id="start_time" name="BEGIN_DATE" size="12" maxlength="10" class="BigInput" value="<?=$BEGIN_DATE?>" onClick="WdatePicker()">
         <?=_("至")?>&nbsp;
        <input type="text" name="END_DATE" size="12" maxlength="10" class="BigInput" value="<?=$END_DATE?>" onClick="WdatePicker({minDate:'#F{$dp.$D(\'start_time\')}'})">
    
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData" width="100"> <?=_("导出用于：")?></td>
      <td class="TableData">
       <!--<select name="OUTLOOK_OA" id="OUTLOOK_OA" class="BigSelect">
          <option value="1">Outlook</option>
          <option value="2"><?=_("OA系统")?></option>
       </select>
       -->
       <input type="radio" name="TO_OUTLOOK_OA" id="TO_OUTLOOK" value="1" ><label for="OUTLOOK">Outlook</label>
       <input type="radio" name="TO_OUTLOOK_OA" id="TO_OA_SYSTEM" value="2" checked><label for="OA_SYSTEM">OA<?=_("系统")?></label>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData" width="100"> <?=_("导出内容：")?></td>
      <td class="TableData">
      	<input type="checkbox" name="CALENDAR_CONTENT" id="CALENDAR_CONTENT" checked onClick="if(CALENDAR_CONTENT.checked==false && TASK_CONTENT.checked==false) DISPLAY_TYPE.style.display='none'; else DISPLAY_TYPE.style.display='';"><label for="CALENDAR_CONTENT"><?=_("日程")?></label>
      	<input type="checkbox" name="AFFAIR_CONTENT" id="AFFAIR_CONTENT"><label for="AFFAIR_CONTENT"><?=_("周期性事务")?></label>
      	<input type="checkbox" name="TASK_CONTENT" id="TASK_CONTENT"onclick="if(CALENDAR_CONTENT.checked==false && TASK_CONTENT.checked==false) DISPLAY_TYPE.style.display='none'; else DISPLAY_TYPE.style.display='';"><label for="TASK_CONTENT"><?=_("任务")?></label>
      </td>
    </tr>
    <tr id="DISPLAY_TYPE" style="display:;">
      <td nowrap class="TableData"> <?=_("事务类型：")?></td>
      <td class="TableData">
        <select name="CAL_TYPE" class="BigSelect">
          <option value=""><?=_("所有")?></option>
          <?=code_list("CAL_TYPE","")?>
        </select>
      </td>
    </tr>
    <tr align="center" class="TableControl">
      <td colspan="2" nowrap>
      	<input type="hidden" name="CONTENT_NAME" value="">
        <input type="button" value="<?=_("导出")?>" class="BigButton" onClick="my_export();">
      </td>
    </tr>
  </table>
</form>

<table border="0" width="90%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/inout.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><span class="big3"> <?=_("日程数据导入")?></span>
    </td>
  </tr>
</table>
<br>

<form enctype="multipart/form-data" action="#"  method="post" name="form2" onSubmit="return CheckForm();">
<table class="TableBlock" width="60%" align="center">
<?
if($_SESSION["LOGIN_USER_PRIV"]=="1")
{
?>
  <tr>
    <td nowrap class="TableData"><?=_("选择范围(部门）：")?></td>
    <td class="TableData">
      <input type="hidden" name="TO_ID_IN" value="">
      <textarea cols=35 name="TO_NAME_IN" rows=2 class="BigStatic" wrap="yes" readonly></textarea>
      <a href="javascript:;" class="orgAdd" onClick="SelectDept('','TO_ID_IN','TO_NAME_IN','','form2')"><?=_("添加")?></a>
     <a href="javascript:;" class="orgClear" onClick="ClearUser('TO_ID_IN','TO_NAME_IN')"><?=_("清空")?></a>
    </td>
  </tr>
  <tr>
    <td nowrap class="TableData"><?=_("选择范围(角色)：")?></td>
    <td class="TableData">
      <input type="hidden" name="PRIV_ID_IN" value="">
      <textarea cols=35 name="PRIV_NAME_IN" rows=2 class="BigStatic" wrap="yes" readonly></textarea>
      <a href="javascript:;" class="orgAdd" onClick="SelectPriv('','PRIV_ID_IN','PRIV_NAME_IN','','form2')"><?=_("添加")?></a>
      <a href="javascript:;" class="orgClear" onClick="ClearUser('PRIV_ID_IN', 'PRIV_NAME_IN')"><?=_("清空")?></a>
    </td>
  </tr>
  <tr>
    <td nowrap class="TableData"><?=_("选择范围(人员)：")?></td>
    <td class="TableData">
      <input type="hidden" name="TO_ID3_IN" value="">
      <textarea cols=35 name="TO_NAME3_IN" rows="2" class="BigStatic" wrap="yes" readonly></textarea>
      <a href="javascript:;" class="orgAdd" onClick="SelectUser('8','','TO_ID3_IN', 'TO_NAME3_IN','','form2')"><?=_("添加")?></a>
      <a href="javascript:;" class="orgClear" onClick="ClearUser('TO_ID3_IN', 'TO_NAME3_IN')"><?=_("清空")?></a>
    </td>
  </tr>
<?
}
?>
  <tr height="25">
    <td nowrap class="TableData"><?=_("选择要导入的文件：")?></td>
    <td class="TableData">
       <input type="file" name="XML_FILE" class="BigInput" size="35">
       <input type="hidden" name="FILE_NAME">
   </td>
  </tr>
  <tr height="25">
    <td nowrap class="TableData"><?=_("文件来自：")?></td>
    <td class="TableData">
       <input type="radio" name="FROM_OUTLOOK_OA" id="OUTLOOK" value="1" onClick="span_erea.style.display=''"><label for="OUTLOOK">Outlook</label>
       <input type="radio" name="FROM_OUTLOOK_OA" id="OA_SYSTEM" value="2" onClick="span_erea.style.display='none'"checked><label for="OA_SYSTEM">OA<?=_("系统")?></label>
   </td>
  </tr>
  <tr id="span_erea" style="display:none;">
    <td nowrap class="TableData"> <?=_("导入至：")?></td>
    <td class="TableData">
    	<input type="radio" name="CAL_AFF_TASK" id="CALENDAR_CONTENT2" value="1" checked><label for="CALENDAR_CONTENT2"><?=_("日程")?></label>
    	<input type="radio" name="CAL_AFF_TASK" id="AFFAIR_CONTENT2" value="2"><label for="AFFAIR_CONTENT2"><?=_("周期性事务")?></label>
    	<input type="radio" name="CAL_AFF_TASK" id="TASK_CONTENT2" value="3"><label for="TASK_CONTENT2"><?=_("任务")?></label>
    </td>
  </tr>
  <tr align="center" class="TableControl">
   <td colspan="2" nowrap>
     <input type="button" value="<?=_("导入")?>" class="BigButton" onClick="my_inport();">
   </td>
 </tr>
</table>
</form>


</body>
</html>