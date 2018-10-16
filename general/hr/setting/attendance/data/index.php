<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("删除考勤数据");
include_once("inc/header.inc.php");
?>
<link rel="stylesheet" type="text/css" href="/static/js/bootstrap/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="/static/common/bootstrap.reset.css">
<link rel="stylesheet" type="text/css" href="/static/modules/attendance/personal/common.css">
<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>
<script>
function CheckForm()
{
   if(window.confirm("<?=_("删除后数据将不可恢复，确定要删除吗？")?>"))
      return true;

   return false;
}
</script>


<body class="attendance">

<h5 class="attendance-title"><?=_("删除考勤数据")?></h5>

<br>
<form action="delete.php" method="post" name="form1" onsubmit="return CheckForm();">
  <table class="table table-middle table-bordered"  width="500" align="center">
  
    <tr>
      <th nowrap class=""><?=_("用户：")?></th>
      <td class="">
          <input type="hidden" name="TO_ID" value="">
        <textarea cols=35 name="TO_NAME" rows="3" class="" wrap="yes" readonly></textarea>
        <a href="javascript:;" class="orgAdd" onClick="SelectUser('27','','TO_ID', 'TO_NAME')"><?=_("添加")?></a>
        <a href="javascript:;" class="orgClear" onClick="ClearUser('TO_ID', 'TO_NAME')"><?=_("清空")?></a>
        <br><?=_("提示：如果不选择用户，则删除所有用户的考勤记录。")?>
      </td>
    </tr>    
    <tr>
      <th nowrap class=""><?=_("起始时间：")?></th>
      <td class=""><input type="text" name="BEGIN_DATE" size="20" maxlength="20" class="" value="" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})">
      </td>
    </tr>
    <tr>
      <th nowrap class=""><?=_("截止时间：")?></th>
      <td class=""><input type="text" name="END_DATE" size="20" maxlength="20" class="" value="<?=date('Y-m-d H:i:s',time())?>" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})">
      </td>
    </tr>
    <tr>
      <th nowrap class=""><?=_("删除项目：")?></th>
      <td class="">
          <label for="DUTY"><input type="checkbox" name="DUTY" id="DUTY"><?=_("上下班登记")?></label>
          <label for="OUT"><input type="checkbox" name="OUT" id="OUT"><?=_("外出登记")?></label>
          <label for="LEAVE"><input type="checkbox" name="LEAVE" id="LEAVE"><?=_("请假登记")?></label>
          <label for="EVECTION"><input type="checkbox" name="EVECTION" id="EVECTION"><?=_("出差登记")?></label>
          <label for="OVERTIME"><input type="checkbox" name="OVERTIME" id="OVERTIME"><?=_("加班登记")?></label>
          <label for="MOBILE_ATTENDANCE"><input type="checkbox" name="MOBILE_ATTENDANCE" id="MOBILE_ATTENDANCE"><?=_("外勤登记")?></label>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableControl" colspan="2" style="text-align: center;">
          <input type="submit"  value="<?=_("确定")?>" class="btn btn-primary" title="<?=_("确定")?>">&nbsp;&nbsp;
          <input type="button"  value="<?=_("返回")?>" class="btn" onClick="location='../#dataManage';">
      </td>
    </tr>
   
  </table>
 </form>
<br>

</body>
</html>