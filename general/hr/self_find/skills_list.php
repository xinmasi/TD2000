<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");

$HTML_PAGE_TITLE = _("劳动技能管理");
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
   $query = "SELECT count(*) from HR_STAFF_LABOR_SKILLS where STAFF_NAME='".$_SESSION["LOGIN_USER_ID"]."' ";
   $cursor= exequery(TD::conn(),$query);
   if($ROW=mysql_fetch_array($cursor))
      $TOTAL_ITEMS=$ROW[0];
}
$TOTAL_ITEMS=intval($TOTAL_ITEMS);
?>
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/infofind.gif" align="absmiddle"><span class="big3"> <?=_("劳动技能")?></span>&nbsp;
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
      <td nowrap align="center"><?=_("技能名称")?></td>
      <td nowrap align="center"><?=_("级别")?></td>
      <td nowrap align="center"><?=_("发证日期")?></td>
      <td nowrap align="center"><?=_("有效期")?></td>
      <td nowrap align="center"><?=_("操作")?></td>
  </tr>
<?
}
else
   Message("",_("无劳动技能信息记录"));	

$query = "SELECT * from HR_STAFF_LABOR_SKILLS where STAFF_NAME='".$_SESSION["LOGIN_USER_ID"]."' order by ADD_TIME desc limit $PAGE_START, $PAGE_SIZE";
$cursor= exequery(TD::conn(),$query);
$SKILLS_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
   $SKILLS_COUNT++;

   $SKILLS_ID=$ROW["SKILLS_ID"];
   $CREATE_USER_ID=$ROW["CREATE_USER_ID"];
   $CREATE_DEPT_ID=$ROW["CREATE_DEPT_ID"];
   $STAFF_NAME=$ROW["STAFF_NAME"];
   $ABILITY_NAME=$ROW["ABILITY_NAME"];
   $SKILLS_LEVEL=$ROW["SKILLS_LEVEL"];
   $ISSUE_DATE=$ROW["ISSUE_DATE"];
   $EXPIRES=$ROW["EXPIRES"];
   $ADD_TIME=$ROW["ADD_TIME"]; 
	 
	 $STAFF_NAME1=substr(GetUserNameById($STAFF_NAME),0,-1);
?>
   <tr class="TableData">
      <td nowrap align="center"><?=$STAFF_NAME1?></td>
      <td nowrap align="center"><?=$ABILITY_NAME?></td>
      <td nowrap align="center"><?=$SKILLS_LEVEL?></td>
      <td nowrap align="center"><?=$ISSUE_DATE?></td>
      <td nowrap align="center"><?=$EXPIRES?></td>
      <td nowrap align="center">
      <a href="javascript:;" onClick="window.open('skills_detail.php?SKILLS_ID=<?=$SKILLS_ID?>','','height=500,width=820,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=200,top=100,resizable=yes');"><?=_("详细信息")?></a>&nbsp;
      </td>
   </tr>
<?
}
?>
</table>
</body>

</html>
