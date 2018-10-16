<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");

$HTML_PAGE_TITLE = _("奖惩信息查询");
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
      alert("<?=_("奖惩日期的结束查询时间不能小于奖惩日期的开始查询时间！")?>");
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
    <td class="Big"><img align="absMiddle" src="<?=MYOA_STATIC_SERVER?>/static/images/menu/infofind.gif"><span class="big3"> <?=_("奖惩信息查询")?></span></td>
  </tr>
</table>
<br>
<form enctype="multipart/form-data" action="#"  method="post" name="form1" >
 <table class="TableBlock" width="450" align="center">
    <tr>
      <td nowrap class="TableData"><?=_("单位员工：")?></td>
      <td class="TableData">
        <input type="text" name="STAFF_NAME1" size="10" class="BigStatic" readonly value="">&nbsp;
        <input type="hidden" name="STAFF_NAME" value="<?=$STAFF_NAME?>">
        <a href="javascript:;" class="orgAdd" onClick="SelectUserSingle('27','','STAFF_NAME', 'STAFF_NAME1','1')"><?=_("选择")?></a>
      </td>
    </tr>
        <tr>
      <td nowrap class="TableData"> <?=_("奖惩日期：")?></td>
      <td class="TableData">
        <input type="text" name="INCENTIVE_TIME1" size="10" maxlength="10" class="BigInput" id="start_time" value="<?=$INCENTIVE_TIME1?>" onClick="WdatePicker()"/>
        <?=_("至")?>
        <input type="text" name="INCENTIVE_TIME2" size="10" maxlength="10" class="BigInput" value="<?=$INCENTIVE_TIME2?>" onClick="WdatePicker({minDate:'#F{$dp.$D(\'start_time\')}'})"/>     
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData" width="100"> <?=_("奖惩项目：")?></td>
      <td class="TableData" >
        <select name="INCENTIVE_ITEM" style="background: white;" title="<?=_("奖惩项目名称可在“人力资源设置”->“HRMS代码设置”模块设置。")?>">
          <option value=""><?=_("请选择")?></option>
          <?=hrms_code_list("HR_STAFF_INCENTIVE1","")?>
        </select>
      </td> 
   </tr>
    </tr>
    <tr>
      <td nowrap class="TableData"><?=_("奖惩属性：")?></td>
      <td class="TableData">
        <select name="INCENTIVE_TYPE" >
          <option value=""><?=_("请选择")?></option>
          <?=hrms_code_list("INCENTIVE_TYPE",$INCENTIVE_TYPE)?>
        </select>
      </td> 
    </tr>
    
    <tr align="center" class="TableControl">
      <td colspan="2" nowrap>
        <input type="button" value="<?=_("查询")?>" class="BigButton" onclick="do_search()">&nbsp;&nbsp;
        <input type="button" value="<?=_("导出")?>" class="BigButton" onclick="do_export()">&nbsp;&nbsp;
      </td>
    </tr>
  </table>
</form>


</body>
</html>