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
    	$ALLOW_DESC=_("������");
 	else if($ALLOW=="1" && $STATUS=="1")
    	$ALLOW_DESC="<font color=green>"._("����׼")."</font>";
 	else if($ALLOW=="2" && $STATUS=="1")
    	$ALLOW_DESC="<font color=red>"._("δ��׼")."</font>";
 	else if($ALLOW=="3" && $STATUS=="1")
    	$ALLOW_DESC=_("��������");
 	else if($ALLOW=="3" && $STATUS=="2")
    	$ALLOW_DESC=_("������");
}

$HTML_PAGE_TITLE = _("�޸���ټ�¼");
include_once("inc/header.inc.php");
?>

<link rel="stylesheet" type="text/css" href="/static/js/bootstrap/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="/static/common/bootstrap.reset.css">
<link rel="stylesheet" type="text/css" href="/static/modules/attendance/personal/common.css">
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

<body class="attendance">
<h5 class="attendance-title"><?=_("�޸���ټ�¼")?></h5>
<br>
<div align="center">
  <form action="leave_edit_submit.php"  method="post" name="form1" class="big1">
  <table class="table table-bordered" align="center">
    <tr>
      <td nowrap class=""> <?=_("�����Ա��")?></td>
      <td class="">
      	 <?=substr(GetUserNameById($USER_ID),0,-1)?>
      </td>
    </tr>
   <tr>
      <td nowrap class=""> <?=_("����ʱ�䣺")?></td>
      <td class="">
         <?=$RECORD_TIME?>
      </td>
    </tr>
    <tr>
      <td nowrap class=""> <?=_("���ԭ��")?></td>
      <td class="" colspan="3">
      	 <textarea name="LEAVE_TYPE" class="" cols="60" rows="3"><?=$LEAVE_TYPE?></textarea>
      </td>
    </tr>
    <tr>
      <td nowrap class=""> <?=_("���ʱ�䣺")?></td>
      <td class="" colspan="3">
        <input type="text" name="LEAVE_DATE1" size="20" maxlength="22" class="input-medium" value="<?=$LEAVE_DATE1?>" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})"/>
        <a href="javascript:resetTime1();" style="font-size: 13px"><?=_("��Ϊ��ǰʱ��")?></a>
        <?=_("��")?>
        <input type="text" name="LEAVE_DATE2" size="20" maxlength="22" class="input-medium" value="<?=$LEAVE_DATE2?>" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})"/>
        <a href="javascript:resetTime2();" style="font-size: 13px"><?=_("��Ϊ��ǰʱ��")?></a>
      </td>
    </tr>
    <tr>
      <td nowrap class=""> <?=_("����ʱ�䣺")?></td>
      <td class="">
         <input type="text" name="DESTROY_TIME" size="20" maxlength="22" class="" value="<?=$DESTROY_TIME?>" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})"/>
         <a href="javascript:resetTime4();" style="font-size: 13px"><?=_("��Ϊ��ǰʱ��")?></a>
      </td>
    </tr>
    <tr>
      <td nowrap class=""> <?=_("������ͣ�")?></td>
      <td class="">
    	   <select name="LEAVE_TYPE2" class="">
           <?=hrms_code_list("ATTEND_LEAVE",$LEAVE_TYPE2);?>
         </select>
      </td>
    </tr>
    <tr>
      <td nowrap class=""> <?=_("ʹ�����ݼ٣�")?></td>
      <td class="">
      	 <input type="text" name="ANNUAL_LEAVE" size="3" maxlength="3" class="" value="<?=$ANNUAL_LEAVE?>"><?=_("��")?>
      </td>
    </tr>
    <tr>
      <td nowrap class=""> <?=_("�Ǽ�IP��")?></td>
      <td class="">
      	 <?=$REGISTER_IP?>
      </td>
    </tr>
    <tr>
      <td nowrap class=""> <?=_("������Ա��")?></td>
      <td class="">
      	 <?=$LEADER_NAME?>
      </td>
    </tr>
    <tr>
      <td nowrap class=""> <?=_("״̬��")?></td>
      <td class="">
      	 <?=$ALLOW_DESC?>
      </td>
    </tr>
  </table>
</div>
<br><br><br>
<center>
	<input type="hidden" name="LEAVE_ID" value="<?=$LEAVE_ID?>">
	<input type="submit" value="<?=_("����")?>" class="btn btn-primary">&nbsp;&nbsp;
	<input type="button" value="<?=_("�ر�")?>" class="btn" onClick="javascript:window.close();">
</center>
</form>
</body>
</html>
