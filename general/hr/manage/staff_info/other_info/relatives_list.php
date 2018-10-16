<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");

$HTML_PAGE_TITLE = _("社会关系管理");
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
   $query = "SELECT count(*) from HR_STAFF_RELATIVES where STAFF_NAME ='$USER_ID' ";
   $cursor= exequery(TD::conn(),$query);
   if($ROW=mysql_fetch_array($cursor))
      $TOTAL_ITEMS=$ROW[0];
}
$TOTAL_ITEMS=intval($TOTAL_ITEMS);
?>
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/infofind.gif" align="absmiddle"><span class="big3"> <?=_("社会关系")?></span>&nbsp;
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
     <input type="button" value="<?=_("新建社会关系信息")?>" class="BigButton" onClick="window.open('relatives_new.php?USER_ID=<?=$USER_ID?>','','height=500,width=820,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=200,top=100,resizable=yes');">
  </div>
  <br>
<?
if($TOTAL_ITEMS>0)
{
?>  
<table class="TableList" width="100%">
  <tr class="TableHeader">
      <td nowrap align="center"><?=_("员工姓名")?></td>
      <td nowrap align="center"><?=_("成员姓名")?></td>
      <td nowrap align="center"><?=_("与本人关系")?></td>
      <td nowrap align="center"><?=_("职业")?></td>
      <td nowrap align="center"><?=_("联系电话（个人）")?></td>
      <td nowrap align="center"><?=_("操作")?></td>
  </tr>
<?
}
else
{
   Message("",_("无社会关系记录"));	
}

$query = "SELECT * from HR_STAFF_RELATIVES where STAFF_NAME ='$USER_ID' order by ADD_TIME desc limit $PAGE_START, $PAGE_SIZE";
$cursor= exequery(TD::conn(),$query);
$RELATIVES_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
   $RELATIVES_COUNT++;

   $RELATIVES_ID=$ROW["RELATIVES_ID"];
   $CREATE_USER_ID=$ROW["CREATE_USER_ID"];
   $CREATE_DEPT_ID=$ROW["CREATE_DEPT_ID"];
   $MEMBER=$ROW["MEMBER"];
   $RELATIONSHIP=$ROW["RELATIONSHIP"];
   $PERSONAL_TEL=$ROW["PERSONAL_TEL"];
   $JOB_OCCUPATION=$ROW["JOB_OCCUPATION"];
   $STAFF_NAME=$ROW["STAFF_NAME"];
   $ADD_TIME=$ROW["ADD_TIME"];
   
	 $RELATIONSHIP=get_hrms_code_name($RELATIONSHIP,"HR_STAFF_RELATIVES");
	 
	 $STAFF_NAME1=substr(GetUserNameById($STAFF_NAME),0,-1)
	 
?>
   <tr class="TableData">
      <td nowrap align="center"><?=$STAFF_NAME1?></td>
      <td nowrap align="center"><?=$MEMBER?></td>
      <td nowrap align="center"><?=$RELATIONSHIP?></td>
      <td nowrap align="center"><?=$JOB_OCCUPATION?></td>
      <td nowrap align="center"><?=$PERSONAL_TEL?></td>
      <td nowrap align="center">
      <a href="javascript:;" onClick="window.open('relatives_detail.php?RELATIVES_ID=<?=$RELATIVES_ID?>','','height=500,width=820,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=200,top=100,resizable=yes');"><?=_("详细信息")?></a>&nbsp;
      <a href="javascript:;" onClick="window.open('relatives_modify.php?RELATIVES_ID=<?=$RELATIVES_ID?>','','height=500,width=820,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=200,top=100,resizable=yes');"><?=_("编辑")?></a>&nbsp;
      </td>
   </tr>
<?
}
?>
</table>
</body>

</html>
