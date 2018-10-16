<?
include_once("inc/auth.inc.php");
include_once("inc/utility_org.php");

$HTML_PAGE_TITLE = _("查岗质询信息修改");
include_once("inc/header.inc.php");
?>


<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/theme/<?=$_SESSION["LOGIN_THEME"]?>/bbs.css" />
<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/utility.js"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>
<script Language="JavaScript">
	function CheckForm()
{
   if(document.form1.CHECK_USER_ID.value=="")
   { 
      alert("<?=_("缺岗人想不能为空！")?>");
      return (false);
   }
   if(document.form1.CHECK_DUTY_MANAGER.value=="")
   { 
      alert("<?=_("查岗人不能为空！")?>");
      return (false);
   }
 	 if(document.form1.CHECK_DUTY_TIME.value=="")
   { 
      alert("<?=_("查岗时间不能为空！")?>");
      return (false);
   }

   if(document.form1.CHECK_DUTY_TIME.value!="" && document.form1.CHECK_DUTY_TIME.value > document.form1.RECORD_TIME.value)
   { 
      alert("<?=_("缺岗人说明时间不能小于查岗时间！")?>");
      return (false);
   }
 return (true);
}
</script>

<?
$query="select * from ATTEND_ASK_DUTY where ASK_DUTY_ID='$ASK_DUTY_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
   $ASK_DUTY_ID=$ROW["ASK_DUTY_ID"];
   $CHECK_USER_ID=$ROW["CHECK_USER_ID"];
   $CHECK_DUTY_MANAGER=$ROW["CHECK_DUTY_MANAGER"];

   $CHECK_DUTY_TIME=$ROW["CHECK_DUTY_TIME"];
   $RECORD_TIME=$ROW["RECORD_TIME"];
   $CHECK_DUTY_REMARK=$ROW["CHECK_DUTY_REMARK"];
   $EXPLANATION=$ROW["EXPLANATION"];

	 $CHECK_USER_NAME=substr(GetUserNameById($CHECK_USER_ID),0,-1);
   $CHECK_MANAGER_NAME=substr(GetUserNameById($CHECK_DUTY_MANAGER),0,-1);


   if($CHECK_DUTY_TIME=="0000-00-00 00:00:00")
     $CHECK_DUTY_TIME="";
   if($RECORD_TIME=="0000-00-00 00:00:00")
     $RECORD_TIME="";
}
 
?>

<body class="bodycolor">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_new.gif" align="absmiddle"><span class="big3"> <?=_("修改查岗质询信息")?></span>&nbsp;&nbsp;
    </td>
  </tr>
</table>
<form action="update.php"  method="post" name="form1" enctype="multipart/form-data" onsubmit="return CheckForm();">
 <table class="TableBlock" width="80%" align="center">
   <tr>
      <td nowrap class="TableData"><?=_("缺岗人：")?></td>
      <td class="TableData">
        <input type="text" name="CHECK_USER_NAME" size="15" class="BigStatic" readonly value="<?=substr(GetUserNameById($CHECK_USER_ID),0,-1)?>">&nbsp;
        <input type="hidden" name="CHECK_USER_ID" value="<?=$CHECK_USER_ID?>">
        <a href="javascript:;" class="orgAdd" onClick="SelectUserSingle('7','','CHECK_USER_ID', 'CHECK_USER_NAME','1')"><?=_("选择")?></a>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"><?=_("查岗人：")?></td>
      <td class="TableData">
        <input type="text" name="CHECK_MANAGER_NAME" size="15" class="BigStatic" readonly value="<?=substr(GetUserNameById($CHECK_DUTY_MANAGER),0,-1)?>">&nbsp;
        <input type="hidden" name="CHECK_DUTY_MANAGER" value="<?=$CHECK_DUTY_MANAGER?>">
        <a href="javascript:;" class="orgAdd" onClick="SelectUserSingle('7','','CHECK_DUTY_MANAGER', 'CHECK_MANAGER_NAME')"><?=_("选择")?></a>
      </td>
    </tr>
    <tr>
    	 <td nowrap class="TableData"> <?=_("查岗时间：")?></td>
      <td class="TableData">
        <input type="text" name="CHECK_DUTY_TIME" size="20" maxlength="20" class="BigInput" value="<?=$CHECK_DUTY_TIME?>" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})"/>
      </td>
    </tr>
    <tr>
    	<td nowrap class="TableData"> <?=_("缺岗人说明时间：")?></td>
      <td class="TableData">
        <input type="text" name="RECORD_TIME" size="20" maxlength="20" class="BigInput" value="<?=$RECORD_TIME?>" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})"/>
      </td>
    </tr>
    <tr>
    	<td nowrap class="TableData"> <?=_("查岗人说明：")?></td>
      <td class="TableData">
				<textarea name="CHECK_DUTY_REMARK" rows="3" cols="50" class="BigInput"><?=$CHECK_DUTY_REMARK?></textarea>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("缺岗人说明：")?></td>
      <td class="TableData">
        <textarea name="EXPLANATION" rows="3" cols="50" class="BigInput"><?=$EXPLANATION?></textarea>
      </td>
    </tr>
    <tr align="center" class="TableControl">
      <td colspan=4 nowrap>
      	<input type="hidden" value="<?=$ASK_DUTY_ID?>" name="ASK_DUTY_ID">
        <input type="submit" value="<?=_("保存")?>" class="BigButton">
        <input type="button" value="<?=_("返回")?>" class="BigButton" onClick="location='index1.php?start=<?=$start?>'">
      </td>
    </tr>
  </table>
</form>

</body>
</html>