<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");

$HTML_PAGE_TITLE = _("员工关怀管理");
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

$WHERE_STR = hr_priv("BY_CARE_STAFFS");
if($CARE_TYPE!="")
   $WHERE_STR .= " and CARE_TYPE='$CARE_TYPE'";

if(!isset($TOTAL_ITEMS))
{
   $query = "SELECT count(*) from HR_STAFF_CARE where BY_CARE_STAFFS='$USER_ID' ";
   $cursor= exequery(TD::conn(),$query);
   if($ROW=mysql_fetch_array($cursor))
      $TOTAL_ITEMS=$ROW[0];
}
$TOTAL_ITEMS=intval($TOTAL_ITEMS);
?>
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/infofind.gif" align="absmiddle"><span class="big3"> <?=_("员工关怀")?></span>&nbsp;</td>
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
     <input type="button" value="<?=_("新建员工关怀信息")?>" class="BigButton" onClick="window.open('care_new.php?USER_ID=<?=$USER_ID?>','','height=500,width=820,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=200,top=100,resizable=yes');">
  </div>
  <br>
<?
if($TOTAL_ITEMS>0)
{
?>  
<table class="TableList" width="100%">
  <tr class="TableHeader">
      <td nowrap align="center"><?=_("关怀类型")?></td>
      <td nowrap align="center"><?=_("被关怀员工")?></td>
      <td nowrap align="center"><?=_("关怀开支费用")?></td>
      <td nowrap align="center"><?=_("参与人")?></td>
      <td nowrap align="center"><?=_("关怀日期")?></td>
      <td nowrap align="center"><?=_("操作")?></td>
  </tr>
<?
}
else
{
   Message("",_("无员工关怀记录"));	
}

$query = "SELECT * from HR_STAFF_CARE where BY_CARE_STAFFS='$USER_ID' order by CARE_DATE desc limit $PAGE_START, $PAGE_SIZE";
$cursor= exequery(TD::conn(),$query);
$CARE_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
   $CARE_COUNT++;

   $CARE_ID=$ROW["CARE_ID"];
   $CREATE_USER_ID=$ROW["CREATE_USER_ID"];
   $CREATE_DEPT_ID=$ROW["CREATE_DEPT_ID"];
   $BY_CARE_STAFFS=$ROW["BY_CARE_STAFFS"];
   $CARE_DATE=$ROW["CARE_DATE"];
   $PARTICIPANTS=$ROW["PARTICIPANTS"];
   $CARE_FEES=$ROW["CARE_FEES"];
   $CARE_TYPE=$ROW["CARE_TYPE"];
   $ADD_TIME=$ROW["ADD_TIME"];
   
   $TYPE_NAME=get_hrms_code_name($CARE_TYPE,"HR_STAFF_CARE");
   
   $BY_CARE_STAFFS_NAME = substr(GetUserNameById($BY_CARE_STAFFS),0,-1);
   $PARTICIPANTS_NAME = substr(GetUserNameById($PARTICIPANTS),0,-1);
?>
   <tr class="TableData">
      <td nowrap align="center"><?=$TYPE_NAME?></td>
      <td nowrap align="center"><?=$BY_CARE_STAFFS_NAME?></td>
      <td nowrap align="center"><?=$CARE_FEES?> (<?=_("元")?>)</td>
      <td align="center"><?=$PARTICIPANTS_NAME?></td>
      <td nowrap align="center"><?=$CARE_DATE?></td>
      <td align="center">
      <a href="javascript:;" onClick="window.open('care_detail.php?CARE_ID=<?=$CARE_ID?>','','height=500,width=820,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=200,top=100,resizable=yes');"><?=_("详细信息")?></a>&nbsp;
      <a href="javascript:;" onClick="window.open('care_modify.php?CARE_ID=<?=$CARE_ID?>','','height=500,width=820,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=200,top=100,resizable=yes');"><?=_("编辑")?></a>&nbsp;
      </td>
   </tr>
<?
}
?>
</table>
</body>

</html>
