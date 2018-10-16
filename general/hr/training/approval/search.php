<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

$T_PLAN_NO0=$T_PLAN_NO;
$T_CHANNEL0=$T_CHANNEL;
$ASSESSING_OFFICER0=$ASSESSING_OFFICER;
$ASSESSING_STATUS0=$ASSESSING_STATUS;
$ASSESSING_TIME10=$ASSESSING_TIME1;
$ASSESSING_TIME20=$ASSESSING_TIME2;

$HTML_PAGE_TITLE = _("计划(审批)查询结果");
include_once("inc/header.inc.php");
?>


<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/theme/<?=$_SESSION["LOGIN_THEME"]?>/dialog.css">
<script src="<?=MYOA_JS_SERVER?>/static/js/dialog.js"></script>
<script language="javascript" src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.5.1/jquery.min.js<?=$GZIP_POSTFIX?>"></script>
<script type="text/javascript">
var $ = function(id) {return document.getElementById(id);};
function approval(T_PLAN_ID,PASS)
{
	if(PASS==1)
	  var msg="<?=_("确认要审批通过此计划申请吗？请填写审批意见：")?>";
	else
		var msg="<?=_("确认要驳回此计划申请吗？请填写驳回理由：")?>";
	$("confirm").innerHTML="<font color=red>"+msg+"</font>";
	$("T_PLAN_ID").value=T_PLAN_ID;
	$("PASS").value=PASS;
  ShowDialog('comment');
}
function ShowDetial(T_PLAN_ID)
{
	myleft=(screen.availWidth-800)/2;
  window.open("../plan/plan_detail.php?T_PLAN_ID="+T_PLAN_ID,"","status=0,toolbar=no,menubar=no,location=no,scrollbars=yes,resizable=yes,width=800,height=600,left="+myleft+",top=50");
}
function check_form()
{
	if(document.getElementById("assessing_view").value=="")
	{
		alert("<?=_("请填写审批意见！")?>");
		return(false);
	}
	return(true);
}
</script>


<body class="bodycolor">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
	<tr><td>
    <img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/hr_training.gif" align="absmiddle"/>
    <span class="big3"><?=_("计划(审批)查询结果")?></span>
	<td></tr>
</table>

<table class="TableList" width="100%">
<?
//------------------------ 生成条件字符串 ------------------
$CONDITION_STR="";
if($T_PLAN_NAME!="")
   $CONDITION_STR.=" and T_PLAN_NAME='$T_PLAN_NAME'";
if($T_CHANNEL!="")
   $CONDITION_STR.=" and T_CHANNEL='$T_CHANNEL'";
if($ASSESSING_OFFICER!="")
  $CONDITION_STR.=" and ASSESSING_OFFICER='$ASSESSING_OFFICER'";
if($ASSESSING_STATUS!="")
  $CONDITION_STR.=" and ASSESSING_STATUS='$ASSESSING_STATUS'";
if($ASSESSING_TIME1!="")
  $CONDITION_STR.=" and ASSESSING_TIME>='$ASSESSING_TIME1'";   
if($ASSESSING_TIME2!="")
  $CONDITION_STR.=" and ASSESSING_TIME<='$ASSESSING_TIME2'";
  
$query = "SELECT * from HR_TRAINING_PLAN WHERE (ASSESSING_OFFICER='".$_SESSION["LOGIN_USER_ID"]."') ".$CONDITION_STR." ORDER BY T_PLAN_ID desc";
$cursor = exequery(TD::conn(),$query);
$COUNT =0;
while($ROW=mysql_Fetch_array($cursor))
{
	 $COUNT++;
	 
	 $T_CHANNEL = $ROW["T_CHANNEL"];
   $ASSESSING_STATUS = $ROW["ASSESSING_STATUS"];
   $T_COURSE_TYPES = $ROW["T_COURSE_TYPES"];
   $T_PLAN_NO =	$ROW["T_PLAN_NO"];
   $T_PLAN_NAME = $ROW["T_PLAN_NAME"];
   $T_ADDRESS = $ROW["T_ADDRESS"];
   $T_PLAN_ID =	$ROW["T_PLAN_ID"];

   if($T_CHANNEL=="0") 
    	$T_CHANNEL=_("内部培训");
   if($T_CHANNEL=="1")
    	$T_CHANNEL=_("渠道培训"); 
    	 

   $T_COURSE_TYPES=get_hrms_code_name($T_COURSE_TYPES,"T_COURSE_TYPE");
   
   if($ASSESSING_STATUS=="0") 
    	$ASSESSING_STATUS_STR=_("待审批");
   if($ASSESSING_STATUS=="1")
    	$ASSESSING_STATUS_STR="<font color=green>"._("已批准")."</font>"; 
   if($ASSESSING_STATUS=="2")
    	$ASSESSING_STATUS_STR="<font color=red>"._("未通过")."</font>";   	  
    	      
	 if($COUNT==1)
	    echo '
  <tr class="TableHeader">
    <td nowrap align="center">'._("培训计划编号").'</td>
    <td nowrap align="center">'._("培训计划名称").'</td>
    <td nowrap align="center">'._("培训渠道").'</td>
    <td nowrap align="center">'._("培训形式").'</td>
    <td nowrap align="center">'._("培训地点").'</td>
    <td nowrap align="center">'._("计划状态").'</td>
    <td nowrap align="center">'._("操作").'</td>
  </tr>';

	 if($COUNT%2==1)
	    $TableLine='TableLine1';
	 else
	    $TableLine='TableLine2';
  $aaa='<tr class="'.$TableLine.'">
	  <td nowrap align="center">'.$T_PLAN_NO.'</td>
	  <td align="center">'.$T_PLAN_NAME.'</td>
	  <td nowrap align="center">'.$T_CHANNEL.'</td>
	  <td nowrap align="center">'.$T_COURSE_TYPES.'</td>
	  <td nowrap align="center">'.$T_ADDRESS.'</td>
	  <td nowrap align="center">'.$ASSESSING_STATUS_STR.'</td>
	  <td nowrap align="center">
	  	<a href="#" onclick=ShowDetial("'.$T_PLAN_ID.'")>'._("详细信息").'</a>&nbsp;';

  if($ASSESSING_STATUS==0)
  {
     $aaa.='<a href="#" onclick=approval("'.$ROW["T_PLAN_ID"].'","1")>'._("批准").'</a>&nbsp;&nbsp;';
     $aaa.='<a href="#" onclick=approval("'.$T_PLAN_ID.'","0")>'._("拒绝").'</a>&nbsp;';
  }          
  echo $aaa.='</td></tr>';
}
?>	
</table>
<?
if($COUNT==0)
{
   Message(_("提示"),_("无符合条件的计划信息！"));
   Button_Back();
   exit;
}
?>
<div id="overlay"></div>
<div id="comment" class="ModalDialog" style="width:500px;">
  <div class="header"><span id="title" class="title"><?=_("审批意见")?></span><a class="operation" href="javascript:HideDialog('comment');"><img src="<?=MYOA_STATIC_SERVER?>/static/images/close.png"/></a></div>
  <form name="form1" method="post" onSubmit="return check_form();" action="approval.php">
  <div id="detail_body" class="body">
  <span id="confirm"></span>
  <textarea id="assessing_view" name="ASSESSING_VIEW" cols="60" rows="5" style="overflow-y:auto;" class="BigInput" wrap="yes"></textarea>
  </div>
  <input type="hidden" name="T_PLAN_ID" id="T_PLAN_ID">
  <input type="hidden" name="PASS" id="PASS">
<!-- 传递查询条件begin  -->  
  <input type="hidden" name="T_PLAN_NO" id="T_PLAN_NO" value="<?=$T_PLAN_NO0?>">
  <input type="hidden" name="T_CHANNEL" id="T_CHANNEL" value="<?=$T_CHANNEL0?>">
  <input type="hidden" name="ASSESSING_OFFICER" id="ASSESSING_OFFICER" value="<?=$ASSESSING_OFFICER0?>">
  <input type="hidden" name="ASSESSING_STATUS" id="ASSESSING_STATUS" value="<?=$ASSESSING_STATUS0?>">
  <input type="hidden" name="ASSESSING_TIME1" id="ASSESSING_TIME1" value="<?=$ASSESSING_TIME10?>">
  <input type="hidden" name="ASSESSING_TIME2" id="ASSESSING_TIME2" value="<?=$ASSESSING_TIME20?>">
<!-- 传递查询条件end  -->   
  <div id="footer" class="footer">
    <input class="BigButton" type="submit" value="<?=_("确定")?>"/>
    <input class="BigButton" onClick="HideDialog('comment')" type="button" value="<?=_("关闭")?>"/>
  </div>
  </form>  
</div>
</br>
<center><input type="button" class="BigButtonA" value="返回" onClick="history.back();"></center>
</body>
</html>