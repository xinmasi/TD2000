<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");

$HTML_PAGE_TITLE = _("学习经历管理");
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
   $query = "SELECT count(*) from HR_STAFF_LEARN_EXPERIENCE where STAFF_NAME='".$_SESSION["LOGIN_USER_ID"]."' ";
   $cursor= exequery(TD::conn(),$query);
   if($ROW=mysql_fetch_array($cursor))
      $TOTAL_ITEMS=$ROW[0];
}
$TOTAL_ITEMS=intval($TOTAL_ITEMS);
?>
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/infofind.gif" align="absmiddle"><span class="big3"> <?=_("学习经历")?></span>&nbsp;
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
      <td nowrap align="center"><?=_("员工姓名")?></td>
      <td nowrap align="center"><?=_("所学专业")?></td>
      <td nowrap align="center"><?=_("所获学历")?></td>
      <td nowrap align="center"><?=_("所在院校")?></td>
      <td nowrap align="center"><?=_("证明人")?></td>
      <td nowrap align="center"><?=_("操作")?></td>
  </tr>
<?
}
 else 
{
    Message("", _("无学习经历"));
}

$query = "SELECT * from HR_STAFF_LEARN_EXPERIENCE where STAFF_NAME='".$_SESSION["LOGIN_USER_ID"]."' order by ADD_TIME desc limit $PAGE_START, $PAGE_SIZE";
$cursor= exequery(TD::conn(),$query);
$EXPERIENCE_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
   $EXPERIENCE_COUNT++;

   $L_EXPERIENCE_ID=$ROW["L_EXPERIENCE_ID"];
   $CREATE_USER_ID=$ROW["CREATE_USER_ID"];
   $CREATE_DEPT_ID=$ROW["CREATE_DEPT_ID"];
   $STAFF_NAME=$ROW["STAFF_NAME"];
   $MAJOR=$ROW["MAJOR"];
   $ACADEMY_DEGREE=$ROW["ACADEMY_DEGREE"];
   $SCHOOL=$ROW["SCHOOL"];
   $WITNESS=$ROW["WITNESS"];
   $ADD_TIME=$ROW["ADD_TIME"]; 
	 
	 $STAFF_NAME1=substr(GetUserNameById($STAFF_NAME),0,-1);
	 
	 if(strlen($SCHOOL) > 20)
	 $SCHOOL=substr($SCHOOL, 0, 20)."...";
?>
   <tr class="TableData">
      <td nowrap align="center"><?=$STAFF_NAME1?></td>
      <td nowrap align="center"><?=$MAJOR?></td>
      <td nowrap align="center"><?=$ACADEMY_DEGREE?></td>
      <td nowrap align="center"><?=$SCHOOL?></td>
      <td nowrap align="center"><?=$WITNESS?></td>
      <td nowrap align="center">
      <a href="javascript:;" onClick="window.open('experience_detail.php?L_EXPERIENCE_ID=<?=$L_EXPERIENCE_ID?>','','height=500,width=820,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=200,top=100,resizable=yes');"><?=_("详细信息")?></a>&nbsp;
      </td>
   </tr>
<?
}
?>
</table>
</body>

</html>
