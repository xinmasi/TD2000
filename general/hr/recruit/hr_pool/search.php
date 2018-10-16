<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");

$PAGE_SIZE = 20;
if(!isset($start) || $start=="")
   $start=0;

$HTML_PAGE_TITLE = _("人才档案查询");
include_once("inc/header.inc.php");
?>


<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/theme/<?=$_SESSION["LOGIN_THEME"]?>/bbs.css">
<script>
function delete_pool(EXPERT_ID)
{
  msg='<?=_("确认要删除该人才档案信息吗？")?>';
  if(window.confirm(msg))
  {
     URL="delete.php?EXPERT_ID=" + EXPERT_ID+"&PAGE_START=<?=$PAGE_START?>";
     window.location=URL;
  }
}

function check_all()
{
   for(i=0;i<document.getElementsByName("email_select").length;i++)
   {
      if(document.getElementsByName("allbox").item(0).checked)
         document.getElementsByName("email_select").item(i).checked=true;
      else
         document.getElementsByName("email_select").item(i).checked=false;
   }

   if(i==0)
   {
      if(document.getElementsByName("allbox").item(0).checked)
         document.getElementsByName("email_select").checked=true;
      else
         document.getElementsByName("email_select").checked=false;
   }
}

function check_one(el)
{
   if(!el.checked)
      document.getElementsByName("allbox").item(0).checked=false;
}

function get_checked()
{
   checked_str="";
   for(i=0;i<document.getElementsByName("email_select").length;i++)
   {

      el=document.getElementsByName("email_select").item(i);
      if(el.checked)
      {  val=el.value;
         checked_str+=val + ",";
      }
   }

  if(i==0)
  {
      el=document.getElementsByName("email_select");
      if(el.checked)
      {  val=el.value;
         checked_str+=val + ",";
      }
  }
  return checked_str;
}

function delete_mail()
{
  delete_str=get_checked();
  if(delete_str=="")
  {
     alert("<?=_("要删除人才档案信息，请至少选择其中一条。")?>");
     return;
  }

  msg='<?=_("确认要删除该人才档案信息吗？")?>';
  if(window.confirm(msg))
  {
    url="delete.php?EXPERT_ID="+ delete_str +"&PAGE_START=<?=$PAGE_START?>";
    location=url;
  }
}

function change_type(type)
{
   window.location="index1.php?start=<?=$start?>";
}
</script>

<body class="bodycolor">
<?
//------------------------ 生成条件字符串 ------------------
$CONDITION_STR="";
if($PLAN_NO!="")
   $CONDITION_STR.=" and PLAN_NO='$PLAN_NO'";
if($EMPLOYEE_NAME!="")
   $CONDITION_STR.=" and EMPLOYEE_NAME='$EMPLOYEE_NAME'";
if($EMPLOYEE_SEX!="")
   $CONDITION_STR.=" and EMPLOYEE_SEX='$EMPLOYEE_SEX'";
if($EMPLOYEE_NATIVE_PLACE!="")
  $CONDITION_STR.=" and EMPLOYEE_NATIVE_PLACE='$EMPLOYEE_NATIVE_PLACE'";
if($EMPLOYEE_POLITICAL_STATUS!="")
  $CONDITION_STR.=" and EMPLOYEE_POLITICAL_STATUS='$EMPLOYEE_POLITICAL_STATUS'";   
if($POSITION!="")
  $CONDITION_STR.=" and POSITION like '%".$POSITION."%'"; 
if($JOB_CATEGORY!="")
  $CONDITION_STR.=" and JOB_CATEGORY='$JOB_CATEGORY'";
if($JOB_INTENSION!="")
  $CONDITION_STR.=" and JOB_INTENSION like '%".$JOB_INTENSION."%'";   
if($WORK_CITY!="")
  $CONDITION_STR.=" and WORK_CITY like '%".$WORK_CITY."%'"; 
if($EXPECTED_SALARY!="")
  $CONDITION_STR.=" and EXPECTED_SALARY='$EXPECTED_SALARY'";  
if($START_WORKING!="")
  $CONDITION_STR.=" and START_WORKING='$START_WORKING'";    
if($EMPLOYEE_MAJOR!="")
  $CONDITION_STR.=" and EMPLOYEE_MAJOR like '%".$EMPLOYEE_MAJOR."%'";   
if($EMPLOYEE_HIGHEST_SCHOOL!="")
  $CONDITION_STR.=" and EMPLOYEE_HIGHEST_SCHOOL='$EMPLOYEE_HIGHEST_SCHOOL'";  
if($RESIDENCE_PLACE!="")
  $CONDITION_STR.=" and RESIDENCE_PLACE like '%".$RESIDENCE_PLACE."%'";     
if($EMPLOYEE_NATIONALITY!="")
  $CONDITION_STR.=" and EMPLOYEE_NATIONALITY='$EMPLOYEE_NATIONALITY'";    
if($EMPLOYEE_HEALTH!="")
  $CONDITION_STR.=" and EMPLOYEE_HEALTH like '%".$EMPLOYEE_HEALTH."%'"; 
if($EMPLOYEE_MARITAL_STATUS!="")
  $CONDITION_STR.=" and EMPLOYEE_MARITAL_STATUS='$EMPLOYEE_MARITAL_STATUS'";     
if($EMPLOYEE_DOMICILE_PLACE!="")
  $CONDITION_STR.=" and EMPLOYEE_DOMICILE_PLACE like '%".$EMPLOYEE_DOMICILE_PLACE."%'"; 
if($GRADUATION_SCHOOL!="")
  $CONDITION_STR.=" and GRADUATION_SCHOOL like '%".$GRADUATION_SCHOOL."%'";   
if($COMPUTER_LEVEL!="")
  $CONDITION_STR.=" and COMPUTER_LEVEL like '%".$COMPUTER_LEVEL."%'";     
if($FOREIGN_LANGUAGE1!="")
  $CONDITION_STR.=" and FOREIGN_LANGUAGE1 like '%".$FOREIGN_LANGUAGE1."%'";   
if($FOREIGN_LANGUAGE2!="")
  $CONDITION_STR.=" and FOREIGN_LANGUAGE2 like '%".$FOREIGN_LANGUAGE2."%'";       
if($FOREIGN_LEVEL1!="")
  $CONDITION_STR.=" and FOREIGN_LEVEL1 like '%".$FOREIGN_LEVEL1."%'";   
if($FOREIGN_LEVEL2!="")
  $CONDITION_STR.=" and FOREIGN_LEVEL2 like '%".$FOREIGN_LEVEL2."%'";       
    
?>
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/menu/infofind.gif" align="absMiddle"><span class="big3"> <?=_("人才档案查询结果")?></span><br>
    	</td>
    <td align="right" valign="bottom" class="small1"><?=page_bar($start,$STAFF_COUNT,$PAGE_SIZE)?></td>
	</tr>
</table>
<?
$CONDITION_STR = hr_priv("").$CONDITION_STR;
$query = "SELECT * from HR_RECRUIT_POOL where".$CONDITION_STR."order by ADD_TIME desc";
$cursor=exequery(TD::conn(),$query);
$POOL_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
   $POOL_COUNT++;

   $EXPERT_ID=$ROW["EXPERT_ID"];
   $CREATE_USER_ID=$ROW["CREATE_USER_ID"];
   $CREATE_DEPT_ID=$ROW["CREATE_DEPT_ID"];
   $PLAN_NO=$ROW["PLAN_NO"];
   $POSITION=$ROW["POSITION"];
   $ADD_TIME=$ROW["ADD_TIME"];
   $EMPLOYEE_NAME=$ROW["EMPLOYEE_NAME"];
   $EMPLOYEE_BIRTH=$ROW["EMPLOYEE_BIRTH"];
   $EMPLOYEE_PHONE=$ROW["EMPLOYEE_PHONE"];
   $EMPLOYEE_HIGHEST_SCHOOL=$ROW["EMPLOYEE_HIGHEST_SCHOOL"];
   $EMPLOYEE_MAJOR=$ROW["EMPLOYEE_MAJOR"];
   $EMPLOYEE_AGE=$ROW["EMPLOYEE_AGE"];
   
   $EMPLOYEE_HIGHEST_SCHOOL=get_hrms_code_name($EMPLOYEE_HIGHEST_SCHOOL,"STAFF_HIGHEST_SCHOOL");
   $POSITION_NAME=get_hrms_code_name($POSITION,"POOL_POSITION");
	 $EMPLOYEE_MAJOR_NAME=get_hrms_code_name($EMPLOYEE_MAJOR,"POOL_EMPLOYEE_MAJOR");
   $query1 = "SELECT PLAN_NAME from HR_RECRUIT_PLAN where PLAN_NO='$PLAN_NO'";
   $cursor1= exequery(TD::conn(),$query1);
   if($ROW1=mysql_fetch_array($cursor1))
      $PLAN_NAME=$ROW1["PLAN_NAME"];       
   
  if($POOL_COUNT==1)
  {
?>
<table class="TableList" width="100%">
  <thead class="TableHeader">
  	  <td nowrap align="center"><?=_("选择")?></td>      
      <td nowrap align="center"><?=_("计划名称")?></td>
      <td nowrap align="center"><?=_("应聘者姓名")?></td>
      <td nowrap align="center"><?=_("出生日期")?></td>
      <td nowrap align="center"><?=_("联系电话")?></td>
      <td nowrap align="center"><?=_("学历")?></td>
      <td nowrap align="center"><?=_("专业")?></td>
      <td nowrap align="center"><?=_("入库时间")?></td>
      <td nowrap align="center"><?=_("岗位")?></td>
      <td nowrap align="center"><?=_("操作")?></td>
  </thead> 
  
<?
   }
?>
     <tr class="TableData">
      <td>&nbsp;<input type="checkbox" name="email_select" value="<?=$EXPERT_ID?>" onClick="check_one(self);">
      <td nowrap align="center"><?=$PLAN_NAME?></td>
      <td nowrap align="center"><?=$EMPLOYEE_NAME?></td>
      <td nowrap align="center"><?=$EMPLOYEE_BIRTH?></td>
      <td nowrap align="center"><?=$EMPLOYEE_PHONE?></td>
      <td nowrap align="center"><?=$EMPLOYEE_HIGHEST_SCHOOL?></td>
      <td nowrap align="center"><?=$EMPLOYEE_MAJOR_NAME?></td>
      <td nowrap align="center"><?=$ADD_TIME?></td>
      <td nowrap align="center"><?=$POSITION_NAME?></td>      
      <td nowrap align="center">
      <a href="javascript:;" onClick="window.open('pool_detail.php?EXPERT_ID=<?=$EXPERT_ID?>','','height=500,width=820,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=200,top=100,resizable=yes');"><?=_("详细信息")?></a>&nbsp;
      <a href="modify.php?EXPERT_ID=<?=$EXPERT_ID?>"> <?=_("修改")?></a>&nbsp;
			<a href="javascript:delete_pool(<?=$EXPERT_ID?>);"> <?=_("删除")?></a>&nbsp;
      </td>
   </tr>
<?
}

if($POOL_COUNT==0)
{
   Message("",_("无符合条件的人才档案信息！"));
   Button_Back();
   exit;
}
else
{
?>
   <tr class="TableControl">
     <td colspan="19">
       <input type="checkbox" name="allbox" id="allbox_for" onClick="check_all();"><label for="allbox_for"><?=_("全选")?></label>&nbsp;
       <a href="javascript:delete_mail();" title="<?=_("删除所选人才档案信息")?>"><img src="<?=MYOA_STATIC_SERVER?>/static/images/delete.gif" align="absMiddle"><?=_("删除")?></a>&nbsp; 
     </td>
   </tr>
</table>
<?
   Button_Back();
}
?>
</body>

</html>
