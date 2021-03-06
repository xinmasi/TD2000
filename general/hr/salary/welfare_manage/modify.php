<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");

$HTML_PAGE_TITLE = _("编辑员工福利信息");
include_once("inc/header.inc.php");
?>

<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>

<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>
<script Language="JavaScript">
function CheckForm()
{
	if(document.form1.STAFF_NAME.value=="")
   { 
      alert("<?=_("请选择单位员工！")?>");
      return (false);
   }
  if(document.form1.WELFARE_ITEM.value=="")
   { 
      alert("<?=_("福利项目不能为空！")?>");
      return (false);
   }
  if(document.form1.WELFARE_MONTH.value=="")
   { 
      alert("<?=_("工资月份不能为空！")?>");
      return (false);
   }
   var pattern = /^\d+(?=\.{0,1}\d+$|$)/;
   if(!pattern.exec(document.form1.WELFARE_PAYMENT.value))
   {
      alert("<?=_("福利金额应为大于等于0的数字！")?>");
      return (false);
   }
 return (true);
}
</script>	


<body class="bodycolor">
<?
$CUR_DATE=date("Y-m-d",time());
$CUR_MONTH=substr(date("Y-m-d",time()),0,-3);

$query = "SELECT * from  HR_WELFARE_MANAGE where WELFARE_ID='$WELFARE_ID'";
$cursor= exequery(TD::conn(),$query);
$WELFARE_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
   $WELFARE_COUNT++;

   $WELFARE_ID=$ROW["WELFARE_ID"];
   $USER_ID=$ROW["USER_ID"];
   $DEPT_ID=$ROW["DEPT_ID"];
   $STAFF_NAME=$ROW["STAFF_NAME"];
   $WELFARE_MONTH=$ROW["WELFARE_MONTH"];
   $PAYMENT_DATE=$ROW["PAYMENT_DATE"];
   $WELFARE_ITEM=$ROW["WELFARE_ITEM"];
   $WELFARE_PAYMENT=$ROW["WELFARE_PAYMENT"];
   $FREE_GIFT=$ROW["FREE_GIFT"];
   $TAX_AFFAIRS=$ROW["TAX_AFFAIRS"];
   $REMARK=$ROW["REMARK"];
   $ADD_TIME=$ROW["ADD_TIME"]; 
	 
	 $STAFF_NAME1=substr(GetUserNameById($STAFF_NAME),0,-1);
}
?>
<table border="0" width="90%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_new.gif" align="absmiddle"><span class="big3"> <?=_("编辑员工福利信息")?></span>&nbsp;&nbsp;
    </td>
  </tr>
</table>
<br>
<form enctype="multipart/form-data" action="update.php"  method="post" name="form1" onsubmit="return CheckForm();">
<table class="TableBlock" width="50%" align="center">
	<tr>
		<td nowrap class="TableData"><?=_("单位员工：")?></td>
      <td class="TableData">
        <input type="text" name="STAFF_NAME1" size="15" class="BigStatic" readonly value="<?=$STAFF_NAME1?>">&nbsp;
        <input type="hidden" name="STAFF_NAME" value="<?=$STAFF_NAME?>">
        <a href="javascript:;" class="orgAdd" onClick="SelectUserSingle('27','','STAFF_NAME', 'STAFF_NAME1')"><?=_("选择")?></a>
      </td>
 	  <td nowrap class="TableData"><?=_("福利项目：")?></td>
      <td class="TableData">
      	<select name="WELFARE_ITEM" style="background: white;" title="<?=_("福利项目可在“人力资源设置”->“HRMS代码设置”模块设置。")?>">
          <option value=""><?=_("福利项目")?>&nbsp&nbsp&nbsp&nbsp&nbsp;</option>
          <?=hrms_code_list("HR_WELFARE_MANAGE",$WELFARE_ITEM)?>
        </select> 
    </td>
	</tr>
	<tr>
    <td nowrap class="TableData"><?=_("发放日期：")?></td>
     <td class="TableData">
       <input type="text" name="PAYMENT_DATE" size="15" maxlength="10" class="BigInput" value="<?=$PAYMENT_DATE=="0000-00-00"?"":$PAYMENT_DATE;?>" onClick="WdatePicker()"/>
     </td>
    <td nowrap class="TableData"><?=_("工资月份：")?></td>
     <td class="TableData">
       <input name="WELFARE_MONTH" size="15" class="BigInput" value="<?=$WELFARE_MONTH?>" onClick="WdatePicker({dateFmt:'yyyy-MM'})">
     </td>
  </tr>
	<tr>
    <td nowrap class="TableData"><?=_("福利金额：")?></td>
     <td class="TableData">
       <INPUT type="text"name="WELFARE_PAYMENT" class=BigInput size="15" value="<?=$WELFARE_PAYMENT?>">&nbsp;<?=_("元")?>
     </td>
    <td nowrap class="TableData"><?=_("是否纳税：")?></td>
     <td class="TableData">
       <select name="TAX_AFFAIRS" style="background: white;" title="">
          <option value="" ><?=_("是否纳税")?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>
          <option value="0" <?if($TAX_AFFAIRS=="0")echo "selected";?>><?=_("否")?></option>
          <option value="1" <?if($TAX_AFFAIRS=="1")echo "selected";?>><?=_("是")?></option>
        </select>
     </td>
  </tr>
	<tr>
    <td nowrap class="TableData"><?=_("发放物品：")?></td>
     <td class="TableData" colspan=3>
       <textarea name="FREE_GIFT" cols="70" rows="5" class="BigInput" value=""><?=$FREE_GIFT?></textarea>
     </td>
  </tr>
  <tr>
    <td nowrap class="TableData"><?=_("备注：")?></td>
     <td class="TableData" colspan=3>
       <textarea name="REMARK" cols="70" rows="6" class="BigInput" value=""><?=$REMARK?></textarea>
     </td>
  </tr> 
	<tr align="center" class="TableControl">
    <td colspan=4 nowrap>
    	 <input type="hidden"  name="WELFARE_ID" value="<?=$WELFARE_ID?>">
       <input type="submit" value="<?=_("保存")?>" class="BigButton">
       <input type="button" value="<?=_("返回")?>" class="BigButton" onClick="location='index1.php?start=<?=$start?>'">
     </td>
  </tr>
</table>
</form>
</body>	
</html>