<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");

$PRIV_NO_FLAG="2";
$MANAGE_FLAG="0";
$MODULE_ID=3;
include_once("inc/my_priv.php");

$HTML_PAGE_TITLE = _("安排查询");
include_once("inc/header.inc.php");
?>
<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/theme/<?=$_SESSION["LOGIN_THEME"]?>/calendar.css">
<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/js/bootstrap/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/js/bootstrap/datepicker/bootstrap.datepicker.css">
<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/js/bootstrap/timepicker/bootstrap.timepicker.css">
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/bootstrap/datepicker/bootstrap.datepicker.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/bootstrap/timepicker/bootstrap.timepicker.js"></script>
<script Language="JavaScript">
function query()
{ 
  var  nowTem = new Date(),
       start_time = document.form1.SEND_TIME_MIN.value,
       end_time = document.form1.SEND_TIME_MAX.value;

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

function select_cal_type()
{
   if(form1.CAL_TYPE.value=="0")
   {
    document.getElementById("cal").style.display="";
    document.getElementById("cal1").style.display="";
    document.getElementById("task").style.display="none";
    document.getElementById("task1").style.display="none";
   }
    if(form1.CAL_TYPE.value=="1")
   {
      document.getElementById("cal").style.display="none";
      document.getElementById("cal1").style.display="none";
      document.getElementById("task").style.display="none";
      document.getElementById("task1").style.display="none";
   }
    if(form1.CAL_TYPE.value=="2")
   {
      document.getElementById("cal").style.display="none";
      document.getElementById("cal1").style.display="none";
      document.getElementById("task").style.display="";
      document.getElementById("task1").style.display="";
   }

}
function export_excel()
{
   document.form1.action='export_excel.php';
   //document.form1.target='_blank';
   document.form1.submit();
}

(function($){
	jQuery(document).ready(function(){
		var dateLangConfigs = {
			monthNames: ['一月','二月','三月','四月','五月','六月','七月','八月','九月','十月','十一月','十二月'],
            monthNamesShort: ['1','2','3','4','5','6','7','8','9','10','11','12'],
            dayNames: ['星期日','星期一','星期二','星期三','星期四','星期五','星期六'],
            dayNamesShort: ['日','一','二','三','四','五','六']
		};

		$.fn.datepicker.dates = {
			days: dateLangConfigs['dayNames'],
			daysShort: dateLangConfigs['dayNamesShort'],
			daysMin: dateLangConfigs['dayNamesShort'],
			months: dateLangConfigs['monthNames'],
			monthsShort:  dateLangConfigs['monthNamesShort']
		};
		$('.calendar-startdate, .calendar-enddate').datepicker({
		   format: "yyyy-mm-dd"
		}); 
		$('.calendar-starttime, .calendar-endtime').timepicker({ 
		   minuteStep: 5
		});
	});
})(jQuery);
</script>


<body class="bodycolor" onLoad="document.form1.CONTENT.focus();">
<div style="width:650px; margin:0 auto; height:50px; line-height:50px;">
   <span class="big3"> <?=_("日程安排查询")?></span>
</div>
<div style="width:650px;margin:0 auto; background-color:#FFF; padding:20px 0 20px 0">
  <form action="search.php"  method="post" name="form1" class="form-horizontal">
      <div class="control-group">
        <label class="control-label" for="CAL_TYPE"><?=_("类型选择：")?></label>
        <div class="controls">
        <select name="CAL_TYPE" onChange=select_cal_type()>
          <option value="0"><?=_("日程")?></option>
          <option value="1"><?=_("周期性事务")?></option>
          <option value="2"><?=_("任务")?></option>
        </select>
        </div>
      </div>
<?
if($DEPT_PRIV!="3")
{
?>
    <div class="control-group">
      <label class="control-label" for="DEPT_ID"><?=_("部门：")?></label>
      <div class="controls">
       <select name="DEPT_ID">
        <option value=""></option>
         <?=my_dept_tree(0,$DEPT_ID,array("DEPT_PRIV" => $DEPT_PRIV,"DEPT_ID_STR" => $DEPT_ID_STR));?>
       </select>
     </div>
    </div>
<?
}
?>
    <div class="control-group">
      <label class="control-label"><?=_("日期：")?></label>
      <div class="controls">
        <!--<input type="text" name="SEND_TIME_MIN" class="input-small" onClick="WdatePicker()">
       <?=_("至")?>
        <input type="text" name="SEND_TIME_MAX" class="input-small" onClick="WdatePicker()">-->
        <input class="input input-small calendar-startdate valtype" name="SEND_TIME_MIN" style="width:82px" placeholder="开始日期" type="text" data-valtype="placeholder">
       <?=_("至")?>
        <input class="input input-small calendar-startdate valtype" name="SEND_TIME_MAX" style="width:82px" placeholder="结束日期" type="text" data-valtype="placeholder">        
      </div>
    </div>
    <div id="cal" class="control-group">
      <label class="control-label" for="CAL_LEVEL"><?=_("优先程度：")?></label>
      <div class="controls">
        <select name="CAL_LEVEL">
          <option value=""><?=_("所有")?></option>
          <option value="0"><?=cal_level_desc("")?></option>
          <option value="1"><?=cal_level_desc("1")?></option>
          <option value="2"><?=cal_level_desc("2")?></option>
          <option value="3"><?=cal_level_desc("3")?></option>
          <option value="4"><?=cal_level_desc("4")?></option>
        </select>
      </div>
     </div>
     <div id="cal1" class="control-group">
      <label class="control-label" for="OVER_STATUS"><?=_("状态：")?></label>
      <div class="controls">
        <select name="OVER_STATUS">
          <option value=""><?=_("所有")?></option>
          <option value="1"><?=_("未开始")?></option>
          <option value="2"><?=_("进行中")?></option>
          <option value="3"><?=_("已超时")?></option>
          <option value="4"><?=_("已完成")?></option>
        </select>
      </div>
     </div>
      <div id="task" style="display:none" class="control-group">
      <label class="control-label" for="IMPORTANT"><?=_("优先级：")?></label>
      <div class="controls">
        <select name="IMPORTANT">
          <option value=""><?=_("所有")?></option>
          <option value="0"><?=cal_level_desc("")?></option>
          <option value="1"><?=cal_level_desc("1")?></option>
          <option value="2"><?=cal_level_desc("2")?></option>
          <option value="3"><?=cal_level_desc("3")?></option>
          <option value="4"><?=cal_level_desc("4")?></option>
        </select>
      </div>
      </div>
      <div id="task1" style="display:none" class="control-group">
        <label class="control-label" for="TASK_STATUS"><?=_("状态：")?></label>
        <div class="controls">
        <select name="TASK_STATUS">
          <option value=""><?=_("所有")?></option>
          <option value="1"><?=_("未开始")?></option>
          <option value="2"><?=_("进行中")?></option>
          <option value="3"><?=_("已完成")?></option>
          <option value="4"><?=_("等待其他人")?></option>
          <option value="5"><?=_("已推辞")?></option>
        </select>
      </div>
      </div>
      <label class="control-label"><?=_("事务内容：")?></label>
      <div class="controls">
        <input name="CONTENT" type="text">
      </div>

      <div class="controls queryButtonGroup">
        <input type="hidden" value="" name="ACTION_TYPE">
        <button type="button" onClick="query();" class="btn btn-primary"><?=_("查询")?></button>
        <button type="button" onClick="export_excel();" class="btn"><?=_("导出EXCEL")?></button>
        <button type="button" onClick="javascript:window.location='index.php'" class="btn"><?=_("返回")?></button>
      </div>
</form>
</div>
</body>
</html>