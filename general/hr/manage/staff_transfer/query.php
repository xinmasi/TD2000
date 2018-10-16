<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");

$HTML_PAGE_TITLE = _("人事调动信息查询");
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
      alert("<?=_("调动开始日期不能大于调动结束日期！")?>");
      return (false);
   }
   if(document.form1.TRANSFER_EFFECTIVE_DATE1.value!="" && document.form1.TRANSFER_EFFECTIVE_DATE2.value!="" && document.form1.TRANSFER_EFFECTIVE_DATE1.value > document.form1.TRANSFER_EFFECTIVE_DATE2.value)
   { 
      alert("<?=_("调动生效开始日期不能大于调动结束日期！")?>");
      return (false);
   }
  document.form1.action='export.php';
  document.form1.submit();
}
function do_search()
{
  if(document.form1.TRANSFER_DATE1.value!="" && document.form1.TRANSFER_DATE2.value!="" && document.form1.TRANSFER_DATE1.value > document.form1.TRANSFER_DATE2.value)
   { 
      alert("<?=_("调动开始日期不能大于调动结束日期！")?>");
      return (false);
   }
   if(document.form1.TRANSFER_EFFECTIVE_DATE1.value!="" && document.form1.TRANSFER_EFFECTIVE_DATE2.value!="" && document.form1.TRANSFER_EFFECTIVE_DATE1.value > document.form1.TRANSFER_EFFECTIVE_DATE2.value)
   { 
      alert("<?=_("调动生效开始日期不能大于调动结束日期！")?>");
      return (false);
   }
  document.form1.action='search.php';
  document.form1.submit();
}
</script>

<body class="bodycolor">

<table border="0" width="80%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img align="absMiddle" src="<?=MYOA_STATIC_SERVER?>/static/images/menu/infofind.gif"><span class="big3"> <?=_("人事调动信息查询")?></span></td>
  </tr>
</table>
<br>
<form enctype="multipart/form-data" action="search.php"  method="post" name="form1" >
 <table class="TableBlock" width="450" align="center">
    <tr>
      <td nowrap class="TableData"><?=_("调动人员：")?></td>
      <td class="TableData">
        <input type="text" name="TRANSFER_PERSON_NAME" size="15" class="BigInput" value="">&nbsp;
        <input type="hidden" name="TRANSFER_PERSON" value="<?=$TRANSFER_PERSON?>">
        <a href="javascript:;" class="orgAdd" onClick="SelectUserSingle('27','','TRANSFER_PERSON', 'TRANSFER_PERSON_NAME','1')"><?=_("选择")?></a>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("调动类型：")?></td>
      <td class="TableData" >
        <select name="TRANSFER_TYPE" style="background: white;" title="<?=_("调动类型可在“人力资源设置”->“HRMS代码设置”模块设置。")?>">
          <option value=""><?=_("请选择")?></option>
          <?=hrms_code_list("HR_STAFF_TRANSFER","")?>
        </select>
      </td> 
    </tr> 
    </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("调动日期：")?></td>
      <td class="TableData">
        <input type="text" name="TRANSFER_DATE1" size="10" maxlength="10" class="BigInput" id="transfer_start_time" value="<?=$TRANSFER_DATE1?>" onClick="WdatePicker()"/>
        <?=_("至")?>
        <input type="text" name="TRANSFER_DATE2" size="10" maxlength="10" class="BigInput" value="<?=$TRANSFER_DATE2?>" onClick="WdatePicker({minDate:'#F{$dp.$D(\'transfer_start_time\')}'})"/>   
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("调动生效日期：")?></td>
      <td class="TableData">
        <input type="text" name="TRANSFER_EFFECTIVE_DATE1" size="10" maxlength="10" id="start_time" class="BigInput" value="<?=$TRANSFER_EFFECTIVE_DATE1?>" onClick="WdatePicker()"/> 
        <?=_("至")?>
        <input type="text" name="TRANSFER_EFFECTIVE_DATE2" size="10" maxlength="10" class="BigInput" value="<?=$TRANSFER_EFFECTIVE_DATE2?>" onClick="WdatePicker({minDate:'#F{$dp.$D(\'start_time\')}'})"/>        
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData" width="100"> <?=_("调动原因：")?></td>
      <td class="TableData">
        <input type="text" name="TRAN_REASON" size="25" maxlength="200" class="BigInput" value="<?=$TRAN_REASON?>">
      </td>
    </tr>
    <tr align="center" class="TableControl">
      <td colspan="2" nowrap>
        <input type="button" value="<?=_("查询")?>" class="BigButton" onclick="do_search()">&nbsp;&nbsp;
        <input type="button" value="<?=_("导出")?>" class="BigButton" onclick="do_export()">&nbsp;&nbsp;
      </td>
    </tr>
  </form>
 </table>


</body>
</html>