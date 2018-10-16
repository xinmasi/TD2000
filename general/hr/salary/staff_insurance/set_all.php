<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");

$HTML_PAGE_TITLE = _("薪酬基数设置");
include_once("inc/header.inc.php");
?>

<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/js/validation/validationEngine.jquery.min.css">
<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script type="text/javascript" src="/inc/js_lang.php"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/validation/jquery.validationEngine.min.js" type="text/javascript"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script>
jQuery(document).ready(function(){
    jQuery("#form1").validationEngine({promptPosition:"centerRight"});
}); 
function FormatNumber(srcStr,nAfterDot){ 
var srcStr,nAfterDot; 
var resultStr,nTen; 
srcStr = ""+srcStr+""; 
strLen = srcStr.length; 
dotPos = srcStr.indexOf("."); 
if(dotPos == -1){ 
resultStr = srcStr+"."; 
for(i=0;i<nAfterDot;i++){ 
resultStr = resultStr+"0"; 
} 
return resultStr; 
}else{ 
if((strLen - dotPos - 1) >= nAfterDot){ 
nAfter = dotPos + nAfterDot + 1; 
nTen =1; 
for(j=0;j<nAfterDot;j++){ 
nTen = nTen*10; 
} 
resultStr = Math.round(parseFloat(srcStr)*nTen)/nTen; 
return resultStr; 
}else{ 
resultStr = srcStr; 
for(i=0;i<(nAfterDot - strLen + dotPos + 1);i++){ 
resultStr = resultStr+"0"; 
} 
return resultStr; 
} 
} 
} 

function changeRange()
{
   if (document.getElementById("rang_role").style.display=="none")
   {
      document.getElementById("rang_role").style.display="";
      document.getElementById("rang_user").style.display="";
      document.getElementById("href_txt").innerText="<?=_("隐藏按人员或角色发布")?>";
   }
   else
   {
      document.getElementById("rang_role").style.display="none";
      document.getElementById("rang_user").style.display="none";
      document.getElementById("href_txt").innerText="<?=_("按人员或角色发布")?>";
   }
}
<?
$query = "SELECT * from HR_INSURANCE_PARA";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
?>
    var PENSION_U_PAY=<?=$ROW["PENSION_U_PAY"]?>;
    var PENSION_U_PAY_ADD=<?=$ROW["PENSION_U_PAY_ADD"]?>;
    var PENSION_P_PAY=<?=$ROW["PENSION_P_PAY"]?>;
    var PENSION_P_PAY_ADD=<?=$ROW["PENSION_P_PAY_ADD"]?>;
    var HEALTH_P_PAY=<?=$ROW["HEALTH_P_PAY"]?>;
    var HEALTH_P_PAY_ADD=<?=$ROW["HEALTH_P_PAY_ADD"]?>;
    var HEALTH_U_PAY=<?=$ROW["HEALTH_U_PAY"]?>;
    var HEALTH_U_PAY_ADD=<?=$ROW["HEALTH_U_PAY_ADD"]?>;
    var UNEMPLOYMENT_P_PAY=<?=$ROW["UNEMPLOYMENT_P_PAY"]?>;
    var UNEMPLOYMENT_P_PAY_ADD=<?=$ROW["UNEMPLOYMENT_P_PAY_ADD"]?>;
    var UNEMPLOYMENT_U_PAY=<?=$ROW["UNEMPLOYMENT_U_PAY"]?>;
    var UNEMPLOYMENT_U_PAY_ADD=<?=$ROW["UNEMPLOYMENT_U_PAY_ADD"]?>;
    var HOUSING_P_PAY=<?=$ROW["HOUSING_P_PAY"]?>;
    var HOUSING_P_PAY_ADD=<?=$ROW["HOUSING_P_PAY_ADD"]?>;
    var HOUSING_U_PAY=<?=$ROW["HOUSING_U_PAY"]?>;
    var HOUSING_U_PAY_ADD=<?=$ROW["HOUSING_U_PAY_ADD"]?>;
    var INJURY_U_PAY=<?=$ROW["INJURY_U_PAY"]?>;
    var INJURY_U_PAY_ADD=<?=$ROW["INJURY_U_PAY_ADD"]?>;
    var MATERNITY_U_PAY=<?=$ROW["MATERNITY_U_PAY"]?>;
    var MATERNITY_U_PAY_ADD=<?=$ROW["MATERNITY_U_PAY_ADD"]?>; 
    var YES_OTHER = <?=$ROW["YES_OTHER"]?>; 		
<?     
}
?>

function calculate()
{
   	  //养老
   	  var ALL_BASE = parseFloat(document.getElementById('ALL_BASE').value);
   	  var PENSION_U=ALL_BASE * PENSION_U_PAY / 100 + PENSION_U_PAY_ADD;;
   	  var PENSION_P=ALL_BASE * PENSION_P_PAY / 100 + PENSION_P_PAY_ADD;;
   	  document.getElementById('PENSION_U').value=FormatNumber(PENSION_U,2);
   	  document.getElementById('PENSION_P').value=FormatNumber(PENSION_P,2);
   	  document.getElementById('PENSION_BASE').value = FormatNumber(PENSION_U + PENSION_P,2);
   	  //医疗
   	  var MEDICAL_U=ALL_BASE * HEALTH_U_PAY / 100 + HEALTH_U_PAY_ADD;;
   	  var MEDICAL_P=ALL_BASE * HEALTH_P_PAY / 100 + HEALTH_P_PAY_ADD;;
   	  document.getElementById('MEDICAL_U').value=FormatNumber(MEDICAL_U,2);
   	  document.getElementById('MEDICAL_P').value=FormatNumber(MEDICAL_P,2);
   	  document.getElementById('MEDICAL_BASE').value = FormatNumber(MEDICAL_U + MEDICAL_P,2);
   	  //生育
   	  var MATERNITY_U=ALL_BASE * MATERNITY_U_PAY / 100 + MATERNITY_U_PAY_ADD;;
   	  document.getElementById('FERTILITY_U').value=FormatNumber(MATERNITY_U,2);
   	  document.getElementById('FERTILITY_BASE').value = FormatNumber(MATERNITY_U,2);
      //失业
   	  var UNEMPLOYMENT_U=ALL_BASE * UNEMPLOYMENT_U_PAY / 100 + UNEMPLOYMENT_U_PAY_ADD;
   	  var UNEMPLOYMENT_P=ALL_BASE * UNEMPLOYMENT_P_PAY / 100 + UNEMPLOYMENT_P_PAY_ADD;
   	  document.getElementById('UNEMPLOYMENT_U').value=FormatNumber(UNEMPLOYMENT_U,2);
   	  document.getElementById('UNEMPLOYMENT_P').value=FormatNumber(UNEMPLOYMENT_P,2);
   	  document.getElementById('UNEMPLOYMENT_BASE').value = FormatNumber(UNEMPLOYMENT_U + UNEMPLOYMENT_P,2);
   	  //工伤
   	  var INJURIES_U=ALL_BASE * INJURY_U_PAY / 100 + INJURY_U_PAY_ADD;;
   	  document.getElementById('INJURIES_U').value=FormatNumber(INJURIES_U,2);
   	  document.getElementById('INJURIES_BASE').value = FormatNumber(INJURIES_U,2);
   	  
      //住房公积金
   	  var HOUSING_U=ALL_BASE * HOUSING_U_PAY / 100 + HOUSING_U_PAY_ADD;;
   	  var HOUSING_P=ALL_BASE * HOUSING_P_PAY / 100 + HOUSING_P_PAY_ADD;;
   	  document.getElementById('HOUSING_U').value=FormatNumber(HOUSING_U,2);
   	  document.getElementById('HOUSING_P').value=FormatNumber(HOUSING_P,2);
   	  document.getElementById('HOUSING_BASE').value = FormatNumber(HOUSING_U + HOUSING_P,2);   	   
}
</script>


<body class="bodycolor">
<?
//-- 首先查询是否已录入过数据 --
//$RECALL以URL参数方式传递
/*
if($RECALL=="")
{
   $query="select * from HR_SAL_DATA where USER_ID='$USER_ID'";
   $cursor= exequery(TD::conn(),$query);
   if($ROW=mysql_fetch_array($cursor))
      $OPERATION=2; //-- 将执行数据更新 --
   else
      $OPERATION=1; //-- 将执行数据插入 --
}
*/
//-- 生成薪酬项目--
$wherestr[1]="ISREPORT='1'";
$wherestr[0]="ISREPORT='0' and ISCOMPUTER='0'";
?>
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/edit.gif" WIDTH="22" HEIGHT="20" align="absmiddle"><span class="big3"> <?=_("薪酬基数批量设置")?></span>&nbsp;&nbsp;
    </td>
  </tr>
</table>

<div align="center">
<form id="form1" name="form1" method="post" action="all_submit.php">
 <table width="600" class="TableBlock">
     <tr class="TableHeader">
      <td nowrap align="center"><?=_("选择批量设置的人员范围")?>:</td>
      <td nowrap align="center">&nbsp;</td>
      </td>
     </tr>    	
     <tr>
      <td nowrap class="TableData"><?=_("按部门设置：")?><br>&nbsp;<a href="javascript:;" id="href_txt" onclick="changeRange();"><?=_("按人员或角色发布")?></a></td>
      <td class="TableData">
        <input type="hidden" name="TO_ID" value="<?=$TO_ID?>">
        <textarea cols=38 name=TO_NAME rows=2 class="BigStatic" wrap="yes" readonly><?=$TO_NAME?></textarea>
        <a href="javascript:;" class="orgAdd" onClick="SelectDept('')"><?=_("添加")?></a>
       <a href="javascript:;" class="orgClear" onClick="ClearUser()"><?=_("清空")?></a>
      </td>
    </tr>
    <tr id="rang_user" style="display:none;border-right:1px #606275 solid;">
      <td nowrap class="TableData"><?=_("按人员设置：")?></td>
      <td class="TableData">
        <input type="hidden" name="COPY_TO_ID" value="">
        <textarea cols=38 name="COPY_TO_NAME" rows=2 class="BigStatic" wrap="yes" readonly></textarea>
        <a href="javascript:;" class="orgAdd" onClick="SelectUser('27','','COPY_TO_ID', 'COPY_TO_NAME','1')"><?=_("添加")?></a>
        <a href="javascript:;" class="orgClear" onClick="ClearUser('COPY_TO_ID', 'COPY_TO_NAME')"><?=_("清空")?></a>
      </td>
   </tr>
    <tr id="rang_role" style="display:none;">
      <td nowrap class="TableData"><?=_("按角色设置：")?></td>
      <td class="TableData">
        <input type="hidden" name="PRIV_ID" value="">
        <textarea cols=38 name="PRIV_NAME" rows=2 class="BigStatic" wrap="yes" readonly></textarea>
        <a href="javascript:;" class="orgAdd" onClick="SelectPriv('','PRIV_ID', 'PRIV_NAME')"><?=_("添加")?></a>
        <a href="javascript:;" class="orgClear" onClick="ClearUser('PRIV_ID', 'PRIV_NAME')"><?=_("清空")?></a>
        <br><?=_("设置范围取部门、人员和角色的并集")?>
      </td>
   </tr>
<?
//输入项
$query="select * from SAL_ITEM where  ".$wherestr[0];
$cursor= exequery(TD::conn(),$query);
$ITEM_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{      
   $ITEM_COUNT++;       
   $ITEM_ID=$ROW["ITEM_ID"];
   $ISREPORT=$ROW["ISREPORT"];
   $ITEM_NAME=$ROW["ITEM_NAME"];
   $ISCOMPUTER=$ROW["ISCOMPUTER"];
   $S_ID="S".$ITEM_ID;
   $S_NAME=$S_ID."_NAME";

   $TOTAL1=$TOTAL1+$$S_ID;

   if($ITEM_COUNT==1)
   {
?>
 <table width="600" class="TableBlock">
     <tr class="TableHeader">
       <td nowrap align="center"><?=_("输入项")?></td>
       <td nowrap align="center"><?=_("金额")?>
       </td>
     </tr>    	
<?
   }
?>
    <tr class="TableData">
      <td nowrap align="center" width="150"><?=$ITEM_NAME?></td>
      <td nowrap align="left">
         <input type="hidden" name="<?=$S_NAME?>" value="<?=$ITEM_NAME?>">
         <input type="text" name="<?=$S_ID?>" size="20" maxlength="14" class="BigInputMoney" value="<?=$$S_ID?>">
         <input type="hidden" name="<?=$S_ID?>index" value="<?=$ITEM_COUNT?>">
      </td>
    </tr>
<?
}
//保险
   $query = "SELECT YES_OTHER from HR_INSURANCE_PARA";
   $cursor= exequery(TD::conn(),$query);
   if($ROW=mysql_fetch_array($cursor))
   {
    $YES_OTHER=$ROW["YES_OTHER"];
   }

if($YES_OTHER==1)
{
?>
<table width="600" class="TableList">
   <tr class="TableHeader">
     <td nowrap align="center"><?=_("保险项")?></td>
     <td nowrap align="center"><?=_("金额")?>
     </td>
  </tr>    	
  <tr class="TableData">
    <td nowrap align="center" width="150"><?=_("保险基数")?></td>
    <td nowrap align="left">
       <input type="text" style="text-align: right;" id="ALL_BASE" class="BigInputMoney validate[custom[nonNegative]]" data-prompt-position="centerRight:0,-7" name="ALL_BASE" value="<?=$ALL_BASE?>" size="20" maxlength="14">
       <input type="button" value="<?=_("计算")?>" class="BigButton" onclick="calculate()">
    </td>                     
  </tr>
  <tr class="TableLine1">
    <td nowrap align="center" width="150"><?=_("养老保险")?></td>
    <td nowrap align="left">
       <input type="text" style="text-align: right;" id="PENSION_BASE" class="BigInputMoney validate[custom[nonNegative]]" data-prompt-position="centerRight:0,-7" name="PENSION_BASE" value="<?=$PENSION_BASE?>" size="20" maxlength="14">
    </td>                     
  </tr>
  <tr class="TableLine1">
    <td nowrap align="center" width="150"><?=_("单位养老")?></td>
    <td nowrap align="left">
       <input type="text" style="text-align: right;" id="PENSION_U" class="BigInputMoney validate[custom[nonNegative]]" data-prompt-position="centerRight:0,-7" name="PENSION_U" value="<?=$PENSION_U?>" size="20" maxlength="14">
    </td>                     
  </tr>
  <tr class="TableLine1">
    <td nowrap align="center" width="150"><?=_("个人养老")?></td>
    <td nowrap align="left">
       <input type="text" style="text-align: right;" id="PENSION_P" class="BigInputMoney validate[custom[nonNegative]]" data-prompt-position="centerRight:0,-7" name="PENSION_P" value="<?=$PENSION_P?>" size="20" maxlength="14">
    </td>                     
  </tr>  
  
  <tr class="TableLine2">
    <td nowrap align="center" width="150"><?=_("医疗保险")?></td>
    <td nowrap align="left">
       <input type="text" style="text-align: right;" id="MEDICAL_BASE" class="BigInputMoney validate[custom[nonNegative]]" data-prompt-position="centerRight:0,-7" name="MEDICAL_BASE" value="<?=$MEDICAL_BASE?>" size="20" maxlength="14">
    </td>                     
  </tr>
  <tr class="TableLine2">
    <td nowrap align="center" width="150"><?=_("单位医疗")?></td>
    <td nowrap align="left">
       <input type="text" style="text-align: right;" id="MEDICAL_U" class="BigInputMoney validate[custom[nonNegative]]" data-prompt-position="centerRight:0,-7" name="MEDICAL_U" value="<?=$MEDICAL_U?>" size="20" maxlength="14">
    </td>                     
  </tr>
  <tr class="TableLine2">
    <td nowrap align="center" width="150"><?=_("个人医疗")?></td>
    <td nowrap align="left">
       <input type="text" style="text-align: right;" id="MEDICAL_P" class="BigInputMoney validate[custom[nonNegative]]" data-prompt-position="centerRight:0,-7" name="MEDICAL_P" value="<?=$MEDICAL_P?>" size="20" maxlength="14">
    </td>                     
  </tr>  
    
  <tr class="TableLine1">
    <td nowrap align="center" width="150"><?=_("生育保险")?></td>
    <td nowrap align="left">
       <input type="text" style="text-align: right;" id="FERTILITY_BASE" class="BigInputMoney validate[custom[nonNegative]]" data-prompt-position="centerRight:0,-7" name="FERTILITY_BASE" value="<?=$FERTILITY_BASE?>" size="20" maxlength="14">
    </td>                     
  </tr>    
  <tr class="TableLine1">
    <td nowrap align="center" width="150"><?=_("单位生育")?></td>
    <td nowrap align="left">
       <input type="text" style="text-align: right;" id="FERTILITY_U" class="BigInputMoney validate[custom[nonNegative]]" data-prompt-position="centerRight:0,-7" name="FERTILITY_U" value="<?=$FERTILITY_U?>" size="20" maxlength="14">
    </td>                     
  </tr>    
  <tr class="TableLine2">
    <td nowrap align="center" width="150"><?=_("失业保险")?></td>
    <td nowrap align="left">
       <input type="text" style="text-align: right;" id="UNEMPLOYMENT_BASE" class="BigInputMoney validate[custom[nonNegative]]" data-prompt-position="centerRight:0,-7" name="UNEMPLOYMENT_BASE" value="<?=$UNEMPLOYMENT_BASE?>" size="20" maxlength="14">
    </td>                     
  </tr>    
  <tr class="TableLine2">
    <td nowrap align="center" width="150"><?=_("单位失业")?></td>
    <td nowrap align="left">
       <input type="text" style="text-align: right;" id="UNEMPLOYMENT_U" class="BigInputMoney validate[custom[nonNegative]]" data-prompt-position="centerRight:0,-7" name="UNEMPLOYMENT_U" value="<?=$UNEMPLOYMENT_U?>" size="20" maxlength="14">
    </td>                     
  </tr>    
  <tr class="TableLine2">
    <td nowrap align="center" width="150"><?=_("个人失业")?></td>
    <td nowrap align="left">
       <input type="text" style="text-align: right;" id="UNEMPLOYMENT_P" class="BigInputMoney validate[custom[nonNegative]]" data-prompt-position="centerRight:0,-7" name="UNEMPLOYMENT_P" value="<?=$UNEMPLOYMENT_P?>" size="20" maxlength="14">
    </td>                     
  </tr>      
  <tr class="TableLine1">
    <td nowrap align="center" width="150"><?=_("工伤保险")?></td>
    <td nowrap align="left">
       <input type="text" style="text-align: right;" id="INJURIES_BASE" class="BigInputMoney validate[custom[nonNegative]]" data-prompt-position="centerRight:0,-7" name="INJURIES_BASE" value="<?=$INJURIES_BASE?>" size="20" maxlength="14">
    </td>                     
  </tr>      
  <tr class="TableLine1">
    <td nowrap align="center" width="150"><?=_("单位工伤")?></td>
    <td nowrap align="left">
       <input type="text" style="text-align: right;" id="INJURIES_U" class="BigInputMoney validate[custom[nonNegative]]" data-prompt-position="centerRight:0,-7" name="INJURIES_U" value="<?=$INJURIES_U?>" size="20" maxlength="14">
    </td>                     
  </tr>      
  <tr class="TableLine2">
    <td nowrap align="center" width="150"><?=_("住房公积金")?></td>
    <td nowrap align="left">
       <input type="text" style="text-align: right;" id="HOUSING_BASE" class="BigInputMoney validate[custom[nonNegative]]" data-prompt-position="centerRight:0,-7" name="HOUSING_BASE" value="<?=$HOUSING_BASE?>" size="20" maxlength="14">
    </td>                     
  </tr>      
  <tr class="TableLine2">
    <td nowrap align="center" width="150"><?=_("单位住房")?></td>
    <td nowrap align="left">
       <input type="text" style="text-align: right;" id="HOUSING_U" class="BigInputMoney validate[custom[nonNegative]]" data-prompt-position="centerRight:0,-7" name="HOUSING_U" value="<?=$HOUSING_U?>" size="20" maxlength="14">
    </td>                     
  </tr>    
  <tr class="TableLine2">
    <td nowrap align="center" width="150"><?=_("个人住房")?></td>
    <td nowrap align="left">
       <input type="text" style="text-align: right;" id="HOUSING_P" class="BigInputMoney validate[custom[nonNegative]]" data-prompt-position="centerRight:0,-7" name="HOUSING_P" value="<?=$HOUSING_P?>" size="20" maxlength="14">
    </td>                     
  </tr>   
</table>             
<?
}
//-- 生成上报项目--
$ITEM_COUNT1=0;
$query="select * from SAL_ITEM where  ".$wherestr[1];
$cursor= exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
   $ITEM_COUNT1++;       
   $ITEM_ID=$ROW["ITEM_ID"];
   $ISREPORT=$ROW["ISREPORT"];
   $ITEM_NAME=$ROW["ITEM_NAME"];
   $ISCOMPUTER=$ROW["ISCOMPUTER"];
   $S_ID="S".$ITEM_ID;
   $S_NAME=$S_ID."_NAME";

   if($ITEM_COUNT1==1)
   {
?>

    <table width="600" class="TableBlock">
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
        <input type="text" name="<?=$S_ID?>"  size="20" maxlength="14" class="BigInputMoney validate[custom[nonNegative]]" data-prompt-position="centerRight:0,-7" value="<?=$$S_ID?>">
        <input type="hidden" name="<?=$S_ID?>index" value="<?=$ITEM_COUNT1?>">
      </td>
    </tr>
<?
}
?>	 
    <tfoot align="center" class="TableFooter">
      <td nowrap colspan="2">
        <input type="hidden" value="<?=$OPERATION?>" name="OPERATION">
        <input type="hidden" value="<?=$USER_ID?>" name="USER_ID">
        <input type="hidden" value="<?=$USER_NAME?>" name="USER_NAME">
        <input type="submit" value="<?=_("保存")?>" class="BigButton">&nbsp;&nbsp;&nbsp;
        <input type="reset" value="<?=_("清空")?>" class="BigButton" >
      </td>
    </tfoot>
   </table>
</form>
</div>

</body>
</html>