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


$HTML_PAGE_TITLE = _("外出归来，主管确认外出记录");
include_once("inc/header.inc.php");
?>


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
</script>

<body class="bodycolor">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/attendance.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><span class="big3"> <?=_("外出归来，主管确认外出记录")?></span><br>
    </td>
  </tr>
</table>
<br>	
<div align="center">
  <form action="out_edit_submit.php"  method="post" name="form1" class="big1">
  <table class="TableBlock" width="90%" align="center">
    <tr>
      <td nowrap class="TableData"> <?=_("外出人员：")?></td>
      <td class="TableData">
      	 <?=substr(GetUserNameById($USER_ID),0,-1)?>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("申请时间：")?></td>
      <td class="TableData">
      	 <?=$CREATE_DATE?>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("外出原因：")?></td>
      <td class="TableData">
      	 <textarea name="OUT_TYPE" class="BigInput" cols="60" rows="4"><?=$OUT_TYPE?></textarea>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("外出时间：")?></td>
      <td class="TableData">
         <?=_("日期")?> <input type="text" name="OUT_DATE" size="15" maxlength="10" class="BigStatic" readonly value="<?=$SUBMIT_TIME?>"/>
         <?=_("从")?> <input type="text" name="OUT_TIME1" size="5" maxlength="5" class="BigInput" value="<?=$OUT_TIME1?>">
         <img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/clock.gif" align="absMiddle" border="0" align="absMiddle" style="cursor:hand" onclick="td_clock('form1.OUT_TIME1');">
         <?=_("至")?> <input type="text" name="OUT_TIME2" size="5" maxlength="5" class="BigInput" value="<?=$OUT_TIME2?>">
         <img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/clock.gif" align="absMiddle" border="0" align="absMiddle" style="cursor:hand" onclick="td_clock('form1.OUT_TIME2');"><br>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("登记IP：")?></td>
      <td class="TableData">
      	 <?=$REGISTER_IP?>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("审批人员：")?></td>
      <td class="TableData">
      	 <?=$LEADER_NAME?>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("状态：")?></td>
      <td class="TableData">
      	 <?=$STATUS_DESC?>
      </td>
    </tr>
  </table>
</div>	
<br><br><br>
<center>	
	<input type="hidden" name="OUT_ID" value="<?=$OUT_ID?>">
	<input type="submit" value="<?=_("保存")?>" class="BigButton">&nbsp;&nbsp;
	<input type="button" value="<?=_("关闭")?>" class="BigButton" onClick="TJF_window_close();">
</center>	
</form>
</body>
</html>