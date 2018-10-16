<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
include_once("inc/utility_file.php");

$HTML_PAGE_TITLE = _("员工培训管理");
include_once("inc/header.inc.php");
?>
<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/theme/<?=$_SESSION['LOGIN_THEME']?>/bbs.css">
<script type="text/javascript" src="/inc/js_lang.php"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>
<script>
</script>

<body class="bodycolor">
<?
if(!$PAGE_SIZE)
   $PAGE_SIZE = get_page_size("HR_MANAGE", 1);
$PAGE_START=intval($PAGE_START);

$WHERE_STR = hr_priv("STAFF_USER_ID");
$query = "select * from  HR_TRAINING_RECORD where STAFF_USER_ID = '$USER_ID' and ".$WHERE_STR;
$cursor=exequery(TD::conn(),$query);
$TOTAL_ITEMS = mysql_num_rows($cursor);
$TOTAL_ITEMS=intval($TOTAL_ITEMS);

$query .= "ORDER BY RECORD_ID  desc limit $PAGE_START,$PAGE_SIZE";
$cursor=exequery(TD::conn(),$query);
?>
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/infofind.gif" align="absmiddle"><span class="big3"> <?=_("员工培训")?></span>&nbsp;</td>
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
     <input type="button" value="<?=_("新建员工培训信息")?>" class="BigButton" onClick="window.open('record_new.php?USER_ID=<?=$USER_ID?>','','height=500,width=820,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=200,top=100,resizable=yes');">
  </div>
  <br>
<?
if($TOTAL_ITEMS>0)
{
?>  
<table class="TableList" width="100%">
   <thead class="TableHeader">
      <td nowrap align="center"><?=_("培训计划名称")?></td>
      <td nowrap align="center"><?=_("受训人")?></td>
      <td nowrap align="center"><?=_("培训费用")?></td>
      <td nowrap align="center"><?=_("培训机构")?></td>
      <td nowrap align="center"><?=_("相关附件")?></td>
      <td nowrap align="center"><?=_("操作")?></td>
   </thead>
<?
}
else
{
   Message("",_("无员工培训记录"));	
}
$REQUIREMENTS_COUNT=0;

while($ROW=mysql_fetch_array($cursor))
{
   $RECORD_ID=$ROW["RECORD_ID"];
   $STAFF_USER_ID=$ROW["STAFF_USER_ID"];
   $T_PLAN_NO=$ROW["T_PLAN_NO"];
   $T_PLAN_NAME=$ROW["T_PLAN_NAME"];
   $T_INSTITUTION_NAME=$ROW["T_INSTITUTION_NAME"];
   $TRAINNING_COST=$ROW["TRAINNING_COST"];
   $ATTACHMENT_ID=$ROW["ATTACHMENT_ID"];
   $ATTACHMENT_NAME=$ROW["ATTACHMENT_NAME"];
   
   if($ATTACHMENT_ID=="")
   {
   		$ATTACHMENT=_("无附件");
   	}
   else
   {
   		$ATTACHMENT = attach_link($ATTACHMENT_ID, $ATTACHMENT_NAME, 1,1,1,1,0,1,1);
   	}

   $query2= "SELECT USER_NAME from USER WHERE USER_ID='$STAFF_USER_ID'";
	 $cursor2=exequery(TD::conn(),$query2);
	 if($ROW2=mysql_fetch_array($cursor2))
	 {
	 		$STAFF_USER_NAME=$ROW2["USER_NAME"];
	 		
	 }
?>
<tr class="TableData">
      <td align="center"><?=$T_PLAN_NAME?></td>
      <td align="center"><?=$STAFF_USER_NAME?></td>
      <td align="center"><?=$TRAINNING_COST?></td>
      <td align="center"><?=$T_INSTITUTION_NAME?></td>
      <td align="center"><?=$ATTACHMENT?></td>
      <td align="center">
      	<a href="javascript:;" onClick="window.open('record_detail.php?RECORD_ID=<?=$RECORD_ID?>','','height=500,width=820,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=200,top=100,resizable=yes');"><?=_("详细信息")?></a>&nbsp;
        <a href="record_modify.php?RECORD_ID=<?=$RECORD_ID?>"><?=_("编辑")?></a>&nbsp;&nbsp;
      </td>
</tr>
<?
}
?>
</table>
</body>

</html>
