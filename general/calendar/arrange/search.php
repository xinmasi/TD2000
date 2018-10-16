<?
include_once("inc/auth.inc.php");
include_once("inc/check_type.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
//2013-4-11 主服务查询
if($IS_MAIN==1)
	$QUERY_MASTER=true;
else
   $QUERY_MASTER="";   

$HTML_PAGE_TITLE = _("日程安排查询");
include_once("inc/header.inc.php");
?>
<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/modules/calendar/css/calendar_person.css">
<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/js/bootstrap/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/theme/<?=$_SESSION["LOGIN_THEME"]?>/calendar.css">
<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/theme/<?=$_SESSION["LOGIN_THEME"]?>/dialog.css">
<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/js/bootstrap/datepicker/bootstrap.datepicker.css">
<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/js/bootstrap/timepicker/bootstrap.timepicker.css">
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/utility.js"></script>
<script type="text/javascript" src="/inc/js_lang.php"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/calendar.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js<?=$GZIP_POSTFIX?>"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/bootstrap/js/bootstrap.min.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery-ui/js/jquery-ui-1.10.3.custom.min.js<?=$GZIP_POSTFIX?>"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/dialog.js"></script>
<!--<script src="/module/DatePicker/WdatePicker.js"></script>-->
<script src="<?=MYOA_JS_SERVER?>/static/js/bootstrap/datepicker/bootstrap.datepicker.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/bootstrap/timepicker/bootstrap.timepicker.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script>
jQuery(function(){
    jQuery("#allbox_for").click(function(){
        if(jQuery("#allbox_for").is(":checked"))
        {
            jQuery("input[name='cal_select']").prop("checked",true);
        }
        else
        {
            jQuery("input[name='cal_select']").prop("checked",false);
        }    
    });
    jQuery("input[name='cal_select']").click(function(){
        jQuery("#allbox_for").prop("checked",false);
    });
})    
function get_checked()
{
    checked_str = "";
    jQuery("input[name='cal_select']:checkbox").each(function(){
        if(jQuery(this).is(":checked"))
        {
            checked_str +=jQuery(this).val()+',';
        }
    })
    return checked_str;
}

function sms_back(MANAGER_ID,MANAGER_NAME)
{
   var top = (screen.availHeight-265)/2;
   var left= (screen.availWidth-420)/2;  
   window.open("../../status_bar/sms_back.php?TO_ID="+escape(MANAGER_ID)+"&CONTENT=<?=urlencode('您好，已收到您的日程安排。')?>&TO_NAME="+escape(MANAGER_NAME),"","height=265,width=420,status=0,toolbar=no,menubar=no,location=no,scrollbars=no,top="+top+",left="+left+",resizable=yes");
}

function del_cal_this()
{
   delete_str=get_checked();
   if(delete_str=="")
   {
      alert("<?=_("要删除日程，请至少选择其中一条。")?>");
      return;
   }

   msg='<?=_("确认要删除所选日程吗？")?>';
   if(window.confirm(msg))
   {
      url="delete.php?action=search&CAL_ID="+ delete_str;
      location=url;
   }
   
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
		   minuteStep: 5
		});
		
		
        
		$("#color").click(function(){
          $("#color_menu").slideToggle();
        });
        /*
        $("#color_menu").mouseout(function (){
            $("#color_menu").hide();
        })*/
        
    	$("a[id^=calcolor]").each(function(i){
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
function ShowDialog(id,vTopOffset)
{
   if(typeof arguments[1] == "undefined")
     vTopOffset = 90;
     
   var bb=(document.compatMode && document.compatMode!="BackCompat") ? document.documentElement : document.body;
   $("overlay").style.width = Math.max(parseInt(bb.scrollWidth),parseInt(bb.offsetWidth))+"px";
   $("overlay").style.height = Math.max(parseInt(bb.scrollHeight),parseInt(bb.offsetHeight))+"px";

   $("overlay").style.display = 'block';
   $(id).style.display = 'block';

   $(id).style.left = ((bb.offsetWidth - $(id).offsetWidth)/2)+"px";
   $(id).style.top  = (vTopOffset)+"px";//(vTopOffset + bb.scrollTop)+
}
</script>

<body class="bodycolor">

<?
 $CUR_DATE=date("Y-m-d",time());
 $CUR_TIME=date("Y-m-d H:i:s",time());
 $CUR_TIME_CHANGE=time();
  //-----------合法性校验---------
  if($SEND_TIME_MIN!="")
  {
    $TIME_OK=is_date($SEND_TIME_MIN);

    if(!$TIME_OK)
    { 
    	$MSG1 = sprintf(_("日期的格式不对，应形如 %s"), $CUR_DATE);
			Message(_("错误"),$MSG1);
      Button_Back();
      exit;
    }
    $SEND_TIME_MIN=$SEND_TIME_MIN." 00:00:00";
    $SEND_TIME_MIN=strtotime($SEND_TIME_MIN);
  }

  if($SEND_TIME_MAX!="")
  {
    $TIME_OK=is_date($SEND_TIME_MAX);

    if(!$TIME_OK)
    { 
    	$MSG2 = sprintf(_("日期的格式不对，应形如 %s"), $CUR_DATE);
			Message(_("错误"),$MSG2);
      Button_Back();
      exit;
    }
    $SEND_TIME_MAX=$SEND_TIME_MAX." 23:59:59";
    $SEND_TIME_MAX=strtotime($SEND_TIME_MAX);
  }

 //------------------------ 生成条件字符串 ------------------
 $CONDITION_STR="";

 if($CAL_TYPE!="")
    $CONDITION_STR.=" and CAL_TYPE='$CAL_TYPE'";
 if($CONTENT!="")
    $CONDITION_STR.=" and CONTENT like '%".$CONTENT."%'";
 if($SEND_TIME_MIN!="")
    $CONDITION_STR.=" and CAL_TIME>='$SEND_TIME_MIN'";
 if($SEND_TIME_MAX!="")
    $CONDITION_STR.=" and END_TIME<='$SEND_TIME_MAX'";

 if($OVER_STATUS=="1")
    $CONDITION_STR.=" and OVER_STATUS='0' and CAL_TIME>'$CUR_TIME_CHANGE'";
 else if($OVER_STATUS=="2")
    $CONDITION_STR.=" and OVER_STATUS='0' and CAL_TIME<='$CUR_TIME_CHANGE' and END_TIME>='$CUR_TIME_CHANGE'";
 else if($OVER_STATUS=="3")
    $CONDITION_STR.=" and OVER_STATUS='0' and END_TIME<'$CUR_TIME_CHANGE'";
 else if($OVER_STATUS=="4")
    $CONDITION_STR.=" and OVER_STATUS='1'";
     

 $query = "SELECT * from CALENDAR where (USER_ID='".$_SESSION["LOGIN_USER_ID"]."' or find_in_set('".$_SESSION["LOGIN_USER_ID"]."',TAKER) or find_in_set('".$_SESSION["LOGIN_USER_ID"]."',OWNER)) ".$CONDITION_STR." order by CAL_TIME,END_TIME";
?>
<br>
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><span class="big3"> <?=_("日程安排查询结果")?></span><br>
    </td>
  </tr>
</table>
<br>
<?
$DEL_COUNT=0;
$CAL_COUNT=0;
$CODE_NAME=array();
$MANAGER=array();
$cursor=exequery(TD::conn(),$query,$QUERY_MASTER);
while($ROW=mysql_fetch_array($cursor))
{
    $CAL_COUNT++;

    $CAL_ID=$ROW["CAL_ID"];
    $CAL_TIME=$ROW["CAL_TIME"];
    $CAL_TIME=date("Y-m-d H:i:s",$CAL_TIME);
    $END_TIME=$ROW["END_TIME"];
    $END_TIME=date("Y-m-d H:i:s",$END_TIME);
    $CAL_TYPE=$ROW["CAL_TYPE"];
    $CAL_LEVEL=$ROW["CAL_LEVEL"];
    $CONTENT=$ROW["CONTENT"];
    $MANAGER_ID=$ROW["MANAGER_ID"];
    $OVER_STATUS=$ROW["OVER_STATUS"];
    $OWNER=$ROW["OWNER"];
    $CREATOR=$ROW["USER_ID"]; 

    $MANAGER_NAME="";
    if($MANAGER_ID!="")
    {
       $query = "SELECT * from USER where USER_ID='$MANAGER_ID'";
       $cursor1= exequery(TD::conn(),$query);
       if($ROW1=mysql_fetch_array($cursor1))
          $MANAGER_NAME=$ROW1["USER_NAME"];
    }

    $CONTENT=csubstr(strip_tags($CONTENT), 0, 100);

    if(!array_key_exists($CAL_TYPE, $MANAGER))
       $CODE_NAME[$CAL_TYPE]=get_code_name($CAL_TYPE,"CAL_TYPE");
    $CAL_TYPE=$CODE_NAME[$CAL_TYPE];

    if($OVER_STATUS=="0")
    {
       if(compare_time($CUR_TIME,$END_TIME)>0)
       {
          $STATUS_COLOR="#FF0000";
          $CAL_TITLE=_("状态：已超时");
       }
       else if(compare_time($CUR_TIME,$CAL_TIME)<0)
       {
          $STATUS_COLOR="#0000FF";
          $CAL_TITLE=_("状态：未开始");
       }
       else
       {
          $STATUS_COLOR="#0000FF";
          $CAL_TITLE=_("状态：进行中");
       }
    }
    else
    {
       $STATUS_COLOR="#00AA00";
       $CAL_TITLE=_("状态：已完成");
    }

   if($CAL_COUNT==1)
    {
?>

<table class="table table-bordered table-hover" width="95%" align="center">
   <thead style="background-color:#EBEBEB">
   	  <th nowrap align="center" width="40"><?=_("选择")?></th>
      <th nowrap align="center"><?=_("开始时间")?><i class="icon-arrow-up"></i></th>
      <th nowrap align="center"><?=_("结束时间")?></th>
      <th nowrap align="center"><?=_("事务类型")?></th>
      <th nowrap align="center"><?=_("事务内容")?></th>
      <th nowrap align="center"><?=_("安排人")?></th>
   </thead>

<?
    }
?>

   <tr id="list_tr_<?=$CAL_ID?>" class="TableData">
   	<td nowrap align="center">
<?
// 安排者、创建者、OA管理员、所属者 可删除
   if(($CREATOR==$_SESSION["LOGIN_USER_ID"] && $MANAGER_ID=="") || $MANAGER_ID==$_SESSION["LOGIN_USER_ID"] || $_SESSION["LOGIN_USER_PRIV"]==1 || find_id($OWNER,$_SESSION["LOGIN_USER_ID"]))
   {
   	  $DEL_COUNT++;
?>
     <input type="checkbox" name="cal_select" value="<?=$CAL_ID?>">
<?
   }
?>
      </td>
      <td nowrap align="center"><?=substr($CAL_TIME,0,-3)?></td>
      <td nowrap align="center"><?=substr($END_TIME,0,-3)?></td>
      <td nowrap align="center"><?=$CAL_TYPE?></td>
      <td title="<?=$CAL_TITLE?>"><span class="CalLevel<?=$CAL_LEVEL?>" title="<?=cal_level_desc($CAL_LEVEL)?>">&nbsp</span><a id="cal_<?=$CAL_ID?>" href="javascript:my_note(<?=$CAL_ID?>,'<?=$IS_MAIN?>');" status="<?=$OVER_STATUS?>" onMouseOver="showMenu(this.id)" style="color:<?=$STATUS_COLOR?>;"><?=$CONTENT?></a>
	 
	  </td>
      <td nowrap align="center"><?=$MANAGER_NAME?></td>
    </tr>
<?
 }

if($CAL_COUNT==0)
{
   Message("",_("无符合条件的日程安排"));
   Button_Back();
   exit;
}
else
{
?>
<tr class="TableControl" style="background:#fff">
     <td colspan="7" class="form-inline">
<?
if($DEL_COUNT > 0)
{
?>     	
       <label class="checkbox" for="allbox_for">
       <input type="checkbox" name="allbox" id="allbox_for" ><?=_("全选")?>
       </label>
       <!--<input type="checkbox" name="allbox" id="allbox_for" onClick="check_all();">
       <label for="allbox_for"><?=_("全选")?></label> &nbsp;-->
       <button type="button" class="btn" onClick="del_cal_this();" title="<?=_("删除所选日程")?>"><?=_("删除")?></button>
<?
}
?>
     </td>
   </tr>
</table>
<br>
<center>
<button type="button" class="btn btn-info" onClick="document.execCommand('Print');"><?=_("打印")?></button>
<button type="button" class="btn" onClick="history.back();"><?=_("返回")?></button></center>
<?
}
?>

<div id="overlay"></div>
<div id="form_div" class="ModalDialog1">
  <div class="modal-header"><a class="operation" href="javascript:;"><button type="button" class="close" data-dismiss="modal" aria-hidden="true" onClick="HideDialog('form_div');">&times;</button></a>
  <h3><span id="title" class="title"><?=_("新建日程")?></span></h3>
  </div>
  <div id="form_body" class="modal-body">
     
  </div>
</div>

<iframe name="form_iframe" id="form_iframe" style="display:none;"></iframe>
</body>
</html>
