<?
include_once("inc/auth.inc.php");
include_once("check_cfg.php");
include_once("inc/utility_org.php");
include_once("inc/utility_field.php");
include_once("inc/utility_file.php");

//---------------- 检查折旧相关参数是否配置好 ----------------------------
check_assetcfg();

 $query = "SELECT * from CP_DPCT_SUB where CPTL_ID='$CPTL_ID'";
 $cursor= exequery(TD::conn(),$query);
 if($ROW=mysql_fetch_array($cursor))
    $DPCT_FLAG=1;
 else
    $DPCT_FLAG=0;

$HTML_PAGE_TITLE = _("修改固定资产");
include_once("inc/header.inc.php");
?>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js<?=$GZIP_POSTFIX?>"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>
<script Language="JavaScript">
function CheckForm()
{
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
       alert("<?=_("残值和累计折旧之和不能大于资产原值")?>");
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
<?
$query="select * from CP_CPTL_INFO where CPTL_ID='$CPTL_ID'";
$cursor=exequery(TD::conn(),$query);
$CPTL_COUNT=0;
$DEPT_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
   $CPTL_COUNT++;
   if($CPTL_COUNT>200)
      break;
   
   $CPTL_ID=$ROW["CPTL_ID"];
   $CPTL_NO=$ROW["CPTL_NO"];
   $CPTL_NAME=$ROW["CPTL_NAME"];
   $TYPE_ID=$ROW["TYPE_ID"];
   $DEPT_ID=$ROW["DEPT_ID"];
   $CPTL_VAL=$ROW["CPTL_VAL"];
   $CPTL_BAL=$ROW["CPTL_BAL"];
   $DPCT_YY=$ROW["DPCT_YY"];
   $MON_DPCT=$ROW["MON_DPCT"];
   $SUM_DPCT=$ROW["SUM_DPCT"];
   $CPTL_KIND=$ROW["CPTL_KIND"];
   $PRCS_ID=$ROW["PRCS_ID"];
   $FINISH_FLAG=$ROW["FINISH_FLAG"];
   $CREATE_DATE=$ROW["CREATE_DATE"];
   $FROM_YYMM=$ROW["FROM_YYMM"];
   $KEEPER=$ROW["KEEPER"];
   $REMARK=$ROW["REMARK"];
	 $ATTACHMENT_ID=$ROW["ATTACHMENT_ID"];
   $ATTACHMENT_NAME=$ROW["ATTACHMENT_NAME"];

   $query_name = "SELECT USER_NAME from USER where USER_ID = '$KEEPER'";
   $cursor_name= exequery(TD::conn(),$query_name);
   if($ROW_NAME=mysql_fetch_array($cursor_name)){
      $USER_ID = $ROW_NAME["USER_NAME"] != ""?$KEEPER:"";
      $KEEPER = $ROW_NAME["USER_NAME"] != ""?$ROW_NAME["USER_NAME"]:$KEEPER;
   }
   
   if($CPTL_KIND=="01")
      $CPTL_KIND_DESC=_("资产");
   else if($CPTL_KIND=="02")
      $CPTL_KIND_DESC=_("费用");
      
   $query1="select * from CP_PRCS_PROP where PRCS_ID='$PRCS_ID'";
   $cursor1=exequery(TD::conn(),$query1);
   if($ROW1=mysql_fetch_array($cursor1))
      $PRCS_LONG_DESC=$ROW1["PRCS_LONG_DESC"];
   
   $query1="select * from DEPARTMENT where DEPT_ID='$DEPT_ID'";
   $cursor1= exequery(TD::conn(),$query1);
   if($ROW1=mysql_fetch_array($cursor1))
       $DEPT_NAME=$ROW1["DEPT_NAME"];
}
?>
<table border="0" width="90%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/asset.gif" align="absmiddle"><span class="big3"> <?=_("修改固定资产")?></span>
    </td>
  </tr>
</table>
<br>
<table class="TableBlock"  width="100%" align="center">
  <form enctype="multipart/form-data" action="update.php"  method="post" name="form1">
    <tr>
      <td nowrap class="TableData" width="100"> <?=_("资产编号：")?></td>
      <td class="TableData"><?=$CPTL_NO?></td>
			<td class="TableData" rowspan="6" width="280" align="center">
				<?
					 $MODULE=attach_sub_dir();
					 $ATTACHMENT_ID1=$ATTACHMENT_ID;
					 $YM=substr($ATTACHMENT_ID1,0,strpos($ATTACHMENT_ID1,"_"));
					 if($YM)
							$ATTACHMENT_ID1=substr($ATTACHMENT_ID1,strpos($ATTACHMENT_ID,"_")+1);
					 $ATTACHMENT_ID_ENCODED=attach_id_encode($ATTACHMENT_ID1,$ATTACHMENT_NAME); 
							
					 if($ATTACHMENT_NAME=="")
							echo _("暂无照片");
					 else
					 {
				?>
							 <a href="/inc/attach.php?MODULE=<?=$MODULE?>&YM=<?=$YM?>&ATTACHMENT_ID=<?=$ATTACHMENT_ID_ENCODED?>&ATTACHMENT_NAME=<?=urlencode($ATTACHMENT_NAME)?>&DIRECT_VIEW=1?>" title="<?=_("点击查看放大图片")?>" target="_blank"><img src="/inc/attach.php?MODULE=<?=$MODULE?>&YM=<?=$YM?>&ATTACHMENT_ID=<?=$ATTACHMENT_ID_ENCODED?>&ATTACHMENT_NAME=<?=urlencode($ATTACHMENT_NAME)?>"  width='250' border=1 alt="<?=_("文件名：")?><?=$ATTACHMENT_NAME?>"></a>	
				<?
				}
				?>	
			</td>
    </tr>
    <tr>
      <td nowrap class="TableData" width="100"> <?=_("资产名称：")?></td>
      <td class="TableData"> 
        <input type="text" name="CPTL_NAME" size="40" maxlength="200" class="BigInput" value="<?=$CPTL_NAME?>">
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData" width="100"> <?=_("资产类别：")?></td>
      <td class="TableData"> 
      <select name="TYPE_ID" class="BigSelect">
        <option value="0"<?if($TYPE_ID==0) echo " selected";?>></option>
<?
 $query = "SELECT * from CP_ASSET_TYPE order by TYPE_NO";
 $cursor= exequery(TD::conn(),$query);
 while($ROW=mysql_fetch_array($cursor))
 {
    $TYPE_ID1=$ROW["TYPE_ID"];
    $TYPE_NAME=$ROW["TYPE_NAME"];
?>
        <option value="<?=$TYPE_ID1?>"<?if($TYPE_ID==$TYPE_ID1) echo " selected";?>><?=$TYPE_NAME?></option>
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
        <option value="0"<?if($DEPT_ID==0) echo " selected";?>></option>
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
<?
if($DPCT_FLAG)
{
   if($CPTL_KIND=="01")
   {
?>
        <option value="01" selected"><?=_("资产")?></option>
<?
   }
   else if($CPTL_KIND=="02")
   {
?>
        <option value="02" selected"><?=_("费用")?></option>
<?
   }
}
else
{
?>
        <option value="01" <?if($CPTL_KIND=="01") echo "selected";?>><?=_("资产")?></option>
        <option value="02" <?if($CPTL_KIND=="02") echo "selected";?>><?=_("费用")?></option>
<?
}
?>
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
    $PRCS_ID1=$ROW["PRCS_ID"];
    $PRCS_LONG_DESC=$ROW["PRCS_LONG_DESC"];
?>
        <option value="<?=$PRCS_ID1?>"<?if($PRCS_ID1==$PRCS_ID) echo " selected";?>><?=$PRCS_LONG_DESC?></option>
<?
 }
?>
      </select>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData" width="100"> <?=_("资产原值：")?></td>
      <td class="TableData" colspan="2"> 
        <input type="text" name="CPTL_VAL" size="20" maxlength="23" value="<?=$CPTL_VAL?>" class="BigInput" onkeypress="check_input()" onblur="check_value(this)" style="text-align:right;"> <?=_("数字")?>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData" width="100"> <?if($BAL_SORT=="01") echo _("残值："); else echo _("残值率：");?></td>
      <td class="TableData" colspan="2"> 
        <input type="text" name="CPTL_BAL" size="20" maxlength="23" value="<?=$CPTL_BAL?>"  class="BigInput" onkeypress="check_input()" onblur="check_value(this)" style="text-align:right;"> <?if($BAL_SORT=="01") echo _("数字"); else echo "%";?>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData" width="100"> <?=_("折旧年限：")?></td>
      <td class="TableData" colspan="2"> 
        <input type="text" name="DPCT_YY" size="20" maxlength="13" value="<?=$DPCT_YY?>"  class="BigInput" onkeypress="check_input()" onblur="check_value(this)" style="text-align:right;"> <?=_("数字")?>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData" width="100"> <?=_("累计折旧：")?></td>
      <td class="TableData" colspan="2"> 
        <input type="text"  name="SUM_DPCT" size="20" maxlength="23" value="<?=$SUM_DPCT?>"  class="BigInput" onkeypress="check_input()" onblur="check_value(this)" style="text-align:right;"> <?=_("数字")?>
        <?=_("为空表示新资产")?>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData" width="100"> <?=_("月折旧额：")?></td>
      <td class="TableData" colspan="2"> 
        <input type="text" name="MON_DPCT" size="20" maxlength="23" class="BigStatic" value="<?=$MON_DPCT?>" style="text-align:right;" readonly>&nbsp
<?
if($DPCT_FLAG==0)
{
?>
        <input type="button" name="MON_DPCT_CAL"  class="SmallButton" onclick="cal_mon_dpct()" value="<?=_("计算")?>">
<?
}
?>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData" width="100"> <?=_("折旧：")?></td>
      <td class="TableData" colspan="2"> 
        <input type="radio" name="FINISH_FLAG" id="FINISH_FLAG1" value="1"<?if($FINISH_FLAG=="1") echo " checked";?>><label for="FINISH_FLAG1"><?=_("提足")?></label>
        <input type="radio" name="FINISH_FLAG" id="FINISH_FLAG2" value="0"<?if($FINISH_FLAG=="0") echo " checked";?>><label for="FINISH_FLAG2"><?=_("未提足")?></label>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData" width="100"> <?=_("启用日期：")?></td>
      <td class="TableData" colspan="2"> 
        <input type="input" name="FROM_YYMM" size="12" value="<?if($FROM_YYMM!="0000-00-00") echo $FROM_YYMM;?>" class="BigInput" onClick="WdatePicker()">
       <?=_("为空表示未启用")?>
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData" width="100"> <?=_("保管人：")?></td>
      <td class="TableData" colspan="2"> 
        <input type="hidden" name="TO_ID" value="<?=$USER_ID?>">
				<input type="text" name="TO_NAME" size="20" class="BigInput" maxlength="20"  value="<?=$KEEPER?>">&nbsp;
				<input type="button" value="<?=_("选择")?>" class="SmallButton" onClick="SelectUserSingle('108','','TO_ID','TO_NAME')" title="<?=_("选择")?>" name="button">&nbsp;
				<input type="button" value="<?=_("清空")?>" class="SmallButton" onClick="ClearUser()" title="<?=_("清空")?>" name="button">
      </td>
    </tr>
		<tr>
      <td nowrap class="TableData"> <?=_("图片更改：")?></td>
      <td class="TableData" colspan="2">
        <input type="file" name="PHOTO" size="40" class="BigInput" title="<?=_("选择附件文件")?>">
      </td>
    </tr>
    <tr>
      <td nowrap class="TableData" width="100"> <?=_("备注：")?></td>
      <td class="TableData" colspan="2"> 
        <textarea cols="45" name="REMARK" rows="3" class="BigInput" wrap="yes"><?=$REMARK?></textarea>
      </td>
    </tr>
    <tr>
      <td class="TableData" colspan="4">
<?
  echo get_field_table(get_field_html("CP_CPTL_INFO",$CPTL_ID));
?>
      </td>
    </tr>       
    <tr align="center" class="TableControl">
      <td colspan="3" nowrap>
        <input type="hidden" value="<?=$CPTL_ID?>" name="CPTL_ID" >
        <input type="hidden" value="<?=$DPCT_FLAG?>" name="DPCT_FLAG" >
				<input type="hidden" value="<?=$ATTACHMENT_ID?>" name="ATTACHMENT_ID_OLD">
        <input type="hidden" value="<?=$ATTACHMENT_NAME?>" name="ATTACHMENT_NAME_OLD">
        <input type="button" value="<?=_("确定")?>" class="BigButton" onclick="sendForm();">&nbsp;&nbsp;
        <input type="button" value="<?=_("关闭")?>" class="BigButton" onClick="window.close();">
      </td>
    </tr>
   </form>
  </table>
</body>
</html>