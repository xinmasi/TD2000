<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");

$HTML_PAGE_TITLE = _("员工离职管理");
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

//OA管理员 档案管理员 新建人 
$WHERE_STR = hr_priv("LEAVE_PERSON");
if(!isset($TOTAL_ITEMS))
{
   $query = "SELECT count(*) from HR_STAFF_LEAVE where LEAVE_PERSON='".$_SESSION["LOGIN_USER_ID"]."' ";
   $cursor= exequery(TD::conn(),$query);
   if($ROW=mysql_fetch_array($cursor))
      $TOTAL_ITEMS=$ROW[0];
}
$TOTAL_ITEMS=intval($TOTAL_ITEMS);
?>
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/infofind.gif" align="absmiddle"><span class="big3"> <?=_("员工离职")?></span>&nbsp;
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
      <td nowrap align="center"><?=_("离职人员")?></td>
      <td nowrap align="center"><?=_("离职类型")?></td>
      <td nowrap align="center"><?=_("拟离职日期")?></td>
      <td nowrap align="center"><?=_("工资截止日期")?></td>
      <td nowrap align="center"><?=_("操作")?></td>
  </tr>
<?
}
else
   Message("",_("无员工离职信息记录"));	

$query = "SELECT * from HR_STAFF_LEAVE where LEAVE_PERSON='".$_SESSION["LOGIN_USER_ID"]."' order by ADD_TIME desc limit $PAGE_START, $PAGE_SIZE";
$cursor= exequery(TD::conn(),$query);
$LEAVE_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
   $LEAVE_COUNT++;

   $LEAVE_ID=$ROW["LEAVE_ID"];
   $CREATE_USER_ID=$ROW["CREATE_USER_ID"];
   $CREATE_DEPT_ID=$ROW["CREATE_DEPT_ID"];
   $QUIT_TYPE=$ROW["QUIT_TYPE"];
   $LAST_SALARY_TIME=$ROW["LAST_SALARY_TIME"];
   $QUIT_TIME_PLAN=$ROW["QUIT_TIME_PLAN"];
   $LEAVE_PERSON=$ROW["LEAVE_PERSON"];
   $ADD_TIME=$ROW["ADD_TIME"];
   
	 $QUIT_TYPE=get_hrms_code_name($QUIT_TYPE,"HR_STAFF_LEAVE");
	 
	 $LEAVE_PERSON_NAME=substr(GetUserNameById($LEAVE_PERSON),0,-1);
	 
?>
   <tr class="TableData">
      <td nowrap align="center"><?=$LEAVE_PERSON_NAME?></td>
      <td nowrap align="center"><?=$QUIT_TYPE?></td>
      <td nowrap align="center"><?=$QUIT_TIME_PLAN?></td>
      <td nowrap align="center"><?=$LAST_SALARY_TIME?></td>
      <td nowrap align="center">
      <a href="javascript:;" onClick="window.open('leave_detail.php?LEAVE_ID=<?=$LEAVE_ID?>','','height=500,width=820,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=200,top=100,resizable=yes');"><?=_("详细信息")?></a>&nbsp;
      </td>
   </tr>
<?
}
?>
</table>
</body>

</html>
