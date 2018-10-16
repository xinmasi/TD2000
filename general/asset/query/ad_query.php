<?
include_once("inc/auth.inc.php");
include_once("inc/utility_org.php");
include_once("inc/utility_field.php");

$HTML_PAGE_TITLE = _("固定资产查询");
include_once("inc/header.inc.php");
?>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.5.1/jquery.min.js<?=$GZIP_POSTFIX?>"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>
<script Language="JavaScript">
//verify for netscape/mozilla
var isNS4 = (navigator.appName=="Netscape")?1:0;

function check_input()
{
  if(!isNS4)
  {
  	 if(event.keyCode < 45 || event.keyCode > 57)
  	    event.returnValue = false;
  }
  else
  {
  	 if(event.which < 45 || event.which > 57)
  	    return false;
  }  
}

function  ForDight(Dight,How)  
{  
           Dight  =  Math.round  (Dight*Math.pow(10,How))/Math.pow(10,How);  
           return  Dight;  
} 

function check_value(obj)
{
   
   if(obj.value=="")
      return;
   val=parseFloat(obj.value);
   if(isNaN(val))
   {
      alert("<?=_("非法的数字")?>");
      obj.focus();
      return;
   }
   if(val<0)
   {
      alert("<?=_("数值不能小于")?>0");
      obj.focus();
      return;
   }
   
   obj.value=ForDight(val,2);

   value_array=obj.value.split(".");
   if(value_array.length==1)
   {
   	  obj.value=value_array[0]+".00";
   	  return;
   }
   else if(value_array.length==2)
   {
   	  if(value_array[1].length==0)
   	    obj.value=value_array[0]+".00";
   	  else if(value_array[1].length==1)
   	    obj.value=value_array[0]+"."+value_array[1]+"0";
   	  else if(value_array[1].length>=2)
   	    obj.value=value_array[0]+"."+value_array[1].substr(0,2);
   }
}

</script>

<?
 $query = "SELECT * from CP_ASSETCFG";
 $cursor= exequery(TD::conn(),$query);
 if($ROW=mysql_fetch_array($cursor))
    $BAL_SORT=$ROW["BAL_SORT"];
?>
<body class="bodycolor" onload="form1.CPTL_NO.focus();">

<table border="0" width="90%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/asset.gif"  width="24" height="24"><span class="big3"> <?=_("固定资产查询")?></span>
    <input type="button" value="<?=_("资产报表")?>" class="BigButton" onclick="window.open('report.php','','height=500,width=600,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,top=100,left=210,resizable=yes')">
    </td>
  </tr>
</table>

<form enctype="multipart/form-data" action="index.php"  method="post" name="form1">
<table class="TableBlock"  width="90%" align="center">
    <tr>
      <td nowrap class="TableData" width="100"> <?=_("资产编号：")?></td>
      <td class="TableData"> 
        <input type="text" name="CPTL_NO" size="20" maxlength="100" class="BigInput" value="">
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData" width="100"> <?=_("资产名称：")?></td>
      <td class="TableData"> 
        <input type="text" name="CPTL_NAME" size="30" maxlength="200" class="BigInput" value="">
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData" width="100"> <?=_("资产类别：")?></td>
      <td class="TableData"> 
      <select name="TYPE_ID" class="BigSelect">
        <option value=""><?=_("所有类别")?></option>
        <option value="0"><?=_("未指定类别")?></option>
<?
 $query = "SELECT * from CP_ASSET_TYPE order by TYPE_NO";
 $cursor= exequery(TD::conn(),$query);
 while($ROW=mysql_fetch_array($cursor))
 {
    $TYPE_ID=$ROW["TYPE_ID"];
    $TYPE_NAME=$ROW["TYPE_NAME"];
?>
        <option value="<?=$TYPE_ID?>"><?=$TYPE_NAME?></option>
<?
 }
?>
      </select>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData" width="100"> <?=_("所属部门：")?></td>
      <td class="TableData">
      <select name="DEPT_ID" class="BigSelect">
        <option value=""><?=_("所有部门")?></option>
        <option value="0"><?=_("未指定部门")?></option>
<?
      echo my_dept_tree(0,$DEPT_ID,0);
?>
      </select>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData" width="100"> <?=_("资产性质：")?></td>
      <td class="TableData"> 
      <select name="CPTL_KIND" class="BigSelect">
        <option value="" selected></option>
        <option value="01"><?=_("资产")?></option>
        <option value="02"><?=_("费用")?></option>
      </select>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData" width="100"> <?=_("增加类型：")?></td>
      <td class="TableData"> 
      <select name="PRCS_ID" class="BigSelect">
        <option value=""></option>
<?
 $query = "SELECT * from CP_PRCS_PROP where left(PRCS_CLASS,1)='A' order by PRCS_CLASS";

 $cursor= exequery(TD::conn(),$query);
 while($ROW=mysql_fetch_array($cursor))
 {
    $PRCS_ID=$ROW["PRCS_ID"];
    $PRCS_LONG_DESC=$ROW["PRCS_LONG_DESC"];
?>
        <option value="<?=$PRCS_ID?>"><?=$PRCS_LONG_DESC?></option>
<?
 }
?>
      </select>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData" width="100"> <?=_("减少类型：")?></td>
      <td class="TableData"> 
      <select name="DCR_PRCS_ID" class="BigSelect">
        <option value=""></option>
<?
 $query = "SELECT * from CP_PRCS_PROP where left(PRCS_CLASS,1)='D' order by PRCS_CLASS";

 $cursor= exequery(TD::conn(),$query);
 while($ROW=mysql_fetch_array($cursor))
 {
    $PRCS_ID=$ROW["PRCS_ID"];
    $PRCS_LONG_DESC=$ROW["PRCS_LONG_DESC"];
?>
        <option value="<?=$PRCS_ID?>"><?=$PRCS_LONG_DESC?></option>
<?
 }
?>
      </select>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData" width="100"> <?=_("折旧：")?></td>
      <td class="TableData"> 
        <select name="FINISH_FLAG" class="BigSelect">
          <option value="" selected></option>
          <option value="1"><?=_("提足")?></option>
          <option value="0"><?=_("未提足")?></option>
      </select>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData" width="100"> <?=_("资产原值：")?></td>
      <td class="TableData"> 
        <?=_("大于等于：")?> <input type="text" name="CPTL_VAL_MIN" size="10" maxlength="23" class="BigInput" value="" onkeypress="check_input()" onblur="check_value(this)" style="text-align:right;"> <?if($BAL_SORT=="02") echo "%";?>&nbsp;&nbsp;&nbsp;
        <?=_("小于等于：")?> <input type="text" name="CPTL_VAL_MAX" size="10" maxlength="23" class="BigInput" value="" onkeypress="check_input()" onblur="check_value(this)" style="text-align:right;"> <?if($BAL_SORT=="02") echo "%";?>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData" width="100"> <?if($BAL_SORT=="01") echo _("残值："); else echo _("残值率：");?></td>
      <td class="TableData"> 
        <?=_("大于等于：")?> <input type="text" name="CPTL_BAL_MIN" size="10" maxlength="23" class="BigInput" value="" onkeypress="check_input()" onblur="check_value(this)" style="text-align:right;"> <?if($BAL_SORT=="02") echo "%";?>&nbsp;&nbsp;&nbsp;
        <?=_("小于等于：")?> <input type="text" name="CPTL_BAL_MAX" size="10" maxlength="23" class="BigInput" value="" onkeypress="check_input()" onblur="check_value(this)" style="text-align:right;"> <?if($BAL_SORT=="02") echo "%";?>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData" width="100"> <?=_("增加日期：")?></td>
      <td class="TableData"> 
        <?=_("开始日期：")?><input type="text" name="CREATE_DATE_MIN" size="12" maxlength="10" class="BigInput" value="" onClick="WdatePicker()">
       
        <?=_("结束日期：")?><input type="text" name="CREATE_DATE_MAX" size="12" maxlength="10" class="BigInput" value="" onClick="WdatePicker()">
      
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData" width="100"> <?=_("减少日期：")?></td>
      <td class="TableData"> 
        <?=_("开始日期：")?><input type="text" name="DCR_DATE_MIN" size="12" maxlength="10" class="BigInput" value="" onClick="WdatePicker()">
       
        <?=_("结束日期：")?><input type="text" name="DCR_DATE_MAX" size="12" maxlength="10" class="BigInput" value="" onClick="WdatePicker()">
       
    </tr>
    <tr>
      <td nowrap class="TableData" width="100"> <?=_("启用日期：")?></td>
      <td class="TableData"> 
        <?=_("开始日期：")?><input type="text" name="FROM_YYMM_MIN" size="12" maxlength="10" class="BigInput" value="" onClick="WdatePicker()">
       
        <?=_("结束日期：")?><input type="text" name="FROM_YYMM_MAX" size="12" maxlength="10" class="BigInput" value="" onClick="WdatePicker()">
        
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData" width="100"> <?=_("保管人：")?></td>
      <td class="TableData"> 
		<input type="hidden" name="TO_ID" value="<?=$KEEP_USER?>">
		<input type="text" name="TO_NAME" size="20" class="BigInput" maxlength="20"  value="<?=$KEEPER?>">&nbsp;
		<input type="button" value="<?=_("选择")?>" class="SmallButton" onClick="SelectUserSingle('108','','TO_ID','TO_NAME')" title="<?=_("选择")?>" name="button">&nbsp;
		<input type="button" value="<?=_("清空")?>" class="SmallButton" onClick="ClearUser()" title="<?=_("清空")?>" name="button">
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData" width="100"> <?=_("备注：")?></td>
      <td class="TableData"> 
        <textarea cols="35" name="REMARK" rows="2" class="BigInput" wrap="yes"></textarea>
      </td>
    </tr>
    </tr>
      <td class="TableData" colspan="4">
        <?=get_field_table(get_field_html("CP_CPTL_INFO","",1))?>
      </td>
    </tr>    
    <tr align="center" class="TableControl">
      <td colspan="2" nowrap>
        <input type="submit" value="<?=_("查询")?>" class="BigButton">&nbsp;&nbsp;
        <input type="button" value="<?=_("返回")?>" class="BigButton" onclick="location='index.php'">&nbsp;&nbsp;
      </td>
    </tr>
  </table>
</form>

</body>
</html>