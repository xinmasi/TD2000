<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");

$HTML_PAGE_TITLE = _("证照信息");
include_once("inc/header.inc.php");
?>
<link rel="stylesheet" type="text/css" href="/theme/<?=$_SESSION['LOGIN_THEME']?>/bbs.css">
<script>
</script>

<body class="bodycolor">
<?
if(!$PAGE_SIZE)
   $PAGE_SIZE = get_page_size("HR_MANAGE", 10);
$PAGE_START=intval($PAGE_START);
if(!isset($TOTAL_ITEMS))
{
   $query = "SELECT count(*) from HR_STAFF_LICENSE where STAFF_NAME='$USER_ID'";
   $cursor= exequery(TD::conn(),$query);
   if($ROW=mysql_fetch_array($cursor))
      $TOTAL_ITEMS=$ROW[0];
}
$TOTAL_ITEMS=intval($TOTAL_ITEMS);
?>
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/infofind.gif" align="absmiddle"><span class="big3"> <?=_("证照信息")?></span>&nbsp;
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
  <div align="center">
     <input type="button" value="<?=_("新建证照信息")?>" class="BigButton" onClick="window.open('license_new.php?USER_ID=<?=$USER_ID?>','','height=500,width=820,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=200,top=100,resizable=yes');">
  </div>
  <br>
<?
if($TOTAL_ITEMS>0)
{
?>  
<table class="TableList" width="100%">
  <tr class="TableHeader">
      <td nowrap align="center"><?=_("单位员工")?></td>
      <td nowrap align="center"><?=_("证照类型")?></td>
      <td nowrap align="center"><?=_("证照编号")?></td>
      <td nowrap align="center"><?=_("证照名称")?></td>
      <td nowrap align="center"><?=_("状态")?></td>
      <td nowrap align="center"><?=_("操作")?></td>
  </tr>
<?
}
else
{
   Message("",_("无员工证照信息记录"));
}
$query = "SELECT * from  HR_STAFF_LICENSE where STAFF_NAME='$USER_ID' order by ADD_TIME desc limit $PAGE_START, $PAGE_SIZE";
$cursor= exequery(TD::conn(),$query);
$LICENSE_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
   $LICENSE_COUNT++;

   $LICENSE_ID=$ROW["LICENSE_ID"];
   $CREATE_USER_ID=$ROW["CREATE_USER_ID"];
   $CREATE_DEPT_ID=$ROW["CREATE_DEPT_ID"];
   $STAFF_NAME=$ROW["STAFF_NAME"];
   $LICENSE_TYPE=$ROW["LICENSE_TYPE"];
   $LICENSE_NO=$ROW["LICENSE_NO"];
   $LICENSE_NAME=$ROW["LICENSE_NAME"];
   $STATUS=$ROW["STATUS"];
   $ADD_TIME=$ROW["ADD_TIME"]; 
	 
	 $STAFF_NAME1=substr(GetUserNameById($STAFF_NAME),0,-1);
	 
	 $LICENSE_TYPE=get_hrms_code_name($LICENSE_TYPE,"HR_STAFF_LICENSE1");
	 $STATUS=get_hrms_code_name($STATUS,"HR_STAFF_LICENSE2");
	 
?>
   <tr class="TableData">
      <td nowrap align="center"><?=$STAFF_NAME1?></td>
      <td nowrap align="center"><?=$LICENSE_TYPE?></td>
      <td nowrap align="center"><?=$LICENSE_NO?></td>
      <td nowrap align="center"><?=$LICENSE_NAME?></td>
      <td nowrap align="center"><?=$STATUS?></td>
      <td nowrap align="center">
      <a href="javascript:;" onClick="window.open('license_detail.php?LICENSE_ID=<?=$LICENSE_ID?>','','height=500,width=820,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=200,top=100,resizable=yes');"><?=_("详细信息")?></a>&nbsp;
      <a href="javascript:;" onClick="window.open('license_modify.php?LICENSE_ID=<?=$LICENSE_ID?>','','height=500,width=820,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=200,top=100,resizable=yes');"><?=_("编辑")?></a>&nbsp;
      </td>
   </tr>
<?
}
?>

</table>
</body>

</html>
