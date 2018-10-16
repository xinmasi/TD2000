<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
include_once("inc/check_type.php");

$HTML_PAGE_TITLE = _("奖惩信息");
include_once("inc/header.inc.php");
?>



<body class="bodycolor">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/infofind.gif" align="absMiddle"><span class="big3"> <?=_("奖惩信息查询结果")?></span><br>
    	</td>
  </tr>
</table>
<?
$query = "SELECT * from HR_STAFF_INCENTIVE where STAFF_NAME='$STAFF_NAME' order by ADD_TIME desc";
$cursor=exequery(TD::conn(),$query);
$INCENTIVE_COUNT=0;
$ALL_INCENTIVE_AMOUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
   $INCENTIVE_COUNT++;

   $INCENTIVE_ID=$ROW["INCENTIVE_ID"];
   $CREATE_DEPT_ID=$ROW["CREATE_DEPT_ID"];
   $CREATE_DEPT_ID=$ROW["CREATE_DEPT_ID"];
   $STAFF_NAME=$ROW["STAFF_NAME"];
   $INCENTIVE_ITEM=$ROW["INCENTIVE_ITEM"];
   $INCENTIVE_TIME=$ROW["INCENTIVE_TIME"];
   $INCENTIVE_TYPE=$ROW["INCENTIVE_TYPE"];
   $INCENTIVE_AMOUNT=$ROW["INCENTIVE_AMOUNT"];
   $ADD_TIME=$ROW["ADD_TIME"];
  	
   $ALL_INCENTIVE_AMOUNT +=  $INCENTIVE_AMOUNT;
  	
   $STAFF_NAME1=substr(GetUserNameById($STAFF_NAME),0,-1);
	 if($STAFF_NAME1=="")
	 {
	   $query1 = "SELECT STAFF_NAME from HR_STAFF_INFO where USER_ID='$STAFF_NAME'";
       $cursor1= exequery(TD::conn(),$query1);
      if($ROW1=mysql_fetch_array($cursor1))
          $STAFF_NAME1=$ROW1["STAFF_NAME"];
	  }
	 $INCENTIVE_ITEM=get_hrms_code_name($INCENTIVE_ITEM,"HR_STAFF_INCENTIVE1");
	 $INCENTIVE_TYPE=get_hrms_code_name($INCENTIVE_TYPE,"INCENTIVE_TYPE");
  
  if($INCENTIVE_COUNT==1)
  {
?>
<table class="TableList" width="100%">
  <thead class="TableHeader">
      <td nowrap align="center"><?=_("单位员工")?></td>
      <td nowrap align="center"><?=_("奖惩项目")?></td>
      <td nowrap align="center"><?=_("奖惩日期")?></td>
      <td nowrap align="center"><?=_("奖惩属性")?></td>
      <td nowrap align="center"><?=_("奖惩金额")?></td>
      <td nowrap align="center"><?=_("操作")?></td>
  </thead> 
  
<?
   }
?>
     <tr class="TableData">
      <td nowrap align="center"><?=$STAFF_NAME1?></td>
      <td nowrap align="center"><?=$INCENTIVE_ITEM?></td>
      <td nowrap align="center"><?=$INCENTIVE_TIME?></td>
      <td nowrap align="center"><?=$INCENTIVE_TYPE?></td>
      <td nowrap align="center"><?=$INCENTIVE_AMOUNT?></td>
      <td align="center">
			<a href="javascript:;" onClick="window.open('incentive_detail.php?INCENTIVE_ID=<?=$INCENTIVE_ID?>','','height=500,width=820,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=200,top=100,resizable=yes');"><?=_("详细信息")?></a>&nbsp;
      </td>
	</tr>
<?
}
?>
</body>

</html>
