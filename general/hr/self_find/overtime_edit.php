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
   $OVERTIME_HOURS_ARRAY=explode(_("Сʱ"),$OVERTIME_HOURS);
   $OVERTIME_MINUTES_ARRAY=explode(_("��"),$OVERTIME_HOURS_ARRAY[1]);   
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
   	 $ALLOW_DESC=_("������");
 	else if($ALLOW=="1" && $STATUS=="0")
    	$ALLOW_DESC="<font color=green>"._("����׼")."</font>";
 	else if($ALLOW=="2" && $STATUS=="0")
    	$ALLOW_DESC= "<font color=\"red\">"._("δ��׼")."</font>";   
 	else if($ALLOW=="3" && $STATUS=="0")
    	$ALLOW_DESC=_("����ȷ��");
 	else if($ALLOW=="3" && $STATUS=="1")
    	$ALLOW_DESC=_("��ȷ��");	

}

$HTML_PAGE_TITLE = _("�޸ļӰ�Ǽ�");
include_once("inc/header.inc.php");
?>


<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>
<script>
	function resetTime()
{
   document.form1.START_TIME.value="<?=date("Y-m-d H:i:s",time())?>";
}
function resetTime1()
{
   document.form1.END_TIME.value="<?=date("Y-m-d H:i:s",time())?>";
}
</script>

<body class="bodycolor">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/attendance.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><span class="big3"><?=_("�޸ļӰ�Ǽ�")?></span><br>
    </td>
  </tr>
</table>
<br>	
<div align="center">
<form action="overtime_edit_submit.php"  method="post" name="form1" class="big1">
<table class="TableBlock" width="90%" align="center">
 <tr>
  <td nowrap class="TableData"> <?=_("�Ӱ���Ա��")?></td>
  <td class="TableData">
  	 <?=substr(GetUserNameById($USER_ID),0,-1)?>
  </td>
 </tr>
 <tr>
  <td nowrap class="TableData"> <?=_("����ʱ�䣺")?></td>
  <td class="TableData">
  	 <?=$RECORD_TIME?>
  </td>
 </tr>
 <tr>
   <td nowrap class="TableData"> <?=_("�Ӱ࿪ʼʱ�䣺")?></td>
   <td class="TableData">
    <input type="text" name="START_TIME" size="20" maxlength="20" class="BigInput" value="<?=$START_TIME?>" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})"/>
        &nbsp;&nbsp;<a href="javascript:resetTime();"><?=_("��Ϊ��ǰʱ��")?></a>
   </td>
 </tr>
 <tr> 
   <td nowrap class="TableData"> <?=_("�Ӱ����ʱ�䣺")?></td>
   <td class="TableData">
    <input type="text" name="END_TIME" size="20" maxlength="20" class="BigInput" value="<?=$END_TIME?>" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})"/>
        &nbsp;&nbsp;<a href="javascript:resetTime1();"><?=_("��Ϊ��ǰʱ��")?></a>
   </td>
  </tr>
  <tr>
   <td nowrap class="TableData"> <?=_("�Ӱ�ʱ����")?></td>
   <td class="TableData">
   	<input type="text" name="OVERTIME_HOURS" size="2" maxlength="2" class="BigInput" value="<?=$OVERTIME_HOURS_ARRAY[0]?>"><?=_("Сʱ")?>
   	<input type="text" name="OVERTIME_MINUTES" size="2" maxlength="2" class="BigInput" value="<?=$OVERTIME_MINUTES_ARRAY[0]?>"><?=_("��")?>
   </td>
  </tr>
  <tr>
   <td nowrap class="TableData"> <?=_("�Ӱ����ݣ�")?></td>
   <td class="TableData">
   	 <textarea name="OVERTIME_CONTENT" class="BigInput" cols="60" rows="4"><?=$OVERTIME_CONTENT?></textarea>
   </td>
  </tr>
  <tr>
   <td nowrap class="TableData"> <?=_("ȷ�������")?></td>
   <td class="TableData">
   	 <textarea name="CONFIRM_VIEW" class="BigInput" cols="60" rows="4"><?=$CONFIRM_VIEW?></textarea>
   </td>
  </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("�Ǽ�IP��")?></td>
      <td class="TableData">
      	 <?=$REGISTER_IP?>
      </td>
    </tr>
  <tr>
   <td nowrap class="TableData"> <?=_("������Ա��")?></td>
   <td class="TableData">
   	 <?=$APPROVE_NAME?>
   </td>
  </tr>
  <tr>
   <td nowrap class="TableData"> <?=_("״̬��")?></td>
   <td class="TableData">
   	 <?=$ALLOW_DESC?>
   </td>
  </tr>
</table>
</div>	
<br><br><br>
<center>	
	<input type="hidden" name="OVERTIME_ID" value="<?=$OVERTIME_ID?>">
	<input type="submit" value="<?=_("����")?>" class="BigButton">&nbsp;&nbsp;
	<input type="button" value="<?=_("�ر�")?>" class="BigButton" onClick="javascript:window.close();">
</center>	
</form>
</body>
</html>