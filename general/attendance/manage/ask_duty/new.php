<?
include_once("inc/auth.inc.php");
include_once("inc/check_type.php");
include_once("inc/utility_org.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("新增查岗信息");
include_once("inc/header.inc.php");
?>
<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/utility.js"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>
<script Language="JavaScript">
	function CheckForm()
{
   if(document.form1.CHECK_USER_ID.value=="")
   { 
      alert("<?=_("缺岗人不能为空！")?>");
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
   
 return (true);
}
</script>

<body class="bodycolor">

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_new.gif" align="absmiddle"><span class="big3"> <?=_("新增查岗信息")?></span>&nbsp;&nbsp;
    </td>
  </tr>
</table>
<br>
<form action="add.php"  method="post" name="form1" onsubmit="return CheckForm();">
<table class="TableBlock" width="50%" align="center">
   <tr>
      <td nowrap class="TableData"><?=_("缺岗人：")?></td>
      <td class="TableData">
      <textarea cols=35 name="CHECK_USER_NAME" rows=2 class="BigStatic" readonly value="<?=substr(GetUserNameById($CHECK_USER_ID),0,-1)?>"></textarea>&nbsp;
        <input type="hidden" name="CHECK_USER_ID" value="<?=$CHECK_USER_ID?>">
        <a href="javascript:;" class="orgAdd" onClick="SelectUser('7','','CHECK_USER_ID', 'CHECK_USER_NAME')"><?=_("选择")?></a>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"><?=_("查岗人：")?></td>
      <td class="TableData">
      <textarea cols=35 name="CHECK_MANAGER_NAME" rows=2 class="BigStatic" readonly value="<?=substr(GetUserNameById($CHECK_DUTY_MANAGER),0,-1)?>"></textarea>&nbsp;
        <input type="hidden" name="CHECK_DUTY_MANAGER" value="<?=$CHECK_DUTY_MANAGER?>">
        <a href="javascript:;" class="orgAdd" onClick="SelectUser('7','','CHECK_DUTY_MANAGER', 'CHECK_MANAGER_NAME')"><?=_("选择")?></a>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData"> <?=_("查岗时间：")?></td>
      <td class="TableData">
        <input type="text" name="CHECK_DUTY_TIME" size="20" maxlength="20" class="BigInput" value="" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})"/>
      </td>
    </tr>
    
    <tr>
      <td class="TableData" > <?=_("查岗人说明：")?>
      </td>
      <td class="TableData">
      	<textarea name="CHECK_DUTY_REMARK" maxlength="150" rows="3" cols="50" class="BigInput"></textarea>
      </td>
    </tr>     
    <tr align="center" class="TableControl">
      <td colspan=4 nowrap>
        <input type="submit" value="<?=_("保存")?>" class="BigButton"">
      </td>
    </tr>
  </table>
</form>
</body>
</html>