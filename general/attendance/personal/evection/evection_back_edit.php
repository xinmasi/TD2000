<?
include_once("inc/auth.inc.php");
include_once("inc/utility_sms1.php");

$CUR_DATE=date("Y-m-d",time());
$EVECTION_ID=intval($EVECTION_ID);

$query = "SELECT * from ATTEND_EVECTION where EVECTION_ID='$EVECTION_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
   $EVECTION_DEST=$ROW["EVECTION_DEST"];
   $EVECTION_DATE1=substr($ROW["EVECTION_DATE1"],0,10);
   $EVECTION_DATE2=substr($ROW["EVECTION_DATE2"],0,10);
   $REASON=$ROW["REASON"];
   $LEADER_ID=$ROW["LEADER_ID"];
}

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

<body class="bodycolor attendance">
  <h5 class="attendance-title"><span class="big3"> <?=_("�޸ĳ����¼")?></span></h5><br>
<br>	

<form action="evection_back_edit_submit.php"  method="post" name="form1" class="big1">
<div align="center">
  <table class="TableBlock" width="90%" align="center">   
    <tr>
      <td nowrap class="TableData"><?=_("����ص㣺")?></td>
      <td class="TableData">
         <input type="text" name="EVECTION_DEST" size="50" maxlength="100"  readonly value="<?=$EVECTION_DEST?>">
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"><?=_("����ʱ�䣺")?></td>
      <td class="TableData">
        <input type="text" name="EVECTION_DATE1" size="10" maxlength="10"  readonly value="<?=$EVECTION_DATE1?>">
        <?=_("��")?> 
        <input type="text" name="EVECTION_DATE2" size="10" maxlength="10" class="BigInput" value="<?=$EVECTION_DATE2?>" onClick="WdatePicker()"/>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"><?=_("���ɣ�")?></td>
      <td class="TableData">
        <textarea name="REASON" cols="100" rows="4"  readonly><?=$REASON?></textarea>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("�������ѣ�")?></td>
      <td class="TableData"> <?=sms_remind(6);?></td>
    </tr>        
  </table>
</div>	
<br><br><br>
<center>	
	<input type="hidden" name="EVECTION_ID" value="<?=$EVECTION_ID?>">
	<input type="hidden" name="LEADER_ID" value="<?=$LEADER_ID?>">	
	<input type="submit" value="<?=_("����")?>" class="btn btn-primary">&nbsp;&nbsp;
	<input type="button" value="<?=_("�ر�")?>" class="btn" onClick="javascript:window.close();">
</center>	
</form>
</body>
</html>