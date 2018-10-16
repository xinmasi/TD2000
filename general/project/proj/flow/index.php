<?
include_once("inc/auth.inc.php");
include_once("inc/utility.php");

$query = "select RUN_ID_STR FROM PROJ_TASK WHERE PROJ_ID='$PROJ_ID'";
$cursor = exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
	 $RUN_ID_STR .= $ROW["RUN_ID_STR"].",";
}



$HTML_PAGE_TITLE = _("项目流程");
include_once("inc/header.inc.php");
?>


<script>
//--------zfc-------------
var c_g = false;
function view_graph(FLOW_ID)
{
    if(c_g)
     c_g.close();
  myleft=(screen.availWidth-800)/2;
  c_g = window.open("/general/workflow/list/view_graph/?FLOW_ID="+FLOW_ID,"","status=0,toolbar=no,menubar=no,location=no,scrollbars=yes,resizable=yes,width=800,height=500,left="+myleft+",top=50");
}

var f_v_w = false;
function form_view(RUN_ID,FLOW_ID)
{
  if(f_v_w)
      f_v_w.close();
  f_v_w = window.open("/general/workflow/list/print/?RUN_ID="+RUN_ID+"&FLOW_ID="+FLOW_ID,"","status=0,toolbar=no,menubar=no,width="+(screen.availWidth-12)+",height="+(screen.availHeight-38)+",location=no,scrollbars=yes,resizable=yes,left=0,top=0");
}
var flow_w = false;
function flow_view(RUN_ID,FLOW_ID)
{
    if(flow_W)
        flow_W.close();
  myleft=(screen.availWidth-800)/2;
  flow_W = window.open("/general/workflow/list/flow_view/?RUN_ID="+RUN_ID+"&FLOW_ID="+FLOW_ID,RUN_ID,"status=0,toolbar=no,menubar=no,location=no,scrollbars=yes,resizable=yes,width=800,height=400,left="+myleft+",top=100");
}
</script>

<body class="bodycolor">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
 <tr>
  <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/project.gif" align="absmiddle"><span class="big3"> <?=_("项目流程")?></span>
  </td>
 </tr>
</table>
<?
$COUNT=0;
$query = "select RUN_ID,FLOW_ID,RUN_NAME,END_TIME,BEGIN_USER,BEGIN_TIME FROM FLOW_RUN WHERE FIND_IN_SET(RUN_ID,'$RUN_ID_STR')";
$cursor = exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
	$COUNT++;
	$RUN_ID=$ROW["RUN_ID"];
	$FLOW_ID=$ROW["FLOW_ID"];
	$BEGIN_USER=$ROW["BEGIN_USER"];
	$BEGIN_TIME=$ROW["BEGIN_TIME"];
	$RUN_NAME=$ROW["RUN_NAME"];
	$END_TIME=$ROW["END_TIME"];
	
	$query = "select USER_NAME FROM USER WHERE USER_ID='$BEGIN_USER'";
  $cursor1 = exequery(TD::conn(),$query);
  if($ROW=mysql_fetch_array($cursor1))
    $BEGIN_NAME=$ROW["USER_NAME"];
	if($END_TIME=="")
	  $STATUS="<font color=green>"._("进行中")."</font>";
	else
	  $STATUS="<font color=red>"._("已结束")."</font>";
  if($COUNT==1)
  {
?>
<table class="TableList" width="100%">
  <tr class="TableHeader">
    <td nowrap align="center"><?=_("流水号")?></td>
    <td nowrap align="center"><?=_("工作名称/文号")?></td>
    <td nowrap align="center"><?=_("办理人")?></td>
    <td nowrap align="center"><?=_("开始时间")?></td>
    <td nowrap align="center"><?=_("当前状态")?></td>
  </tr>
<?
   }

   if($v%2==0) $TableLine="TableLine1";
   else $TableLine="TableLine2";
?>
  <tr class="<?=$TableLine?>">
    <td nowrap align="center" width=100><?=$RUN_ID?></td>
    <td nowrap align="center"><a href="javascript:form_view(<?=$RUN_ID?>,<?=$FLOW_ID?>);"><?=$RUN_NAME?></a></td>
    <td nowrap align="center"><?=$BEGIN_NAME?></td>
    <td nowrap align="center"><?=$BEGIN_TIME?></td>
    <td nowrap align="center"><?=$STATUS?></td>
  </tr>
<?
}
if($COUNT>0)
  echo "</table>";
else
  Message("",_("无项目流程!"));
?>

</body>
</html>