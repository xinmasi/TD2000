<?
include_once("inc/auth.inc.php");
include_once("inc/utility_org.php");

$query = "SELECT * from ATTEND_EVECTION where EVECTION_ID='$EVECTION_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
	 $USER_ID=$ROW["USER_ID"];
   $EVECTION_DEST=$ROW["EVECTION_DEST"];
   $EVECTION_DATE1=substr($ROW["EVECTION_DATE1"],0,10);
   $EVECTION_DATE2=substr($ROW["EVECTION_DATE2"],0,10);
   $RECORD_TIME=$ROW["RECORD_TIME"]=="0000-00-00 00:00:00" ? $EVECTION_DATE1 : $ROW["RECORD_TIME"];
   $REASON=$ROW["REASON"];
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
  	else if($ALLOW=="1" && $STATUS=="2")
     	$ALLOW_DESC=_("�ѹ���");
}

//�޸���������״̬--yc
update_sms_status('6',$EVECTION_ID);

$HTML_PAGE_TITLE = _("�޸ĳ����¼");
include_once("inc/header.inc.php");
?>

<link rel="stylesheet" type="text/css" href="/static/js/bootstrap/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="/static/common/bootstrap.reset.css">
<link rel="stylesheet" type="text/css" href="/static/modules/attendance/personal/common.css">
<script type="text/javascript" src="/inc/js_lang.php"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/utility.js"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>

<body class="attendance">
<h5 class="attendance-title"><?=_("�޸ĳ����¼")?></h5>
<br>
<div align="center">
	<form action="evection_edit_submit.php"  method="post" name="form1" class="big1">
  <table class="table table-bordered" align="center">
   <tr>
      <td nowrap class=""> <?=_("������Ա��")?></td>
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
      <td nowrap class=""><?=_("����ص㣺")?></td>
      <td class="">
         <input type="text" name="EVECTION_DEST" size="50" maxlength="100" class="" value="<?=$EVECTION_DEST?>">
      </td>
    </tr>
    <tr>
      <td nowrap class=""><?=_("����ʱ�䣺")?></td>
      <td class="">
        <input type="text" name="EVECTION_DATE1" size="10" maxlength="10" class="input-small" value="<?=$EVECTION_DATE1?>" onClick="WdatePicker()"/>
		<a href="javascript:resetTime1();" style="font-size: 13px"><?=_("��Ϊ��ǰʱ��")?></a>
        <?=_("��")?>
        <input type="text" name="EVECTION_DATE2" size="10" maxlength="10" class="input-small" value="<?=$EVECTION_DATE2?>" onClick="WdatePicker()"/>
		<a href="javascript:resetTime2();" style="font-size: 13px"><?=_("��Ϊ��ǰʱ��")?></a>
	  </td>
    </tr>
    <tr>
      <td nowrap class=""><?=_("����ԭ��")?></td>
      <td class="">
        <textarea name="REASON" cols="50" rows="4" class=""><?=$REASON?></textarea>
      </td>
    </tr>
    <tr>
      <td nowrap class=""> <?=_("�Ǽ�IP��")?></td>
      <td class="">
      	 <?=$REGISTER_IP?>
      </td>
    </tr>
    <tr>
      <td nowrap class=""><?=_("������Ա��")?></td>
      <td class="">
        <?=$LEADER_NAME?>
      </td>
    </tr>
    <tr>
      <td nowrap class=""><?=_("״̬��")?></td>
      <td class="">
        <?=$ALLOW_DESC?>
      </td>
    </tr>
  </table>
</div>
<br><br><br>
<center>
	<input type="hidden" name="EVECTION_ID" value="<?=$EVECTION_ID?>">
	<input type="submit" value="<?=_("����")?>" class="btn btn-primary">&nbsp;&nbsp;
	<input type="button" value="<?=_("�ر�")?>" class="btn" onClick="TJF_window_close();">
</center>
</form>
<script>
	function resetTime1()
	{
	   document.form1.EVECTION_DATE1.value="<?=date("Y-m-d",time())?>";
	}
	function resetTime2()
	{
	   document.form1.EVECTION_DATE2.value="<?=date("Y-m-d",time())?>";
	}
</script>
</body>
</html>
