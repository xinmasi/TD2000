<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");

$HTML_PAGE_TITLE = _("职称评定管理");
include_once("inc/header.inc.php");
?>


<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/theme/<?=$_SESSION["LOGIN_THEME"]?>/bbs.css">
<script>
</script>

<body class="bodycolor">
<?
if(!$PAGE_SIZE)
   $PAGE_SIZE = get_page_size("HR_MANAGE", 10);
$PAGE_START=intval($PAGE_START);

if(!isset($TOTAL_ITEMS))
{
   $query = "SELECT count(*) from HR_STAFF_TITLE_EVALUATION where BY_EVALU_STAFFS='".$_SESSION["LOGIN_USER_ID"]."' ";
   $cursor= exequery(TD::conn(),$query);
   if($ROW=mysql_fetch_array($cursor))
      $TOTAL_ITEMS=$ROW[0];
}
$TOTAL_ITEMS=intval($TOTAL_ITEMS);
?>
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/infofind.gif" align="absmiddle"><span class="big3"> <?=_("职称评定")?></span>&nbsp;
    </td>
<?
if($TOTAL_ITEMS>0)
{
?>    
    <td align="right" valign="bottom" class="small1"><?=page_bar($PAGE_START,$TOTAL_ITEMS,$PAGE_SIZE,"PAGE_START")?></td>
<?
}
?>
    </tr>
</table>
<?
if($TOTAL_ITEMS>0)
{
?>  
<table class="TableList" width="100%">
  <tr class="TableHeader">
      <td nowrap align="center"><?=_("评定对象")?></td>
      <td nowrap align="center"><?=_("批准人")?></td>
      <td nowrap align="center"><?=_("获取职称")?></td>
      <td nowrap align="center"><?=_("获取方式")?></td>
      <td nowrap align="center"><?=_("获取时间")?></td>
      <td nowrap align="center"><?=_("操作")?></td>
  </tr>
<?
}
else
   Message("",_("无职称评定信息记录"));	

$query = "SELECT * from HR_STAFF_TITLE_EVALUATION where BY_EVALU_STAFFS='".$_SESSION["LOGIN_USER_ID"]."' order by ADD_TIME desc limit $PAGE_START, $PAGE_SIZE";
$cursor= exequery(TD::conn(),$query);
$EVALUATION_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
   $EVALUATION_COUNT++;

   $EVALUATION_ID=$ROW["EVALUATION_ID"];
   $CREATE_USER_ID=$ROW["CREATE_USER_ID"];
   $CREATE_DEPT_ID=$ROW["CREATE_DEPT_ID"];
   $POST_NAME=$ROW["POST_NAME"];
   $GET_METHOD=$ROW["GET_METHOD"];
   $RECEIVE_TIME=$ROW["RECEIVE_TIME"];
   $APPROVE_PERSON=$ROW["APPROVE_PERSON"];
   $BY_EVALU_STAFFS=$ROW["BY_EVALU_STAFFS"];
   $ADD_TIME=$ROW["ADD_TIME"];
   
	 $GET_METHOD_NAME=get_hrms_code_name($GET_METHOD,"HR_STAFF_TITLE_EVALUATION");
	 
	 $BY_EVALU_NAME=substr(GetUserNameById($BY_EVALU_STAFFS),0,-1);
   $APPROVE_PERSON_NAME=substr(GetUserNameById($APPROVE_PERSON),0,-1);
?>
   <tr class="TableData">
      <td nowrap align="center"><?=$BY_EVALU_NAME?></td>
      <td nowrap align="center"><?=$APPROVE_PERSON_NAME?></td>
      <td nowrap align="center"><?=$POST_NAME?></td>
      <td nowrap align="center"><?=$GET_METHOD_NAME?></td>
      <td nowrap align="center"><?=$RECEIVE_TIME=="0000-00-00"?"":$RECEIVE_TIME;?></td>
      <td nowrap align="center">
      <a href="javascript:;" onClick="window.open('evaluation_detail.php?EVALUATION_ID=<?=$EVALUATION_ID?>','','height=500,width=820,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=200,top=100,resizable=yes');"><?=_("详细信息")?></a>&nbsp;
      </td>
   </tr>
<?
}
?>
</table>
</body>

</html>
