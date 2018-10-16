<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");

$PAGE_SIZE = 20;
if(!isset($start) || $start=="")
   $start=0;

$HTML_PAGE_TITLE = _("招聘计划查询");
include_once("inc/header.inc.php");
?>





<?
//------------------------ 生成条件字符串 ------------------
$CONDITION_STR="";
if($PLAN_NAME!="")
   $CONDITION_STR.=" and PLAN_NAME LIKE '%$PLAN_NAME%'";
if($PLAN_STATUS!="")
   $CONDITION_STR.=" and PLAN_STATUS='$PLAN_STATUS'";
if($REGISTER_TIME!="")
  $CONDITION_STR.=" and REGISTER_TIME='$REGISTER_TIME'";
if($START_DATE1!="")
  $CONDITION_STR.=" and START_DATE >= '$START_DATE1'";   
if($END_DATE1!="")
  $CONDITION_STR.=" and START_DATE <= '$END_DATE1'"; 
if($START_DATE2!="")
  $CONDITION_STR.=" and END_DATE >= '$START_DATE2'";
if($END_DATE2!="")
  $CONDITION_STR.=" and END_DATE <= '$END_DATE2'";
if($APPROVE_PERSON!="")
  $CONDITION_STR.=" and APPROVE_PERSON='$APPROVE_PERSON'";
if($PLAN_NO!="")
  $CONDITION_STR.=" and PLAN_NO='$PLAN_NO'";
if($RECRUIT_DIRECTION!="")
  $CONDITION_STR.=" and RECRUIT_DIRECTION LIKE '%".$RECRUIT_DIRECTION."%'";
if($RECRUIT_REMARK!="")
  $CONDITION_STR.=" and RECRUIT_REMARK LIKE '%".$RECRUIT_REMARK."%'";

$query1="SELECT * from HR_MANAGER where find_in_set('".$_SESSION["LOGIN_USER_ID"]."',DEPT_HR_MANAGER)";
$cursor1=exequery(TD::conn(),$query1);
while($ROW1=mysql_fetch_array($cursor1))
{
	$DEPT_ID.=$ROW1['DEPT_ID'].",";
	$DEPT_HR_MANAGER=$ROW1['DEPT_HR_MANAGER'];
} 
$DEPT_HR_MANAGER1=td_trim($DEPT_HR_MANAGER);
$query = "SELECT * from HR_RECRUIT_PLAN WHERE (APPROVE_PERSON='".$_SESSION["LOGIN_USER_ID"]."' or (CREATE_USER_ID='$DEPT_HR_MANAGER1' or find_in_set(CREATE_DEPT_ID,'$DEPT_ID')))".$CONDITION_STR ;

$cursor=exequery(TD::conn(),$query);
$STAFF_COUNT = mysql_num_rows($cursor);

$query = "SELECT * from HR_RECRUIT_PLAN WHERE (APPROVE_PERSON='".$_SESSION["LOGIN_USER_ID"]."' or (CREATE_USER_ID='$DEPT_HR_MANAGER1' or find_in_set(CREATE_DEPT_ID,'$DEPT_ID')))".$CONDITION_STR."ORDER BY PLAN_ID limit $start,$PAGE_SIZE";
$cursor=exequery(TD::conn(),$query);
$COUNT = mysql_num_rows($cursor);

if($COUNT <= 0)
{
   Message("", _("无符合条件的招聘计划"));
   Button_Back();
   exit;
}
?>
<body class="bodycolor">
	<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/infofind.gif" align="absMiddle"><span class="big3"> <?=_("招聘计划查询结果")?></span><br>
    	</td>
    <td align="right" valign="bottom" class="small1"><?=page_bar($start,$STAFF_COUNT,$PAGE_SIZE)?></td>
	</tr>
</table>
<table class="TableList" width="100%">
   <thead class="TableHeader">
      <td nowrap align="center"><?=_("编号")?></td>
      <td nowrap align="center"><?=_("招聘名称")?></td>
      <td nowrap align="center"><?=_("招聘人数")?></td>
      <td nowrap align="center"><?=_("开始日期")?></td>
      <td nowrap align="center"><?=_("计划状态")?></td>
      <td width="150"><?=_("操作")?></td>
   </thead>
<?
while($ROW=mysql_fetch_array($cursor))
{
   $REQUIREMENTS_COUNT++;

   $PLAN_ID=$ROW["PLAN_ID"];
   $PLAN_NO=$ROW["PLAN_NO"];
   $PLAN_NAME=$ROW["PLAN_NAME"];
   $PLAN_DITCH=$ROW["PLAN_DITCH"];
   $PLAN_BCWS=$ROW["PLAN_BCWS"];
   $PLAN_RECR_NO=$ROW["PLAN_RECR_NO"];
   $REGISTER_TIME=$ROW["REGISTER_TIME"];
   $START_DATE=$ROW["START_DATE"];
   $END_DATE=$ROW["END_DATE"];
   $RECRUIT_DIRECTION=$ROW["RECRUIT_DIRECTION"]; 
   $RECRUIT_REMARK=$ROW["RECRUIT_REMARK"];
   $APPROVE_PERSON=$ROW["APPROVE_PERSON"];
   $APPROVE_DATE=$ROW["APPROVE_DATE"];
   $APPROVE_COMMENT=$ROW["APPROVE_COMMENT"];
   $APPROVE_RESULT=$ROW["APPROVE_RESULT"];
   $PLAN_STATUS=$ROW["PLAN_STATUS"];
   $ATTACHMENT_ID=$ROW["ATTACHMENT_ID"];
   $ATTACHMENT_NAME=$ROW["ATTACHMENT_NAME"];

?>
<tr class="TableData">
      <td align="center"><?=$PLAN_NO?></td>
      <td align="center"><?=$PLAN_NAME?></td>
      <td align="center"><?=$PLAN_RECR_NO?></td>
      <td align="center"><?=$START_DATE?></td>
      <td align="center"><?if($PLAN_STATUS==0){ echo _("待审批")?>
      <td align="center">
      	<a href="javascript:;" onClick="window.open('plan_detail.php?PLAN_ID=<?=$PLAN_ID?>','','height=500,width=820,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=200,top=100,resizable=yes');"><?=_("详细信息")?></a>&nbsp;
        <a href="modify1.php?PLAN_ID=<?=$PLAN_ID?>"><?=_("批准")?></a>&nbsp;
      	<a href="modify2.php?PLAN_ID=<?=$PLAN_ID?>"><?=_("不批准")?></a>
      </td>
<?
 }
 if($PLAN_STATUS==1) 
 {
 	 echo "<font color=green>"._("已批准")."</font>" ?>
      <td align="center">
      	<a href="javascript:;" onClick="window.open('plan_detail.php?PLAN_ID=<?=$PLAN_ID?>','','height=500,width=820,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=200,top=100,resizable=yes');"><?=_("详细信息")?></a>&nbsp;
      	<a href="modify2.php?PLAN_ID=<?=$PLAN_ID?>"><?=_("不批准")?></a>
      </td>
<? 
 }
 if($PLAN_STATUS==2)
 {
   echo "<font color=red>"._("未批准")."</font>" ;
?>
      <td align="center">
      	<a href="javascript:;" onClick="window.open('plan_detail.php?PLAN_ID=<?=$PLAN_ID?>','','height=500,width=820,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=200,top=100,resizable=yes');"><?=_("详细信息")?></a>&nbsp;
      	<a href="modify1.php?PLAN_ID=<?=$PLAN_ID?>"><?=_("批准")?></a>
      </td>
<?
 }
?>
      
</tr>
<?
}
?>
</table>
<br>
<div align="center">
   <input type="button" value="<?=_("返回")?>" class="BigButton" onClick="location='query.php';">
</div>
</html>