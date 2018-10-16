<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");

$HTML_PAGE_TITLE = _("工作经历管理");
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
   $query = "SELECT count(*) from HR_STAFF_WORK_EXPERIENCE where STAFF_NAME='$USER_ID' ";
   $cursor= exequery(TD::conn(),$query);
   if($ROW=mysql_fetch_array($cursor))
      $TOTAL_ITEMS=$ROW[0];
}
$TOTAL_ITEMS=intval($TOTAL_ITEMS);
?>
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/infofind.gif" align="absmiddle"><span class="big3"> <?=_("工作经历")?></span>&nbsp;
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
     <input type="button" value="<?=_("新建工作经历信息")?>" class="BigButton" onClick="window.open('w_experience_new.php?USER_ID=<?=$USER_ID?>','','height=500,width=820,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=200,top=100,resizable=yes');">
  </div>
  <br>
<?
if($TOTAL_ITEMS>0)
{
?>  
<table class="TableList" width="100%">
  <tr class="TableHeader">
      <td nowrap align="center"><?=_("员工姓名")?></td>
      <td nowrap align="center"><?=_("工作单位")?></td>
      <td nowrap align="center"><?=_("行业类别")?></td>
      <td nowrap align="center"><?=_("担任职务")?></td>
      <td nowrap align="center"><?=_("证明人")?></td>
      <td nowrap align="center"><?=_("操作")?></td>
  </tr>
<?
}
else 
{
  Message("",_("未找到工作相应记录！"));	
}

$query = "SELECT * from HR_STAFF_WORK_EXPERIENCE where STAFF_NAME='$USER_ID' order by ADD_TIME desc limit $PAGE_START, $PAGE_SIZE";
$cursor= exequery(TD::conn(),$query);
$EXPERIENCE_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
   $EXPERIENCE_COUNT++;

   $W_EXPERIENCE_ID=$ROW["W_EXPERIENCE_ID"];
   $CREATE_USER_ID=$ROW["CREATE_USER_ID"];
   $CREATE_DEPT_ID=$ROW["CREATE_DEPT_ID"];
   $STAFF_NAME=$ROW["STAFF_NAME"];
   $WORK_UNIT=$ROW["WORK_UNIT"];
   $MOBILE=$ROW["MOBILE"];
   $POST_OF_JOB=$ROW["POST_OF_JOB"];
   $WITNESS=$ROW["WITNESS"];
   $ADD_TIME=$ROW["ADD_TIME"]; 
	 
	 $STAFF_NAME1=substr(GetUserNameById($STAFF_NAME),0,-1);
	 
	 if(strlen($WORK_UNIT) > 20)
	 $WORK_UNIT=substr($WORK_UNIT, 0, 20)."...";
	
?>
   <tr class="TableData">
      <td nowrap align="center"><?=$STAFF_NAME1?></td>
      <td nowrap align="center"><?=$WORK_UNIT?></td>
      <td nowrap align="center"><?=$MOBILE?></td>
      <td nowrap align="center"><?=$POST_OF_JOB?></td>
      <td nowrap align="center"><?=$WITNESS?></td>
      <td nowrap align="center">
      <a href="javascript:;" onClick="window.open('w_experience_detail.php?W_EXPERIENCE_ID=<?=$W_EXPERIENCE_ID?>','','height=500,width=820,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=200,top=100,resizable=yes');"><?=_("详细信息")?></a>&nbsp;
      <a href="javascript:;" onClick="window.open('w_experience_modify.php?W_EXPERIENCE_ID=<?=$W_EXPERIENCE_ID?>','','height=500,width=820,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=200,top=100,resizable=yes');"><?=_("编辑")?></a>&nbsp;
      </td>
   </tr>
<?
}
?>
</table>
</body>

</html>
