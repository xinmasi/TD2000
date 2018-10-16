<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");

$query = "SELECT * from ATTEND_LEAVE where LEAVE_ID='$LEAVE_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
	 $USER_ID=$ROW["USER_ID"];
   $LEAVE_TYPE=$ROW["LEAVE_TYPE"];
   $LEAVE_TYPE2=$ROW["LEAVE_TYPE2"];
   $LEAVE_DATE1=$ROW["LEAVE_DATE1"];
   $LEAVE_DATE2=$ROW["LEAVE_DATE2"];
   $ANNUAL_LEAVE=$ROW["ANNUAL_LEAVE"];
   $DESTROY_TIME=$ROW["DESTROY_TIME"];
   $RECORD_TIME=$ROW["RECORD_TIME"];
   $LEADER_ID=$ROW["LEADER_ID"];
   $STATUS=$ROW["STATUS"];
   $ALLOW=$ROW["ALLOW"];
   $REGISTER_IP=$ROW["REGISTER_IP"];
   $LEADER_NAME="";
   $query = "SELECT * from USER where USER_ID='$LEADER_ID'";
   $cursor1= exequery(TD::conn(),$query);
   if($ROW=mysql_fetch_array($cursor1))
      $LEADER_NAME=$ROW["USER_NAME"];

	if($ALLOW=="0" && $STATUS=="1")
    	$ALLOW_DESC=_("待审批");
 	else if($ALLOW=="1" && $STATUS=="1")
    	$ALLOW_DESC="<font color=green>"._("已批准")."</font>";
 	else if($ALLOW=="2" && $STATUS=="1")
    	$ALLOW_DESC="<font color=red>"._("未批准")."</font>";
 	else if($ALLOW=="3" && $STATUS=="1")
    	$ALLOW_DESC=_("申请销假");
 	else if($ALLOW=="3" && $STATUS=="2")
    	$ALLOW_DESC=_("已销假");
}

$HTML_PAGE_TITLE = _("修改请假记录");
include_once("inc/header.inc.php");
?>


<script type="text/javascript" src="/inc/js_lang.php"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>
<script>
function resetTime1()
{
   document.form1.LEAVE_DATE1.value="<?=date("Y-m-d H:i:s",time())?>";
}
function resetTime2()
{
   document.form1.LEAVE_DATE2.value="<?=date("Y-m-d H:i:s",time())?>";
}
function resetTime3()
{
   document.form1.RECORD_TIME.value="<?=date("Y-m-d H:i:s",time())?>";
}
function resetTime4()
{
   document.form1.DESTROY_TIME.value="<?=date("Y-m-d H:i:s",time())?>";
}
</script>

<body class="bodycolor">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/attendance.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><span class="big3"> <?=_("修改请假记录")?></span><br>
    </td>
  </tr>
</table>
<br>	
<div align="center">
  <form action="leave_edit_submit.php"  method="post" name="form1" class="big1">
  <table class="TableBlock" width="90%" align="center">
    <tr>
      <td nowrap class="TableData"> <?=_("请假人员：")?></td>
      <td class="TableData">
      	 <?=substr(GetUserNameById($USER_ID),0,-1)?>
      </td>
    </tr>
   <tr>
      <td nowrap class="TableData"> <?=_("申请时间：")?></td>
      <td class="TableData">
         <?=$RECORD_TIME?>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("请假原因：")?></td>
      <td class="TableData" colspan="3">
      	 <textarea name="LEAVE_TYPE" class="BigInput" cols="60" rows="3"><?=$LEAVE_TYPE?></textarea>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("请假时间：")?></td>
      <td class="TableData" colspan="3">
        <input type="text" name="LEAVE_DATE1" size="20" maxlength="22" class="BigInput" value="<?=$LEAVE_DATE1?>" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})"/>
        <a href="javascript:resetTime1();"><?=_("置为当前时间")?></a>
        <?=_("至")?> 
        <input type="text" name="LEAVE_DATE2" size="20" maxlength="22" class="BigInput" value="<?=$LEAVE_DATE2?>" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})"/>
        <a href="javascript:resetTime2();"><?=_("置为当前时间")?></a>
      </td>
    </tr>
    <tr>  
      <td nowrap class="TableData"> <?=_("销假时间：")?></td>
      <td class="TableData">
         <input type="text" name="DESTROY_TIME" size="20" maxlength="22" class="BigInput" value="<?=$DESTROY_TIME?>" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})"/>
         <a href="javascript:resetTime4();"><?=_("置为当前时间")?></a>
      </td>      
    </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("请假类型：")?></td>
      <td class="TableData">
    	   <select name="LEAVE_TYPE2" class="BigSelect">
           <?=hrms_code_list("ATTEND_LEAVE",$LEAVE_TYPE2);?>
         </select>      	 
      </td> 
    </tr>
    <tr> 	
      <td nowrap class="TableData"> <?=_("使用年休假：")?></td>
      <td class="TableData">
      	 <input type="text" name="ANNUAL_LEAVE" size="3" maxlength="3" class="BigInput" value="<?=$ANNUAL_LEAVE?>"><?=_("天")?>
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
      	 <?=$ALLOW_DESC?>
      </td>
    </tr>
  </table>
</div>	
<br><br><br>
<center>	
	<input type="hidden" name="LEAVE_ID" value="<?=$LEAVE_ID?>">
	<input type="submit" value="<?=_("保存")?>" class="BigButton">&nbsp;&nbsp;
	<input type="button" value="<?=_("关闭")?>" class="BigButton" onClick="javascript:window.close();">
</center>	
</form>
</body>
</html>