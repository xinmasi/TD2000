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

$HTML_PAGE_TITLE = _("�޸ĳ����¼");
include_once("inc/header.inc.php");
?>


<script type="text/javascript" src="/inc/js_lang.php"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/utility.js"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>

<body class="bodycolor">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/attendance.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><span class="big3"> <?=_("�޸ĳ����¼")?></span><br>
    </td>
  </tr>
</table>
<br>	
<div align="center">
	<form action="evection_edit_submit.php"  method="post" name="form1" class="big1">
  <table class="TableBlock" width="90%" align="center">   
   <tr>
      <td nowrap class="TableData"> <?=_("������Ա��")?></td>
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
      <td nowrap class="TableData"><?=_("����ص㣺")?></td>
      <td class="TableData">
         <input type="text" name="EVECTION_DEST" size="50" maxlength="100" class="BigInput" value="<?=$EVECTION_DEST?>">
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"><?=_("����ʱ�䣺")?></td>
      <td class="TableData">
        <input type="text" name="EVECTION_DATE1" size="10" maxlength="10" class="BigInput" value="<?=$EVECTION_DATE1?>" onClick="WdatePicker()"/>
        <?=_("��")?> 
        <input type="text" name="EVECTION_DATE2" size="10" maxlength="10" class="BigInput" value="<?=$EVECTION_DATE2?>" onClick="WdatePicker()"/>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"><?=_("����ԭ��")?></td>
      <td class="TableData">
        <textarea name="REASON" cols="50" rows="4" class="BigInput"><?=$REASON?></textarea>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("�Ǽ�IP��")?></td>
      <td class="TableData">
      	 <?=$REGISTER_IP?>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"><?=_("������Ա��")?></td>
      <td class="TableData">
        <?=$LEADER_NAME?>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"><?=_("״̬��")?></td>
      <td class="TableData">
        <?=$ALLOW_DESC?>
      </td>
    </tr>
  </table>
</div>	
<br><br><br>
<center>	
	<input type="hidden" name="EVECTION_ID" value="<?=$EVECTION_ID?>">
	<input type="submit" value="<?=_("����")?>" class="BigButton">&nbsp;&nbsp;
	<input type="button" value="<?=_("�ر�")?>" class="BigButton" onClick="TJF_window_close();">
</center>	
</form>
</body>
</html>