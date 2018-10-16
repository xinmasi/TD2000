<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("新闻查询");
include_once("inc/header.inc.php");
?>

<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>

<script language="JavaScript">
function export_word()
{
	document.form1.action="export.php";
	document.form1.submit();
	
}
function search(){
	document.form1.action="search.php";
	document.form1.submit();
}
</script>

<body class="bodycolor">
<table border="0" width="90%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img align="absMiddle" src="<?=MYOA_STATIC_SERVER?>/static/images/menu/infofind.gif"><span class="big3"> <?=_("新闻查询")?></span>
    </td>
  </tr>
</table>
<table class="TableTop" width="550" align="center">
   <tr>
      <td class="left"></td>
      <td class="center"><?=_("输入查询条件")?></td>
      <td class="right"></td>
   </tr>
</table>
<table class="TableBlock no-top-border" width="550" align="center">
  <form enctype="multipart/form-data"   method="post" name="form1">
    <tr>
      <td nowrap class="TableData"><?=_("发布人：")?></td>
      <td class="TableData">
        <input type="hidden" name="TO_ID" value="">
        <textarea cols=35 name="TO_NAME" rows="3" class="BigStatic" wrap="yes" readonly></textarea>
        <a href="javascript:;" class="orgAdd" onClick="SelectUser('147','','TO_ID', 'TO_NAME')"><?=_("添加")?></a>
        <a href="javascript:;" class="orgClear" onClick="ClearUser('TO_ID', 'TO_NAME')"><?=_("清空")?></a>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData" width="100"> <?=_("格式：")?></td>
      <td class="TableData">
      <select name="FORMAT" class="BigSelect">
        <option value="" selected><?=_("全部")?></option>
        <option value="0"><?=_("普通格式")?></option>
        <option value="1">MHT<?=_("格式")?></option>
      </select>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("类型：")?></td>
      <td class="TableData"> 
        <select name="TYPE_ID" class="BigSelect">
          <option value="" selected><?=_("全部")?></option>
<?=code_list("NEWS","")?>
        </select>&nbsp;
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData" width="100"> <?=_("标题：")?></td>
      <td class="TableData">
        <input type="text" name="SUBJECT" size="33" maxlength="100" class="BigInput" value="">
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData" width="100"> <?=_("发布日期：")?></td>
      <td class="TableData">
        <input type="text" name="NEWS_TIME_MIN" size="12" maxlength="10" id="start_time" class="BigInput" value="" onClick="WdatePicker({maxDate:'%y-%M-%d %H-%m-%s'})"/> &nbsp;<?=_("至")?>&nbsp;
        <input type="text" name="NEWS_TIME_MAX" size="12" maxlength="10" class="BigInput" value="" onClick="WdatePicker({maxDate:'%y-%M-%d %H-%m-%s',minDate:'#F{$dp.$D(\'start_time\')}'})"/>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData" width="100"> <?=_("内容：")?></td>
      <td class="TableData">
        <input type="text" name="CONTENT" size="33" maxlength="200" class="BigInput" value="">
      </td>
    </tr>
    <tr align="center" class="TableControl">
      <td colspan="2" nowrap>
        <input type="submit" value="<?=_("查询")?>" class="BigButton" onClick="search()"title="<?=_("查询")?>" name="button">&nbsp;
        <input type="button"  value="<?=_("导出")?>" class="BigButton" onClick="export_word()" title="<?=_("导出")?>word<?=_("文件")?>">&nbsp;&nbsp;
        <input type="reset" value="<?=_("重填")?>" class="BigButton">&nbsp;&nbsp;
      </td>
    </tr>
  </table>
</form>

</body>
</html>