<?
include_once("inc/auth.inc.php");
include_once("inc/utility_org.php");

$query = "SELECT * from ATTEND_OUT where OUT_ID='$OUT_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
    $OUT_TYPE=$ROW["OUT_TYPE"];
    $OUT_TIME1=$ROW["OUT_TIME1"];
    $OUT_TIME2=$ROW["OUT_TIME2"];
    $CREATE_DATE=$ROW["CREATE_DATE"];
    $REGISTER_IP=$ROW["REGISTER_IP"];
    $ALLOW=$ROW["ALLOW"];
    $STATUS=$ROW["STATUS"];
    $USER_ID=$ROW["USER_ID"];
    $LEADER_ID=$ROW["LEADER_ID"];
    $SUBMIT_TIME=substr($ROW["SUBMIT_TIME"],0,10);
    $LEADER_NAME="";
    $query = "SELECT * from USER where USER_ID='$LEADER_ID'";
    $cursor1= exequery(TD::conn(),$query);
    if($ROW=mysql_fetch_array($cursor1))
        $LEADER_NAME=$ROW["USER_NAME"];
    if($ALLOW=="0" && $STATUS=="0")
        $STATUS_DESC=_("待审批");
    else if($ALLOW=="1" && $STATUS=="0")
        $STATUS_DESC="<font color=green>"._("已批准")."</font>";
    else if($ALLOW=="2" && $STATUS=="0")
        $STATUS_DESC="<font color=red>"._("未批准")."</font>";
    else if($ALLOW=="1" && $STATUS=="1")
        $STATUS_DESC=_("已归来");
}

//修改事务提醒状态--yc
update_sms_status('6',$OUT_ID);

$HTML_PAGE_TITLE = _("外出归来，主管确认外出记录");
include_once("inc/header.inc.php");
?>

<link rel="stylesheet" type="text/css" href="/static/js/bootstrap/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="/static/common/bootstrap.reset.css">
<link rel="stylesheet" type="text/css" href="/static/modules/attendance/personal/common.css">
<script type="text/javascript" src="/inc/js_lang.php"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/utility.js"></script>
<script>
function td_calendar(fieldname)
{
  myleft=document.body.scrollLeft+event.clientX-event.offsetX+120;
  mytop=document.body.scrollTop+event.clientY-event.offsetY+230;

  window.showModalDialog("/inc/calendar.php?FIELDNAME="+fieldname,self,"edge:raised;scroll:0;status:0;help:0;resizable:1;dialogWidth:280px;dialogHeight:215px;dialogTop:"+mytop+"px;dialogLeft:"+myleft+"px");
}

function td_clock(fieldname,pare)
{
  myleft=document.body.scrollLeft+event.clientX-event.offsetX+120;
  mytop=document.body.scrollTop+event.clientY-event.offsetY+230;
  window.showModalDialog("../../personal/clock.php?FIELDNAME="+fieldname,self,"edge:raised;scroll:0;status:0;help:0;resizable:1;dialogWidth:280px;dialogHeight:120px;dialogTop:"+mytop+"px;dialogLeft:"+myleft+"px");
}

function resetTime(){
    document.form1.OUT_TIME1.value="<?=date("H:i",time())?>";
    count_time();
}

function resetTime1(){
    document.form1.OUT_TIME2.value="<?=date("H:i",time())?>";
    count_time();
}
</script>

<body class="attendance">
<h5 class="attendance-title"><?=_("外出归来，主管确认外出记录")?></h5>
<br>
<div align="center" style="text-align: left;">
  <form action="out_edit_submit.php"  method="post" name="form1" class="big1">
  <table class="table table-bordered" align="center">
    <tr>
      <td nowrap class=""> <?=_("外出人员：")?></td>
      <td class="">
      	 <?=substr(GetUserNameById($USER_ID),0,-1)?>
      </td>
    </tr>
    <tr>
      <td nowrap class=""> <?=_("申请时间：")?></td>
      <td class="">
      	 <?=$CREATE_DATE?>
      </td>
    </tr>
    <tr>
      <td nowrap class=""> <?=_("外出原因：")?></td>
      <td class="">
      	 <textarea name="OUT_TYPE" class="" cols="60" rows="4"><?=$OUT_TYPE?></textarea>
      </td>
    </tr>

    <tr>
        <td nowrap class=""> <?=_("外出日期：")?></td>
        <td><input type="text" name="OUT_DATE" size="15" maxlength="10" class="input-small" readonly value="<?=$SUBMIT_TIME?>"/></td>
    </tr>
    <tr>
        <td nowrap class=""> <?=_("外出开始时间：")?></td>
        <td>
            <input type="text" name="OUT_TIME1" size="5" maxlength="5" class="input-mini" value="<?=$OUT_TIME1?>" onClick="WdatePicker({dateFmt:'HH:mm'})">
            <a href="javascript:resetTime();" style="font-size: 13px"><?=_("置为当前时间")?></a>
        </td>
    </tr>
    <tr>
        <td nowrap class=""> <?=_("外出结束时间：")?></td>
        <td>
            <input type="text" name="OUT_TIME2" size="5" maxlength="5" class="input-mini" value="<?=$OUT_TIME2?>" onClick="WdatePicker({dateFmt:'HH:mm'})">
            <a href="javascript:resetTime1();" style="font-size: 13px"><?=_("置为当前时间")?></a>
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
      	 <?=$LEADER_NAME?>
      </td>
    </tr>
    <tr>
      <td nowrap class=""> <?=_("状态：")?></td>
      <td class="">
      	 <?=$STATUS_DESC?>
      </td>
    </tr>
  </table>
</div>
<br><br><br>
<center>
	<input type="hidden" name="OUT_ID" value="<?=$OUT_ID?>">
	<input type="submit" value="<?=_("保存")?>" class="btn btn-primary">&nbsp;&nbsp;
	<input type="button" value="<?=_("关闭")?>" class="btn" onClick="TJF_window_close();">
</center>
</form>
</body>
</html>
