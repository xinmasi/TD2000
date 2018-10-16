<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");

$PAGE_SIZE = 20;
if(!isset($start) || $start=="")
   $start=0;

$HTML_PAGE_TITLE = _("招聘录用信息查询");
include_once("inc/header.inc.php");
?>





<script>
function delete_user(PLAN_ID)
{
  if(confirm("<?=_("确定要删除该招聘录用信息吗？删除后将不可恢复")?>"))
     location = "delete.php?RECRUITMENT_ID="+PLAN_ID;
}

function check_one(el)
{
   if(!el.checked)
      document.getElementsByName("allbox").item(0).checked=false;
}

function check_all()
{
 for (i=0;i<document.getElementsByName("hrms_select").length;i++)
 {
   if(document.getElementsByName("allbox").item(0).checked)
      document.getElementsByName("hrms_select").item(i).checked=true;
   else
      document.getElementsByName("hrms_select").item(i).checked=false;
 }

 if(i==0)
 {
   if(document.getElementsByName("allbox").item(0).checked)
      document.getElementsByName("hrms_select").checked=true;
   else
      document.getElementsByName("hrms_select").checked=false;
 }
}
function delete_mail()
{
  delete_str="";
  for(i=0;i<document.getElementsByName("hrms_select").length;i++)
  {

      el=document.getElementsByName("hrms_select").item(i);
      if(el.checked)
      {  val=el.value;
         delete_str+=val + ",";
      }
  }

  if(i==0)
  {
      el=document.getElementsByName("hrms_select");
      if(el.checked)
      {  val=el.value;
         delete_str+=val + ",";
      }
  }

  if(delete_str=="")
  {
     alert("<?=_("要删除招聘录用信息，请至少选择其中一条。")?>");
     return;
  }

  msg='<?=_("确认要删除所选招聘录用信息吗？")?>';
  if(window.confirm(msg))
  {
    url="delete.php?RECRUITMENT_ID="+ delete_str +"&start=<?=$start?>";
    location=url;
  }
}
</script>

<?
//------------------------ 生成条件字符串 ------------------
$CONDITION_STR="";
if($PLAN_NO!="")
   $CONDITION_STR.=" and PLAN_NO='$PLAN_NO'";
if($APPLYER_NAME!="")
   $CONDITION_STR.=" and APPLYER_NAME='$APPLYER_NAME'";
if($JOB_STATUS!="")
  $CONDITION_STR.=" and JOB_STATUS='$JOB_STATUS'";
if($ASSESSING_OFFICER!="")
  $CONDITION_STR.=" and ASSESSING_OFFICER='$ASSESSING_OFFICER'";
if($ASS_PASS_TIME_END!="")
  $CONDITION_STR.=" and ASS_PASS_TIME_END>='$ASS_PASS_TIME'";   
if($ASS_PASS_TIME_START!="")
  $CONDITION_STR.=" and ASS_PASS_TIME_START<='$ASS_PASS_TIME'";
if($DEPARTMENT!="")
  $CONDITION_STR.=" and DEPARTMENT='$DEPARTMENT'";
if($TYPE!="")
  $CONDITION_STR.=" and TYPE='$TYPE'";
if($ADMINISTRATION_LEVEL!="")
  $CONDITION_STR.=" and ADMINISTRATION_LEVEL='$ADMINISTRATION_LEVEL'";
if($JOB_POSITION!="")
  $CONDITION_STR.=" and JOB_POSITION='$JOB_POSITION'";
if($PRESENT_POSITION!="")
  $CONDITION_STR.=" and PRESENT_POSITION='$PRESENT_POSITION'";
if($REMARK!="")
  $CONDITION_STR.=" and REMARK LIKE '%".$REMARK."%'";
if($OA_NAME!="")
  $CONDITION_STR.=" and OA_NAME='$OA_NAME'";


$WHERE_STR = hr_priv("");
$CONDITION_STR = hr_priv("").$CONDITION_STR;
$query = "SELECT * from HR_RECRUIT_RECRUITMENT WHERE".$WHERE_STR;
$cursor=exequery(TD::conn(),$query);
$STAFF_COUNT = mysql_num_rows($cursor);

$query = "SELECT * from HR_RECRUIT_RECRUITMENT WHERE".$CONDITION_STR."ORDER BY RECRUITMENT_ID desc limit $start,$PAGE_SIZE";
$cursor=exequery(TD::conn(),$query);
$COUNT = mysql_num_rows($cursor);

?>
<body class="bodycolor">
<?
if($COUNT <= 0)
{
   Message("", _("无符合条件的招聘录用信息"));
   Button_Back();
   exit;
}
?>
	<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/infofind.gif" align="absMiddle"><span class="big3"><?=_("招聘录用信息查询结果")?></span><br>
    	</td>
    <td align="right" valign="bottom" class="small1"><?=page_bar($start,$STAFF_COUNT,$PAGE_SIZE)?></td>
	</tr>
</table>
<table class="TableList" width="100%">
   <thead class="TableHeader">
      <td nowrap align="center"><?=_("选择")?></td>
      <td nowrap align="center"><?=_("计划编号")?></td>
      <td nowrap align="center"><?=_("招聘岗位")?></td>
      <td nowrap align="center"><?=_("应聘人姓名")?></td>
      <td nowrap align="center"><?=_("录用负责人")?></td>
      <td nowrap align="center"><?=_("录入日期")?></td>
      <td width="150"><?=_("操作")?></td>
   </thead>
<?
while($ROW=mysql_fetch_array($cursor))
{
   $REQUIREMENTS_COUNT++;

   $RECRUITMENT_ID=$ROW["RECRUITMENT_ID"];
   $PLAN_NO=$ROW["PLAN_NO"];
   $APPLYER_NAME=$ROW["APPLYER_NAME"];
   $JOB_STATUS=$ROW["JOB_STATUS"];
   $ASSESSING_OFFICER=$ROW["ASSESSING_OFFICER"];
   $ASS_PASS_TIME=$ROW["ASS_PASS_TIME"];
   $DEPARTMENT=$ROW["DEPARTMENT"];
   $TYPE=$ROW["TYPE"];
   $ADMINISTRATION_LEVEL=$ROW["ADMINISTRATION_LEVEL"];
   $JOB_POSITION=$ROW["JOB_POSITION"]; 
   $PRESENT_POSITION=$ROW["PRESENT_POSITION"];
   $ON_BOARDING_TIME=$ROW["ON_BOARDING_TIME"];
   $STARTING_SALARY_TIME=$ROW["STARTING_SALARY_TIME"];
   $REMARK=$ROW["REMARK"];
   
   $ASSESSING_OFFICER_NAME=substr(GetUserNameById($ASSESSING_OFFICER),0,-1);

?>
<tr class="TableData">
      <td align="center"><input type="checkbox" name="hrms_select" value="<?=$RECRUITMENT_ID?>" onClick="check_one(self);"></td>
      <td align="center"><?=$PLAN_NO?></td>
      <td align="center"><?=$JOB_STATUS?></td>
      <td align="center"><?=$APPLYER_NAME?></td>
      <td align="center"><?=$ASSESSING_OFFICER_NAME?></td>
      <td align="center"><?=$ASS_PASS_TIME?></td>
      <td align="center">
      	<a href="javascript:;" onClick="window.open('recruitment_detail.php?RECRUITMENT_ID=<?=$RECRUITMENT_ID?>','','height=500,width=820,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=200,top=100,resizable=yes');"><?=_("详细信息")?></a>&nbsp;
        <a href="modify.php?RECRUITMENT_ID=<?=$RECRUITMENT_ID?>"><?=_("编辑")?></a>&nbsp;&nbsp;
        <a href="javascript:delete_user('<?=$RECRUITMENT_ID?>');"><?=_("删除")?></a>
      </td>
</tr>
<?
}
?>
<tr class="TableControl">
      <td colspan="19">
         <input type="checkbox" name="allbox" id="allbox_for" onClick="check_all();"><label for="allbox_for"><?=_("全选")?></label> &nbsp;
         <a href="javascript:delete_mail();" title="<?=_("删除所选招聘录用信息")?>"><img src="<?=MYOA_STATIC_SERVER?>/static/images/delete.gif" align="absMiddle"><?=_("删除所选招聘录用信息")?></a>&nbsp;
      </td>
   </tr>
</table>
<br>
<div align="center">
   <input type="button" value="<?=_("返回")?>" class="BigButton" onClick="location='query.php';">
</div>
</html>