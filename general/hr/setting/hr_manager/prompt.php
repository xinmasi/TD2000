<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("微讯提醒日期设置");
include_once("inc/header.inc.php");
?>

<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.5.1/jquery.min.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script type="text/javascript">
function CheckForm()
{
    var reg1 =  /^\d+$/;
  if (document.form1.PROMPT[0].checked == true && document.getElementById('TRIAL_DAY_VALUE').value.trim().match(reg1) == null) 
  {
      alert("<?=_("请输入有效的值")?>");
      return (false);
  }  
  if (document.form1.LABOR[0].checked == true && document.getElementById('LABOR_DAY_VALUE').value.trim().match(reg1) == null) 
  {
      alert("<?=_("请输入有效的值")?>");
      return (false);
  }  
}
function expandIt() 
{  
  whichE1 =document.getElementById("TRIAL_DAY");  
  if (document.form1.PROMPT[0].checked == true) 
  {
   whichE1.style.display = ''; 
  }
  if (document.form1.PROMPT[1].checked == true)
  { 
  	whichE1.style.display = 'none';    
  }
}
function expandIt1() 
{  
  whichE1 =document.getElementById("LABOR_DAY");  
  if (document.form1.LABOR[0].checked == true) 
  {
   whichE1.style.display = ''; 
  }
  if (document.form1.LABOR[1].checked == true)
  { 
  	whichE1.style.display = 'none';    
  }
}
</script>

<body class="bodycolor">
<?
$query = "SELECT PARA_VALUE from  sys_para where PARA_NAME='TRIAL_LABOR_DAY'";
$cursor= exequery(TD::conn(),$query, $connstatus);
if($ROW=mysql_fetch_array($cursor))
{
    $TRIAL_LABOR_DAY=explode(",",$ROW['PARA_VALUE']); 
}

?>
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/training.gif" align="absmiddle"><span class="big3"> <?=_("人事合同提醒设置")?></span>
    </td>
  </tr>
</table>

<div align="center">
 
<table class="TableList" width="90%">
<form action="prompt_submit.php"  method="post" name="form1" onSubmit="return CheckForm();">  
  <tr class="TableHeader" align="center">
    <td width="120"><?=_("选项")?></td>
    <td><?=_("参数")?></td>
    <td width="250"><?=_("备注")?></td>
  </tr>
  <tr class="TableData" align="center" height="30">
    <td width="300"><b><?=_("试用期到期提醒：")?></b></td>
    <td align="left">
       <input type="radio" name="PROMPT" value="1" onClick="expandIt()" <?if($TRIAL_LABOR_DAY[0]!="") echo "checked";?>><label for="YES_PROMPT1"><?=_("是")?></label>
       <input type="radio" name="PROMPT" value="0" onClick="expandIt()" <?if($TRIAL_LABOR_DAY[0]=="") echo "checked";?>><label for="YES_PROMPT2"><?=_("否")?></label>
    </td>
    <td width="300" align="left">
       <?=_("选择是，则可以设置合同试用期截止日期前指定天数自动弹出消息提醒是否转正")?>
    </td>
  </tr>
  
  <tr class="TableData" align="center" height="30" id="TRIAL_DAY" style="display:<?if($TRIAL_LABOR_DAY[0]=="") echo "none";?>">
    <td width="300"><b><?=_("试用期到期提前提醒的天数：")?></b></td>
    <td align="left">
       <input type="text" name="TRIAL_DAY_VALUE" id="TRIAL_DAY_VALUE" value="<?=_($TRIAL_LABOR_DAY[0]) ?>"><?=_("天")?> &nbsp;       
    </td>
    <td width="300" align="left">
       <?=_("天数必须是正整数")?>
    </td>
  </tr>
  
  <tr class="TableData" align="center" height="30">
    <td width="300"><b><?=_("人事合同到期提醒：")?></b></td>
    <td align="left">
       <input type="radio" name="LABOR" value="1" onClick="expandIt1()" <?if($TRIAL_LABOR_DAY[1]!="") echo "checked";?>><label for="YES_LABOR1"><?=_("是")?></label>
       <input type="radio" name="LABOR" value="0" onClick="expandIt1()" <?if($TRIAL_LABOR_DAY[1]=="") echo "checked";?>><label for="YES_LABOR2"><?=_("否")?></label>
    </td>
    <td width="300" align="left">
       <?=_("选择是，则可以设置人事合同到期前指定天数自动弹出消息提醒")?>
    </td>
  </tr>
  
  <tr class="TableData" align="center" height="30" id="LABOR_DAY" style="display:<?if($TRIAL_LABOR_DAY[1]=="") echo "none";?>">
    <td width="300"><b><?=_("人事合同到期提前提醒的天数：")?></b></td>
    <td align="left">
       <input type="text" name="LABOR_DAY_VALUE" id="LABOR_DAY_VALUE" value="<?=_($TRIAL_LABOR_DAY[1]) ?>"><?=_("天")?> &nbsp;       
    </td>
    <td width="300" align="left">
       <?=_("天数必须是正整数")?>
    </td>
  </tr>
  
   <tr>
    <td nowrap  class="TableControl" colspan="3" align="center">
      <input type="submit" value="<?=_("确定")?>" class="BigButton">&nbsp;&nbsp;
    </td>
   </tr> 
  </form>
</table>

</body>
</html>