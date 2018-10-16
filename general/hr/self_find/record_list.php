<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");

$PAGE_SIZE = 10;
if(!isset($start) || $start=="")
   $start=0;

$HTML_PAGE_TITLE = _("培训记录信息");
include_once("inc/header.inc.php");
?>



<script>
</script>

<?
$WHERE_STR = hr_priv("STAFF_USER_ID");
$query = "select * from  HR_TRAINING_RECORD where STAFF_USER_ID='".$_SESSION["LOGIN_USER_ID"]."' ";
$cursor=exequery(TD::conn(),$query);
$STAFF_COUNT = mysql_num_rows($cursor);

$query = "SELECT * from  HR_TRAINING_RECORD WHERE STAFF_USER_ID='".$_SESSION["LOGIN_USER_ID"]."' ORDER BY RECORD_ID  desc limit $start,$PAGE_SIZE";
$cursor=exequery(TD::conn(),$query);
$COUNT = mysql_num_rows($cursor);
?>
<body class="bodycolor">
	<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/infofind.gif" align="absMiddle"><span class="big3"> <?=_("培训记录信息")?></span><br>
    	</td>
    <?
if($COUNT > 0)
{
  ?>
    <td align="right" valign="bottom" class="small1"><?=page_bar($start,$STAFF_COUNT,$PAGE_SIZE)?></td>
    <?
}
    ?>
	</tr>
</table>
<?
if($COUNT <= 0)
{
   Message("", _("无符合条件的培训记录"));
   exit;
}
?>
<table class="TableList" width="100%">
   <thead class="TableHeader">
      <td nowrap align="center"><?=_("培训计划名称")?></td>
      <td nowrap align="center"><?=_("受训人")?></td>
      <td nowrap align="center"><?=_("培训费用")?></td>
      <td nowrap align="center"><?=_("培训机构")?></td>
      <td width="150"><?=_("操作")?></td>
   </thead>
<?
while($ROW=mysql_fetch_array($cursor))
{ 
   $REQUIREMENTS_COUNT++;
   
	 $RECORD_ID=$ROW["RECORD_ID"];
   $STAFF_USER_ID=$ROW["STAFF_USER_ID"];
   $T_PLAN_NO=$ROW["T_PLAN_NO"];
   $T_PLAN_NAME=$ROW["T_PLAN_NAME"];
   $T_INSTITUTION_NAME=$ROW["T_INSTITUTION_NAME"];
   $TRAINNING_COST=$ROW["TRAINNING_COST"];
   

   $query2= "SELECT USER_NAME from  USER WHERE USER_ID='$STAFF_USER_ID'";
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
      <td align="center">
      	<a href="javascript:;" onClick="window.open('record_detail.php?RECORD_ID=<?=$RECORD_ID?>','','height=500,width=820,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=200,top=100,resizable=yes');"><?=_("详细信息")?></a>&nbsp;
      </td>
</tr>
<?
}
?>
</table>
<br>
</body>
</html>