<?
include_once("inc/auth.inc.php");
include_once("inc/utility.php");

$query = "select FLOW_ID_STR,RUN_ID_STR FROM PROJ_TASK WHERE TASK_ID='$TASK_ID'";
$cursor = exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
	 $FLOW_ID_STR = $ROW["FLOW_ID_STR"];
	 $RUN_ID_STR = $ROW["RUN_ID_STR"];
}
$FLOW_ID_ARR=explode(",",$FLOW_ID_STR);
$RUN_ID_ARR=explode(",",$RUN_ID_STR);

$ARR1=array(); //����
$ARR2=array(); //������
$ARR3=array(); //�ѽ���

$query = "select RUN_ID,FLOW_ID,END_TIME FROM FLOW_RUN WHERE FIND_IN_SET(RUN_ID,'$RUN_ID_STR')";
$cursor = exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
  $RUN_ID=$ROW["RUN_ID"];
  $FLOW_ID=$ROW["FLOW_ID"];
  $END_TIME=$ROW["END_TIME"];
  if(find_id($FLOW_ID_STR,$FLOW_ID))
  {
  	if($END_TIME=="")
      $ARR2[]=$RUN_ID;
    else
      $ARR3[]=$RUN_ID;
  }
  $FLOW_ING_STR.=$FLOW_ID.",";
}

$FLOW_ING_ARR = explode(",",$FLOW_ING_STR);
$FLOW_ID_ARR = array_filter($FLOW_ID_ARR);

if(is_array($FLOW_ING_ARR))
{
   foreach($FLOW_ING_ARR as $k => $v)
   {
      foreach($FLOW_ID_ARR as $m => $n)
      {
         if($v == $n)
         {
            unset($FLOW_ID_ARR[$m]);
            break;   
         }
      }   
   }
}
$ARR1 = $FLOW_ID_ARR;
sort($ARR1);

$HTML_PAGE_TITLE = _("��Ŀ����");
include_once("inc/header.inc.php");
?>


<script>
//-------------zfc-----------------
var vig = false;
function view_graph(FLOW_ID)
{
    if(vig)
       vig.close();
  myleft=(screen.availWidth-800)/2;
  vig = window.open("/general/workflow/list/flow_view/view_graph.php?FLOW_ID="+FLOW_ID,"","status=0,toolbar=no,menubar=no,location=no,scrollbars=yes,resizable=yes,width=800,height=500,left="+myleft+",top=50");
}
var fov = false;
function form_view(RUN_ID,FLOW_ID)
{
    if(fov)
        fov.close();
  fov = window.open("/general/workflow/list/print/?RUN_ID="+RUN_ID+"&FLOW_ID="+FLOW_ID,"","status=0,toolbar=no,menubar=no,width="+(screen.availWidth-12)+",height="+(screen.availHeight-38)+",location=no,scrollbars=yes,resizable=yes,left=0,top=0");
}

var flov = false;
function flow_view(FLOW_ID)
{
    if(flov)
        flov.close();
  myleft=(screen.availWidth-800)/2;
  flov = window.open("/general/workflow/flow_view.php?FLOW_ID="+FLOW_ID,FLOW_ID,"status=0,toolbar=no,menubar=no,location=no,scrollbars=yes,resizable=yes,width=800,height=400,left="+myleft+",top=100");
}


var work_flow_z = false;
function work_flow_banli(url){
    if(work_flow_z)
        work_flow_z.close();
    work_flow_z = window.open(url,"","status=0,toolbar=no,menubar=no,location=no,scrollbars=yes,resizable=yes,width="+(screen.width-15)+",height="+(screen.height-100)+",left=0,top=0");
}

</script>

<body class="bodycolor">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
 <tr>
  <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/project.gif" align="absmiddle"><span class="big3"> <?=_("��Ŀ����")?>-<?=_("����")?></span>
  </td>
 </tr>
</table>
<?
if(count($ARR1)>0)
{
  foreach($ARR1 AS $k => $v)
  {
  	if($k==0)
  	{
?>
<table class="TableList" width="100%">
  <tr class="TableHeader">
    <td nowrap align="center" width="100"><?=_("��������")?></td>
    <td nowrap align="center"><?=_("��������")?></td>
    <td nowrap align="center"><?=_("����")?></td>
  </tr>
<?
     }
      $query = "select FLOW_NAME,FLOW_TYPE FROM FLOW_TYPE WHERE FLOW_ID='$v'";
      $cursor = exequery(TD::conn(),$query);
      if($ROW=mysql_fetch_array($cursor))
      {
      	$FLOW_NAME=$ROW["FLOW_NAME"];
      	$FLOW_TYPE=$ROW["FLOW_TYPE"];
      	if($FLOW_TYPE==1) $TYPE_DESC=_("�̶�����");
      	else $TYPE_DESC=_("��������");
      }
      if($v%2==0) $TableLine="TableLine1";
      else $TableLine="TableLine2";
?>
  <tr class="<?=$TableLine?>">
    <td nowrap align="center" width="100"><?=$TYPE_DESC?></td>
    <td nowrap align="center"><a href="javascript:flow_view(<?=$v?>)"><?=$FLOW_NAME?></a></td>
    <td nowrap align="center"><a href="#" onclick="work_flow_banli('insert.php?FLOW_ID=<?=$v?>&PROJ_ID=<?=$PROJ_ID?>&TASK_ID=<?=$TASK_ID?>')"><?=_("�½�����")?></a></td>
  </tr>
<?
  }
  echo "</table>";
}
else
  Message("",_("�޴�����Ŀ����!"));
?>
<br> 
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
 <tr>
  <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/project.gif" align="absmiddle"><span class="big3"> <?=_("��Ŀ����")?>-<?=_("������")?></span>
  </td>
 </tr>
</table>
<?
if(count($ARR2)>0)
{
  foreach($ARR2 AS $k => $v)
  {
  	if($k==0)
  	{
?>
<table class="TableList" width="100%">
  <tr class="TableHeader">
    <td nowrap align="center"><?=_("��ˮ��")?></td>
    <td nowrap align="center"><?=_("��������/�ĺ�")?></td>
    <td nowrap align="center"><?=_("����")?></td>
  </tr>
<?
     }
      $query = "select FLOW_ID,RUN_NAME FROM FLOW_RUN WHERE RUN_ID='$v'";
      $cursor = exequery(TD::conn(),$query);
      if($ROW=mysql_fetch_array($cursor))
      {
      	$FLOW_ID=$ROW["FLOW_ID"];
      	$RUN_NAME=$ROW["RUN_NAME"];
      	if($FLOW_TYPE==1) $TYPE_DESC=_("�̶�����");
      	else $TYPE_DESC=_("��������");
      }
      if($k%2==0) $TableLine="TableLine1";
      else $TableLine="TableLine2";
      
?>
  <tr class="<?=$TableLine?>">
    <td nowrap align="center" width=100><?=$v?></td>
    <td><a href="javascript:form_view(<?=$v?>,<?=$FLOW_ID?>);"><?=$RUN_NAME?></a></td>
<?
$query = "select 1 FROM FLOW_RUN_PRCS WHERE RUN_ID='$v' and USER_ID='".$_SESSION["LOGIN_USER_ID"]."' AND PRCS_FLAG IN (1,2)";
$cursor = exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
?>
    <td nowrap align="center"><a href="#" onclick="work_flow_banli('/general/workflow/list/input_form/?FLOW_ID=<?=$FLOW_ID?>&RUN_ID=<?=$v?>&PRCS_ID=1&FLOW_PRCS=1')"><?=_("����")?></a></td>
<?
}
else
   echo '<td nowrap align="center"><font color=green>'._("������").'</font></td>';
?>
  </tr>
<?
  }
  echo "</table>";
}
else
  Message("",_("�޽�������Ŀ����!"));
?>
<br>
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
 <tr>
  <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/project.gif" align="absmiddle"><span class="big3"> <?=_("��Ŀ����")?>-<?=_("�ѽ���")?></span>
  </td>
 </tr>
</table>
<?
if(count($ARR3)>0)
{
  foreach($ARR3 AS $k => $v)
  {
  	if($k==0)
  	{
?>
<table class="TableList" width="100%">
  <tr class="TableHeader">
    <td nowrap align="center"><?=_("��ˮ��")?></td>
    <td nowrap align="center"><?=_("��������/�ĺ�")?></td>
  </tr>
<?
     }
      $query = "select FLOW_ID,RUN_NAME FROM FLOW_RUN WHERE RUN_ID='$v'";
      $cursor = exequery(TD::conn(),$query);
      if($ROW=mysql_fetch_array($cursor))
      {
      	$FLOW_ID=$ROW["FLOW_ID"];
      	$RUN_NAME=$ROW["RUN_NAME"];
      	if($FLOW_TYPE==1) $TYPE_DESC=_("�̶�����");
      	else $TYPE_DESC=_("��������");
      }
      if($k%2==0) $TableLine="TableLine1";
      else $TableLine="TableLine2";
?>
  <tr class="<?=$TableLine?>">
    <td nowrap align="center" width=100><?=$v?></td>
    <td><a href="javascript:form_view(<?=$v?>,<?=$FLOW_ID?>);"><?=$RUN_NAME?></a></td>
  </tr>
<?
  }
  echo "</table>";
}
else
  Message("",_("���ѽ�������Ŀ����!"));
?>

</body>
</html>