<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("新闻查询");
include_once("inc/header.inc.php");
?>


<script src="/module/DatePicker/WdatePicker.js"></script>
<script Language="JavaScript">
function checkForm()
{
    if(document.getElementById("OPERATION1").checked)
        document.form1.action="search.php";
    else if(document.getElementById("OPERATION2").checked)
    {
         msg='删除后不能恢复，确认删除吗？';
        if(window.confirm(msg))
        {
            document.form1.action="search_del.php";
        }
        else
            return false;
    }
    return true;
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
<form enctype="multipart/form-data" action="search.php"  method="post" name="form1" onsubmit="return checkForm();">
<table class="TableBlock no-top-border" width="550" align="center">
    <tr>
      <td nowrap class="TableData" width="100"> <?=_("格式：")?></td>
      <td class="TableData">
      <select name="FORMAT" class="BigSelect">
        <option value="" selected><?=_("全部")?></option>
        <option value="0"><?=_("普通格式")?></option>
        <option value="1">MHT<?=_("格式")?></option>
        <option value="2"><?=_("超链接格式")?></option>
      </select>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("类型：")?></td>
      <td class="TableData"> 
        <select name="TYPE_ID" class="BigSelect">
          <option value="" selected><?=_("全部")?></option>
          <?=code_list("NEWS","");?>
        </select>&nbsp;
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData" width="100"> <?=_("状态：")?></td>
      <td class="TableData">
      <select name="PUBLISH" class="BigSelect">
        <option value="" selected><?=_("全部")?></option>
        <option value="0"><?=_("未发布")?></option>
        <option value="1"><?=_("已发布")?></option>
      </select>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData" width="100"> <?=_("是否置顶：")?></td>
      <td class="TableData">
      <select name="TOP" class="BigSelect">
        <option value="" selected><?=_("全部")?></option>
        <option value="0"><?=_("未置顶")?></option>
        <option value="1"><?=_("已置顶")?></option>
      </select>
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
        <input type="text" name="SEND_TIME_MIN" size="12" maxlength="10" id="start_time" class="BigInput" value="" onClick="WdatePicker()"/> &nbsp;<?=_("至")?>&nbsp;
        <input type="text" name="SEND_TIME_MAX" size="12" maxlength="10" class="BigInput" value="" onClick="WdatePicker({minDate:'#F{$dp.$D(\'start_time\')}'})"/>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData" width="100"> <?=_("内容：")?></td>
      <td class="TableData">
        <input type="text" name="CONTENT" size="33" maxlength="200" class="BigInput" value="">
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData" width="100"> <?=_("点击次数：")?></td>
      <td class="TableData">
        <input type="text" name="CLICK_COUNT_MIN" size="4" maxlength="10" class="BigInput" value=""> &nbsp;<?=_("至")?>&nbsp;
        <input type="text" name="CLICK_COUNT_MAX" size="4" maxlength="10" class="BigInput" value="">
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData" width="100"> <?=_("操作：")?></td>
      <td class="TableData">
        <input type="radio" name="OPERATION" id="OPERATION1" value="1" checked><label for="OPERATION1"><?=_("查询")?></label>
        <input type="radio" name="OPERATION" id="OPERATION2" value="2"><label for="OPERATION2"><?=_("删除")?></label>
      </td>
    </tr>
    <tr align="center" class="TableControl">
      <td colspan="2" nowrap>
        <input type="submit" value="<?=_("确定")?>" class="BigButton">&nbsp;&nbsp;
        <input type="reset" value="<?=_("重填")?>" class="BigButton">&nbsp;&nbsp;
      </td>
    </tr>
  </table>
</form>

</body>
</html>