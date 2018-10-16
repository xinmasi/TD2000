<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_sms1.php");
$connstatus = ($connstatus) ? true : false;

$HTML_PAGE_TITLE = _("修改销假信息");
include_once("inc/header.inc.php");
?>

<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/utility.js"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>
<script>
function CheckForm()
{
	var annual_day=document.getElementById("ANNUAL_LEAVE").value;
	if(annual_day=="")
	{
			annual_day=0.0;
			document.getElementById("ANNUAL_LEAVE").value=0.0;
	}
	if(confirm(sprintf("<?=_("确认占用年休假为%s天")?>", annual_day)))
		document.form1.submit();
}

</script>

<?
$query="select * from SYS_PARA where PARA_NAME='SMS_REMIND'";
$cursor=exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
   $PARA_VALUE=$ROW["PARA_VALUE"];
$SMS_REMIND1=substr($PARA_VALUE,0,strpos($PARA_VALUE,"|"));
$SMS2_REMIND1_TMP=substr($PARA_VALUE,strpos($PARA_VALUE,"|")+1);
$SMS2_REMIND1=substr($SMS2_REMIND1_TMP,0,strpos($SMS2_REMIND1_TMP,"|"));

if($OP=="1")
{
	$CUR_TIME=date("Y-m-d H:i:s",time());
	$SELECTED_STR = td_trim($SELECTED_STR);
	if($SELECTED_STR != "")
	{
	   $query="update ATTEND_LEAVE set STATUS='2',ALLOW='3',DESTROY_TIME='$CUR_TIME',LEAVE_DATE2='$LEAVE_DATE2',ANNUAL_LEAVE='$ANNUAL_LEAVE' where LEAVE_ID in ($SELECTED_STR)";
	   exequery(TD::conn(),$query);
	}
	if($LEAVE_ID != "")
	{
	   $query="update ATTEND_LEAVE set STATUS='2',ALLOW='3',DESTROY_TIME='$CUR_TIME',LEAVE_DATE2='$LEAVE_DATE2',ANNUAL_LEAVE='$ANNUAL_LEAVE' where LEAVE_ID = '$LEAVE_ID'";
	   exequery(TD::conn(),$query);
	}
	
	//header("location: ./leave_back.php");
   //---------- 事务提醒 ----------
   $SMS_CONTENT=_("您的销假申请已被批准，已销假！");
   if(find_id($SMS_REMIND1,6))
      send_sms("",$_SESSION["LOGIN_USER_ID"],$USER_ID,6,$SMS_CONTENT,$REMIND_URL);

  if($MOBILE_FLAG=="1")
    if(find_id($SMS2_REMIND1,6))
       send_mobile_sms_user("",$_SESSION["LOGIN_USER_ID"],$USER_ID,$SMS_CONTENT,6);
?>
<script>
	if(window.opener.location.href.indexOf("connstatus") < 0 ){
		window.opener.location.href = window.opener.location.href+"?connstatus=1";
	}else{
		window.opener.location.reload();
	}
window.close();
</script>
<?
exit;
}
$query = "SELECT * from ATTEND_LEAVE where LEAVE_ID='$LEAVE_ID'";
$cursor= exequery(TD::conn(),$query, $connstatus);
$CONFIRM_DATE = "";
if($ROW=mysql_fetch_array($cursor))
{
   $LEAVE_DATE1=$ROW["LEAVE_DATE1"];
   $LEAVE_DATE2=$ROW["LEAVE_DATE2"];
   $DESTROY_TIME=$ROW["DESTROY_TIME"];
   
   if(strtotime($LEAVE_DATE2) <= strtotime($DESTROY_TIME))
     $CONFIRM_DATE = $LEAVE_DATE2;
   else
     $CONFIRM_DATE = $DESTROY_TIME;
      
   $RECORD_TIME=$ROW["RECORD_TIME"]; 
   $LEAVE_TYPE=$ROW["LEAVE_TYPE"];
   $USER_ID=$ROW["USER_ID"];
   $ALLOW=$ROW["ALLOW"];
   $REGISTER_IP=$ROW["REGISTER_IP"];
   $ANNUAL_LEAVE=$ROW["ANNUAL_LEAVE"];
   $LEAVE_TYPE2=$ROW["LEAVE_TYPE2"];
   $LEAVE_TYPE2=get_hrms_code_name($LEAVE_TYPE2,"ATTEND_LEAVE");
}
$query = "SELECT USER.DEPT_ID,USER_NAME from USER,DEPARTMENT where USER.DEPT_ID=DEPARTMENT.DEPT_ID and USER_ID='$USER_ID'";
$cursor= exequery(TD::conn(),$query, $connstatus);
if($ROW=mysql_fetch_array($cursor))
{
   $DEPT_ID=$ROW["DEPT_ID"];
   $USER_NAME=$ROW["USER_NAME"];
   $DEPT_LONG_NAME=dept_long_name($DEPT_ID);
}
?>

<body class="bodycolor">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/attendance.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><span class="big3"> <?=_("销假审批")?></span><br>
    </td>
  </tr>
</table>
<br>
<form action="update_back_time.php?connstatus=1"  method="post" name="form1" class="big1">
 <table class="TableBlock" width="100%" align="center">
    <tr>
      <td nowrap class="TableData" width=150> <?=_("请假人：")?></td>
      <td class="TableData">
      	 &nbsp;<span style="font-size:10pt"><?=$USER_NAME?></span>
      </td>
      <td nowrap class="TableData"> <?=_("请假人部门：")?></td>
      <td class="TableData">
      	 &nbsp;<span style="font-size:10pt"><?=$DEPT_LONG_NAME?></span>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData" width=150> <?=_("请假原因：")?></td>
      <td class="TableData" colspan="3"><?=$LEAVE_TYPE?></td>
    </tr>
    <tr>
      <td nowrap class="TableData" width=150> <?=_("申请时间：")?></td>
      <td class="TableData">
      	 &nbsp;<span style="font-size:10pt"><?=$RECORD_TIME?></span>
      </td>
      <td nowrap class="TableData"> <?=_("开始时间：")?></td>
      <td class="TableData">
      	 &nbsp;<span style="font-size:10pt"><?=$LEAVE_DATE1."(".get_week($LEAVE_DATE1).")"?></span>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData" width=150> <?=_("占年休假：")?></td>
      <td class="TableData">
      	 &nbsp;<span style="font-size:10pt"><input type="text" size="4"  id="ANNUAL_LEAVE" name="ANNUAL_LEAVE"  value="<?=$ANNUAL_LEAVE?>"><?=_("天")?></span>
      </td>
      <td nowrap class="TableData"> <?=_("登记IP")?></td>
      <td class="TableData">
      	 &nbsp;<span style="font-size:10pt"><?=$REGISTER_IP?></span>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData" width=150> <?=_("申请类型")?></td>
      <td class="TableData">
      	 &nbsp;<span style="font-size:10pt"><?if($ALLOW=="0")echo _("请假申请");else echo _("销假申请");?></span>
      </td>
      <td nowrap class="TableData"> <?=_("请假类型：")?></td>
      <td class="TableData">
      	 &nbsp;<span style="font-size:10pt"><?=$LEAVE_TYPE2?></span>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData" width=150> <?=_("请假时填写的结束时间：")?></td>
      <td class="TableData"  colspan="3">
      	 &nbsp;<span style="font-size:10pt"><?=$LEAVE_DATE2."(".get_week($LEAVE_DATE2).")"?></span>&nbsp;&nbsp;<input type="button" class="SmallButton" onClick="LEAVE_DATE2.value='<?=$LEAVE_DATE2?>';" value="<?=_("以此时间为准")?>">
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("申请销假时间：")?></td>
      <td class="TableData"  colspan="3">
        &nbsp;<span style="font-size:10pt"><?=date("Y-m-d H:i:s",time())."(".get_week(time()).")"?></span>&nbsp;&nbsp;<input type="button" class="SmallButton" onClick="LEAVE_DATE2.value='<?=date("Y-m-d H:i:s",time())?>';" value="<?=_("以此时间为准")?>">
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("审批人确认请假结束时间：")?></td>
      <td class="TableData" colspan="3">
        <input type="text" name="LEAVE_DATE2" size="20" maxlength="22" class="BigInput" readonly value="<?=date("Y-m-d H:i:s",time())?>" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})">
      </td>
    </tr>
    <tr align="center" class="TableControl">
      <td colspan="4" nowrap>
       <input type="button"  value="<?=_("确定")?>" class="BigButton" onclick="CheckForm()">
	   <input type="button"  value="<?=_("关闭")?>" class="BigButton" onclick="window.close()">
       <input type="hidden" name="LEAVE_ID"  value="<?=$LEAVE_ID?>">
       <input type="hidden" name="USER_ID"  value="<?=$USER_ID?>">
       <input type="hidden" name="MOBILE_FLAG"  value="<?=$MOBILE_FLAG?>">
       <input type="hidden" name="OP"  value="1">
      </td>
    </tr>
  </table>
</form>
</body>
</html>
