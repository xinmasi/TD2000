<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
$IDS_ARR=explode(",",$IDS_STR);

$HTML_PAGE_TITLE = _("人才档案管理");
include_once("inc/header.inc.php");
?>


<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/theme/<?=$_SESSION["LOGIN_THEME"]?>/bbs.css">

<body class="bodycolor">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_open.gif" align="absmiddle"><span class="big3"> <?=_("管理人才档案")?></span>&nbsp;
    </td>
  </tr>
</table>
<?
if(count($IDS_ARR)>0)
{
?>  
<table class="TableList" width="100%">
  <tr class="TableHeader">
      <td nowrap align="center"><?=_("计划名称")?></td>
      <td nowrap align="center"><?=_("应聘者姓名")?></td>
      <td nowrap align="center"><?=_("出生日期")?></td>
      <td nowrap align="center"><?=_("联系电话")?></td>
      <td nowrap align="center"><?=_("学历")?></td>
      <td nowrap align="center"><?=_("专业")?></td>
      <td nowrap align="center"><?=_("应聘岗位")?></td>
      <td nowrap align="center"><?=_("入库时间")?></td>
      <td nowrap align="center"><?=_("操作")?></td>
  </tr>
<?
foreach($IDS_ARR as $value)
{
	$query="select * from HR_RECRUIT_POOL where EXPERT_ID='$value'";
	$cursor=exequery(TD::conn(),$query);
	if($ROW=mysql_fetch_array($cursor))
	{
	 $EXPERT_ID=$ROW["EXPERT_ID"];
   $CREATE_USER_ID=$ROW["CREATE_USER_ID"];
   $CREATE_DEPT_ID=$ROW["CREATE_DEPT_ID"];
   $PLAN_NAME=$ROW["PLAN_NAME"];
   $POSITION=$ROW["POSITION"];
   $ADD_TIME=$ROW["ADD_TIME"];
   $EMPLOYEE_NAME=$ROW["EMPLOYEE_NAME"];
   $EMPLOYEE_BIRTH=$ROW["EMPLOYEE_BIRTH"];
   $EMPLOYEE_PHONE=$ROW["EMPLOYEE_PHONE"];
   $EMPLOYEE_HIGHEST_SCHOOL=$ROW["EMPLOYEE_HIGHEST_SCHOOL"];
   $EMPLOYEE_MAJOR=$ROW["EMPLOYEE_MAJOR"];
   
   $EMPLOYEE_HIGHEST_SCHOOL=get_hrms_code_name($EMPLOYEE_HIGHEST_SCHOOL,"STAFF_HIGHEST_SCHOOL");
	 $POSITION=get_hrms_code_name($POSITION,"POOL_POSITION");
	 $EMPLOYEE_MAJOR=get_hrms_code_name($EMPLOYEE_MAJOR,"POOL_EMPLOYEE_MAJOR");
?>
   <tr class="TableData">
      <td nowrap align="center"><?=$PLAN_NAME?></td>
      <td nowrap align="center"><?=$EMPLOYEE_NAME?></td>
      <td nowrap align="center"><?=$EMPLOYEE_BIRTH?></td>
      <td nowrap align="center"><?=$EMPLOYEE_PHONE?></td>
      <td nowrap align="center"><?=$EMPLOYEE_HIGHEST_SCHOOL?></td>
      <td nowrap align="center"><?=$EMPLOYEE_MAJOR?></td>
      <td nowrap align="center"><?=$POSITION?></td>
      <td nowrap align="center"><?=$ADD_TIME?></td>
      <td nowrap align="center">
      <a href="javascript:;" onClick="window.open('../hr_pool/pool_detail.php?EXPERT_ID=<?=$EXPERT_ID?>','','height=500,width=820,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=200,top=100,resizable=yes');"><?=_("详细信息")?></a>&nbsp;
      </td>
   </tr>
<?
}
}
?>
</table>
<?
}
else{
   Message("",_("无人才档案记录"));	
}
?>
<br />
<div align="center">
	<input type="button" class="BigButton" onClick="javascript: window.close()" value="<?=_("关闭")?>" />
</div>