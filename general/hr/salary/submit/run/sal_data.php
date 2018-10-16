<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
?>
<script language="javascript">
var FormulData=new Array();
var TargetData=new Array();
var flag=0;
var SPENSION_P=0;
var SMEDICAL_P=0;
var SUNEMPLOYMENT_P=0;
var SHOUSING_P=0;
var SPENSION_U=0;
var SMEDICAL_U=0;
var SFERTILITY_U=0;
var SUNEMPLOYMENT_U=0;
var SINJURIES_U=0;
var SHOUSING_U=0;
function calculate(FORMULA,TARGET,SIGN)
{
	var s1=document.all(FORMULA).value;
	var re;
	//------所得税计算---------
	if(s1.indexOf("<")!=-1 && s1.indexOf(">")!=-1)
	{
	re=/\<|\>/gi;
	var r=s1.replace(re, "");
	re=/\[|\]/gi;
	r=r.replace(re, "");
	re=/\$/gi;
	r=r.replace(re, "S");
<?
  $query_yes = "SELECT YES_OTHER from HR_INSURANCE_PARA";
  $cursor_yes= exequery(TD::conn(),$query_yes);
  if($ROW_YES=mysql_fetch_array($cursor_yes))
  if($ROW_YES["YES_OTHER"]==1)
  {
?>	
    if(document.getElementById("INSURANCE_OTHER").checked==true)
    {
    	r=r.replace("SPENSION_P", document.all("PENSION_P").value==''?0:document.all("PENSION_P").value);
    	r=r.replace("SMEDICAL_P", document.all("MEDICAL_P").value==''?0:document.all("MEDICAL_P").value);
    	r=r.replace("SUNEMPLOYMENT_P", document.all("UNEMPLOYMENT_P").value==''?0:document.all("UNEMPLOYMENT_P").value);
    	r=r.replace("SHOUSING_P", document.all("HOUSING_P").value==''?0:document.all("HOUSING_P").value);
    	r=r.replace("SPENSION_U", document.all("PENSION_U").value==''?0:document.all("PENSION_U").value);
    	r=r.replace("SMEDICAL_U", document.all("MEDICAL_U").value==''?0:document.all("MEDICAL_U").value);
    	r=r.replace("SFERTILITY_U", document.all("FERTILITY_U").value==''?0:document.all("FERTILITY_U").value);
    	r=r.replace("SUNEMPLOYMENT_U", document.all("UNEMPLOYMENT_U").value==''?0:document.all("UNEMPLOYMENT_U").value);
    	r=r.replace("SINJURIES_U", document.all("INJURIES_U").value==''?0:document.all("INJURIES_U").value);
    	r=r.replace("SHOUSING_U", document.all("HOUSING_U").value==''?0:document.all("HOUSING_U").value);
    }
<?
  }
?>         
    for(var i=document.form1.ITEM_COUNT.value; i>0; i--)
    {
    	 re="S"+i;
    	 if (document.all(re).value=="" && r.indexOf(re)!=-1)
    	 {
    	 	 if(SIGN==0)
    	 	 {
    	 	   if(Number(document.all(re+"index").value) > Number(document.all(TARGET+"index").value))
    	 	    {
    	 	      alert(document.all(re+"_NAME").value+"<?=_("的值尚未填写或计算！")?>");
    	 	      document.all(re).value="0.00";
    	 	    }
    	   }  
    	 	 r=r.replace(re,"0");
    	 }
    	 else
       {
    	   r=r.replace(re,"("+document.all(re).value+")");
    	 }
    }
     cha=eval(r);
     if (cha<=0) {document.all(TARGET).value=0;}
     if (cha>0&&cha<=1500) {document.all(TARGET).value=(cha*0.03).toFixed(2);}
     if (cha>1500&&cha<=4500) {document.all(TARGET).value=(cha*0.1-105).toFixed(2);}
     if (cha>4500&&cha<=9000) {document.all(TARGET).value=(cha*0.2-555).toFixed(2);}
     if (cha>9000&&cha<=35000) {document.all(TARGET).value=(cha*0.25-1005).toFixed(2);}
     if (cha>35000&&cha<=55000) {document.all(TARGET).value=(cha*0.3-2755).toFixed(2);}
     if (cha>55000&&cha<=80000) {document.all(TARGET).value=(cha*0.35-5505).toFixed(2);}
     if (cha>80000) {document.all(TARGET).value=(cha*0.45-13505).toFixed(2);}
     return;
  }

	re=/\[|\]/gi;
	var r=s1.replace(re, "");
	re=/\$/gi;
	var r=r.replace(re, "S");
<?
  $query_yes = "SELECT YES_OTHER from HR_INSURANCE_PARA";
  $cursor_yes= exequery(TD::conn(),$query_yes);
  if($ROW_YES=mysql_fetch_array($cursor_yes))
  if($ROW_YES["YES_OTHER"]==1)
  {
?>	
    if(document.getElementById("INSURANCE_OTHER").checked==true)
    {
    	r=r.replace("SPENSION_P", document.all("PENSION_P").value==''?0:document.all("PENSION_P").value);
    	r=r.replace("SMEDICAL_P", document.all("MEDICAL_P").value==''?0:document.all("MEDICAL_P").value);
    	r=r.replace("SUNEMPLOYMENT_P", document.all("UNEMPLOYMENT_P").value==''?0:document.all("UNEMPLOYMENT_P").value);
    	r=r.replace("SHOUSING_P", document.all("HOUSING_P").value==''?0:document.all("HOUSING_P").value);
    	r=r.replace("SPENSION_U", document.all("PENSION_U").value==''?0:document.all("PENSION_U").value);
    	r=r.replace("SMEDICAL_U", document.all("MEDICAL_U").value==''?0:document.all("MEDICAL_U").value);
    	r=r.replace("SFERTILITY_U", document.all("FERTILITY_U").value==''?0:document.all("FERTILITY_U").value);
    	r=r.replace("SUNEMPLOYMENT_U", document.all("UNEMPLOYMENT_U").value==''?0:document.all("UNEMPLOYMENT_U").value);
    	r=r.replace("SINJURIES_U", document.all("INJURIES_U").value==''?0:document.all("INJURIES_U").value);
    	r=r.replace("SHOUSING_U", document.all("HOUSING_U").value==''?0:document.all("HOUSING_U").value);
    }
<?
  }
?>	
 for(var i=document.form1.ITEM_COUNT.value; i>0; i--)
 {
	 re="S"+i;
	 if (document.all(re).value=="" && r.indexOf(re)!=-1)
	 {
	 	 if(SIGN==0)
	 	 {
	 	   if(Number(document.all(re+"index").value) > Number(document.all(TARGET+"index").value))
	 	   {
	 	   	 alert(document.all(re+"_NAME").value+"<?=_("的值尚未填写或计算！")?>");
	 	   	 document.all(re).value="0.00";
	 	   }
	 	 }
	 	 r=r.replace(re,"0");
	 }
	 else
	   r=r.replace(re,document.all(re).value);

 }
 
 document.all(TARGET).value=eval(r).toFixed(2);
}

function funcal(FORMULA,TARGET)
{
  calculate(FORMULA,TARGET,0);
  for (var key=0;key<TargetData.length;key++)
  {
  	 if (TARGET!=TargetData[key] && (document.all(TargetData[key]).value>1))
     {
       calculate(FormulData[key],TargetData[key],1);
     }
  }
}

function calculate_all()
{
 for(var i=0;i<2;i++)
 {
 	for(var key=0;key<TargetData.length;key++)
  {
    calculate(FormulData[key],TargetData[key],1);
  }
 }
}

function view(FORMULANAME)
{
	alert(FORMULANAME);
}

function clear_all(TARGET)
{
	document.all(TARGET).value="0.00";
	for (var key=0;key<TargetData.length;key++)
  {
  	 if (TARGET!=TargetData[key] && (document.all(TargetData[key]).value>1))
     {
      calculate(FormulData[key],TargetData[key],1);
     }
  }
}

function createstr(FORMULA,TARGET)
{
  FormulData[flag]=FORMULA;
  TargetData[flag]=TARGET;
  flag=flag+1;
	//formustr+=FORMULA+",";
	//targetstr+=TARGET+",";
}
</script>

<?
$HTML_PAGE_TITLE = _("工资数据录入");
include_once("inc/header.inc.php");
?>

<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>

<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>


<body class="bodycolor">
<?
$query = "SELECT * from USER where USER_ID='$USER_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
   $USER_NAME=$ROW["USER_NAME"];
}
?>
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/salary.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><span class="big3"> <?=sprintf(_("工资数据录入（%s）"), $USER_NAME)?></span>
    </td>
  </tr>
</table>

<div align="center">
<form name="form1" method="post" action="submit.php">
<?
//-- 首先查询是否已录入过数据 --
//$RECALL以URL参数方式传递\
if($RECALL=="")
{	
   $query="select USER_ID from SAL_DATA where FLOW_ID='$FLOW_ID' and USER_ID='$USER_ID'";
  $cursor= exequery(TD::conn(),$query);
  if($ROW=mysql_fetch_array($cursor))
     $OPERATION=2; //-- 将执行数据更新 --
  else
     $OPERATION=1; //-- 将执行数据插入 -- 
	
  $query="select * from SAL_DATA where FLOW_ID='$FLOW_ID' and USER_ID='$USER_ID' and IS_FINA_INPUT='1'";
  $cursor= exequery(TD::conn(),$query);
  if($ROW=mysql_fetch_array($cursor))
  {
     for($I=1;$I<=50;$I++)
     {
       $STR="S".$I;
       $$STR=format_money($ROW["$STR"]);
     }
     $MEMO=$ROW["MEMO"];
     $PENSION_BASE = $ROW["PENSION_BASE"];
     $PENSION_U = $ROW["PENSION_U"];     
     $PENSION_P = $ROW["PENSION_P"];     
     $MEDICAL_BASE = $ROW["MEDICAL_BASE"];     
     $MEDICAL_U = $ROW["MEDICAL_U"];     
     $MEDICAL_P = $ROW["MEDICAL_P"];     
     $FERTILITY_BASE = $ROW["FERTILITY_BASE"];     
     $FERTILITY_U = $ROW["FERTILITY_U"];
     $UNEMPLOYMENT_BASE = $ROW["UNEMPLOYMENT_BASE"];     
     $UNEMPLOYMENT_U = $ROW["UNEMPLOYMENT_U"];     
     $UNEMPLOYMENT_P = $ROW["UNEMPLOYMENT_P"];     
     $INJURIES_BASE = $ROW["INJURIES_BASE"];     
     $INJURIES_U = $ROW["INJURIES_U"];     
     $HOUSING_BASE = $ROW["HOUSING_BASE"];     
     $HOUSING_U = $ROW["HOUSING_U"];
     $HOUSING_P = $ROW["HOUSING_P"];
     $INSURANCE_DATE = $ROW["INSURANCE_DATE"]=="0000-00-00"?"":$ROW["INSURANCE_DATE"];
     $INSURANCE_OTHER = $ROW["INSURANCE_OTHER"];
     $IS_FINA_INPUT = $ROW["IS_FINA_INPUT"];    

  }
  else
  {
    $query="select * from HR_SAL_DATA where USER_ID='$USER_ID'";
    $cursor= exequery(TD::conn(),$query);
    if($ROW=mysql_fetch_array($cursor))
    {
       for($I=1;$I<=50;$I++)
       {
         $STR="S".$I;
         $$STR=format_money($ROW["$STR"]);
       }
    
       $ALL_BASE = $ROW["ALL_BASE"];
       $PENSION_BASE = $ROW["PENSION_BASE"];
       $PENSION_U = $ROW["PENSION_U"];     
       $PENSION_P = $ROW["PENSION_P"];     
       $MEDICAL_BASE = $ROW["MEDICAL_BASE"];     
       $MEDICAL_U = $ROW["MEDICAL_U"];     
       $MEDICAL_P = $ROW["MEDICAL_P"];     
       $FERTILITY_BASE = $ROW["FERTILITY_BASE"];     
       $FERTILITY_U = $ROW["FERTILITY_U"];
       $UNEMPLOYMENT_BASE = $ROW["UNEMPLOYMENT_BASE"];     
       $UNEMPLOYMENT_U = $ROW["UNEMPLOYMENT_U"];     
       $UNEMPLOYMENT_P = $ROW["UNEMPLOYMENT_P"];     
       $INJURIES_BASE = $ROW["INJURIES_BASE"];     
       $INJURIES_U = $ROW["INJURIES_U"];     
       $HOUSING_BASE = $ROW["HOUSING_BASE"];     
       $HOUSING_U = $ROW["HOUSING_U"];
       $HOUSING_P = $ROW["HOUSING_P"];
    }
  }
}
//-- 生成上报项目--
$ITEM_COUNT=0;
$wherestr[1]="ISREPORT='1'";//上报
$wherestr[0]="ISREPORT='0' and ISCOMPUTER='0'";//录入
$wherestr[2]="ISCOMPUTER='1'";//计算
for($I=0;$I< 3;$I++)
{
   $query="select * from SAL_ITEM where  ".$wherestr[$I];
   $cursor= exequery(TD::conn(),$query);
   while($ROW=mysql_fetch_array($cursor))
   	  $ITEM_COUNT++;
}
//财务录入项
$query="select * from SAL_ITEM where  ".$wherestr[0];
$cursor= exequery(TD::conn(),$query);
$ITEM_COUNT1=0;
while($ROW=mysql_fetch_array($cursor))
{
   $ITEM_COUNT1++;
   $ITEM_ID=$ROW["ITEM_ID"];
   $ISREPORT=$ROW["ISREPORT"];
   $ITEM_NAME=$ROW["ITEM_NAME"];
   $ISCOMPUTER=$ROW["ISCOMPUTER"];
   $FORMULA=$ROW["FORMULA"];
   $FORMULANAME=$ROW["FORMULANAME"];
   $FORMULAFLAG=$ITEM_ID."FORNULA";
   $FORMULAFLAGNAME=$ITEM_ID."FORNULANAME";
   $S_ID="S".$ITEM_ID;
   $S_NAME=$S_ID."_NAME";
   $CHECK_NAME="C".$S_ID;
 
   if($ITEM_COUNT1==1)
   {
?> 
 <table width="500" class="TableBlock">
     <tr class="TableHeader">
      <td nowrap align="center"><?=_("输入项")?></td>
      <td nowrap align="center"><?=_("金额")?>&nbsp;&nbsp;
       <a style="cursor:pointer;text-decoration: underline" onClick="javascript:location='wage_info.php?USER_ID=<?=$USER_ID?>&FLOW_ID=<?=$FLOW_ID?>';" title=""><?=_("修改薪酬基数")?></a>
      </td>
     </tr>
<?
    }
?> 
     <tr class="TableData">
      <td nowrap align="center" width="150"><?=$ITEM_NAME?></td>
      <td nowrap align="left"> 
      	<input type="hidden" name="<?=$S_NAME?>" value="<?=$ITEM_NAME?>">
      	<input type="text" style="text-align: right;" name="<?=$S_ID?>"  size="17" maxlength="14" class="BigInputMoney" value="<?=$$S_ID?>">
      	<input type="hidden"  name="<?=$S_ID?>index" value="<?=$ITEM_COUNT1?>">
      </td>
    </tr>   
<?
}
if($ITEM_COUNT1 > 0)
	echo "</table>";
//计算项
$query="select * from SAL_ITEM where  ".$wherestr[2];
$cursor= exequery(TD::conn(),$query);
$ITEM_COUNT3=0;
while($ROW=mysql_fetch_array($cursor))
{
   $ITEM_COUNT3++;
   $ITEM_ID=$ROW["ITEM_ID"];
   $ISREPORT=$ROW["ISREPORT"];
   $ITEM_NAME=$ROW["ITEM_NAME"];
   $ISCOMPUTER=$ROW["ISCOMPUTER"];
   $FORMULA=$ROW["FORMULA"];
   $FORMULANAME=$ROW["FORMULANAME"];
   $FORMULAFLAG=$ITEM_ID."FORNULA";
   $FORMULAFLAGNAME=$ITEM_ID."FORNULANAME";
   $S_ID="S".$ITEM_ID;
   $S_NAME=$S_ID."_NAME";
   $CHECK_NAME="C".$S_ID;
 
   if($ITEM_COUNT3==1)
   {
?> 
<table width="500" class="TableBlock">
     <tr class="TableHeader">
      <td nowrap align="center"><?=_("计算项")?></td>
      <td nowrap align="center"><?=_("金额")?> &nbsp;
      <a style="cursor:pointer;text-decoration: underline" onClick="calculate_all();" title="<?=_("单击计算所有项目")?>"><?=_("计算全部")?></a></td>
    </tr>
<?
   }
?> 
     <tr class="TableData">
      <td nowrap align="center" width="150"><?=$ITEM_NAME?></td>
      <td nowrap align="left"> 
      	<input type="hidden" name="<?=$FORMULAFLAG?>" value="<?=$FORMULA?>">
        <input type="hidden" name="<?=$FORMULAFLAGNAME?>" value="<?=$FORMULANAME?>">
        <script language="javascript">createstr('<?=$FORMULAFLAG?>','<?=$S_ID?>')</script>
      	<input type="hidden" name="<?=$S_NAME?>" value="<?=$ITEM_NAME?>">
      	<input type="text" style="text-align: right;" name="<?=$S_ID?>"  size="17" maxlength="14" class="BigStatic" readonly value="<?=$$S_ID?>">
      	<input type="hidden" style="text-align: right;" name="<?=$S_ID?>index" value="<?=$ITEM_COUNT3?>">
      	<input type="button" value="<?=_("计算")?>" class="SmallButton" onClick="funcal('<?=$FORMULAFLAG?>','<?=$S_ID?>');" title="<?=_("计算")?>" name="button">
        <input type="button" value="<?=_("清空")?>" class="SmallButton" onClick="clear_all('<?=$S_ID?>');" title="<?=_("清空")?>" name="button">
          <a href="javascript:view('<?=$FORMULANAME?>');"><?=_("计算公式")?></a>
      </td>
    </tr>   
<?
}
if($ITEM_COUNT3 > 0)
	echo "</table>";

//部门上报项
$query="select * from SAL_ITEM where  ".$wherestr[1];
$cursor= exequery(TD::conn(),$query);
$ITEM_COUNT2=0;
while($ROW=mysql_fetch_array($cursor))
{
   $ITEM_COUNT2++;
   $ITEM_ID=$ROW["ITEM_ID"];
   $ISREPORT=$ROW["ISREPORT"];
   $ITEM_NAME=$ROW["ITEM_NAME"];
   $ISCOMPUTER=$ROW["ISCOMPUTER"];
   $FORMULA=$ROW["FORMULA"];
   $FORMULANAME=$ROW["FORMULANAME"];
   $FORMULAFLAG=$ITEM_ID."FORNULA";
   $FORMULAFLAGNAME=$ITEM_ID."FORNULANAME";
   $S_ID="S".$ITEM_ID;
   $S_NAME=$S_ID."_NAME";
   $CHECK_NAME="C".$S_ID;
 
   if($ITEM_COUNT2==1)
   {
?> 
 <table width="500" class="TableBlock">
     <tr class="TableHeader">
      <td nowrap align="center"><?=_("上报项")?></td>
      <td nowrap align="center"><?=_("金额")?></td>
     </tr>
<?
   }
?> 
     <tr class="TableData">
      <td nowrap align="center" width="150"><?=$ITEM_NAME?></td>
      <td nowrap align="left"> 
      	<input type="hidden" name="<?=$S_NAME?>" value="<?=$ITEM_NAME?>">
      	<input type="text" style="text-align: right;" name="<?=$S_ID?>"  size="17" maxlength="14" class="BigStatic" readonly value="<?=$$S_ID?>">
      	<input type="hidden" style="text-align: right;" name="<?=$S_ID?>index" value="<?=$ITEM_COUNT2?>">
      </td>
    </tr>   
<?
}
if($ITEM_COUNT2 > 0)
	echo "</table>";
//保险
$query1="select ALL_BASE from HR_SAL_DATA where USER_ID='$USER_ID'";
$cursor1= exequery(TD::conn(),$query1);
if($ROW=mysql_fetch_array($cursor1))
  $ALL_BASE = $ROW["ALL_BASE"];	
if($ALL_BASE=="0.00")
{
	$TMP_STR = "<font color=red>"._("未给该用户设置保险默认值")."</font>";
}
$query = "SELECT YES_OTHER from HR_INSURANCE_PARA";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
   $YES_OTHER=$ROW["YES_OTHER"];

if($YES_OTHER==1)
{
?>
<table width="500" class="TableList">
  <tr class="TableHeader">
    <td nowrap align="center"><?=_("保险项(是否投保")?><input type="checkbox" name="INSURANCE_OTHER"  id="INSURANCE_OTHER" <? if($INSURANCE_OTHER==1||$INSURANCE_OTHER=="") echo "checked";?>>)</td>
    <td nowrap align="center"><?=_("金额")?>&nbsp;&nbsp;<?=$TMP_STR?>
    </td>
  </tr>
  <tr class="TableLine1">
    <td nowrap align="center" width="150"><?=_("养老保险")?></td>
    <td nowrap align="left">
       <input type="text" style="text-align: right;" id="PENSION_BASE" class="BigStatic" readonly name="PENSION_BASE" value="<?=$PENSION_BASE?>" size="17" maxlength="14">
    </td>                     
  </tr>
  <tr class="TableLine1">
    <td nowrap align="center" width="150"><?=_("单位养老")?></td>
    <td nowrap align="left">
       <input type="text" style="text-align: right;" id="PENSION_U" class="BigStatic" readonly name="PENSION_U" value="<?=$PENSION_U?>" size="17" maxlength="14">
    </td>                     
  </tr>
  <tr class="TableLine1">
    <td nowrap align="center" width="150"><?=_("个人养老")?></td>
    <td nowrap align="left">
       <input type="text" style="text-align: right;" id="PENSION_P" class="BigStatic" readonly name="PENSION_P" value="<?=$PENSION_P?>" size="17" maxlength="14">
    </td>                     
  </tr>  
  
  <tr class="TableLine2">
    <td nowrap align="center" width="150"><?=_("医疗保险")?></td>
    <td nowrap align="left">
       <input type="text" style="text-align: right;" id="MEDICAL_BASE" class="BigStatic" readonly name="MEDICAL_BASE" value="<?=$MEDICAL_BASE?>" size="17" maxlength="14">
    </td>                     
  </tr>
  <tr class="TableLine2">
    <td nowrap align="center" width="150"><?=_("单位医疗")?></td>
    <td nowrap align="left">
       <input type="text" style="text-align: right;" id="MEDICAL_U" class="BigStatic" readonly name="MEDICAL_U" value="<?=$MEDICAL_U?>" size="17" maxlength="14">
    </td>                     
  </tr>
  <tr class="TableLine2">
    <td nowrap align="center" width="150"><?=_("个人医疗")?></td>
    <td nowrap align="left">
       <input type="text" style="text-align: right;" id="MEDICAL_P" class="BigStatic" readonly name="MEDICAL_P" value="<?=$MEDICAL_P?>" size="17" maxlength="14">
    </td>                     
  </tr>  
    
  <tr class="TableLine1">
    <td nowrap align="center" width="150"><?=_("生育保险")?></td>
    <td nowrap align="left">
       <input type="text" style="text-align: right;" id="FERTILITY_BASE" class="BigStatic" readonly name="FERTILITY_BASE" value="<?=$FERTILITY_BASE?>" size="17" maxlength="14">
    </td>                     
  </tr>    
  <tr class="TableLine1">
    <td nowrap align="center" width="150"><?=_("单位生育")?></td>
    <td nowrap align="left">
       <input type="text" style="text-align: right;" id="FERTILITY_U" class="BigStatic" readonly name="FERTILITY_U" value="<?=$FERTILITY_U?>" size="17" maxlength="14">
    </td>                     
  </tr>    
  <tr class="TableLine2">
    <td nowrap align="center" width="150"><?=_("失业保险")?></td>
    <td nowrap align="left">
       <input type="text" style="text-align: right;" id="UNEMPLOYMENT_BASE" class="BigStatic" readonly name="UNEMPLOYMENT_BASE" value="<?=$UNEMPLOYMENT_BASE?>" size="17" maxlength="14">
    </td>                     
  </tr>    
  <tr class="TableLine2">
    <td nowrap align="center" width="150"><?=_("单位失业")?></td>
    <td nowrap align="left">
       <input type="text" style="text-align: right;" id="UNEMPLOYMENT_U" class="BigStatic" readonly name="UNEMPLOYMENT_U" value="<?=$UNEMPLOYMENT_U?>" size="17" maxlength="14">
    </td>                     
  </tr>    
  <tr class="TableLine2">
    <td nowrap align="center" width="150"><?=_("个人失业")?></td>
    <td nowrap align="left">
       <input type="text" style="text-align: right;" id="UNEMPLOYMENT_P" class="BigStatic" readonly name="UNEMPLOYMENT_P" value="<?=$UNEMPLOYMENT_P?>" size="17" maxlength="14">
    </td>                     
  </tr>      
  <tr class="TableLine1">
    <td nowrap align="center" width="150"><?=_("工伤保险")?></td>
    <td nowrap align="left">
       <input type="text" style="text-align: right;" id="INJURIES_BASE" class="BigStatic" readonly name="INJURIES_BASE" value="<?=$INJURIES_BASE?>" size="17" maxlength="14">
    </td>                     
  </tr>      
  <tr class="TableLine1">
    <td nowrap align="center" width="150"><?=_("单位工伤")?></td>
    <td nowrap align="left">
       <input type="text" style="text-align: right;" id="INJURIES_U" class="BigStatic" readonly name="INJURIES_U" value="<?=$INJURIES_U?>" size="17" maxlength="14">
    </td>                     
  </tr>      
  <tr class="TableLine1">
    <td nowrap align="center" width="150"><?=_("住房公积金")?></td>
    <td nowrap align="left">
       <input type="text" style="text-align: right;" id="HOUSING_BASE" class="BigStatic" readonly name="HOUSING_BASE" value="<?=$HOUSING_BASE?>" size="17" maxlength="14">
    </td>                     
  </tr>      
  <tr class="TableLine1">
    <td nowrap align="center" width="150"><?=_("单位住房")?></td>
    <td nowrap align="left">
       <input type="text" style="text-align: right;" id="HOUSING_U" class="BigStatic" readonly name="HOUSING_U" value="<?=$HOUSING_U?>" size="17" maxlength="14">
    </td>                     
  </tr>    
  <tr class="TableLine1">
    <td nowrap align="center" width="150"><?=_("个人住房")?></td>
    <td nowrap align="left">
       <input type="text" style="text-align: right;" id="HOUSING_P" class="BigStatic" readonly name="HOUSING_P" value="<?=$HOUSING_P?>" size="17" maxlength="14">
    </td>                     
  </tr>
  <tr>
    <td nowrap class="TableData" align="center" width="150"><?=_("投保日期")?></td>
    <td class="TableData">
     <input type="text" name="INSURANCE_DATE" size="15" maxlength="10" class="BigInput" value="<?=$INSURANCE_DATE?>" onClick="WdatePicker()"/>
    </td>
  </tr>         
  </table> 
<?
}
$query = "SELECT BANK1,BANK_ACCOUNT1,BANK2,BANK_ACCOUNT2 from HR_STAFF_INFO where USER_ID='$USER_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
	$BANK1=$ROW["BANK1"];
	$BANK_ACCOUNT1=$ROW["BANK_ACCOUNT1"];
	$BANK2=$ROW["BANK2"];
	$BANK_ACCOUNT2=$ROW["BANK_ACCOUNT2"];
}
?>
<table width="500" class="TableList">
  <tr class="TableHeader">
     <td nowrap align="center"><?=_("工资卡")?></td>
     <td nowrap align="left">&nbsp;</td>
  </tr>  
  <tr>
    <td nowrap class="TableData" align="center" width="150"><?=_("开户行1")?></td>
    <td class="TableData"  width="180"><?=$BANK1?></td>
  </tr>    
   <tr>
    <td nowrap class="TableData" align="center" width="150"><?=_("账户1")?></td>
    <td class="TableData"  width="180"><?=$BANK_ACCOUNT1?></td>
  </tr>    
   <tr>
    <td nowrap class="TableData" align="center" width="150"><?=_("开户行2")?></td>
    <td class="TableData"  width="180"><?=$BANK2?></td>
  </tr>    
   <tr>
    <td nowrap class="TableData" align="center" width="150"><?=_("账户2")?></td>
    <td class="TableData"  width="180"><?=$BANK_ACCOUNT2?></td>
  </tr> 
<?php 
if($ITEM_COUNT>0)
{
?>
   <tr class="TableHeader">
     <td nowrap align="center"><?=_("备注")?></td>
     <td nowrap align="left">&nbsp;</td>
  </tr>  
  <tr class="TableData">
	 <td nowrap align="center" width="150"><?=_("备注")?></td>
    	 <td nowrap align="center"><textarea name="MEMO" cols="45" rows="5"><? echo $MEMO; ?></textarea></td>
  </tr>  	
<?
}
?>
  <tfoot align="center" class="TableFooter">
      <td nowrap colspan="2">
        <input type="hidden" style="text-align: right;" name="<?=$S_ID?>index" value="<?=$ITEM_COUNT?>">
        <input type="hidden" value="<?=$OPERATION?>" name="OPERATION">
        <input type="hidden" value="<?=$USER_ID?>" name="USER_ID">
        <input type="hidden" value="<?=$UID?>" name="UID">
        <input type="hidden" value="<?=$DEPT_ID?>" name="DEPT_ID">
        <input type="hidden" value="<?=$USER_NAME?>" name="USER_NAME">
        <input type="hidden" value="<?=$FLOW_ID?>" name="FLOW_ID">
        <input type="hidden" value="<?=$ITEM_COUNT?>" name="ITEM_COUNT">
        <input type="hidden" value="<?=$ALL_BASE?>" name="ALL_BASE">
        <input type="submit" value="<?=_("上报")?>" class="BigButton">&nbsp;&nbsp;&nbsp;
        <input type="button" value="<?=_("取消")?>" class="BigButton" onClick="location='blank.php'">
      </td>
   </tfoot>
</table>
</form>
<?php 
if($ITEM_COUNT < 1)
    message("",_("尚未定义工资项目，请与财务主管联系！"));
?>
</div>

</body>
</html>