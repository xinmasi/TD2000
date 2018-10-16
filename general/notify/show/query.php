<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("公告通知查询");
include_once("inc/header.inc.php");
?>

<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>

<body class="bodycolor">
<table border="0" width="90%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img align="absMiddle" src="<?=MYOA_STATIC_SERVER?>/static/images/menu/infofind.gif"><span class="big3"> <?=_("公告通知查询")?></span>
    </td>
  </tr>
</table>
<table class="TableBlock" width="550" align="center">
  <form enctype="multipart/form-data" action="search.php"  method="post" name="form1">
    <tr>
      <td nowrap class="TableData"><?=_("发布人：")?></td>
      <td class="TableData">
        <input type="hidden" name="TO_ID" value="">
        <textarea cols=35 name="TO_NAME" rows="3" class="BigStatic" wrap="yes" readonly></textarea>
        <a href="javascript:;" class="orgAdd" onClick="SelectUser('4','','TO_ID', 'TO_NAME')"><?=_("添加")?></a>
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
<?=code_list("NOTIFY","")?>
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
        <input type="text" name="SEND_TIME_MIN" id="start_time" size="12" maxlength="10" class="BigInput" value="" onClick="WdatePicker()"/> <?=_("至")?>&nbsp;
        <input type="text" name="SEND_TIME_MAX" size="12" maxlength="10" class="BigInput" value="" onclick="WdatePicker({minDate:'#F{$dp.$D(\'start_time\')}'})"/>
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
        <input type="submit" value="<?=_("查询")?>" class="BigButton">&nbsp;&nbsp;
        <input type="reset" value="<?=_("重填")?>" class="BigButton">&nbsp;&nbsp;
      </td>
    </tr>
  </table>
</form>

</body>
</html>