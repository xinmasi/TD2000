<?
include_once("inc/auth.inc.php");
include_once("inc/utility_org.php");
include_once("inc/utility_all.php");
//2013-04-11 主从服务器查询判断
if($IS_MAIN==1)
   $QUERY_MASTER=true;
else
   $QUERY_MASTER="";
if($AFF_ID=="")
   $WIN_TITLE=_("新建周期性事务");
else
   $WIN_TITLE=_("编辑周期性事务");

if($AFF_ID!="")
{
   $query = "SELECT * from AFFAIR where AFF_ID='$AFF_ID' ";
   $cursor= exequery(TD::conn(),$query,$QUERY_MASTER);
   if($ROW=mysql_fetch_array($cursor))
   {
      $AFF_ID=$ROW["AFF_ID"]; 
      $USER_ID=$ROW["USER_ID"];   
      $CAL_TIME=$ROW["BEGIN_TIME"];
      $CAL_TIME=date("Y-m-d H:i:s",$CAL_TIME);
      $END_TIME=$ROW["END_TIME"];
      if($END_TIME!=0)
         $END_TIME=date("Y-m-d",$END_TIME);
      $TYPE=$ROW["TYPE"];
      $REMIND_DATE=$ROW["REMIND_DATE"];
      $REMIND_TIME=$ROW["REMIND_TIME"];
      $CONTENT=$ROW["CONTENT"];
      $SMS2_REMIND=$ROW["SMS2_REMIND"];
      $END_TIME=$END_TIME=="0" ? "" : $END_TIME;
      $ADD_TIME=$ROW["ADD_TIME"];
      $MANAGER_ID=$ROW["MANAGER_ID"];
	  $TAKER=$ROW["TAKER"];
	  $TAKER_NAME=GetUserInfoByUID(UserId2Uid($TAKER),"USER_NAME");
	  $BEGIN_TIME_TIME=$ROW["BEGIN_TIME_TIME"];
	  $END_TIME_TIME=$ROW["END_TIME_TIME"];
	  $ALLDAY=$ROW["ALLDAY"];
      if($ADD_TIME!="0000-00-00 00:00:00")
      {
         $querys="select AFF_ID,USER_ID from AFFAIR where ADD_TIME='$ADD_TIME' and MANAGER_ID='$MANAGER_ID'";
         $cursors=exequery(TD::conn(),$querys,$QUERY_MASTER);
  
         $AFF_ID_STR="";
         $USER_ID_STR="";
         while($ROWS=mysql_fetch_array($cursors))
         {
      	    $AFF_IDS=$ROWS["AFF_ID"];
            $USER_IDS=$ROWS["USER_ID"];	
      	
            $AFF_ID_STR.=$AFF_IDS.",";
            $USER_ID_STR.=$USER_IDS.",";	
         }
        $USER_NAME_STR=GetUserNameById($USER_ID_STR);  
      }
      else
     {
      	
      if(substr($USER_ID,-1)!=",")
        $USER_ID.=",";
      $USER_NAME_STR=GetUserNameById($USER_ID);
      $AFF_ID_STR=$AFF_ID.",";
     }
       $END_TIME=$END_TIME=="0" ? "" : $END_TIME;
 if($TYPE=="5")
   {
       $REMIND_ARR=explode("-",$REMIND_DATE);
       $REMIND_DATE_MON=$REMIND_ARR[0];
       $REMIND_DATE_DAY=$REMIND_ARR[1];
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
   
   if($TIME_DIFF=='' || $TIME_DIFF=="undefined")
      $TIME_DIFF="+1 hours";
   $END_TIME=date("Y-m-d H:i:s",strtotime($TIME_DIFF,strtotime($CAL_TIME))-1);
}
 
if(substr($USER_ID,-1)!=",")
   $USER_ID.=",";
$USER_NAME=GetUserNameById($USER_ID);  
$USER_ID_EDIT=trim($USER_ID,",");
$CUR_TIME=date("H:i:s",time());
if($AFF_ID=="")
{
	$BEGIN_TIME_TIME=date("H:i:00",time());
	$END_END_TIME=date("H:i:00",time()+3600);
}

$HTML_PAGE_TITLE = $WIN_TITLE;


function settime($thistime)
{
    $app_array = array();
    $timestamp=strtotime($thistime);
    $H =  date('H',$timestamp);
    if($H-12>=0)
    {
        $str = "PM";
    }else
    {
        $str = "AM";
    }
    $thistime = date('h:i',$timestamp);
    
    
    $app_array[]=array(
        'thistime' => $thistime,
        'thisstr'  => $str,
        
    );
    return  $app_array;
}

//重复时间
$REMIND_ARR = settime($REMIND_TIME);
$REMIND_TIME = $REMIND_ARR[0]['thistime'];
$REMIND_STR  = $REMIND_ARR[0]['thisstr'];

//开始时间
$BEGIN_TIME_ARR = settime($BEGIN_TIME_TIME);
$BEGIN_TIME_TIME = $BEGIN_TIME_ARR[0]['thistime'];
$BEGIN_TIME_STR  = $BEGIN_TIME_ARR[0]['thisstr'];

//结束时间
$END_TIME_ARR = settime($END_TIME_TIME);
$END_TIME_TIME = $END_TIME_ARR[0]['thistime'];
$END_TIME_STR  = $END_TIME_ARR[0]['thisstr'];


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
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/bootstrap/datepicker/bootstrap.datepicker.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/bootstrap/timepicker/bootstrap.timepicker.js"></script>
<script Language="JavaScript">
function CheckForm()
{
	if(document.form1.AFF_ID.value=="")
	{
	   if(document.form1.USER_ID.value=="")
	   {
	   	 alert("<?=_("请选择人员！")?>");
	     return (false);
	   }
	}
   if(document.form1.CONTENT.value=="")
   { alert("<?=_("事务内容不能为空！")?>");
   	document.form1.CONTENT.focus();
     return (false);
   }
   return (true);
}
<?
if($TYPE=="2")
   echo "var aff_type=\"day\";\n";
if($TYPE=="3")
   echo "var aff_type=\"week\";\n";
if($TYPE=="4")
   echo "var aff_type=\"mon\";\n";
if($TYPE=="5")
   echo "var aff_type=\"year\";\n";
if($TYPE=="6")
   echo "var aff_type=\"workday\";\n";
if($TYPE=="")
   echo "var aff_type=\"day\";\n";
?>
function sel_change()
{
	
   if(aff_type!="")
      document.getElementById(aff_type).style.display="none";
   if(form1.TYPE.value=="2")
      aff_type="day";
   if(form1.TYPE.value=="3")
      aff_type="week";
   if(form1.TYPE.value=="4")
      aff_type="mon";
   if(form1.TYPE.value=="5")
      aff_type="year";
   if(form1.TYPE.value=="6")
      aff_type="workday";
   document.getElementById(aff_type).style.display="";
}

function td_clock1(fieldname)
{
  document.form1.REMIND_TIME2.value="";
  document.form1.REMIND_TIME3.value="";
  document.form1.REMIND_TIME4.value="";
  document.form1.REMIND_TIME5.value="";
  myleft=document.body.scrollLeft+event.clientX-event.offsetX-80;
  mytop=document.body.scrollTop+event.clientY-event.offsetY+140;

  window.showModalDialog("/inc/clock.php?FIELDNAME="+fieldname,self,"edge:raised;scroll:0;status:0;help:0;resizable:1;dialogWidth:280px;dialogHeight:120px;dialogTop:"+mytop+"px;dialogLeft:"+myleft+"px");
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
		   format: "yyyy-m-d"
		}); 
		$('.calendar-starttime, .calendar-endtime').timepicker({ 
		   minuteStep: 5,
		   //format: 'hh:ii:ss P'
		});
          if ($("#all_day_check").prop('checked'))
		  {
             $("#start_time_area").hide();
			 $("#end_time_area").hide();
          }
		  else
		  {
			 $("#end_time_area").show();
             $("#start_time_area").show();
          }
          if ($("#end_day_check").prop('checked'))
		  {
             $("#end_day_area").show();
          }
		  else
		  {
             $("#end_day_area").hide();
          }
		$("#all_day_check").change(function (){
          var ischecked = $(this).prop('checked');
          if (ischecked) 
		  {
             $("#start_time_area").hide();
			 $("#end_time_area").hide();
          } 
		  else 
		  {
			 $("#end_time_area").show();
             $("#start_time_area").show();
          }
        })
        
		$("#end_day_check").change(function (){
          var ischecked = $(this).prop('checked');
          if (ischecked) 
		  {
             $("#end_day_area").show();
          } 
		  else 
		  {
             $("#end_day_area").hide();
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
        })
		$("#SMS_REMIND").change(function (){
          var ischecked = $(this).prop('checked');
          if (ischecked) 
		  {
             $("#remind_type").show();
          } 
		  else 
		  {
             $("#remind_type").hide();
          }
        })
	});
})(jQuery);
function resets()
{
	document.getElementById("CONTENT").value = '';
}
</script>

<body class="bodycolor" onLoad="document.form1.CONTENT.focus();">
<table border="0" class="head">
  <tr>
    <td class="Big"><span class="big3" style="padding-left:20px;"> <?=$WIN_TITLE?></span>
    </td>
  </tr>
</table>

  <form action="new_affair_submit.php"  method="post" name="form1" onSubmit="return CheckForm();" class="form-horizontal head_top">
 <table>
    <tr>
      <div class="control-group">
        <label class="control-label"><?=_("事务内容：")?></label>
        <div class="controls">
         <textarea name="CONTENT" id="CONTENT"  rows="3"><?=$CONTENT?></textarea><font color=red><?=_("(*)")?></font>
        </div>
      </div>
    </tr>
   
      <div class="control-group" style="margin-bottom:10px;">
      <label class="control-label" for="CAL_TIME"><?=_("开始日期：")?></label>
      <div class="controls">
        <input class="input input-small calendar-startdate valtype" name="CAL_TIME" value="<?=date("Y-m-d",strtotime($CAL_TIME))?>" placeholder="开始日期" type="text" data-valtype="placeholder" style="outline:none">
        <span class="bootstrap-timepicker" id="start_time_area">
        <input class="input input-mini calendar-starttime valtype" style="width:90px;outline:none" name="BEGIN_TIME_TIME" value="<?=$BEGIN_TIME_TIME?> <?=$BEGIN_TIME_STR?>" placeholder="开始时间" type="text" data-valtype="placeholder"> 
        </span>
      </div>
      </div>
     
      <div class="control-group" style="clear:both;display:; margin-bottom:10px;" id="end_day_area">
      <label class="control-label" for="END_TIME"><?=_("结束日期：")?></label>
      <div class="controls">
        <input class="input input-small calendar-startdate valtype" name="END_TIME" value="<?if ($AFF_ID!=""){echo $END_TIME;} else{echo date("Y-m-d",strtotime($END_TIME)+24*3600);}?>" placeholder="开始日期" type="text" data-valtype="placeholder" style="outline:none">
        <span class="bootstrap-timepicker" id="end_time_area">
        <input class="input input-mini calendar-starttime valtype" style="width:90px;outline:none" name="END_TIME_TIME" value="<?=$END_TIME_TIME?> <?=$END_TIME_STR?>" placeholder="结束时间" type="text" data-valtype="placeholder"> 
        </span>
      </div>
      </div>
  <tr>
    <div class="controls form-inline checkGroup1" style="margin-bottom:10px;clear:both;">
        <label class="checkbox" style="vertical-align:bottom;width:50px;"><input type="checkbox" id="all_day_check" name="all_day_check" <? echo $ALLDAY ? "checked" : ""?> ><?=_("全天")?></label>
        <label class="checkbox" style="vertical-align:bottom;width:100px;"><input type="checkbox" id="end_day_check" name="end_day_check" <? echo $END_TIME ? "checked" : ""?> ><?=_("结束日期")?></label>
    </div>
  </tr>
    <tr>
    <div class="control-group" style="clear:both;">
      <label class="control-label"><?=_("人员：")?></label>
      <div class="controls">
<?
if($AFF_ID!="")
{
?>
    <textarea name="USER_NAME" style="outline:none; height:39px; min-height:39px;" readonly rows="3"><?=trim($USER_NAME_STR,",")?></textarea>
<?
}
else
{
?>
        <input type="hidden" name="USER_ID" value="<?=$USER_ID?>">
        <textarea name="USER_NAME" style="outline:none;height:39px; min-height:39px;" readonly rows="3"><?=$USER_NAME?></textarea>
        <a href="javascript:;" class="orgAdd" onClick="SelectUser('8','3','USER_ID', 'USER_NAME')"><?=_("添加")?></a>
        <a href="javascript:;" class="orgClear" onClick="ClearUser('USER_ID', 'USER_NAME')"><?=_("清空")?></a>
        <font color=red><?=_("(*)")?></font>
<?
}
?>
    
      </div>
    </div>
    </tr>
    <tr>
    <div class="control-group">
      <label class="control-label"><?=_("参与者：")?></label>
      <div class="controls">
        <input type="hidden" name="TAKER" id="TAKER" value="<?=$TAKER?>">
        <textarea name="TAKER_NAME" style="outline:none;height:39px; min-height:39px;" wrap="yes" readonly rows="3"><?=$TAKER_NAME?></textarea>
        <a href="javascript:;" class="orgAdd" onClick="SelectUser('8','','TAKER', 'TAKER_NAME','','form1')"><?=_("添加")?></a>
        <a href="javascript:;" class="orgClear" onClick="ClearUser('TAKER', 'TAKER_NAME')"><?=_("清空")?></a>
      </div>
    </div>
    </tr>
    <tr>
    <div class="control-group form-inline" style="line-height:30px;">
      <label class="control-label"><?=_("事务提醒：")?></label>
      <div class="controls">
    <?=sms_remind(45);?>
      </div>
    </div>
    </tr>
    
    <tr >
    <div class="control-group">
      <label class="control-label" for="TYPE"><?=_("重复类型：")?></label>
      <div class="controls">
        <select name="TYPE"  onChange="sel_change()">
          <option value="2" <? if($TYPE==2) echo "selected";?>><?=_("按日重复")?></option>
          <option value="3" <? if($TYPE==3) echo "selected";?>><?=_("按周重复")?></option>
          <option value="4" <? if($TYPE==4) echo "selected";?>><?=_("按月重复")?></option>
          <option value="5" <? if($TYPE==5) echo "selected";?>><?=_("按年重复")?></option>
          <option value="6" <? if($TYPE==6) echo "selected";?>><?=_("按工作日重复")?></option>
        </select>
      </div>
    </div>
    </tr>
    <tr id="day" <?if($TYPE!="2"&&$TYPE!="") echo "style=\"display:none\"";?>>
      <td nowrap class="remindTimeLabel"> <?=_("重复时间：")?>

        <!--<input name="REMIND_TIME2" class="input-small" type="text" value="<?if($REMIND_TIME=="") echo $CUR_TIME;else echo $REMIND_TIME;?>">-->
        <span class="bootstrap-timepicker">
        <input class="input input-mini calendar-starttime valtype" style="width:90px;outline:none" name="REMIND_TIME2" placeholder="开始时间" type="text" data-valtype="placeholder" value="<?=$REMIND_TIME?> <?=$REMIND_STR?>"> 
        </span>
      </td>
    </tr>
    <tr id="workday" <?if($TYPE!="6") echo "style=\"display:none\"";?>>
      <td nowrap class="remindTimeLabel"> <?=_("重复时间：")?>
        <span class="bootstrap-timepicker">
        <input class="input input-mini calendar-starttime valtype" style="width:90px;outline:none" name="REMIND_TIME6" placeholder="开始时间" type="text" data-valtype="placeholder" value="<?=$REMIND_TIME?> <?=$REMIND_STR?>"> 
        </span>
        <!--<input name="REMIND_TIME6" type="text" class="input-small" value="<?if($REMIND_TIME=="") echo $CUR_TIME;else echo $REMIND_TIME;?>">-->
        </td>
    </tr>
    <tr id="week" <?if($TYPE!="3") echo "style=\"display:none\"";?>>
      <td nowrap class="remindTimeLabel"> <?=_("重复时间：")?>
        <select name="REMIND_DATE3" style="width:110px;">
          <option value="1" <?if($TYPE=="3"&&$REMIND_DATE==1 || $TYPE!="3"&&date("w",time())==1) echo "selected";?>><?=_("星期一")?></option>
          <option value="2" <?if($TYPE=="3"&&$REMIND_DATE==2 || $TYPE!="3"&&date("w",time())==2) echo "selected";?>><?=_("星期二")?></option>
          <option value="3" <?if($TYPE=="3"&&$REMIND_DATE==3 || $TYPE!="3"&&date("w",time())==3) echo "selected";?>><?=_("星期三")?></option>
          <option value="4" <?if($TYPE=="3"&&$REMIND_DATE==4 || $TYPE!="3"&&date("w",time())==4) echo "selected";?>><?=_("星期四")?></option>
          <option value="5" <?if($TYPE=="3"&&$REMIND_DATE==5 || $TYPE!="3"&&date("w",time())==5) echo "selected";?>><?=_("星期五")?></option>
          <option value="6" <?if($TYPE=="3"&&$REMIND_DATE==6 || $TYPE!="3"&&date("w",time())==6) echo "selected";?>><?=_("星期六")?></option>
          <option value="0" <?if($TYPE=="3"&&$REMIND_DATE==0 || $TYPE!="3"&&date("w",time())==0) echo "selected";?>><?=_("星期日")?></option>
        </select>
        <span class="bootstrap-timepicker">
        <input class="input input-mini calendar-starttime valtype" style="width:90px;outline:none" name="REMIND_TIME3" placeholder="开始时间" type="text" data-valtype="placeholder" value="<?=$REMIND_TIME?> <?=$REMIND_STR?>"> 
        </span>
        <!--<input name="REMIND_TIME3" type="text" class="input-small" value="<?if($TYPE=="3") echo $REMIND_TIME; else echo $CUR_TIME;?>">-->
      </td>
    </tr>
    <tr id="mon" <?if($TYPE!="4") echo "style=\"display:none\"";?>>
      <td nowrap class="remindTimeLabel"> <?=_("重复时间：")?>
        <select name="REMIND_DATE4" style="width:110px;" >
<?
for($I=1;$I<=31;$I++)
{
?>
          <option value="<?=$I?>" <?if($TYPE=="4"&&$REMIND_DATE==$I || $TYPE!="4"&&date("j",time())==$I) echo "selected";?>><?=$I?><?=_("日")?></option>
<?
}
?>
        </select>
        <span class="bootstrap-timepicker">
        <input class="input input-mini calendar-starttime valtype" style="width:90px;outline:none" name="REMIND_TIME4" placeholder="开始时间" type="text" data-valtype="placeholder" value="<?=$REMIND_TIME?> <?=$REMIND_STR?>"> 
        </span>
        <!--<input name="REMIND_TIME4" type="text" class="input-small" value="<?if($TYPE=="4") echo $REMIND_TIME; else echo $CUR_TIME;?>">-->
      </td>
    </tr>
    <tr id="year" <?if($TYPE!="5") echo "style=\"display:none\"";?>>
      <td nowrap class="remindTimeLabel"> <?=_("重复时间：")?>
        <select name="REMIND_DATE5_MON" style="width:108px;">
<?
for($I=1;$I<=12;$I++)
{
?>
          <option value="<?=$I?>" <?if($TYPE=="5"&&$REMIND_DATE_MON==$I || $TYPE!="5"&&date("n",time())==$I) echo "selected";?>><?=$I?><?=_("月")?></option>
<?
}
?>
        </select>
        <select name="REMIND_DATE5_DAY" style="width:108px;">
<?
for($I=1;$I<=31;$I++)
{
?>
          <option value="<?=$I?>" <?if($TYPE=="5"&&$REMIND_DATE_DAY==$I || $TYPE!="5"&&date("j",time())==$I) echo "selected";?>><?=$I?><?=_("日")?></option>
<?
}
?>
        </select>
        <span class="bootstrap-timepicker">
        <input class="input input-mini calendar-starttime valtype" style="width:90px;outline:none" name="REMIND_TIME5" placeholder="开始时间" type="text" data-valtype="placeholder" value="<?=$REMIND_TIME?> <?=$REMIND_STR?>"> 
        </span>
        <!--<input name="REMIND_TIME5" type="text" class="input-small" value="<?if($TYPE=="5") echo $REMIND_TIME; else echo $CUR_TIME;?>">-->
      </td>
    </tr>
    <tr>
      <td colspan="2" nowrap class="bottomButtons">
        <input type="hidden" name="AFF_ID" value="<?=$AFF_ID?>">
        <input type="hidden" name="AFF_ID_STR" value="<?=$AFF_ID_STR?>">
         <input type="hidden" name="FROM" value="<?=$FROM?>">
        <button class="btn btn-primary" type="submit" style=" margin-left:179px"><?=_("确定")?></button>
        <button class="btn" type="button" onClick="resets()"><?=_("重填")?></button>
        <button class="btn" type="button" onClick="parent.close();"><?=_("关闭")?></button>
      </td>
    </tr>
  </table>
</form>

</body>
</html>