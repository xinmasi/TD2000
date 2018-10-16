<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("安排查询");
include_once("inc/header.inc.php");
?>
<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/js/bootstrap/css/bootstrap.css">
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>
<script Language="JavaScript">
function query()
{
	if(document.form1.SEND_TIME_MIN.value > document.form1.SEND_TIME_MAX.value)
	{
		window.alert("<?=_("开始日期不能大于结束日期")?>");
		document.form1.SEND_TIME_MIN.focus();
		return false;	
	}
   document.form1.action='search.php';
   document.form1.target='_self';
   document.form1.submit();
}

function cal_export()
{
   document.form1.action='export.php';
   document.form1.target='_blank';
   document.form1.submit();
}
</script>


<body class="bodycolor" onLoad="document.form1.CONTENT.focus();">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small" style="margin-top:10px;">
  <tr>
    <td class="Big" style="padding-left:20px;"><span class="big3"> <?=_("日程安排查询")?></span>
    </td>
  </tr>
</table>

<br>
 <table style="width:450px; margin:0 auto" align="center">
  <form action="search.php"  method="get" name="form1">
    <tr>
      <td nowrap width="100"> <?=_("日期：")?></td>
      <td>
        <input type="text"id="start_time" name="SEND_TIME_MIN" size="12" maxlength="10" style="width:79px" class="input-small" onClick="WdatePicker()">
      <?=_("至")?>&nbsp;
        <input type="text" name="SEND_TIME_MAX" size="12" maxlength="10" style="width:79px" class="input-small" onClick="WdatePicker({minDate:'#F{$dp.$D(\'start_time\')}'})">
      
      </td>
    </tr>
    <tr>
      <td nowrap> <?=_("状态：")?></td>
      <td>
        <select name="OVER_STATUS" >
          <option value=""><?=_("所有")?></option>
          <option value="1"><?=_("未开始")?></option>
          <option value="2"><?=_("进行中")?></option>
          <option value="3"><?=_("已超时")?></option>
          <option value="4"><?=_("已完成")?></option>
        </select>
      </td>
    </tr>
    <tr>
      <td nowrap> <?=_("事务类型：")?></td>
      <td>
        <select name="CAL_TYPE" >
          <option value=""><?=_("所有")?></option>
          <?=code_list("CAL_TYPE","")?>
        </select>
      </td>
    </tr>
    <tr>
      <td nowrap> <?=_("事务内容：")?></td>
      <td>
        <input type="text" name="CONTENT" size="33" >
      </td>
    </tr>
    <tr align="center">
      <td colspan="2" nowrap>
        <button type="button" class="btn btn-primary" onClick="query();" style="margin-left:-30px"><?=_("查询")?></button>
        <button type="button" class="btn" onClick="cal_export();"><?=_("打印")?></button>
<?
if($_COOKIE["cal_view"]=="")
   $BACK_URL = "index";
else
   $BACK_URL = $_COOKIE["cal_view"];
?>
        <button type="button" class="btn" value="" onClick="location='<?=$BACK_URL?>.php'"><?=_("返回")?></button> 
      </td>
    </tr>
  </table>
</form>

</body>
</html>