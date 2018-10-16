<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");

$HTML_PAGE_TITLE = _("招聘需求信息查询");
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
    <td class="Big"><img align="absMiddle" src="<?=MYOA_STATIC_SERVER?>/static/images/menu/infofind.gif"><span class="big3"> <?=_("招聘需求信息查询")?></span></td>
  </tr>
</table>
<br>
<form enctype="multipart/form-data" action=""  method="post" name="form1">
 <table class="TableBlock" width="450" align="center">
    <tr>
      <td nowrap class="TableData"><?=_("需求编号：")?></td>
      <td class="TableData">
         <INPUT type="text"name="REQU_NO" class=BigInput size="15" value="<?=$REQU_NO?>">
      </td>
    </tr>
    <tr>
    	<td nowrap class="TableData"><?=_("需求岗位：")?></td>
      <td class="TableData">
        <INPUT type="text"name="REQU_JOB" class=BigInput size="15" value="<?=$REQU_JOB?>">
      </td>
   </tr>
   <tr>
    	<td nowrap class="TableData"><?=_("需求人数：")?></td>
      <td class="TableData">
       <INPUT type="text"name="REQU_NUM" class=BigInput size="15" value="<?=$REQU_NUM?>">
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData" width="100"> <?=_("需求部门：")?></td>
      <td class="TableData">
        <input type="hidden" name="REQU_DEPT" value="">
        <textarea cols=30 name=REQU_DEPT_NAME rows=2 class="BigStatic" wrap="yes" readonly></textarea>
        <a href="javascript:;" class="orgAdd" onClick="SelectDept('','REQU_DEPT', 'REQU_DEPT_NAME')"><?=_("添加")?></a>
       <a href="javascript:;" class="orgClear" onClick="ClearUser('REQU_DEPT', 'REQU_DEPT_NAME')"><?=_("清空")?></a>
      </td>
   </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("用工日期：")?></td>
      <td class="TableData">
        <input type="text" id="start_time" name="REQU_TIME1" size="10" maxlength="10" class="BigInput" value="<?=$REQU_TIME1?>" onClick="WdatePicker()"/>
        <?=_("至")?>
        <input type="text" name="REQU_TIME2" size="10" maxlength="10" class="BigInput" value="<?=$REQU_TIME2?>" onClick="WdatePicker({minDate:'#F{$dp.$D(\'start_time\')}'})"/>        
      </td>
    </tr>
    <tr align="center" class="TableControl">
      <td colspan="2" nowrap>
        <input type="submit" value="<?=_("查询")?>" class="BigButton" onClick="search()">&nbsp;
        <input type="button" value="<?=_("导出")?>" class="BigButton" onClick="exreport()">&nbsp;&nbsp;
      </td>
    </tr>
  </form>
 </table>


</body>
</html>