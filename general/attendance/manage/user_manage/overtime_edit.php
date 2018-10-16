<?
include_once("inc/auth.inc.php");
include_once("inc/utility_org.php");

$query = "SELECT * from ATTENDANCE_OVERTIME where OVERTIME_ID='$OVERTIME_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
   $USER_ID=$ROW["USER_ID"];
   $APPROVE_ID=$ROW["APPROVE_ID"];
   $RECORD_TIME=$ROW["RECORD_TIME"];
   $START_TIME=$ROW["START_TIME"];
   $END_TIME=$ROW["END_TIME"];
   $OVERTIME_HOURS=$ROW["OVERTIME_HOURS"];
   $OVERTIME_HOURS_ARRAY=explode(_("小时"),$OVERTIME_HOURS);
   $OVERTIME_MINUTES_ARRAY=explode(_("分"),$OVERTIME_HOURS_ARRAY[1]);
   $REGISTER_IP=$ROW["REGISTER_IP"];
   $OVERTIME_CONTENT=$ROW["OVERTIME_CONTENT"];
   $CONFIRM_VIEW=$ROW["CONFIRM_VIEW"];
   $ALLOW=$ROW["ALLOW"];
   $STATUS=$ROW["STATUS"];
   $REASON=$ROW["REASON"];
   $APPROVE_ID=$ROW["APPROVE_ID"];

   $APPROVE_NAME="";
   $query = "SELECT * from USER where USER_ID='$APPROVE_ID'";
   $cursor1= exequery(TD::conn(),$query);
   if($ROW=mysql_fetch_array($cursor1))
      $APPROVE_NAME=$ROW["USER_NAME"];

	if($ALLOW=="0" && $STATUS=="0")
   	 $ALLOW_DESC=_("待审批");
 	else if($ALLOW=="1" && $STATUS=="0")
    	$ALLOW_DESC="<font color=green>"._("已批准")."</font>";
 	else if($ALLOW=="2" && $STATUS=="0")
    	$ALLOW_DESC= "<font color=\"red\">"._("未批准")."</font>";
 	else if($ALLOW=="3" && $STATUS=="0")
    	$ALLOW_DESC=_("申请确认");
 	else if($ALLOW=="3" && $STATUS=="1")
    	$ALLOW_DESC=_("已确认");

}

$HTML_PAGE_TITLE = _("修改加班登记");
include_once("inc/header.inc.php");
?>
<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/js/validation/validationEngine.jquery.min.css">
<link rel="stylesheet" type="text/css" href="/static/js/bootstrap/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="/static/common/bootstrap.reset.css">
<link rel="stylesheet" type="text/css" href="/static/modules/attendance/personal/common.css">
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script type="text/javascript" src="/inc/js_lang.php"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/validation/jquery.validationEngine.min.js" type="text/javascript" charset="utf-8"></script>
<script>
function count_time(){
    var s_time = jQuery("#start_time").val();
    var e_time = jQuery("#end_time").val();

    if(s_time=='' || e_time==''){
        jQuery("#overtime_hours").val(0);
        jQuery("#overtime_minutes").val(0);
    }else{
        var s_ms = Date.parse(new Date(s_time.replace(/-/g, "/")));
        var e_ms = Date.parse(new Date(e_time.replace(/-/g, "/")));

        if(e_ms > s_ms){
            var diff_ms = parseInt((e_ms - s_ms)/1000);
            var diff_h = parseInt(diff_ms/3600);
            var diff_m = parseInt((diff_ms % 3600)/60);
            jQuery("#overtime_hours").val(diff_h);
            jQuery("#overtime_minutes").val(diff_m);
        }else{
            jQuery("#overtime_hours").val(0);
            jQuery("#overtime_minutes").val(0);
        }
    }
}

jQuery(document).ready(function(){
    jQuery("#form1").validationEngine({promptPosition:"centerRight"});

    jQuery("#start_time").blur(function(){
        count_time();
    });

    jQuery("#end_time").blur(function(){
        count_time();
    });
});
	function resetTime()
{
   document.form1.START_TIME.value="<?=date("Y-m-d H:i:s",time())?>";
   count_time();
}
function resetTime1()
{
   document.form1.END_TIME.value="<?=date("Y-m-d H:i:s",time())?>";
   count_time();
}
</script>

<body class="attendance">
<h5 class="attendance-title"><?=_("修改加班登记")?></h5>
<br>
<div align="center">
<form action="overtime_edit_submit.php"  method="post" name="form1" class="big1">
<table class="table table-bordered"  align="center">
 <tr>
  <td nowrap class=""> <?=_("加班人员：")?></td>
  <td class="">
  	 <?=substr(GetUserNameById($USER_ID),0,-1)?>
  </td>
 </tr>
 <tr>
  <td nowrap class=""> <?=_("申请时间：")?></td>
  <td class="">
  	 <?=$RECORD_TIME?>
  </td>
 </tr>
 <tr>
   <td nowrap class=""> <?=_("加班开始时间：")?></td>
   <td class="">
    <input type="text" id="start_time" name="START_TIME" size="20" maxlength="20" class="" value="<?=$START_TIME?>" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})"/>
    <a href="javascript:resetTime();" style="font-size: 13px"><?=_("置为当前时间")?></a>
   </td>
 </tr>
 <tr>
   <td nowrap class=""> <?=_("加班结束时间：")?></td>
   <td class="">
    <input type="text" id="end_time" name="END_TIME" size="20" maxlength="20" class="" value="<?=$END_TIME?>" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})"/>
    <a href="javascript:resetTime1();" style="font-size: 13px"><?=_("置为当前时间")?></a>
   </td>
  </tr>
  <tr>
   <td nowrap class=""> <?=_("加班时长：")?></td>
   <td class="">
   	<input type="text" name="OVERTIME_HOURS" id="overtime_hours" size="2" maxlength="2" class="input-small" value="<?=$OVERTIME_HOURS_ARRAY[0]?>"><?=_("小时")?>
   	<input type="text" name="OVERTIME_MINUTES" id="overtime_minutes" size="2" maxlength="2" class="input-small" value="<?=$OVERTIME_MINUTES_ARRAY[0]?>"><?=_("分")?>
   </td>
  </tr>
  <tr>
   <td nowrap class=""> <?=_("加班内容：")?></td>
   <td class="">
   	 <textarea name="OVERTIME_CONTENT" class="" cols="60" rows="4"><?=$OVERTIME_CONTENT?></textarea>
   </td>
  </tr>
  <tr>
   <td nowrap class=""> <?=_("确认意见：")?></td>
   <td class="">
   	 <textarea name="CONFIRM_VIEW" class="" cols="60" rows="4"><?=$CONFIRM_VIEW?></textarea>
   </td>
  </tr>
    <tr>
      <td nowrap class=""> <?=_("登记IP：")?></td>
      <td class="">
      	 <?=$REGISTER_IP?>
      </td>
    </tr>
  <tr>
   <td nowrap class=""> <?=_("审批人员：")?></td>
   <td class="">
   	 <?=$APPROVE_NAME?>
   </td>
  </tr>
  <tr>
   <td nowrap class=""> <?=_("状态：")?></td>
   <td class="">
   	 <?=$ALLOW_DESC?>
   </td>
  </tr>
</table>
</div>
<br><br><br>
<center>
	<input type="hidden" name="OVERTIME_ID" value="<?=$OVERTIME_ID?>">
	<input type="submit" value="<?=_("保存")?>" class="btn btn-primary">&nbsp;&nbsp;
	<input type="button" value="<?=_("关闭")?>" class="btn" onClick="javascript:window.close();">
</center>
</form>
</body>
</html>
