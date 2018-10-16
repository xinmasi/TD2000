<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
if($CAL_ID=="")
   $WIN_TITLE=_("新建日常事务");
else
   $WIN_TITLE=_("编辑日常事务");
//2013-04-11 主从服务器查询判断
if($IS_MAIN==1)
   $QUERY_MASTER=true;
else
   $QUERY_MASTER="";

$HTML_PAGE_TITLE = $WIN_TITLE;
include_once("inc/header.inc.php");
?>

<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/theme/<?=$_SESSION["LOGIN_THEME"]?>/calendar.css">
<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/js/bootstrap/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/js/bootstrap/datepicker/bootstrap.datepicker.css">
<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/js/bootstrap/timepicker/bootstrap.timepicker.css">
<style>
.textareaBig{
	height:63px; line-height:21px; padding:4px 4px;outline:none;
}
</style>
<script type="text/javascript" src="/inc/js_lang.php"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/calendar.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/bootstrap/datepicker/bootstrap.datepicker.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/bootstrap/timepicker/bootstrap.timepicker.js"></script>
<script Language="JavaScript">
function CheckForm()
{
   if(document.form1.USER_ID.value=="")
   { alert("<?=_("请选择人员！")?>");
     return (false);
   }
   if(document.form1.CONTENT.value=="")
   { alert("<?=_("事务内容不能为空！")?>");
   	 document.form1.CONTENT.focus();
     return (false);
   }
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
		
		/*
		$("#all_day_check").change(function (){
          var ischecked = $(this).prop('checked');
          if (ischecked) 
		  {
             $("#start_time_input").hide();
             $("#end_time_input").hide();
          } 
		  else 
		  {
             $("#start_time_input").show();
             $("#end_time_input").show();
          }
        })
		$("#end_time_check").change(function (){
          var ischecked = $(this).prop('checked');
          if (ischecked) 
		  {
             $("#end_time_area").show();
          }
		  else 
		  {
             $("#end_time_area").hide();
          }
        })*/
        
		$("#color").click(function(){
          $("#color_menu").slideToggle();
        });
        /*
        $("#color_menu").mouseout(function (){
            $("#color_menu").hide();
        })*/
        
    	$("a[id^=CalColor]").each(function(i){
    	    $(this).click(function(){
    	        $("#color").css({"background-color":$(this).css('background-color')});
    	        $("#CAL_LEVEL_FIELD").val($(this).attr("index"));
                $("#color_menu").hide();
    	    })
    	})
    	
    	var show_color = $("#CAL_LEVEL_FIELD").val();
    	if(show_color != '0')
    	{
    	    $("#color").css({"background-color":$('.CalColor'+show_color).css('background-color')});
    	}
	});
})(jQuery);
function resets()
{
	document.getElementById("CONTENT").value = '';
}
</script>

<?
if($CAL_ID!="")
{
   $query = "SELECT * from CALENDAR where CAL_ID='$CAL_ID'";
   $cursor= exequery(TD::conn(),$query,$QUERY_MASTER);
   if($ROW=mysql_fetch_array($cursor))
   {
      $CAL_TIME=$ROW["CAL_TIME"];
      $CAL_TIME=date("Y-m-d H:i:s",$CAL_TIME);
      $END_TIME=$ROW["END_TIME"];
      $END_TIME=date("Y-m-d H:i:s",$END_TIME);
      $CAL_TYPE=$ROW["CAL_TYPE"];
      $CAL_LEVEL=$ROW["CAL_LEVEL"];
      $CONTENT=$ROW["CONTENT"];
      $USER_ID=$ROW["USER_ID"];
      $ADD_TIME=$ROW["ADD_TIME"];
      $MANAGER_ID=$ROW["MANAGER_ID"];
       if($ADD_TIME!="0000-00-00 00:00:00")
      {
         $querys="select CAL_ID,USER_ID from CALENDAR where ADD_TIME='$ADD_TIME' and MANAGER_ID='$MANAGER_ID'";
         $cursors=exequery(TD::conn(),$querys,$QUERY_MASTER);
  
         $CAL_ID_STR="";
         $USER_ID_STR="";
         while($ROWS=mysql_fetch_array($cursors))
         {
      	    $CAL_IDS=$ROWS["CAL_ID"];
            $USER_IDS=$ROWS["USER_ID"];	
      	
            $CAL_ID_STR.=$CAL_IDS.",";
            $USER_ID_STR.=$USER_IDS.",";	
         }
        $USER_NAME_STR=GetUserNameById($USER_ID_STR);  
      }
      else
     {
      	
      if(substr($USER_ID,-1)!=",")
        $USER_ID.=",";
      $USER_ID_STR=$USER_ID;
      $USER_NAME_STR=GetUserNameById($USER_ID);
      $CAL_ID_STR=$CAL_ID.",";
     }
      
   }
}
else
{ 
   if(strlen($CAL_TIME)==8)
      $CAL_TIME=strtotime($CAL_TIME);
    
   if($CAL_TIME=='' || $CAL_TIME=="undefined")
      $CAL_TIME=date("Y-m-d H:i:s",time());
   else
      $CAL_TIME=date("Y-m-d H:i:s",$CAL_TIME);

 if(substr($CAL_TIME,11)=="00:00:00")
      $CAL_TIME=substr($CAL_TIME,0,11).substr(date("Y-m-d H:i:s"),11);
   
   if($TIME_DIFF=='' || $TIME_DIFF=="undefined")
      $TIME_DIFF="+1 hours";
   $END_TIME=date("Y-m-d H:i:s",strtotime($TIME_DIFF,strtotime($CAL_TIME))-1);
   
}
 if(substr($USER_ID,-1)!=",")
   $USER_ID.=",";
	 $USER_NAME.=GetUserNameById($USER_ID);  
	 
if($CAL_LEVEL == '')
{
    $CAL_LEVEL = 0;
}

//新版本时间显示
$CAL_TIME_NEW = date("Y-m-d",strtotime($CAL_TIME));
$CAL_TIME_HOUR = date("h:i:s A",strtotime($CAL_TIME));
$END_TIME_NEW = date("Y-m-d",strtotime($END_TIME));
$END_TIME_HOUR = date("h:i:s A",strtotime($END_TIME));
?>
<body class="bodycolor" onLoad="document.form1.CONTENT.focus();">
<div class="head">
    <span class="big3" style="padding-left:30px;"> <?=$WIN_TITLE?></span>
</div>
<div class="head_top" style="min-height:400px">
  <form action="submit.php"  method="post" name="form1" onSubmit="return CheckForm();" class="form-horizontal">
     <div class="control-group">
      <label class="control-label"><?=_("事务内容：")?></label>
      <div class="controls">
        <textarea name="CONTENT" id="CONTENT"  class="textareaBig"><?=$CONTENT?></textarea><font color=red><?=_("(*)")?></font>
      </div>
     </div>
     
     <div class="control-group">
      <label class="control-label"><?=_("选择颜色：")?></label>
      <div class="controls">
        <div id="color-group" style=" position:relative;z-index:90">
            <a id="color" hidefocus="true"><?=menu_arrow("DOWN")?></a>
            <div id="color_menu" style="">
               <a id="CalColor" href="javascript:;" class="CalColor" index="0"></a>
               <a id="CalColor1" href="javascript:;" class="CalColor1" index="1"></a>
               <a id="CalColor2" href="javascript:;" class="CalColor2" index="2"></a>
               <a id="CalColor3" href="javascript:;" class="CalColor3" index="3"></a>
               <a id="CalColor4" href="javascript:;" class="CalColor4" index="4"></a>
               <a id="CalColor5" href="javascript:;" class="CalColor5" index="5"></a>
               <a id="CalColor6" href="javascript:;" class="CalColor6" index="6"></a>
            </div>
            <input type="hidden" id="CAL_LEVEL_FIELD" name="CAL_LEVEL" value="<?=$CAL_LEVEL?>">
        </div>
      </div>
     </div>
     
    <!--<div class="control-group" style=" clear:both;line-height:30px;">
      <label class="control-label"><?=_("优先级：")?></label>
      <div class="controls">
        <a id="cal_level" style="height:20px;" href="javascript:;" class="CalLevel<?=$CAL_LEVEL?>" onClick="showMenu(this.id,'1');" hidefocus="true"><?=cal_level_desc($CAL_LEVEL)?><?=menu_arrow("DOWN")?></a>&nbsp;
        <div id="cal_level_menu" class="attach_div">
           <a id="cal_level_" href="javascript:set_option('','cal_level','CalLevel');"  class="CalLevel"><?=cal_level_desc("")?></a>
           <a id="cal_level_1" href="javascript:set_option('1','cal_level','CalLevel');" class="CalLevel1"><?=cal_level_desc("1")?></a>
           <a id="cal_level_2" href="javascript:set_option('2','cal_level','CalLevel');" class="CalLevel2"><?=cal_level_desc("2")?></a>
           <a id="cal_level_3" href="javascript:set_option('3','cal_level','CalLevel');" class="CalLevel3"><?=cal_level_desc("3")?></a>
           <a id="cal_level_4" href="javascript:set_option('4','cal_level','CalLevel');" class="CalLevel4"><?=cal_level_desc("4")?></a>
        </div>
        <input type="hidden" id="CAL_LEVEL_FIELD" name="CAL_LEVEL" value="<?=$CAL_LEVEL?>">
      </div>
    </div>-->
    
    <div class="control-group" style=" clear:both;margin-bottom:10px;">
      <label class="control-label"><?=_("起始时间：")?></label>
      <div class="controls">
        <input class="input input-small calendar-startdate valtype" style="outline:none" name="CAL_TIME" value="<?=$CAL_TIME_NEW?>" placeholder="开始日期" type="text" data-valtype="placeholder">
        <span class="bootstrap-timepicker" id="start_time_input">
        <input class="input input-mini calendar-starttime valtype" name="CAL_TIME_HOUR" value="<?=$CAL_TIME_HOUR?>" style="outline:none" placeholder="开始时间" type="text" data-valtype="placeholder"> 
        </span>
      </div>
    </div>
    
    <div class="control-group" style="margin-bottom:10px;" id="end_time_area">
      <label class="control-label"><?=_("结束时间：")?></label>
      <div class="controls">
        <input class="input input-small calendar-startdate valtype" style="outline:none" name="END_TIME" value="<?=$END_TIME_NEW?>" placeholder="结束日期" type="text" data-valtype="placeholder">
        <span class="bootstrap-timepicker" id="end_time_input">
        <input class="input input-mini calendar-starttime valtype" name="END_TIME_HOUR" value="<?=$END_TIME_HOUR?>" style="outline:none" placeholder="结束时间" type="text" data-valtype="placeholder"> 
        </span>
      </div>
    </div>
    <!--
    <div class="control-group">
      <div class="controls form-inline">
        <label class="checkbox" style="vertical-align:bottom"><input type="checkbox" id="all_day_check" name="all_day_check"><?=_("全天")?></label>
        <label class="checkbox" style="vertical-align:bottom;"><input type="checkbox" id="end_time_check" name="end_time_check"><?=_("结束时间")?></label>
      </div>
    </div>-->
    
    <div class="control-group">
      <label class="control-label" style="line-height:40px;"><?=_("人员：")?></label> 
      <div class="controls">
      <input type="hidden" name="USER_ID" value="<?=$USER_ID?>">
<?
if($CAL_ID!="")
{
?>
       <textarea name="USER_NAME" style="outline:none; height:39px; min-height:39px;" readonly rows="3" cols="40"><?=trim($USER_NAME_STR,",")?></textarea>
<?
}
else
{
?>
        <textarea name="USER_NAME" style="outline:none; height:39px; min-height:39px;" readonly rows="3" cols="40"><?=$USER_NAME?></textarea>
        <a href="javascript:;" class="orgAdd" onClick="SelectUser('8','3','USER_ID', 'USER_NAME')"><?=_("添加")?></a>
        <a href="javascript:;" class="orgClear" onClick="ClearUser('USER_ID', 'USER_NAME')"><?=_("清空")?></a>
        <font color=red><?=_("(*)")?></font>
<?
}
?>
       
      </div>
     </div>
    
    <div class="control-group form-inline" style="line-height:30px;">
      <label class="control-label"><?=_("事务提醒：")?></label>
      <div class="controls">
<?=sms_remind(5);?>
      </div>
    </div>
    
    <div class="controls buttonGroupBottom">
        <input type="hidden" name="CAL_ID" value="<?=$CAL_ID?>">
        <input type="hidden" name="CAL_ID_STR" value="<?=$CAL_ID_STR?>">
        <button type="submit" class="btn btn-primary"><?=_("确定")?></button>
        <button type="button" class="btn" onClick="resets()"><?=_("重填")?></button>
        <button type="button" class="btn" onClick="parent.close();"><?=_("关闭")?></button>
    </div>
</form>
</div>
</body>
</html>