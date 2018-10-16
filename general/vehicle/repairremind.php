<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
$SYS_PARA_ARRAY=get_sys_para("SMS_REMIND");
$PARA_VALUE=$SYS_PARA_ARRAY["SMS_REMIND"];
$SMS_REMIND1=substr($PARA_VALUE,0,strpos($PARA_VALUE,"|"));
$SMS2_REMIND1=substr($PARA_VALUE,strpos($PARA_VALUE,"|")+1);

$HTML_PAGE_TITLE = _("添加维护提醒");
include_once("inc/header.inc.php");

if(!find_id($SMS_REMIND1,48)&&!find_id($SMS2_REMIND1,48))
{
Message(_("错误"), _("未设置该模块为提醒"));
?>
  <center><input type="button" value="<?=_("关闭")?>" class="BigButton" onClick="window.close();"></center>
<?
exit;
}


?>
<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>
<script Language="JavaScript">
function CheckForm()
{
   if(document.form1.V_ID.value=="")
   { alert("<?=_("请指车辆！")?>");
     return (false);
   }

   if(document.form1.VU_START.value=="")
   { alert("<?=_("起始时间不能为空！")?>");
     return (false);
   }

   if(document.form1.VU_END.value=="")
   { alert("<?=_("结束时间不能为空！")?>");
     return (false);
   }

   if(document.form1.VU_START.value==document.form1.VU_END.value)
   {  alert("<?=_("开始时间与结束时间不能相等！")?>");
     return (false);
   }

   if(document.form1.VU_OPERATOR.value=="")
   { alert("<?=_("请指定调度人员！")?>");
     return (false);
   }

   form1.submit();
}
</script>

<body class="bodycolor">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_new.gif" HEIGHT="20"><span class="big3"><?=_("添加维护提醒")?></span>
    </td>
  </tr>
</table>

<form enctype="multipart/form-data" action="remind.php" method="post" name="form1">
<table class="TableBlock" align="center">
    </tr>
    <tr>
      <td nowrap class="TableContent"> <?=_("维护时间：")?></td>
      <td class="TableData">
        <input type="text" name="VM_REQUEST_DATE" size="20" maxlength="19" class="BigInput" value="<?=$VM_REQUEST_DATE?>" onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})">
       
      </td>
    </tr>
    <tr>
      <td nowrap class="TableContent"> <?=_("维护类型：")?></td>
      <td class="TableData">
        <SELECT name="VM_TYPE"  class="BigSelect">
        	<?=code_list("VEHICLE_REPAIR_TYPE",$VM_TYPE)?>
        </SELECT>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableContent" > <?=_("维护原因：")?></td>
      <td class="TableData" colspan="3">
        <textarea name="VM_REASON" class="BigInput" cols="45" rows="3"><?=$VM_REASON?></textarea>
      </td>
    </tr>
    <tr>
   <td nowrap class="TableContent" width="110"> <?=_("提醒调度人员：")?></td>
      <td class="TableData" colspan="3">
        <select name="VU_OPERATOR" class="BigSelect">
<?
$query = "SELECT OPERATOR_NAME from VEHICLE_OPERATOR where OPERATOR_ID=1";
$cursor1= exequery(TD::conn(),$query);
if($ROW1=mysql_fetch_array($cursor1))
{
   $OPERATOR_NAME=$ROW1["OPERATOR_NAME"];
   $query = "SELECT USER_ID,USER_NAME from USER where find_in_set(USER_ID,'$OPERATOR_NAME') order by USER_NO,USER_NAME";
   $cursor= exequery(TD::conn(),$query);
   while($ROW=mysql_fetch_array($cursor))
   {
      $USER_ID=$ROW["USER_ID"];
      $USER_NAME=$ROW["USER_NAME"];
?>
          <option value="<?=$USER_ID?>" <? if($VU_OPERATOR==$USER_ID) echo "selected";?>><?=$USER_NAME?></option>
<?
   }
}
?>
        </select>
      </td> 	
   </tr>
    <tr>
    <td nowrap class="TableContent" width="80"><?=_("提醒设置：")?> </td>
    <td class="TableData" colspan="3">
    	 <input type="checkbox" name="SMS_REMIND1" id="SMS_REMIND1"<?if(find_id($SMS_REMIND,"48")) echo " checked";?>><label for="SMS_REMIND1"><?=_("发送事务提醒消息 ")?></label>&nbsp;
<?
$query = "select * from SMS2_PRIV";
$cursor=exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
   $TYPE_PRIV=$ROW["TYPE_PRIV"];

if(find_id($TYPE_PRIV,48))
{
?>
       <input type="checkbox" name="SMS2_REMIND1" id="SMS2_REMIND1"<?if(find_id($SMS2_REMIND,"48")) echo " checked";?>><label for="SMS2_REMIND1"><?=_("发送手机短信提醒")?></label>
<?
}
?>
  </td>
  </tr>
    <tr>
    	<td nowrap class="TableContent" > <?=_("提醒设置：")?></td>
<?
		$MSG = sprintf(_("提前%s天%s小时%s分钟提醒"),"<input type='text' name='BEFORE_DAY' size='2' class='BigInput' value=''>","<input type='text' name='BEFORE_HOUR' size='2' class='BigInput' value=''>","<input type='text' name='BEFORE_MIN' size='2' class='BigInput' value=''>");
?>
      <td class="TableData" colspan="3"><?=$MSG?>
      </td>
    </tr>
    <tr class="TableControl">
      <td nowrap colspan="4" align="center">
      	<input type="hidden" name="V_ID" value="<?=$V_ID?>">
        <input type="submit" value="<?=_("保存")?>" class="BigButton">&nbsp;&nbsp;
        <input type="reset" value="<?=_("重填")?>" class="BigButton">&nbsp;&nbsp;
      </td>
    </tr>
    </table>
</form>

</body>
</html>