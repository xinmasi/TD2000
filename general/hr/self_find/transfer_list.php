<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");

$HTML_PAGE_TITLE = _("人事调动管理");
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
   $query = "SELECT count(*) from HR_STAFF_TRANSFER where TRANSFER_PERSON='".$_SESSION["LOGIN_USER_ID"]."' ";
   $cursor= exequery(TD::conn(),$query);
   if($ROW=mysql_fetch_array($cursor))
      $TOTAL_ITEMS=$ROW[0];
}
$TOTAL_ITEMS=intval($TOTAL_ITEMS);
?>
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/infofind.gif" align="absmiddle"><span class="big3"> <?=_("人事调动")?></span>&nbsp;
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
      <td nowrap align="center"><?=_("调动人员")?></td>
      <td nowrap align="center"><?=_("调动类型")?></td>
      <td nowrap align="center"><?=_("调动日期")?></td>
      <td nowrap align="center"><?=_("调动生效日期")?></td>
      <td nowrap align="center"><?=_("经办人")?></td>
      <td nowrap align="center"><?=_("操作")?></td>
  </tr>
<?
}
else
   Message("",_("无人事调动信息记录"));	

$query = "SELECT * from HR_STAFF_TRANSFER where TRANSFER_PERSON='".$_SESSION["LOGIN_USER_ID"]."' order by ADD_TIME desc limit $PAGE_START, $PAGE_SIZE";
$cursor= exequery(TD::conn(),$query);
$TRANSFER_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
   $TRANSFER_COUNT++;

   $TRANSFER_ID=$ROW["TRANSFER_ID"];
   $CREATE_USER_ID=$ROW["CREATE_USER_ID"];
   $CREATE_DEPT_ID=$ROW["CREATE_DEPT_ID"];
   $TRANSFER_PERSON=$ROW["TRANSFER_PERSON"];
   $TRANSFER_TYPE=$ROW["TRANSFER_TYPE"];
   $TRANSFER_DATE=$ROW["TRANSFER_DATE"];
   $TRANSFER_EFFECTIVE_DATE=$ROW["TRANSFER_EFFECTIVE_DATE"];
   $RESPONSIBLE_PERSON=$ROW["RESPONSIBLE_PERSON"];
   $ADD_TIME=$ROW["ADD_TIME"];
   
	 $TRANSFER_TYPE=get_hrms_code_name($TRANSFER_TYPE,"HR_STAFF_TRANSFER");
	 
	 $TRANSFER_PERSON_NAME=substr(GetUserNameById($TRANSFER_PERSON),0,-1);
	 $RESPONSIBLE_PERSON_NAME=substr(GetUserNameById($RESPONSIBLE_PERSON),0,-1);
?>
   <tr class="TableData">
      <td nowrap align="center"><?=$TRANSFER_PERSON_NAME?></td>
      <td nowrap align="center"><?=$TRANSFER_TYPE?></td>
      <td nowrap align="center"><?=$TRANSFER_DATE=="0000-00-00"?"":$TRANSFER_DATE;?></td>
      <td nowrap align="center"><?=$TRANSFER_EFFECTIVE_DATE=="0000-00-00"?"":$TRANSFER_EFFECTIVE_DATE;?></td>
      <td nowrap align="center"><?=$RESPONSIBLE_PERSON_NAME?></td>
      <td nowrap align="center">
      <a href="javascript:;" onClick="window.open('transfer_detail.php?TRANSFER_ID=<?=$TRANSFER_ID?>','','height=500,width=820,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=200,top=100,resizable=yes');"><?=_("详细信息")?></a>&nbsp;
      </td>
   </tr>
<?
}
?>
</table>
</body>

</html>
