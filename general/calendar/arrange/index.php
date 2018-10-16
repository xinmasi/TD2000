<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_cache.php");

$HTML_PAGE_TITLE = _("日程安排");
include_once("inc/header.inc.php");
$cur_time = date("Y-n-j",time());
$year = date("Y",time());
$week = intval(date("W",time()));
$month = date("m",time());
$day = date("d",time());
$block = floor($week/16);
if($week==1 && $month==12)
{
    $year = $year + 1;
}
$query = "SELECT USER_PARA FROM user WHERE UID='".$_SESSION['LOGIN_UID']."'";
$cursor = exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
    $USER_PARA = $ROW["USER_PARA"];
    $USER_PARAS = unserialize($USER_PARA);
    if($USER_PARAS["cal_starttime"]=="")
    {
        $START_TIME = 8;
    }
    else
    {
      $START_TIME = $USER_PARAS["cal_starttime"] ;
    }
    //$START_TIME = $USER_PARAS["cal_starttime"] ? $USER_PARAS["cal_starttime"] : 8;
    $END_TIME = $USER_PARAS["cal_endtime"] ? $USER_PARAS["cal_endtime"] : 18;

    if($START_TIME==$END_TIME  && $END_TIME<24)
    {
        $END_TIME++;
    }
    else if($START_TIME==$END_TIME)
    {
        $START_TIME--;
    }
}
?>
<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/theme/<?=$_SESSION["LOGIN_THEME"]?>/calendar.css">
<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/theme/<?=$_SESSION["LOGIN_THEME"]?>/dialog.css">
<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/modules/calendar/css/calendar_person.css">
<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/js/bootstrap/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/js/bootstrap/datepicker/bootstrap.datepicker.css">
<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/js/bootstrap/timepicker/bootstrap.timepicker.css">
<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/js/fullcalendar/fullcalendar.css">
<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/js/fullcalendar/fullcalendar.print.css" media='print'>
<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/js/jquery-1.10.2/jquery-ui/css/flick/jquery-ui-1.10.3.custom.min.css">
<style>
#current_status{
    position:relative;
    z-index:55;
    background:#fff;
}
#status_menu{
    min-width:78px;
    top:19px;
    right:0px;
    text-align:right;
}
.attach_div .icon-dropdown-checkbox-checked{
    background:url('/static/images/dropdown_menu_checked.png') no-repeat 0 0;
}
.attach_div {
    border-color:#ccc;
}
.attach_div a{
    color:#5F5F5F;
    padding-right:14px;
}
.attach_div a:hover{
    background:#83C1DE;
    text-decoration:none;
}
.fc-header-title{
    position:relative;
    *+float:left;
}
.fc-header-title h2{
    padding: 0px 6px;
    border-radius:4px;
}
.fc-header-title input[type="button"]{
    position:absolute;
    top:0px;
    left:0px;
    background:transparent;
    height:40px;
    box-shadow:none;
    outline:none;
    cursor:pointer;
    border:none;
    opacity:0;
    filter:alpha(opacity=0):
    filter:progid:DXImageTransform.Microsoft.Alpha(Opacity=0);
}
.in input[type="button"],
.in input[type="button"]:hover,
.in input[type="button"]:focus{
    display:none;
}
</style>
<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js<?=$GZIP_POSTFIX?>"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/bootstrap/js/bootstrap.min.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/fullcalendar/fullcalendar.min.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery-ui/js/jquery-ui-1.10.3.custom.min.js<?=$GZIP_POSTFIX?>"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/bootstrap/datepicker/bootstrap.datepicker.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/bootstrap/timepicker/bootstrap.timepicker.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/modules/calendar/js/calendar_person.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script type="text/javascript">
if(window.external && typeof window.external.OA_SMS != 'undefined')
{
    var h = Math.min(800, screen.availHeight - 180),
        w = Math.min(1280, screen.availWidth - 180);
    window.external.OA_SMS(w, h, "SET_SIZE");
}
</script>
<script>
$(document).ready(function(){
	$("#current_status").mouseover(function(){
		$("#status_menu").css("display","block");
		$(this).css("border-bottom","none");
    });
	$("#current_status").mouseout(function(){
		$(this).css("border-bottom","1px solid #ccc");
    });
	$("#status_menu a").mouseover(function(){
		$("#current_status").css("border-bottom","none");
    });
	$("#status_menu a").mouseout(function(){
		$("#current_status").css("border-bottom","1px solid #ccc");
    });
   $("#slider-range").slider({
      range: true,
      min: 0,
      max: 24,
      values: [<?=$START_TIME?>,<?=$END_TIME?>],
      slide: function(event,ui){
        $("#timebegin").text(ui.values[0]+":00");
        $("#timeend").text(ui.values[1]+":00");
      }
  });
  $("#timebegin").text($("#slider-range").slider("values",0)+":00");
  $("#timeend").text($("#slider-range").slider("values",1)+":00");
  (function(){
      for(var i=0;i<25;i+=4){
          var dt=$('<dt id="sliderdt'+i+'" style="float:left"></dt>');
          dt.html(i);
          $("#slider-scale").append(dt);
      }
  })();
  $("#savesetup").click(function(){
    var starttime = $("#slider-range").slider("values",0);
    var endtime = $("#slider-range").slider("values",1);
    $.get('op_calendar.php',{op:'setup',starttime:starttime,endtime:endtime},function(data){
        if(data.status == "success")
        {
            calendar.options.minTime=starttime;
            calendar.options.maxTime=endtime;
            $("#cal_starttime").val(starttime);
            $("#cal_endtime").val(endtime);
            curview=calendar.getView();
            if(curview.name=="agendaWeek")
            {
                calendar.changeView("agendaDay");
                calendar.changeView("agendaWeek");
            }
            else if(curview.name=="agendaDay")
            {
                calendar.changeView("agendaWeek");
                calendar.changeView("agendaDay");
            }
        }
        $("#setup_panel").modal('hide');
    });
  });
});
</script>
<script>
    function setTimeval(){
        $("#startInput1").show();
         $("#endInput1").show();
    }
</script>
<body style="background:#fff">
<input type="hidden" id="cal_starttime" value="<?=$START_TIME ?>">
<input type="hidden" id="cal_endtime" value="<?=$END_TIME ?>" >
<input type="hidden" id="cur_year" value="<?=$year ?>">
<input type="hidden" id="cur_month" value="<?=$month ?>">
<input type="hidden" id="cur_day" value="<?=$day ?>">
<input type="hidden" id="cur_block" value="<?=$block ?>">
<input type="hidden" id="cur_week" value="<?=$week ?>">
<div id='calendar'>
    <div id="myModal" class="modal hide" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="top: 50%;margin-top: -213px;">
        <div class="modal-header" style="*+padding-top:0px;">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h3><?=_("新建日程")?></h3>
        </div>
        <div class="modal-body" id="new_modal" style="max-height:300px;">
            <form class="form-horizontal">
                <div class="control-group">
                    <label><?=_("事务内容:")?>
                        <textarea  name="CAL_CONTENT" id="CAL_CONTENT" placeholder="<?=_("内容")?>" class="input input-xl"></textarea><font color="red">(<?=_("必填")?>)</font>
                    </label>
                </div>
                <div class="control-group">
                    <label style="width:200px; float:left"><?=_("事务类型:")?>
                        <select class="smallSelect" name="CAL_TYPE" id="CAL_TYPE">
                            <?=code_list("CAL_TYPE","")?>
                        </select>
                    </label>
                    <div class="color_wrapper">
                      <a id="color" class="color" hidefocus="true" index="0"></a>
                      <div id="color_menu" class="color_menu" style="left:28px;top:5px;">
                            <a id="calcolor" href="javascript:;" class="color" index="0" style="margin-top:0px;"></a>
                            <a id="calcolor1" href="javascript:;" class="color1" index="1"></a>
                            <a id="calcolor2" href="javascript:;" class="color2" index="2"></a>
                            <a id="calcolor3" href="javascript:;" class="color3" index="3"></a>
                            <a id="calcolor4" href="javascript:;" class="color4" index="4"></a>
                            <a id="calcolor5" href="javascript:;" class="color5" index="5"></a>
                            <a id="calcolor6" href="javascript:;" class="color6" index="6"></a>
                       </div>
                       <input type="hidden" id="COLOR_FIELD" name="COLOR" value="0">
                     </div>
                     </label>
                </div>
                <div class="control-group" style=" clear:both">
                    <span><?=_("开始时间:")?></span>
                    <input name="START_DATE" id="START_DATE" class="timepadding input input-small calendar-startdate valtype" placeholder="<?=_("开始日期")?>" type="text" data-valtype="placeholder" value="<?=$cur_time?>">
                    <span class="bootstrap-timepicker" id="startInput1">
                        <input name="START_TIME" id="START_TIME" class="input input-mini calendar-starttime valtype" placeholder="<?=_("开始时间")?>" type="text" data-valtype="placeholder">
                    </span>
                </div>
                <div class="control-group" id="endtimeArea1">
                    <span><?=_("结束时间:")?></span>
                        <input name="EDN_DATE" id="EDN_DATE" class="timepadding input input-small calendar-enddate valtype" placeholder="<?=_("结束日期")?>" type="text" data-valtype="placeholder">
                    <span class="bootstrap-timepicker" id="endInput1">
                        <input name="END_TIME" id="END_TIME" class="input input-mini calendar-endtime valtype" placeholder="<?=_("结束时间")?>" type="text" data-valtype="placeholder">
                    </span>
                </div>
                <div class="control-group">
                    <label class="checkbox inline" id="alldaylabel" style="width:28px">
                        <input name="ALLDAY" type="checkbox" value="option1" id="allDayCheckbox1"><?=_("全天")?>
                    </label>
                    <label class="checkbox inline" style="width: 55px">
                        <input type="checkbox" value="option2" id="endCheckbox1"> <?=_("结束时间")?>
                    </label>
                    <label class="checkbox inline" style="width:28px">
                        <input name="REPEAT" type="checkbox" value="option3" id="repeatCheckbox1"> <?=_("重复")?>
                    </label>
                </div>
                <div id="repeatType1" style="display: none">
                    <div class="control-group">
                        <span><?=_("重复类型:")?></span>
                        <select style="width:153px;" id="TYPE">
                            <option value="2"><?=_("按天重复")?></option>
                            <option value="3"><?=_("按周重复")?></option>
                            <option value="4"><?=_("按月重复")?></option>
                            <option value="5"><?=_("按年重复")?></option>
                            <option value="6"><?=_("按工作日重复")?></option>
                        </select>
                    </div>
                    <div class="control-group dropup" id="repeat2">
                        <span><?=_("重复时间:")?></span>
                        <span class="bootstrap-timepicker">
                            <input id="REMIND_TIME2" class="input input-mini calendar-endtime valtype" placeholder="" type="text" data-valtype="placeholder">
                        </span>
                    </div>
                    <div class="control-group repeatTime dropup" id="repeat3">
                        <span><?=_("重复时间:")?></span>
                        <select class="smallSelect" id="REMIND_DATE3">
                            <option value="1" <?if(date("w",time())==1) echo "selected";?>><?=_("星期一")?></option>
                            <option value="2" <?if(date("w",time())==2) echo "selected";?>><?=_("星期二")?></option>
                            <option value="3" <?if(date("w",time())==3) echo "selected";?>><?=_("星期三")?></option>
                            <option value="4" <?if(date("w",time())==4) echo "selected";?>><?=_("星期四")?></option>
                            <option value="5" <?if(date("w",time())==5) echo "selected";?>><?=_("星期五")?></option>
                            <option value="6" <?if(date("w",time())==6) echo "selected";?>><?=_("星期六")?></option>
                            <option value="0" <?if(date("w",time())==0) echo "selected";?>><?=_("星期日")?></option>
                        </select>
                        <span class="bootstrap-timepicker">
                            <input id="REMIND_TIME3" class="input input-mini calendar-endtime valtype" placeholder="" type="text" data-valtype="placeholder">
                        </span>
                    </div>
                    <div class="control-group repeatTime dropup" id="repeat4">
                        <span><?=_("重复时间:")?></span>
                        <select class="smallSelect" id="REMIND_DATE4">
                        <?
                            for($I=1;$I<=31;$I++)
                            {
                        ?>
                                <option value="<?=$I?>" <?if(date("j",time())==$I) echo "selected";?>><?=$I?><?=_("日")?></option>
                        <?
                            }
                        ?>
                        </select>
                        <span class="bootstrap-timepicker">
                            <input id="REMIND_TIME4" class="input input-mini calendar-endtime valtype" placeholder="" type="text" data-valtype="placeholder">
                        </span>
                    </div>
                    <div class="control-group repeatTime dropup" id="repeat5">
                        <span><?=_("重复时间:")?></span>
                        <select class="smallSelect" id="REMIND_DATE5_MON">
                        <?
                            for($I=1;$I<=12;$I++)
                            {
                        ?>
                                <option value="<?=$I?>" <?if(date("n",time())==$I) echo "selected";?>><?=$I?><?=_("月")?></option>
                        <?
                            }
                        ?>
                        </select>
                        <select class="smallSelect" id="REMIND_DATE5_DAY">
                        <?
                            for($I=1;$I<=31;$I++)
                            {
                        ?>
                                <option value="<?=$I?>" <?if(date("j",time())==$I) echo "selected";?>><?=$I?><?=_("日")?></option>
                        <?
                            }
                        ?>
                        </select>
                        <span class="bootstrap-timepicker">
                            <input id="REMIND_TIME5" class="input input-mini calendar-endtime valtype" placeholder="" type="text" data-valtype="placeholder">
                        </span>
                    </div>
                    <div class="control-group repeatTime dropup " id="repeat6">
                        <span><?=_("重复时间:")?></span>
                        <span class="bootstrap-timepicker">
                            <input id="REMIND_TIME6" class="input input-mini calendar-endtime valtype" placeholder="" type="text" data-valtype="placeholder">
                        </span>
                    </div>
                </div>
                <div class="control-group" >
                    <label class="textareapadding inline"><?=_("参与者:")?>
                        <input type="hidden" name="TAKER" id="TAKER" >
                        <textarea name="TAKER_NAME" id="TAKER_NAME" rows=2 class="BigStatic" wrap="yes" readonly></textarea>
                        <a href="javascript:;" class="orgAdd" onClick="SelectUser('8','','TAKER', 'TAKER_NAME')"><?=_("添加")?></a>
                        <a href="javascript:;" class="orgClear" onClick="ClearUser('TAKER', 'TAKER_NAME')"><?=_("清空")?></a>
                    </label>
                </div>
                <div class="control-group" id="OWNER1">
                    <label class="textareapadding inline"><?=_("所属者:")?>
                        <input type="hidden" name="OWNER" id="OWNER">
                        <textarea cols=35 name="OWNER_NAME" id="OWNER_NAME" rows=2 class="BigStatic" wrap="yes" readonly></textarea>
                        <a href="javascript:;" class="orgAdd" onClick="SelectUser('8','','OWNER','OWNER_NAME')"><?=_("添加")?></a>
                        <a href="javascript:;" class="orgClear" onClick="ClearUser('OWNER', 'OWNER_NAME')"><?=_("清空")?></a>
                    </label>
                </div>
                <div id="remindTime">
                    <div class="control-group">
                        <span><?=_("提醒时间:")?></span>
                         <?=sprintf(_("提前%s天%s小时%s分钟提醒"),'<input type="text" name="BEFORE_DAY" id="BEFORE_DAY" size="3" class="input input-small" style="width:30px" value=""> ','<input type="text" name="BEFORE_HOUR" id="BEFORE_HOUR" size="3" class="input input-small" style="width:30px" value=""> ','<input type="text" name="BEFORE_MIN" id="BEFORE_MIN" size="3" class="input input-small" style="width:30px" value="10">')?>
                   </div>
               </div>
                <div class="control-group" id="smsDiv">
                    <label class="checkbox inline remindText"><?=_("提醒:")?>
                    </label>
                    <?=sms_remind(5);?>
                </div>
                <input type="hidden" name="CAL_ID" id="CAL_ID">
                <input type="hidden" name="TYPE_ID" id="TYPE_ID">
                <input type="hidden" name="GET_REPEAT" id="GET_REPEAT">
                <input type="hidden" id="CALSTATUS" value="">
            </form>
        </div>
        <div class="modal-footer" style="padding-top:8px; padding-bottom:8px;">
            <button class="btn btn-primary" id="save" onclick="setTimeval()"><?=_("确定")?></button>
            <button class="btn" data-dismiss="modal" aria-hidden="true"><?=_("关闭")?></button>
        </div>
    </div>
</div>
 <div id="myModal" class="modal hide" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="top: 50%;margin-top: -213px;">
    <div class="modal-header" style="*+padding-top:0px;">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h3><?=_("新建日程")?></h3>
    </div>
    <div class="modal-body" id="task_modal" style="max-height:300px;">
        <iframe src="about:blank" class="task-iframe" border=0></iframe>
    </div>
    <div class="modal-footer" style="padding-top:8px; padding-bottom:8px;">
        <button class="btn btn-primary" id="save"><?=_("确定")?></button>
        <button class="btn" data-dismiss="modal" aria-hidden="true"><?=_("关闭")?></button>
    </div>
</div>
<div id='calendar-quick-setup' class="popover  right ">
    <div class="arrow"></div>
    <div class="popover-content">

        <form class="form">
            <div class="control-group">
                <textarea id="quick-calendar-title" placeholder="内容" class="input" style="width:280px;"></textarea>
            </div>
            <div class="control-group">
                <span id="quick-begin-time"></span>
                <span style="margin:0 2px;" id="quick-name"><?=_("至")?></span>
                <span id="quick-finish-time"></span>
            </div>
        </form>
        <div class="row-fluid">
            <div class="span3">
                 <button class="btn" data-cmd="editmore" type="button"><?=_("完整编辑")?></button>
            </div>
            <div class="span9">
                <div class="pull-right">
                    <button class="btn btn-primary"  data-cmd="ok" type="button"><?=_("确认")?></button>
                    <button class="btn" data-cmd="cancel" type="button"><?=_("取消")?></button>
                </div>
            </div>
        </div>
    </div>
</div>
<div id='calendar-quick-detail' class="popover  right ">
    <div class="arrow"></div>
    <div class="popover-content">

       <form class="form">
            <div class="control-group" style="width:208px;">
                <h5 style="word-break: break-all;"></h5>
            </div>

            <div class="control-group" >
                <span id="BEGIN_TIME">2013-08-23 18:45</span>
                <span id="TO_SPAN" style="margin:0 2px;"><?=_("至")?></span>
                <span id="FINISH_TIME">2013-08-23 18:45</span>
                <span id="state"></span> <!-- sxm 2014-12-16 新增状态 -->
            </div>
            <div class="cal_control">
            </div>
        </form>
        <div class="row-fluid">
            <div class="span3">
                 <button class="btn btn-danger" data-cmd="delete" type="button" id="delete"><?=_("删除")?></button>
            </div>
            <div class="span9">
                <div class="pull-right">
                    <button class="btn btn-primary" data-cmd="detail" type="button" id="detail"><?=_("详情")?></button>
                    <button class="btn btn-primary" data-cmd="edit" type="button" id="edit"><?=_("编辑")?></button>
                    <button class="btn" data-cmd="cancel" type="button"><?=_("取消")?></button>
                </div>
            </div>
        </div>
        <div class="btn-group" id="statuslist">
            <span id="current_status"><span id="status"></span><span class="caret"></span></span>
            <div id="status_menu" class="attach_div small">
              <a href="javascript:;" id="no-finished" ><i class=""></i><?=_("未完成")?></a>
              <a href="javascript:;" id="finished" ><i class="icon-dropdown-checkbox"></i><?=_("已完成")?></a>
            </div>
            <input type="hidden" id="edit-id" value="">
        </div>

    </div>
</div>
<div id="setup_panel" class="modal hide" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="top: 50%;left:50%;margin-top: -200px;margin-left:-250px;width:550px;">
    <div class="modal-header" style="*+padding-top:0px;">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h3><?=_("设置时间段")?></h3>
    </div>
    <div class="modal-body" style="min-height:100px;">
        <div class="control-group">
            <div id="slider-range"></div>
            <dl id="slider-scale">
            </dl>
			<p>
                <span id="timebegin"></span>
                <?=_("—")?>
                <span id="timeend"></span>
            </p>
        </div>
    </div>
    <div class="modal-footer" style="padding-top:8px; padding-bottom:8px;">
        <button class="btn btn-primary" id="savesetup"><?=_("确定")?></button>
        <button class="btn" data-dismiss="modal" aria-hidden="true"><?=_("关闭")?></button>
    </div>
</div>
<input type="hidden" id="weekhidden">
</body>
<script src="<?=MYOA_JS_SERVER?>/static/js//jquery-1.10.2/template/jquery.tmpl.min.js<?=$GZIP_POSTFIX?>"></script>
</html>
