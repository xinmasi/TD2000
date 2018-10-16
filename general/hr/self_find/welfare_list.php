<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");

$HTML_PAGE_TITLE = _("员工福利管理");
include_once("inc/header.inc.php");
?>


<script>
</script>

<body class="bodycolor">
<?
if(!$PAGE_SIZE)
   $PAGE_SIZE = get_page_size("HR_MANAGE", 10);
$PAGE_START=intval($PAGE_START);

//OA管理员 档案管理员 新建人 
$WHERE_STR = hr_priv("STAFF_NAME");

if(!isset($TOTAL_ITEMS))
{
   $query = "SELECT count(*) from HR_WELFARE_MANAGE where STAFF_NAME='".$_SESSION["LOGIN_USER_ID"]."'";
   $cursor= exequery(TD::conn(),$query);
   if($ROW=mysql_fetch_array($cursor))
      $TOTAL_ITEMS=$ROW[0];
}
$TOTAL_ITEMS=intval($TOTAL_ITEMS);
?>
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/infofind.gif" align="absmiddle"><span class="big3"> <?=_("员工福利")?></span>&nbsp;
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
      <td nowrap align="center"><?=_("单位员工")?></td>
      <td nowrap align="center"><?=_("福利项目")?></td>
      <td nowrap align="center"><?=_("工资月份")?></td>
      <td nowrap align="center"><?=_("发放日期")?></td>
      <td nowrap align="center"><?=_("福利金额")?></td>
      <td nowrap align="center"><?=_("操作")?></td>
  </tr>
<?
}
else{
   Message("",_("无员工福利记录"));	
}

$query = "SELECT * from  HR_WELFARE_MANAGE where STAFF_NAME='".$_SESSION["LOGIN_USER_ID"]."' order by PAYMENT_DATE desc limit $PAGE_START, $PAGE_SIZE";
$cursor= exequery(TD::conn(),$query);
$WELFARE_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
   $WELFARE_COUNT++;

   $WELFARE_ID=$ROW["WELFARE_ID"];
   $CREATE_USER_ID=$ROW["CREATE_USER_ID"];
   $CREATE_DEPT_ID=$ROW["CREATE_DEPT_ID"];
   $STAFF_NAME=$ROW["STAFF_NAME"];
   $WELFARE_MONTH=$ROW["WELFARE_MONTH"];
   $PAYMENT_DATE=$ROW["PAYMENT_DATE"];
   $WELFARE_ITEM=$ROW["WELFARE_ITEM"];
   $WELFARE_PAYMENT=$ROW["WELFARE_PAYMENT"];
   $ADD_TIME=$ROW["ADD_TIME"]; 
	 
	 $STAFF_NAME1=substr(GetUserNameById($STAFF_NAME),0,-1);
	 
	 $WELFARE_ITEM=get_hrms_code_name($WELFARE_ITEM,"HR_WELFARE_MANAGE");	 
?>
	<tr class="TableData">
    <td nowrap align="center"><?=$STAFF_NAME1?></td>
    <td nowrap align="center"><?=$WELFARE_ITEM?></td>
    <td nowrap align="center"><?=$WELFARE_MONTH?></td>
    <td nowrap align="center"><?=$PAYMENT_DATE=="0000-00-00"?"":$PAYMENT_DATE;?></td>
    <td nowrap align="center"><?=$WELFARE_PAYMENT?></td>
    <td nowrap align="center">
     <a href="javascript:;" onClick="window.open('welfare_detail.php?WELFARE_ID=<?=$WELFARE_ID?>','','height=500,width=820,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=200,top=100,resizable=yes');"><?=_("详细信息")?></a>&nbsp;
    </td>
  </tr>
<?
}	
?>
</table>
</body>
</html>