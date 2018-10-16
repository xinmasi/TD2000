<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
include_once("inc/check_type.php");

$HTML_PAGE_TITLE = _("奖惩信息查询");
include_once("inc/header.inc.php");
?>


<script Language=JavaScript>
</script>

<body class="bodycolor">

<?
$CUR_DATE=date("Y-m-d",time());
$CUR_TIME=date("Y-m-d H:i:s",time());
 //-----------合法性校验---------

if($DATE1!="")
{
  $TIME_OK=is_date($DATE1);

  if(!$TIME_OK)
  { Message(_("错误"),_("日期的格式不对，应形如 ").$CUR_DATE);
    Button_Back();
    exit;
  }
  $DATE1=$DATE1." 00:00:00";
}

if($DATE2!="")
{
  $TIME_OK=is_date($DATE2);

  if(!$TIME_OK)
  { Message(_("错误"),_("日期的格式不对，应形如 ").$CUR_DATE);
    Button_Back();
    exit;
  }
  $DATE2=$DATE2." 23:59:59";
}

//------------------------ 生成条件字符串 ------------------
$CONDITION_STR="";
if($USER_ID!="")
   $CONDITION_STR.=" and STAFF_NAME='$USER_ID'";
if($DATE1!="")
  $CONDITION_STR.=" and INCENTIVE_TIME>='$DATE1'";
if($DATE2!="")
  $CONDITION_STR.=" and INCENTIVE_TIME<='$DATE2'";   
$CONDITION_STR.="and INCENTIVE_TYPE='1'"
?>

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/infofind.gif" align="absMiddle"><span class="big3"> <?=_("奖惩信息查询结果")?></span><br>
    	</td>
  </tr>
</table>
<?
$CONDITION_STR = hr_priv("STAFF_NAME").$CONDITION_STR;
$query = "SELECT * from HR_STAFF_INCENTIVE where".$CONDITION_STR."order by ADD_TIME desc";
$cursor=exequery(TD::conn(),$query);
$INCENTIVE_COUNT=0;
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
  	
   $STAFF_NAME1=substr(GetUserNameById($STAFF_NAME),0,-1);
	 
	 $INCENTIVE_ITEM=get_hrms_code_name($INCENTIVE_ITEM,"HR_STAFF_INCENTIVE1");
	 
	if($INCENTIVE_TYPE==1)
		 $INCENTIVE_TYPE=_("奖励");
	if($INCENTIVE_TYPE==2)
		 $INCENTIVE_TYPE=_("惩罚");
  
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
      </td>
	</tr>

<?
}

if($INCENTIVE_COUNT==0)
{
   Message("",_("无符合条件的奖惩信息！"));
}
?>
</table>
<br><br>
<div align="center">
	<input type="button"  class="BigButton" value="<?=_("关闭")?>" onclick="window.close();">
</div>
</body>

</html>
