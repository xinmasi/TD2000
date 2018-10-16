<?
include_once("inc/auth.inc.php");
include_once("inc/utility_org.php");
include_once("inc/utility_all.php");

$FLAG=isset($_GET["FLAG"])? $_GET["FLAG"]:0;
$query="select count(USER_ID) from USER where DEPT_ID='$DEPT_ID' ";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
  $HRMS_COUNT = $ROW["0"];

$query = "SELECT ITEM_ID from SAL_ITEM where ISCOMPUTER!='1'";
$cursor= exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
    	$STYLE=$STYLE.$ROW["ITEM_ID"].",";
}
if($DEPT_ID!=0)
   $DEPT_NAME=substr(GetDeptNameById($DEPT_ID),0,-1);

$HTML_PAGE_TITLE = $DEPT_NAME;
include_once("inc/header.inc.php");
?>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>

<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/js/validation/validationEngine.jquery.min.css">
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script type="text/javascript" src="/inc/js_lang.php"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/calendar.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/utility.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/validation/jquery.validationEngine.min.js" charset="utf-8" type="text/javascript"></script>
<script>
jQuery(document).ready(function(){
    jQuery("#form1").validationEngine({promptPosition:"centerRight"});
});
//如果从OA精灵打开，则最大化窗口
if(window.external && typeof window.external.OA_SMS != 'undefined')
{
    var h = screen.availHeight - 180,
        w = screen.availWidth - 180;
    window.external.OA_SMS(w, h, "SET_SIZE");
}
function sort()
{
	window.location="wage_list.php?DEPT_ID=<?=$DEPT_ID?>&FLAG=1";
}
function total_calculate(id)
{
	var arr=document.getElementsByName("user_id");
	var total_temp_id=id+"_TOTAL";
	var total_count=0;
	for(var i=0;i<arr.length;i++)
	{
			var temp_id=arr[i].value+"_"+id;
			var temp=document.getElementById(temp_id).value;
			if(temp=="")
				temp=0;
			else
				temp=parseFloat(document.getElementById(temp_id).value);
			total_count+=temp;
	}
	total_count=FormatNumber(total_count,2);
	document.getElementById(total_temp_id).value=total_count;
}
function FormatNumber(srcStr,nAfterDot)
{
   var srcStr,nAfterDot;
   var resultStr,nTen;
   srcStr = ""+srcStr+"";
   strLen = srcStr.length;
   dotPos = srcStr.indexOf(".");
   if(dotPos == -1)
   {
      resultStr = srcStr+".";
      for(i=0;i<nAfterDot;i++)
      {
         resultStr = resultStr+"0";
      }
      //return resultStr;
      return resultStr=="NaN.00"?"":resultStr;
   }else{
      if((strLen - dotPos - 1) >= nAfterDot)
      {
         nAfter = dotPos + nAfterDot + 1;
         nTen =1;
         for(j=0;j<nAfterDot;j++)
         {
            nTen = nTen*10;
         }
         resultStr = Math.round(parseFloat(srcStr)*nTen)/nTen;
         //return resultStr;
         return resultStr=="NaN.00"?"":resultStr;
      }else{
         resultStr = srcStr;
         for(i=0;i<(nAfterDot - strLen + dotPos + 1);i++)
         {
            resultStr = resultStr+"0";
         }
         //return resultStr;
         return resultStr=="NaN.00"?"":resultStr;
      }
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

/*
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
*/

function calculate()
{
   var tabObj = document.getElementById("cal_table");
   var rowCount = tabObj.rows.length-1;
    var arr=document.getElementsByName("user_id");
   for(var i=1;i < rowCount ;i++)
   {
   	  //养老
   	  var ALL_BASE = parseFloat(eval("document.getElementById('"+arr[i-1].value+"_ALL_BASE')").value);

   	  var PENSION_U=ALL_BASE * PENSION_U_PAY / 100 + PENSION_U_PAY_ADD;
   	  var PENSION_P=ALL_BASE * PENSION_P_PAY / 100 + PENSION_P_PAY_ADD;
   	  eval("document.getElementById('"+arr[i-1].value+"_PENSION_U')").value=FormatNumber(PENSION_U,2);
   	  eval("document.getElementById('"+arr[i-1].value+"_PENSION_P')").value=FormatNumber(PENSION_P,2);
   	  eval("document.getElementById('"+arr[i-1].value+"_PENSION_BASE')").value = FormatNumber(PENSION_U + PENSION_P,2);
   	  //医疗
   	  var MEDICAL_U=ALL_BASE * HEALTH_U_PAY / 100 + HEALTH_U_PAY_ADD;;
   	  var MEDICAL_P=ALL_BASE * HEALTH_P_PAY / 100 + HEALTH_P_PAY_ADD;;
   	  eval("document.getElementById('"+arr[i-1].value+"_MEDICAL_U')").value=FormatNumber(MEDICAL_U,2);
   	  eval("document.getElementById('"+arr[i-1].value+"_MEDICAL_P')").value=FormatNumber(MEDICAL_P,2);
   	  eval("document.getElementById('"+arr[i-1].value+"_MEDICAL_BASE')").value = FormatNumber(MEDICAL_U + MEDICAL_P,2);
   	  //生育
   	  var MATERNITY_U=ALL_BASE * MATERNITY_U_PAY / 100 + MATERNITY_U_PAY_ADD;;
   	  eval("document.getElementById('"+arr[i-1].value+"_FERTILITY_U')").value=FormatNumber(MATERNITY_U,2);
   	  eval("document.getElementById('"+arr[i-1].value+"_FERTILITY_BASE')").value = FormatNumber(MATERNITY_U,2);
      //失业
   	  var UNEMPLOYMENT_U=ALL_BASE * UNEMPLOYMENT_U_PAY / 100 + UNEMPLOYMENT_U_PAY_ADD;
   	  var UNEMPLOYMENT_P=ALL_BASE * UNEMPLOYMENT_P_PAY / 100 + UNEMPLOYMENT_P_PAY_ADD;
   	  eval("document.getElementById('"+arr[i-1].value+"_UNEMPLOYMENT_U')").value=FormatNumber(UNEMPLOYMENT_U,2);
   	  eval("document.getElementById('"+arr[i-1].value+"_UNEMPLOYMENT_P')").value=FormatNumber(UNEMPLOYMENT_P,2);
   	  eval("document.getElementById('"+arr[i-1].value+"_UNEMPLOYMENT_BASE')").value = FormatNumber(UNEMPLOYMENT_U + UNEMPLOYMENT_P,2);
   	  //工伤
   	  var INJURIES_U=ALL_BASE * INJURY_U_PAY / 100 + INJURY_U_PAY_ADD;;
   	  eval("document.getElementById('"+arr[i-1].value+"_INJURIES_U')").value=FormatNumber(INJURIES_U,2);
   	  eval("document.getElementById('"+arr[i-1].value+"_INJURIES_BASE')").value = FormatNumber(INJURIES_U,2);

      //住房公积金
   	  var HOUSING_U=ALL_BASE * HOUSING_U_PAY / 100 + HOUSING_U_PAY_ADD;;
   	  var HOUSING_P=ALL_BASE * HOUSING_P_PAY / 100 + HOUSING_P_PAY_ADD;;
   	  eval("document.getElementById('"+arr[i-1].value+"_HOUSING_U')").value=FormatNumber(HOUSING_U,2);
   	  eval("document.getElementById('"+arr[i-1].value+"_HOUSING_P')").value=FormatNumber(HOUSING_P,2);
   	  eval("document.getElementById('"+arr[i-1].value+"_HOUSING_BASE')").value = FormatNumber(HOUSING_U + HOUSING_P,2);

   }
   total_calculate('ALL_BASE');
    total_calculate('PENSION_BASE');
    total_calculate('PENSION_U');
    total_calculate('PENSION_P');
    total_calculate('MEDICAL_BASE');
    total_calculate('MEDICAL_U');
    total_calculate('MEDICAL_P');
    total_calculate('FERTILITY_BASE');
    total_calculate('FERTILITY_U');
    total_calculate('UNEMPLOYMENT_BASE');
    total_calculate('UNEMPLOYMENT_U');
    total_calculate('UNEMPLOYMENT_P');
    total_calculate('INJURIES_BASE');
    total_calculate('INJURIES_U');
    total_calculate('HOUSING_BASE');
    total_calculate('HOUSING_U');
    total_calculate('HOUSING_P');
}
window.onscroll=window.onresize=function()
{
   op_btn=document.getElementById("OP_BTN");
   if(!op_btn) return false;

   op_btn.style.left=document.body.clientWidth+document.body.scrollLeft-320;
   op_btn.style.top =document.body.scrollTop +5;
};

function CheckForm()
{
    var fanhui=1;
    $('table input[zhi="tishi"]').each(function(){
        if(isNaN($(this).val()))
            {
                alert("请输入相应的数字！");
                return false;
            }
        if($(this).val()=="")
            {
                alert("请输入相应的值！");
                return false;
            }
    });
    // if(fanhui==0)
    // {
    //     return (false);
    // }
    // else
    // {
    //    return (true);
    // }
}
</script>



<body class="bodycolor">
<?
   $query = "SELECT YES_OTHER from HR_INSURANCE_PARA";
   $cursor= exequery(TD::conn(),$query);
   if($ROW=mysql_fetch_array($cursor))
   {
    $YES_OTHER=$ROW["YES_OTHER"];
   }
?>
<form action="list_submit.php"  method="post"id="form1" name="form1"onSubmit="return CheckForm();">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big">
    	<img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_open.gif" align="absmiddle"><span class="big3"> <a name="bottom">[<?=$DEPT_NAME?>]<?=_("员工薪酬基数列表")?>- <?=sprintf(_("%s人"), $HRMS_COUNT)?></span>
      <div id="OP_BTN" style="width:320px;top:5px;right:15px;position:absolute;">
      <input type="button" value="<?=_("人员按拼音排序")?>" class="BigButton" title="<?=_("人员按拼音排序")?>" onclick="sort()"> &nbsp;
<?
   if($YES_OTHER==1)
   {
?>
    	<input type="button" value="<?=_("计算")?>" class="BigButton" onclick="calculate()">
<?
   }
?>
      <input type="submit" value="<?=_("确定")?>" class="BigButton" title="<?=_("确定")?>" name="button">
      <input type="button" value="<?=_("导入")?>" class="BigButton" title="<?=_("导入")?>" onclick="file_ecxel()">
     </div>
    </td>
  </tr>
</table>
<br />
<table class="TableBlock" align="center" id="cal_table">
    <tr class="TableHeader" align="center">
      <td nowrap width="15%"><b><?=_("姓名")?></b></td>
<?
			 $STYLE_ARRAY=explode(",",$STYLE);
			 $ARRAY_COUNT=sizeof($STYLE_ARRAY);
			 $COUNT=0;
			 if($STYLE_ARRAY[$ARRAY_COUNT-1]=="")$ARRAY_COUNT--;
			 for($I=0;$I<$ARRAY_COUNT;$I++)
			 {
			   	 $query1 = "select ITEM_ID,ITEM_NAME from SAL_ITEM where ITEM_ID='$STYLE_ARRAY[$I]'";
			     $cursor1= exequery(TD::conn(),$query1);
			     if($ROW=mysql_fetch_array($cursor1))
			     {
			        $ITEM_NAME=$ROW["ITEM_NAME"];
			        $ITEM_ID[$COUNT]=$ROW["ITEM_ID"];
			     }
			     $COUNT++;
?>
      		 <td nowrap align="center" onClick="clickTitle('<?=$ITEM_ID[$COUNT-1]?>')" style="cursor:hand"><b><?=$ITEM_NAME?></b></td>
<?
			 }
			 if($YES_OTHER==1)
			 {
?>
      <td nowrap align="center" style="cursor:hand"><b><?=_("保险基数")?></b></td>
      <td nowrap align="center" style="cursor:hand"><b><?=_("养老保险")?></b></td>
      <td nowrap align="center" style="cursor:hand"><b><?=_("单位养老")?></b></td>
      <td nowrap align="center" style="cursor:hand"><b><?=_("个人养老")?></b></td>
      <td nowrap align="center" style="cursor:hand"><b><?=_("医疗保险")?></b></td>
      <td nowrap align="center" style="cursor:hand"><b><?=_("单位医疗")?></b></td>
      <td nowrap align="center" style="cursor:hand"><b><?=_("个人医疗")?></b></td>
      <td nowrap align="center" style="cursor:hand"><b><?=_("生育保险")?></b></td>
      <td nowrap align="center" style="cursor:hand"><b><?=_("单位生育")?></b></td>
      <td nowrap align="center" style="cursor:hand"><b><?=_("失业保险")?></b></td>
      <td nowrap align="center" style="cursor:hand"><b><?=_("单位失业")?></b></td>
      <td nowrap align="center" style="cursor:hand"><b><?=_("个人失业")?></b></td>
      <td nowrap align="center" style="cursor:hand"><b><?=_("工伤保险")?></b></td>
      <td nowrap align="center" style="cursor:hand"><b><?=_("单位工伤")?></b></td>
      <td nowrap align="center" style="cursor:hand"><b><?=_("住房公积金")?></b></td>
      <td nowrap align="center" style="cursor:hand"><b><?=_("单位住房")?></b></td>
      <td nowrap align="center" style="cursor:hand"><b><?=_("个人住房")?></b></td>
    </tr>
<?
 				}
//============================ 显示已定义用户 =======================================
 if(!$FLAG)
 	$query = "SELECT * from  USER where DEPT_ID='$DEPT_ID' order by USER_NO";
 else
 	$query = "SELECT * from  USER where DEPT_ID='$DEPT_ID' order by USER_NAME";

 $cursor= exequery(TD::conn(),$query);
 $STAFF_COUNT=0;
 while($ROW=mysql_fetch_array($cursor))
 {
    $STAFF_COUNT++;
    $USER_ID=$ROW["USER_ID"];
    $USER_NAME=$ROW["USER_NAME"];
    $STYLE_USER=$STYLE_USER.$USER_ID.",";
    if($STAFF_COUNT%2==1)
       $TableLine="TableLine1";
    else
       $TableLine="TableContent";
?>
    <tr class="TableLine1" align="center">
      <td nowrap>
      	<input type="hidden" name="user_id" value="<?=$USER_ID ?>">
      	<?=$USER_NAME?>
      </td>
<?
   $query1="select * from HR_SAL_DATA where USER_ID='$USER_ID'";
  
   $cursor1= exequery(TD::conn(),$query1);
   if($ROW=mysql_fetch_array($cursor1))
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

     $OPERATION=2; //-- 将执行数据更新 --
   }
   else
     $OPERATION=1; //-- 将执行数据插入 --

	 $STYLE_ARRAY=explode(",",$STYLE);
	 $ARRAY_COUNT=sizeof($STYLE_ARRAY);
	 $COUNT=0;
	 if($STYLE_ARRAY[$ARRAY_COUNT-1]=="")$ARRAY_COUNT--;
	 for($I=0;$I<$ARRAY_COUNT;$I++)
   {
   	 $query1 = "select ITEM_ID,ITEM_NAME from SAL_ITEM where ITEM_ID='$STYLE_ARRAY[$I]'";
    
     $cursor1= exequery(TD::conn(),$query1);
     if($ROW=mysql_fetch_array($cursor1))
     {
     		$ITEM_NAME=$ROW["ITEM_NAME"];
     		$ITEM_ID[$COUNT]=$ROW["ITEM_ID"];
     		$S_ID="S".$ITEM_ID[$COUNT];
     }
     $COUNT++;
?>
      <td nowrap align="center">
      	<input type="text" zhi="tishi" style="text-align: right;" name="<?=$USER_ID ?>_<?=$ITEM_ID[$COUNT-1]?>" id="<?=$USER_ID ?>_<?=$ITEM_ID[$COUNT-1] ?>" class="SmallInput validate[custom[nonNegative]]"  data-prompt-position="centerRight:-5,-6" value="<?=isset($$S_ID)? $$S_ID:"0.00";?>" size="10"  onblur="total_calculate(<?=$ITEM_ID[$COUNT-1] ?>)">
      </td>
<?
   }
   if($YES_OTHER==1)
   {
?>
     <td nowrap align="center">
     	<input type="text" style="text-align: right;" zhi="tishi" id="<?=$USER_ID?>_ALL_BASE" name="<?=$USER_ID?>_ALL_BASE" class="SmallInput validate[custom[nonNegative]]" data-prompt-position="centerRight:-5,-6" value="<?=isset($ALL_BASE)? $ALL_BASE:"0.00";?>" size="10"  onblur="total_calculate('ALL_BASE')">
     </td>
     <td nowrap align="center"><input type="text" zhi="tishi"  style="text-align: right;" id="<?=$USER_ID?>_PENSION_BASE" name="<?=$USER_ID?>_PENSION_BASE" class="SmallInput validate[custom[nonNegative]]" data-prompt-position="centerRight:-5,-6" value="<?=isset($PENSION_BASE)? $PENSION_BASE:"0.00";?>" size="10" onblur="total_calculate('PENSION_BASE')"> </td>
     <td nowrap align="center"><input type="text" zhi="tishi" style="text-align: right;" id="<?=$USER_ID?>_PENSION_U" name="<?=$USER_ID?>_PENSION_U" class="SmallInput validate[custom[nonNegative]]" data-prompt-position="centerRight:-5,-6" value="<?=isset($PENSION_U)? $PENSION_U:"0.00";?>" size="10"  onblur="total_calculate('PENSION_U')"></td>
     <td nowrap align="center"><input type="text" zhi="tishi" style="text-align: right;" id="<?=$USER_ID?>_PENSION_P" name="<?=$USER_ID?>_PENSION_P" class="SmallInput validate[custom[nonNegative]]" data-prompt-position="centerRight:-5,-6" value="<?=isset($PENSION_P)? $PENSION_P:"0.00";?>" size="10"  onblur="total_calculate('PENSION_P')"></td>
     <td nowrap align="center"><input type="text" zhi="tishi" style="text-align: right;" id="<?=$USER_ID?>_MEDICAL_BASE" name="<?=$USER_ID?>_MEDICAL_BASE" class="SmallInput validate[custom[nonNegative]]" data-prompt-position="centerRight:-5,-6" value="<?=isset($MEDICAL_BASE)? $MEDICAL_BASE:"0.00";?>" size="10"  onblur="total_calculate('MEDICAL_BASE')"></td>
     <td nowrap align="center"><input type="text" zhi="tishi" style="text-align: right;" id="<?=$USER_ID?>_MEDICAL_U" name="<?=$USER_ID?>_MEDICAL_U" class="SmallInput validate[custom[nonNegative]]" data-prompt-position="centerRight:-5,-6" value="<?=isset($MEDICAL_U)? $MEDICAL_U:"0.00";?>" size="10"  onblur="total_calculate('MEDICAL_U')"></td>
     <td nowrap align="center"><input type="text" zhi="tishi" style="text-align: right;" id="<?=$USER_ID?>_MEDICAL_P" name="<?=$USER_ID?>_MEDICAL_P" class="SmallInput validate[custom[nonNegative]]" data-prompt-position="centerRight:-5,-6" value="<?=isset($MEDICAL_P)? $MEDICAL_P:"0.00";?>" size="10"  onblur="total_calculate('MEDICAL_P')"></td>
     <td nowrap align="center"><input type="text" zhi="tishi" style="text-align: right;" id="<?=$USER_ID?>_FERTILITY_BASE" name="<?=$USER_ID?>_FERTILITY_BASE" class="SmallInput validate[custom[nonNegative]]" data-prompt-position="centerRight:-5,-6" value="<?=isset($FERTILITY_BASE)? $FERTILITY_BASE:"0.00";?>" size="10"  onblur="total_calculate('FERTILITY_BASE')"></td>
     <td nowrap align="center"><input type="text" zhi="tishi" style="text-align: right;" id="<?=$USER_ID?>_FERTILITY_U" name="<?=$USER_ID?>_FERTILITY_U" class="SmallInput validate[custom[nonNegative]]" data-prompt-position="centerRight:-5,-6" value="<?=isset($FERTILITY_U)? $FERTILITY_U:"0.00";?>" size="10"  onblur="total_calculate('FERTILITY_U')"></td>
     <td nowrap align="center"><input type="text" zhi="tishi" style="text-align: right;" id="<?=$USER_ID?>_UNEMPLOYMENT_BASE" name="<?=$USER_ID?>_UNEMPLOYMENT_BASE" class="SmallInput validate[custom[nonNegative]]" data-prompt-position="centerRight:-5,-6" value="<?=isset($UNEMPLOYMENT_BASE)? $UNEMPLOYMENT_BASE:"0.00";?>" size="10"  onblur="total_calculate('UNEMPLOYMENT_BASE')"></td>
     <td nowrap align="center"><input type="text" zhi="tishi" style="text-align: right;" id="<?=$USER_ID?>_UNEMPLOYMENT_U" name="<?=$USER_ID?>_UNEMPLOYMENT_U" class="SmallInput validate[custom[nonNegative]]" data-prompt-position="centerRight:-5,-6" value="<?=isset($UNEMPLOYMENT_U)? $UNEMPLOYMENT_U:"0.00";?>" size="10"  onblur="total_calculate('UNEMPLOYMENT_U')"></td>
     <td nowrap align="center"><input type="text" zhi="tishi" style="text-align: right;" id="<?=$USER_ID?>_UNEMPLOYMENT_P" name="<?=$USER_ID?>_UNEMPLOYMENT_P" class="SmallInput validate[custom[nonNegative]]" data-prompt-position="centerRight:-5,-6" value="<?=isset($UNEMPLOYMENT_P)? $UNEMPLOYMENT_P:"0.00";?>" size="10"  onblur="total_calculate('UNEMPLOYMENT_P')"></td>
     <td nowrap align="center"><input type="text" zhi="tishi" style="text-align: right;" id="<?=$USER_ID?>_INJURIES_BASE" name="<?=$USER_ID?>_INJURIES_BASE" class="SmallInput validate[custom[nonNegative]]" data-prompt-position="centerRight:-5,-6" value="<?=isset($INJURIES_BASE)? $INJURIES_BASE:"0.00";?>" size="10"  onblur="total_calculate('INJURIES_BASE')"></td>
     <td nowrap align="center"><input type="text" zhi="tishi" style="text-align: right;" id="<?=$USER_ID?>_INJURIES_U" name="<?=$USER_ID?>_INJURIES_U" class="SmallInput validate[custom[nonNegative]]" data-prompt-position="centerRight:-5,-6" value="<?=isset($INJURIES_U)? $INJURIES_U:"0.00";?>" size="10"  onblur="total_calculate('INJURIES_U')"></td>
     <td nowrap align="center"><input type="text" zhi="tishi" style="text-align: right;" id="<?=$USER_ID?>_HOUSING_BASE" name="<?=$USER_ID?>_HOUSING_BASE" class="SmallInput validate[custom[nonNegative]]" data-prompt-position="centerRight:-5,-6" value="<?=isset($HOUSING_BASE)? $HOUSING_BASE:"0.00";?>" size="10"  onblur="total_calculate('HOUSING_BASE')"></td>
     <td nowrap align="center"><input type="text" zhi="tishi" style="text-align: right;" id="<?=$USER_ID?>_HOUSING_U" name="<?=$USER_ID?>_HOUSING_U" class="SmallInput validate[custom[nonNegative]]" data-prompt-position="centerRight:-5,-6" value="<?=isset($HOUSING_U)? $HOUSING_U:"0.00";?>" size="10"  onblur="total_calculate('HOUSING_U')"></td>
     <td nowrap align="center"><input type="text" zhi="tishi" style="text-align: right;" id="<?=$USER_ID?>_HOUSING_P" name="<?=$USER_ID?>_HOUSING_P" class="SmallInput validate[custom[nonNegative]]" data-prompt-position="centerRight:-5,-6" value="<?=isset($HOUSING_P)? $HOUSING_P:"0.00";?>" size="10"  onblur="total_calculate('HOUSING_P')"></td>
<?
   }
?>
     <input type="hidden" name="<?=$USER_ID?>_OPERATION" class="SmallInput" value="<?=$OPERATION ?>" size="10" >
<?
	}
?>
    <input type="hidden" name="<?=$USER_ID?>_OPERATION" class="SmallInput" value="<?=$OPERATION ?>" size="10" >

  </tr>
  <tr>
  	<td nowrap><?=_("总计") ?></td>
<?
   $query1="select * from HR_SAL_DATA where USER_ID='$USER_ID'";
   $cursor1= exequery(TD::conn(),$query1);
   if($ROW=mysql_fetch_array($cursor1))
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

     $OPERATION=2; //-- 将执行数据更新 --
   }
   else
     $OPERATION=1; //-- 将执行数据插入 --

	 $STYLE_ARRAY=explode(",",$STYLE);
	 $ARRAY_COUNT=sizeof($STYLE_ARRAY);
	 $COUNT=0;
	 if($STYLE_ARRAY[$ARRAY_COUNT-1]=="")$ARRAY_COUNT--;
	 for($I=0;$I<$ARRAY_COUNT;$I++)
   {
   	 $query1 = "select ITEM_ID,ITEM_NAME from SAL_ITEM where ITEM_ID='$STYLE_ARRAY[$I]'";
     $cursor1= exequery(TD::conn(),$query1);
     if($ROW=mysql_fetch_array($cursor1))
     {
     		$ITEM_NAME=$ROW["ITEM_NAME"];
     		$ITEM_ID[$COUNT]=$ROW["ITEM_ID"];
     		$S_ID="S".$ITEM_ID[$COUNT];
     }
     $COUNT++;
?>
      <td nowrap align="center">
      	<input type="text" style="text-align: right;" name="<?=$ITEM_ID[$COUNT-1]?>_TOTAL" id="<?=$ITEM_ID[$COUNT-1] ?>_TOTAL" value=0.00 class="SmallInput validate[custom[nonNegative]]" data-prompt-position="centerRight:-5,-6"  size="10" readonly>
      </td>
<?
   }
   if($YES_OTHER==1)
   {
?>
		<td nowrap align="center">
			<input type="text" style="text-align: right;" id="ALL_BASE_TOTAL" name="ALL_BASE_TOTAL" class="SmallInput validate[custom[nonNegative]]" data-prompt-position="centerRight:-5,-6" value=0.00 size="10" readonly>
		</td>
		<td nowrap align="center">
			<input type="text" style="text-align: right;" id="PENSION_BASE_TOTAL" name="PENSION_BASE_TOTAL" class="SmallInput validate[custom[nonNegative]]"  data-prompt-position="centerRight:-5,-6" value=0.00 size="10" readonly>
		</td>
		<td nowrap align="center">
			<input type="text" style="text-align: right;" id="PENSION_U_TOTAL" name="PENSION_U_TOTAL" class="SmallInput validate[custom[nonNegative]]"  data-prompt-position="centerRight:-5,-6" value=0.00 size="10" readonly>
		</td>
		<td nowrap align="center" >
			<input type="text" style="text-align: right;" id="PENSION_P_TOTAL" name="PENSION_P_TOTAL" class="SmallInput validate[custom[nonNegative]]" data-prompt-position="centerRight:-5,-6"  value=0.00 size="10" readonly>
		</td>
		<td nowrap align="center">
			<input type="text" style="text-align: right;" id="MEDICAL_BASE_TOTAL" name="MEDICAL_BASE_TOTAL" class="SmallInput validate[custom[nonNegative]]" data-prompt-position="centerRight:-5,-6"  value=0.00 size="10" readonly>
		</td>
		<td nowrap align="center">
			<input type="text" style="text-align: right;" id="MEDICAL_U_TOTAL" name="MEDICAL_U_TOTAL" class="SmallInput validate[custom[nonNegative]]" data-prompt-position="centerRight:-5,-6"  value=0.00 size="10" readonly>
		</td>
		<td nowrap align="center">
			<input type="text" style="text-align: right;" id="MEDICAL_P_TOTAL" name="MEDICAL_P_TOTAL" class="SmallInput validate[custom[nonNegative]]" data-prompt-position="centerRight:-5,-6"  value=0.00 size="10" readonly>
		</td>
		<td nowrap align="center">
			<input type="text" style="text-align: right;" id="FERTILITY_BASE_TOTAL" name="FERTILITY_BASE_TOTAL" class="SmallInput validate[custom[nonNegative]]"  data-prompt-position="centerRight:-5,-6" value=0.00 size="10" readonly>
		</td>
		<td nowrap align="center">
			<input type="text" style="text-align: right;" id="FERTILITY_U_TOTAL" name="FERTILITY_U_TOTAL" class="SmallInput validate[custom[nonNegative]]"  data-prompt-position="centerRight:-5,-6" value=0.00 size="10" readonly>
		</td>
		<td nowrap align="center">
			<input type="text" style="text-align: right;" id="UNEMPLOYMENT_BASE_TOTAL" name="UNEMPLOYMENT_BASE_TOTAL" class="SmallInput validate[custom[nonNegative]]" data-prompt-position="centerRight:-5,-6"  value=0.00 size="10" readonly>
		</td>
		<td nowrap align="center">
			<input type="text" style="text-align: right;" id="UNEMPLOYMENT_U_TOTAL" name="UNEMPLOYMENT_U_TOTAL" class="SmallInput validate[custom[nonNegative]]" data-prompt-position="centerRight:-5,-6"  value=0.00 size="10" readonly>
		</td>
		<td nowrap align="center">
			<input type="text" style="text-align: right;" id="UNEMPLOYMENT_P_TOTAL" name="UNEMPLOYMENT_P_TOTAL" class="SmallInput validate[custom[nonNegative]]" data-prompt-position="centerRight:-5,-6"  value=0.00 size="10" readonly>
		</td>
		<td nowrap align="center">
			<input type="text" style="text-align: right;" id="INJURIES_BASE_TOTAL" name="INJURIES_BASE_TOTAL" class="SmallInput validate[custom[nonNegative]]" data-prompt-position="centerRight:-5,-6"  value=0.00 size="10" readonly>
		</td>
		<td nowrap align="center">
			<input type="text" style="text-align: right;" id="INJURIES_U_TOTAL" name="INJURIES_U_TOTAL" class="SmallInput validate[custom[nonNegative]]" data-prompt-position="centerRight:-5,-6"  value=0.00 size="10" readonly>
		</td>
		<td nowrap align="center">
			<input type="text" style="text-align: right;" id="HOUSING_BASE_TOTAL" name="HOUSING_BASE_TOTAL" class="SmallInput validate[custom[nonNegative]]" data-prompt-position="centerRight:-5,-6"  value=0.00 size="10" readonly>
		</td>
		<td nowrap align="center">
			<input type="text" style="text-align: right;" id="HOUSING_U_TOTAL" name="HOUSING_U_TOTAL" class="SmallInput validate[custom[nonNegative]]"  data-prompt-position="centerRight:-5,-6" value=0.00 size="10" readonly>
		</td>
    <td nowrap align="center">
			<input type="text" style="text-align: right;" id="HOUSING_P_TOTAL" name="HOUSING_P_TOTAL" class="SmallInput validate[custom[nonNegative]]"  data-prompt-position="centerRight:-5,-6" value=0.00 size="10" readonly>
    </td>
<?
   }
?>
	</tr>
</table>
<br>
<div align="center" >
	<input type="hidden" value="<?=$STYLE ?>"  name="STYLE">
	<input type="hidden" value="<?=$FLOW_ID ?>"  name="FLOW_ID">
	<input type="hidden" value="<?=$DEPT_ID ?>"  name="DEPT_ID">
	<input type="hidden" value="<?=$STYLE_USER ?>"  name="STYLE_USER">
</div>
</form>
</body>
</html>
<script language="JavaScript">
function clickTitle(ID)
{
  var str1=document.all("STYLE_USER").value;
  var id_value_array=str1.split(",");
  var temp=id_value_array.length-2;
  for(i=0;i<=temp;i++)
  {
  	control=id_value_array[i]+"_"+ID;
  	if(i==0)setvalue=document.all(control).value;
  	document.all(control).value=setvalue;
  }
}
function file_ecxel(){
   location.href="import.php";
}

</script>
<script  defer="defer">
for (var zhi=1;zhi<=parseInt(<?=$COUNT?>); zhi++)
{
total_calculate(zhi);
}
total_calculate('ALL_BASE');
total_calculate('PENSION_BASE');
total_calculate('PENSION_U');
total_calculate('PENSION_P');
total_calculate('MEDICAL_BASE');
total_calculate('MEDICAL_U');
total_calculate('MEDICAL_P');
total_calculate('FERTILITY_BASE');
total_calculate('FERTILITY_U');
total_calculate('UNEMPLOYMENT_BASE');
total_calculate('UNEMPLOYMENT_U');
total_calculate('UNEMPLOYMENT_P');
total_calculate('INJURIES_BASE');
total_calculate('INJURIES_U');
total_calculate('HOUSING_BASE');
total_calculate('HOUSING_U');
total_calculate('HOUSING_P');
</script>