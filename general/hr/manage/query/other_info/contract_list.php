<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");

$HTML_PAGE_TITLE = _("合同管理");
include_once("inc/header.inc.php");
?>


<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/theme/<?=$_SESSION["LOGIN_THEME"]?>/bbs.css">
<script>
</script>

<body class="bodycolor" topmargin="5">
<?
if(!$PAGE_SIZE)
   $PAGE_SIZE = get_page_size("HR_MANAGE", 10);
$PAGE_START=intval($PAGE_START);


if(!isset($TOTAL_ITEMS))
{
   $query = "SELECT count(*) from HR_STAFF_CONTRACT where STAFF_NAME='$USER_ID'";
   $cursor= exequery(TD::conn(),$query);
   if($ROW=mysql_fetch_array($cursor))
      $TOTAL_ITEMS=$ROW[0];
}
$TOTAL_ITEMS=intval($TOTAL_ITEMS);
?>
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/infofind.gif" align="absmiddle"><span class="big3"> <?=_("个人合同")?></span>&nbsp;
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
    <input type="button" value="<?=_("新建合同信息")?>" class="BigButton" onClick="window.open('contract_new.php?USER_ID=<?=$USER_ID?>','','height=500,width=820,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=200,top=100,resizable=yes');">
</div>
<br>
<?
if($TOTAL_ITEMS>0)
{
?>  
<table class="TableList" width="100%">
  <tr class="TableHeader">
      <td nowrap align="center"><?=_("员工姓名")?></td>
      <td nowrap align="center"><?=_("合同编号")?></td>
      <td nowrap align="center"><?=_("合同类型")?></td>
      <td nowrap align="center"><?=_("合同签订日期")?></td>
      <td nowrap align="center"><?=_("合同状态")?></td>
      <td nowrap align="center"><?=_("操作")?></td>
  </tr>
<?
}
else
{
   Message("",_("无员工合同记录"));
}
$query = "SELECT * from  HR_STAFF_CONTRACT where STAFF_NAME='$USER_ID' order by ADD_TIME desc limit $PAGE_START, $PAGE_SIZE";
$cursor= exequery(TD::conn(),$query);
$CONTRACT_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
   $CONTRACT_COUNT++;

   $CONTRACT_ID=$ROW["CONTRACT_ID"];
   $CREATE_USER_ID=$ROW["CREATE_USER_ID"];
   $CREATE_DEPT_ID=$ROW["CREATE_DEPT_ID"];
   $STAFF_NAME=$ROW["STAFF_NAME"];
   $STAFF_CONTRACT_NO=$ROW["STAFF_CONTRACT_NO"];
   $CONTRACT_TYPE=$ROW["CONTRACT_TYPE"];
   $MAKE_CONTRACT=$ROW["MAKE_CONTRACT"];
   $STATUS=$ROW["STATUS"];
   $ADD_TIME=$ROW["ADD_TIME"]; 
	 
	 $STAFF_NAME1=substr(GetUserNameById($STAFF_NAME),0,-1);
	 
	 $CONTRACT_TYPE=get_hrms_code_name($CONTRACT_TYPE,"HR_STAFF_CONTRACT1");
	 $STATUS=get_hrms_code_name($STATUS,"HR_STAFF_CONTRACT2");
         if ($MAKE_CONTRACT == "0000-00-00")
        $MAKE_CONTRACT="";
	 
?>
   <tr class="TableData">
      <td nowrap align="center"><?=$STAFF_NAME1?></td>
      <td nowrap align="center"><?=$STAFF_CONTRACT_NO?></td>
      <td nowrap align="center"><?=$CONTRACT_TYPE?></td>
      <td nowrap align="center"><?=$MAKE_CONTRACT?></td>
      <td nowrap align="center"><?=$STATUS?></td>
      <td nowrap align="center">
      <a href="javascript:;" onClick="window.open('contract_detail.php?CONTRACT_ID=<?=$CONTRACT_ID?>','','height=500,width=820,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=200,top=100,resizable=yes');"><?=_("详细信息")?></a>&nbsp;
      <a href="javascript:;" onClick="window.open('contract_modify.php?CONTRACT_ID=<?=$CONTRACT_ID?>','','height=500,width=820,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=200,top=100,resizable=yes');"><?=_("编辑")?></a>&nbsp;
      </td>
   </tr>
<?
}
?>

</table>
</body>

</html>
