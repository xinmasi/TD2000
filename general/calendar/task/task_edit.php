<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
//2013-04-11 主从服务器查询判断
if($IS_MAIN==1)
   $QUERY_MASTER=true;
else
   $QUERY_MASTER="";

if($TASK_ID!="")
{
    $HTML_SHOW_TITLE = _("修改任务");
}
else
{
    $HTML_SHOW_TITLE = _("新建任务");
}

$HTML_PAGE_TITLE = $HTML_SHOW_TITLE;
include_once("inc/header.inc.php");
?>

<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/theme/<?=$_SESSION["LOGIN_THEME"]?>/calendar.css">
<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/js/bootstrap/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/js/bootstrap/datepicker/bootstrap.datepicker.css">
<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/js/bootstrap/timepicker/bootstrap.timepicker.css">
<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/js/validation/validationEngine.jquery.min.css">
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script type="text/javascript" src="/inc/js_lang.php"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/calendar.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/bootstrap/datepicker/bootstrap.datepicker.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/bootstrap/timepicker/bootstrap.timepicker.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/validation/jquery.validationEngine.min.js" charset="utf-8" type="text/javascript"></script>

<script Language="JavaScript">
jQuery(document).ready(function(){
    jQuery("#form1").validationEngine({promptPosition:"centerRight"});
});
function CheckForm()
{
	 //var TASK_NO=document.form1.TASK_NO.value;
	 var SMS_REMIND=document.form1.SMS_REMIND
	 var SMS2_REMIND=document.form1.SMS2_REMIND;
	 var RATE=document.form1.RATE.value;
	 var TOTAL_TIME=document.form1.TOTAL_TIME.value;
	 var USE_TIME=document.form1.USE_TIME.value;

   return (true);
	
}
(function($){
	jQuery(document).ready(function(){
		var dateLangConfigs = {
			monthNames: ['一月','二月','三月','四月','五月','六月','七月','八月','九月','十月','十一月','十二月'],
            monthNamesShort: ['1','2','3','4','5','6','7','8','9','10','11','12'],
            dayNames: ['星期日','星期一','星期二','星期三','星期四','星期五','星期六'],
            dayNamesShort: ['日','一','二','三','四','五','六']
		};
		if($("#SMS_REMIND").attr("checked")!=true)
		{
			$("#remind_time").hide();
		}
		$.fn.datepicker.dates = {
			days: dateLangConfigs['dayNames'],
			daysShort: dateLangConfigs['dayNamesShort'],
			daysMin: dateLangConfigs['dayNamesShort'],
			months: dateLangConfigs['monthNames'],
			monthsShort:  dateLangConfigs['monthNamesShort']
		};
    var checkin = $('#BEGIN_DATE').datepicker({
      format: "yyyy-m-d"
    }).on('changeDate', function(ev) {         
            var newDate = new Date(ev.date)
            newDate.setDate(newDate.getDate());
            checkout.setValue(newDate);         
          checkin.hide();
          $('#END_DATE')[0].focus();
        }).data('datepicker');
        var checkout = $('#END_DATE').datepicker({
          format: "yyyy-m-d",
          onRender: function(date) {
            return date.valueOf() < checkin.date.valueOf() ? 'disabled' : '';
          }
        }).on('changeDate', function(ev) {
          checkout.hide();
        }).data('datepicker');
         var checkoutfinish = $('#FINISH_TIME').datepicker({
          format: "yyyy-m-d",
          onRender: function(date) {
            return date.valueOf() < checkin.date.valueOf() ? 'disabled' : '';
          }
        }).on('changeDate', function(ev) {
          checkoutfinish.hide();
        }).data('datepicker');
        
		$('.calendar-startdate, .calendar-enddate').datepicker({
		   format: "yyyy-m-d"
		}); 
		$('.calendar-starttime, .calendar-endtime').timepicker({ 
		   minuteStep: 5
		});
		//SMS2_REMIND
		$("#SMS_REMIND").change(function (){
          var ischecked = $(this).prop('checked');
          if (ischecked) 
		  {
             $("#remind_time").show();
          } 
		  else 
		  {
             $("#remind_time").hide();
          }
        })
		$("#SMS2_REMIND").change(function (){
          var ischecked = $(this).prop('checked');
          if (ischecked) 
		  {
             $("#remind_time").show();
          } 
		  else 
		  {
             $("#remind_time").hide();
          }
        })
		$("#color").click(function(){
          $("#color_menu").slideToggle();
        });
        
    	$("a[id^=CalColor]").each(function(i){
    	    $(this).click(function(){
    	        $("#color").css({"background-color":$(this).css('background-color')});
    	        $("#COLOR_FIELD").val($(this).attr("index"));
                $("#color_menu").hide();
    	    })
    	})
    	
    	var show_color = $("#COLOR_FIELD").val();
    	if(show_color != '0')
    	{
    	    $("#color").css({"background-color":$('.CalColor'+show_color).css('background-color')});
    	}
	});
})(jQuery);
</script>
<style>
.tasktitle{
	height:auto;overflow:hidden; text-overflow:ellipsis; white-space:nowrap;
}
</style>
<?
$CUR_DATE=date("Y-m-d",time());
$CUR_TIME=date("H:i:s",time());

if($TASK_ID!="")
{
  $query="select * from TASK where TASK_ID='$TASK_ID'";
  $cursor= exequery(TD::conn(),$query,$QUERY_MASTER);
  if($ROW=mysql_fetch_array($cursor))
  {
    $TASK_ID=$ROW["TASK_ID"];
    $TASK_NO=$ROW["TASK_NO"];
    $USER_ID=$ROW["USER_ID"];
    $MANAGER_ID=$ROW["MANAGER_ID"];
    
    if($MANAGER_ID!=$_SESSION["LOGIN_USER_ID"]&&$USER_ID!=$_SESSION["LOGIN_USER_ID"] && $_SESSION["LOGIN_USER_PRIV"]!=1)
       exit;

    $BEGIN_DATE=$ROW["BEGIN_DATE"];
    $END_DATE=$ROW["END_DATE"];

    if($BEGIN_DATE=="0000-00-00")
       $BEGIN_DATE="";

    if($END_DATE=="0000-00-00")
       $END_DATE="";

    $TASK_TYPE=$ROW["TASK_TYPE"];
    $TASK_STATUS=$ROW["TASK_STATUS"];
    $COLOR=$ROW["COLOR"];
    $IMPORTANT=$ROW["IMPORTANT"];
    $RATE=$ROW["RATE"];
    $FINISH_TIME=$ROW["FINISH_TIME"];
    $TOTAL_TIME=$ROW["TOTAL_TIME"];
    $USE_TIME=$ROW["USE_TIME"];
    $ADD_TIME=$ROW["ADD_TIME"];
    //....批量设置的任务
      if($ADD_TIME!="0000-00-00 00:00:00")
      {
         $querys="select TASK_ID,USER_ID from TASK where ADD_TIME='$ADD_TIME' and MANAGER_ID='$MANAGER_ID'";
         $cursors=exequery(TD::conn(),$querys,$QUERY_MASTER);
  
         $TASK_ID_STR="";
         $USER_ID_STR="";
         while($ROWS=mysql_fetch_array($cursors))
         {
      	    $TASK_IDS=$ROWS["TASK_ID"];
            $USER_IDS=$ROWS["USER_ID"];	
      	
            $TASK_ID_STR.=$TASK_IDS.",";
            $USER_ID_STR.=$USER_IDS.",";	
         }
        $USER_NAME_STR=GetUserNameById($USER_ID_STR);  
      }
      else
     {
      	
      if(substr($USER_ID,-1)!=",")
        $USER_ID.=",";
      $USER_NAME_STR=GetUserNameById($USER_ID);
      $TASK_ID_STR=$TASK_ID.",";
     }
   //............

    $SUBJECT=$ROW["SUBJECT"];
    $SUBJECT=td_htmlspecialchars($SUBJECT);

    $CONTENT=$ROW["CONTENT"];
  }
}
else
{
 if(strlen($CAL_TIME)==8)
    $CAL_TIME=strtotime($CAL_TIME);
 if($CAL_TIME=='' || $CAL_TIME=="undefined")
    $CAL_TIME=date("Y-m-d",time());
 else
    $CAL_TIME=date("Y-m-d",$CAL_TIME);
 $BEGIN_DATE=$CAL_TIME;
 
 $END_DATE="";
}

if($FINISH_TIME=="0000-00-00 00:00:00" || $FINISH_TIME == "")
{
   $FINISH_TIME_NEW = "";
   $FINISH_TIME_TIME = "";
}
else
{
    $FINISH_TIME_NEW = date("Y-m-d",strtotime($FINISH_TIME));
    $FINISH_TIME_TIME = date("h:i:s A",time());
}

if(substr($USER_ID,-1)!=",")
   $USER_ID.=",";
$USER_NAME=GetUserNameById($USER_ID);  
if(substr($USER_NAME,-1)==",")
   $USER_NAME=substr($USER_NAME,0,-1);   
   
if($COLOR == '')
{
    $COLOR = 0;
}

$s_show_name_str = "";
if($TASK_ID!="")
{
   $s_show_name_str = trim($USER_NAME_STR,",");
}
else
{
   $s_show_name_str = $USER_NAME;
}
?>

<body class="bodycolor" topmargin="5" onLoad="document.form1.SUBJECT.focus();">
<div class="head tasktitle">
   <span class="big3" style="padding-left:30px;" title="<?=$s_show_name_str?>"> <?if($TASK_ID=="")echo _("新建任务");else echo _("编辑任务-");?><? if($TASK_ID!="")
   echo trim($USER_NAME_STR,",");else echo $USER_NAME;?></span>
</div>

 <div class="head_top">
  <form id="form1" action="<?if($TASK_ID=="")echo "task_insert";else echo"task_update";?>.php"  method="post" name="form1" class="form-horizontal" onSubmit="return CheckForm();">
    <div class="control-group">
      <label class="control-label" for="TASK_NO"><?=_("排序号：")?></label>
      <div class="controls">
        <input type="text" name="TASK_NO" id="TASK_NO" class="input-small validate[custom[onlyNumberSp]]" data-prompt-position="centerRight:0,-3" value="<?=$TASK_NO?>"><font><?=_("(排序号应为数字型)")?></font>
      </div>
    </div>
    <div class="control-group">
      <label class="control-label" for="SUBJECT"><?=_("任务标题：")?><font color=red><?=_("(*)")?></font></label>
      <div class="controls">
        <input type="text" name="SUBJECT" id="SUBJECT" class="validate[required]" data-prompt-position="centerRight:0,-3" value="<?=$SUBJECT?>" style="width:243px;outline:none"> 
      </div>
    </div>
    
    <div class="control-group">
      <label class="control-label" for="SUBJECT"><?=_("选择颜色：")?></label>
      <div class="controls">
        <div id="color-group" style="position:relative; z-index:90">
        <a id="color" hidefocus="true"><?=menu_arrow("DOWN")?></a>
        <div id="color_menu">
           <a id="CalColor" href="javascript:;" class="CalColor" index="0"></a>
           <a id="CalColor1" href="javascript:;" class="CalColor1" index="1"></a>
           <a id="CalColor2" href="javascript:;" class="CalColor2" index="2"></a>
           <a id="CalColor3" href="javascript:;" class="CalColor3" index="3"></a>
           <a id="CalColor4" href="javascript:;" class="CalColor4" index="4"></a>
           <a id="CalColor5" href="javascript:;" class="CalColor5" index="5"></a>
           <a id="CalColor6" href="javascript:;" class="CalColor6" index="6"></a>
           <a id="CalColor7" href="javascript:;" class="CalColor7" index="7"></a>
        </div>
        <input type="hidden" id="COLOR_FIELD" name="COLOR" value="<?=$COLOR?>">
        </div>
      </div>
    </div>
     
    <div class="control-group" style="clear:both">
      <label class="control-label"><?=_("起止日期：")?></label>
      <div class="controls">
        <input class="input input-small calendar-startdate valtype validate[required]" data-prompt-position="topRight:-70,-8" id="BEGIN_DATE" name="BEGIN_DATE" value="<?=$BEGIN_DATE?>" placeholder="开始日期" type="text" data-valtype="placeholder" style="outline:none">
        <?=_("至")?>
        <input class="input input-small calendar-startdate valtype validate[required]"  data-prompt-position="centerRight:0,-3" id="END_DATE" name="END_DATE" value="<?=$END_DATE?>" placeholder="结束日期" style="margin-left: 22px;outline:none" type="text" data-valtype="placeholder">
       
      </div>
    </div>
    <div class="control-group">
      <label class="control-label" for="TASK_TYPE" style="float:left;"><?=_("类型：")?></label>
      <div class="controls" style="float:left; margin-left:20px;">
        <select name="TASK_TYPE" style="width:104px;">
          <option value="1" <?if($TASK_TYPE=="1") echo "selected";?>><?=_("工作")?></option>
          <option value="2" <?if($TASK_TYPE=="2") echo "selected";?>><?=_("个人")?></option>
        </select>
      </div>
      <label class="control-label" for="TASK_STATUS" style="float:left;width:46px;"><?=_("状态：")?></label>
      <div class="controls" style="float:left; margin-left:3px;">
        <select name="TASK_STATUS" style="width:104px;">
          <option value="1" <?if($TASK_STATUS=="1") echo "selected";?>><?=_("未开始")?></option>
          <option value="2" <?if($TASK_STATUS=="2") echo "selected";?>><?=_("进行中")?></option>
          <option value="3" <?if($TASK_STATUS=="3") echo "selected";?>><?=_("已完成")?></option>
          <option value="4" <?if($TASK_STATUS=="4") echo "selected";?>><?=_("等待其他人")?></option>
          <option value="5" <?if($TASK_STATUS=="5") echo "selected";?>><?=_("已推迟")?></option>
        </select>
      </div>
    </div>
    <div class="control-group">
      <label class="control-label"><?=_("优先级：")?></label>
      <div class="controls">
        <select name="IMPORTANT">
          <option value=""><?=_("未指定")?></option>
		  <option value="1" <? if($IMPORTANT==1) echo "selected";?>><?=_("重要/紧急")?></option>
		  <option value="2" <? if($IMPORTANT==2) echo "selected";?>><?=_("重要/不紧急")?></option>
		  <option value="3" <? if($IMPORTANT==3) echo "selected";?>><?=_("不重要/紧急")?></option>
		  <option value="4" <? if($IMPORTANT==4) echo "selected";?>><?=_("不重要/不紧急")?></option>
		</select>
      </div>
    </div>
    <div class="control-group">
      <label class="control-label" for="CONTENT"><?=_("任务详细：")?></label>
      <div class="controls">
        <textarea name="CONTENT" style="width:243px;outline:none"><?=$CONTENT?></textarea>
      </div>
    </div>
    
    <div class="control-group" style="float:left;">
        <label class="control-label"><?=_("完成率：")?></label> 
        <div class="controls">
        <input type="text" class="input-small validate[custom[number],max[100]]" data-prompt-position="centerRight:10,-3" name="RATE"  id="RATE" value="<?=$RATE?>" style="outline:none"><span class="help-inline">%</span>
        </div>
    </div>
    <div class="control-group" style="clear:both">
        <label class="control-label"><?=_("完成时间：")?></label>
        <div class="controls">
        <input class="input input-small calendar-startdate valtype" name="FINISH_TIME" id="FINISH_TIME" placeholder="完成日期" value="<?=$FINISH_TIME_NEW?>" type="text" data-valtype="placeholder" style="outline:none">
        <span class="bootstrap-timepicker">
        <input class="input input-mini calendar-starttime valtype" name="FINISH_TIME_TIME" placeholder="完成时间" value="<?=$FINISH_TIME_TIME?>" type="text" data-valtype="placeholder" style="outline:none"> 
        </span>
        </div>
    </div>
    <div class="control-group" style="width:325px; float:left;">
        <label class="control-label" for="TOTAL_TIME"><?=_("工作总量：")?></label>
        <div class="controls">
          <input type="text" class="input-small validate[custom[nonNegative]]" data-prompt-position="centerRight:0,-3" name="TOTAL_TIME" id="TOTAL_TIME" value="<?=$TOTAL_TIME?>" style="outline:none">
          <span class="help-inline"><?=_("小时")?></span>
        </div>
    </div>
    <div class="control-group" style="width:325px;float:left;">
        <label class="control-label" for="TOTAL_TIME" style="width:85px;outline:none"><?=_("实际工作：")?></label>
        <div class="controls" style="margin-left:0px;">
          <input type="text" class="input-small validate[custom[nonNegative]]" data-prompt-position="centerRight:0,-3" style="margin-left:20px;outline:none" name="USE_TIME" id="USE_TIME" value="<?=$USE_TIME?>">
          <span class="help-inline"><?=_("小时")?></span>
        </div> 
    </div>
    <div class="control-group form-inline" style="clear:both;line-height:30px;">
      <label class="control-label"><?=_("事务提醒：")?></label>
      <div class="controls">
<?=sms_remind(5);?>
      </div>
    </div>
    <div class="control-group" style="display:" id="remind_time">
      <label class="control-label"><?=_("提醒时间：")?></label>
      <div class="controls">
        <input class="input input-small calendar-startdate valtype" name="REMIND_TIME" value="<?=$REMIND_TIME?>" placeholder="提醒日期" id="REMIND_TIME" type="text" data-valtype="placeholder" style="outline:none">
        <span class="bootstrap-timepicker">
        <input class="input input-mini calendar-starttime valtype" name="REMIND_TIME_TIME" value="<?=$REMIND_TIME_TIME?>" placeholder="提醒时间" type="text" data-valtype="placeholder" style="outline:none"> 
        </span>
      </div>
    </div>
    <div class="controls buttonGroupBottom">
         <INPUT type="hidden" name="USER_ID" value="<?=$USER_ID?>">
      	<INPUT type="hidden" name="PAGE_START" value="<?=$PAGE_START?>">
      	<INPUT type="hidden" name="TASK_ID_STR" value="<?=$TASK_ID_STR?>">
      	<INPUT type="hidden" name="TASK_ID" value="<?=$TASK_ID?>">
      	<INPUT type="hidden" name="FROM" value="<?=$FROM?>">
        <button type="submit" class="btn btn-primary"><?=_("确定")?></button>
        <button type="reset" class="btn"><?=_("重填")?></button>
        <button type="button" onClick="location='index.php'" class="btn"><?=_("返回")?></button>
    </div>

</form>
</div>
</body>
</html>