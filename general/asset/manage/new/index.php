<?
include_once("inc/auth.inc.php");
include_once("../check_cfg.php");
include_once("inc/utility_org.php");
include_once("inc/utility_field.php");
include_once("inc/utility_all.php");

//---------------- 检查折旧相关参数是否配置好 ----------------------------
check_assetcfg();

$CUR_DATE=date("Y-m-d",time());

//2011-06-07 LP jQuery查询固定资产ID唯一
$uni_search = $_POST['uni_search'];
$uni_cptl_no = $_POST['uni_cptl_no'];
$uni_cptl_no = td_iconv($uni_cptl_no, "utf-8", MYOA_CHARSET);

if($uni_search=="search") {
	$query1="select CPTL_NO from CP_CPTL_INFO where CPTL_NO = '$uni_cptl_no'";
  $cursor1= exequery(TD::conn(),$query1);
  $NUM=mysql_num_rows($cursor1);
	ob_clean();
	if($NUM>0) {
		echo "1";
	}else{
		echo "0";
	}	
	exit;
}


$HTML_PAGE_TITLE = _("增加固定资产");
include_once("inc/header.inc.php");
?>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.5.1/jquery.min.js<?=$GZIP_POSTFIX?>"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>
<script Language="JavaScript">
$(document).ready(function () {
	$("input[name=CPTL_NO]").blur(function(){
			var cptl_no = $(this).val();
			if(cptl_no=="") {return;}
			$.ajax({
					type: "POST",
					url: "index.php",
					data: "uni_search=search&uni_cptl_no="+cptl_no,
					success: function(data1){
							if(data1=="1") {
								alert("<?=_("固定资产编号已存在！请重新命名！")?>");
								$("input[name=CPTL_NO]").val("").focus();
								return;
							}
					}
			});
});

});

function CheckForm()
{
	 if(document.form1.CPTL_NO.value=="")
   { alert("<?=_("资产编号不能为空！")?>");
     document.form1.CPTL_NO.focus();
     return (false);
   }
   if(document.form1.CPTL_NAME.value=="")
   { alert("<?=_("资产名称不能为空！")?>");
     document.form1.CPTL_NAME.focus();
     return (false);
   }
   if(document.form1.DEPT_ID.value=="")
   { alert("<?=_("所属部门不能为空！")?>");
     document.form1.DEPT_ID.focus();
     return (false);
   }
   if(document.form1.CPTL_VAL.value=="")
   { alert("<?=_("资产原值不能为空！")?>");
     document.form1.CPTL_VAL.focus();
     return (false);
   }
   if(document.form1.CPTL_BAL.value==""&&document.form1.CPTL_KIND.value=="01")
   { alert("<?=_("残值率不能为空！")?>");
     document.form1.CPTL_BAL.focus();
     return (false);
   }
   if(document.form1.DPCT_YY.value==""&&document.form1.CPTL_KIND.value=="01")
   { alert("<?=_("折旧年限不能为空！")?>");
     document.form1.DPCT_YY.focus();
     return (false);
   }
   if(document.form1.SUM_DPCT.value==""&&document.form1.CPTL_KIND.value=="01")
   { alert("<?=_("累计折旧不能为空！")?>");
     document.form1.SUM_DPCT.focus();
     return (false);
   }
   if(document.form1.MON_DPCT.value==""&&document.form1.CPTL_KIND.value=="01")
   { alert("<?=_("月折旧额不能为空！")?>");
     document.form1.MON_DPCT.focus();
     return (false);
   }
   if(document.form1.CPTL_KIND.value=="")
   { alert("<?=_("资产性质不能为空！")?>");
     document.form1.CPTL_KIND.focus();
     return (false);
   }
   if(document.form1.PRCS_ID.value=="")
   { alert("<?=_("增加类型不能为空！")?>");
     document.form1.PRCS_ID.focus();
     return (false);
   }
   if(document.form1.FINISH_FLAG.value==""&&document.form1.CPTL_KIND.value=="01")
   { alert("<?=_("折旧不能为空！")?>");
     document.form1.FINISH_FLAG.focus();
     return (false);
   }
   if(document.form1.TO_NAME.value=="")
   { alert("<?=_("保管人不能为空！")?>");
     document.form1.TO_NAME.focus();
     return (false);
   }
   
   return true;
}
function sendForm()
{
   if(CheckForm())
      document.form1.submit();
}

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
      alert("<?=_("数值不能小于0")?>");
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

//（资产原值-残值-累计折旧）/（折旧年限*12）---残值
//（资产原值-累计折旧）*（1-残值率/100）/（折旧年限*12）---残值率
function cal_mon_dpct()
{
   if(form1.CPTL_VAL.value=="")
   {
      alert("<?=_("资产原值不能为空")?>");
      document.form1.CPTL_VAL.focus();
      return;
   }
   if(form1.CPTL_BAL.value=="")
   {
      alert("<?=_("残值率不能为空")?>");
      document.form1.CPTL_BAL.focus();
      return;
   }
   if(form1.DPCT_YY.value=="")
   {
      alert("<?=_("折旧年限不能为空")?>");
      document.form1.DPCT_YY.focus();
      return;
   }
   if(form1.SUM_DPCT.value=="")
   {
      form1.SUM_DPCT.value="0.00";
      
   }
   
   cptl_val=ForDight(parseFloat(form1.CPTL_VAL.value),2);
   sum_dpct=ForDight(parseFloat(form1.SUM_DPCT.value),2);
   cptl_bal=ForDight(parseFloat(form1.CPTL_BAL.value),2);
   dpct_yy=ForDight(parseFloat(form1.DPCT_YY.value),2);
   if(cptl_val<0)
   {
      alert("<?=_("资产原值不能小于0")?>");
      document.form1.CPTL_VAL.focus();
      return;
   }
   if(sum_dpct<0)
   {
      alert("<?=_("累计折旧不能小于0")?>");
      document.form1.SUM_DPCT.focus();
      return;
   }
   if(cptl_bal<0)
   {
      alert("<?=_("残值率不能小于0")?>");
      document.form1.CPTL_BAL.focus();
      return;
   }
   if(dpct_yy<0)
   {
      alert("<?=_("折旧年限不能小于0")?>");
      document.form1.DPCT_YY.focus();
      return;
   }
<?
 $query = "SELECT * from CP_ASSETCFG";
 $cursor= exequery(TD::conn(),$query);
 if($ROW=mysql_fetch_array($cursor))
    $BAL_SORT=$ROW["BAL_SORT"];
 if($BAL_SORT=="01")
 {
?>
    if(cptl_val<sum_dpct+cptl_bal)
    {
       alert("<?=_("残值和累计折旧之和不能大于资产原值")?>\"");
       return;
    }
    mon_dpct=(cptl_val-sum_dpct-cptl_bal)/(dpct_yy*12);
<?
 }
 else if($BAL_SORT=="02")
 {
?>
    if(cptl_val<sum_dpct)
    {
       alert("<?=_("累计折旧不能大于资产原值")?>");
       return;
    }
    if(cptl_bal>100)
    {
       alert("<?=_("残值率不能大于100%")?>");
       return;
    }
    mon_dpct=(cptl_val-sum_dpct)*(1-cptl_bal/100)/(dpct_yy*12);
<?
 }
?>
   form1.MON_DPCT.value=ForDight(mon_dpct,2);
 
   check_value(form1.MON_DPCT);
}

</script>


<body class="bodycolor">

<table border="0" width="90%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_new.gif" align="absmiddle"><span class="big3"> <?=_("增加固定资产")?></span>
    </td>
  </tr>
</table>
<form enctype="multipart/form-data" action="submit.php"  method="post" name="form1">
  <table class="TableBlock" width="60%" align="center">
    <tr>
      <td nowrap class="TableData" width="100"> <?=_("资产编号：")?></td>
      <td class="TableData"> 
        <input type="text" name="CPTL_NO" size="30" maxlength="100" class="BigInput" value=""><span class="red">(*)</span>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData" width="100"> <?=_("资产名称：")?></td>
      <td class="TableData"> 
        <input type="text" name="CPTL_NAME" size="30" maxlength="200" class="BigInput" value=""><span class="red">(*)</span>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData" width="100"> <?=_("资产类别：")?></td>
      <td class="TableData"> 
      <select name="TYPE_ID" class="BigSelect">
        <option value="0"></option>
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
        <option value="0"></option>
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
        <option value="01" selected><?=_("资产")?></option>
        <option value="02"><?=_("费用")?></option>
      </select>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData" width="100"> <?=_("增加类型：")?></td>
      <td class="TableData"> 
      <select name="PRCS_ID" class="BigSelect">
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
      <td nowrap class="TableData" width="100"> <?=_("资产原值：")?></td>
      <td class="TableData"> 
        <input type="text" name="CPTL_VAL" size="20" maxlength="23" class="BigInput" value="" onkeypress="check_input()" onblur="check_value(this)" style="text-align:right;"> <?=_("数字")?><span class="red">(*)</span>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData" width="100"> <?if($BAL_SORT=="01") echo _("残值："); else echo _("残值率：");?></td>
      <td class="TableData"> 
        <input type="text" name="CPTL_BAL" size="20" maxlength="23" class="BigInput" value="" onkeypress="check_input()" onblur="check_value(this)" style="text-align:right;"> <?if($BAL_SORT=="01") echo _("数字"); else echo "%";?><span class="red">(*)</span>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData" width="100"> <?=_("折旧年限：")?></td>
      <td class="TableData"> 
        <input type="text" name="DPCT_YY" size="20" maxlength="13" class="BigInput" value="" onkeypress="check_input()" onblur="check_value(this)" style="text-align:right;"> <?=_("数字")?><span class="red">(*)</span>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData" width="100"> <?=_("累计折旧：")?></td>
      <td class="TableData"> 
        <input type="text"  name="SUM_DPCT" size="20" maxlength="23" class="BigInput" value="0.00" onkeypress="check_input()" onblur="check_value(this)" style="text-align:right;"> <?=_("数字")?>
        <?=_("为空表示新资产")?>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData" width="100"> <?=_("月折旧额：")?></td>
      <td class="TableData"> 
        <input type="text" name="MON_DPCT" size="20" maxlength="23" class="BigStatic" value="" style="text-align:right;" readonly>&nbsp<span class="red">(*)</span>
        <input type="button" name="MON_DPCT_CAL"  class="SmallButton" onclick="cal_mon_dpct()" value="<?=_("计算")?>">
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData" width="100"> <?=_("折旧：")?></td>
      <td class="TableData"> 
        <input type="radio" name="FINISH_FLAG" id="FINISH_FLAG1" value="1"><label for="FINISH_FLAG1"><?=_("提足")?></label>
        <input type="radio" name="FINISH_FLAG" id="FINISH_FLAG2" value="0" checked ><label for="FINISH_FLAG2"><?=_("未提足")?></label>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData" width="100"> <?=_("启用日期：")?></td>
      <td class="TableData"> 
        <input type="text" name="FROM_YYMM" id="FROM_YYMM" size="12" maxlength="10" class="BigInput" value="<?=$CUR_DATE?>" onClick="WdatePicker()">
        <input type="button" value="<?=_("清空")?>" class="SmallButton" title="<?=_("清空")?>" onclick="document.getElementById('FROM_YYMM').value = ''"> <?=_("为空表示未启用")?>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData" width="100"> <?=_("保管人：")?></td>
      <td class="TableData"> 
		<input type="hidden" name="TO_ID" value="">
		<input type="text" name="TO_NAME" size="20" class="BigInput" maxlength="20"  value="">&nbsp;
		<input type="button" value="<?=_("选择")?>" class="SmallButton" onClick="SelectUserSingle('108','','TO_ID','TO_NAME')" title="<?=_("选择")?>" name="button">&nbsp;
		<input type="button" value="<?=_("清空")?>" class="SmallButton" onClick="ClearUser()" title="<?=_("清空")?>" name="button"><span class="red">(*)</span>
      </td>
    </tr>
		<tr>
      <td nowrap class="TableData" width="100"> <?=_("固定资产照片：")?></td>
      <td class="TableData"> 
				 <input type="file" name="PHOTO" size="40" class="BigInput" title="<?=_("选择附件文件")?>">
				 <br /><?=_("照片文件要求是gif,jpg,png格式")?>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData" width="100"> <?=_("备注：")?></td>
      <td class="TableData"> 
        <textarea name="REMARK" cols="80" rows="6" class="BigInput" wrap="yes"></textarea>
      </td>
    </tr>
    <tr>
      <td class="TableData" colspan="4">
<?
  echo get_field_table(get_field_html("CP_CPTL_INFO")); 
?>
      </td>
    </tr>        
    <tr align="center" class="TableControl">
      <td colspan="2" nowrap>
        <input type="button" value="<?=_("增加")?>" class="BigButton" onclick="sendForm();">&nbsp;&nbsp;
        <input type="button" value="<?=_("重填")?>" class="BigButton" onclick="location='index.php'">&nbsp;&nbsp;
      </td>
    </tr>

  </table>
 </form>
</body>
</html>