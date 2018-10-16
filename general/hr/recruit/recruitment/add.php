<?
include_once("inc/auth.inc.php");
include_once("inc/utility_file.php");
include_once("inc/check_type.php");

$HTML_PAGE_TITLE = _("新建招聘录用信息");
include_once("inc/header.inc.php");
?>




<body class="bodycolor">
<?
$query1 = "SELECT * from HR_RECRUIT_RECRUITMENT WHERE OA_NAME='$OA_NAME'";
$cursor1=exequery(TD::conn(),$query1);
if($ROW1=mysql_fetch_array($cursor1))
{
   $query="update HR_RECRUIT_RECRUITMENT set PLAN_NO='$PLAN_NO',APPLYER_NAME='$APPLYER_NAME',JOB_STATUS='$JOB_STATUS',ASSESSING_OFFICER='$ASSESSING_OFFICER',ASS_PASS_TIME='$ASS_PASS_TIME',DEPARTMENT='$DEPARTMENT',TYPE='$TYPE',ADMINISTRATION_LEVEL='$ADMINISTRATION_LEVEL',JOB_POSITION='$JOB_POSITION',PRESENT_POSITION='$PRESENT_POSITION',ON_BOARDING_TIME='$ON_BOARDING_TIME',STARTING_SALARY_TIME='$STARTING_SALARY_TIME',REMARK='$REMARK',OA_NAME='$OA_NAME'";
   $query.=" where RECRUITMENT_ID='$RECRUITMENT_ID'";
   exequery(TD::conn(),$query);
}
else
{
  $query="insert into HR_RECRUIT_RECRUITMENT  (CREATE_USER_ID,CREATE_DEPT_ID,PLAN_NO,ASSESSING_OFFICER,ASS_PASS_TIME,DEPARTMENT,TYPE,ADMINISTRATION_LEVEL,JOB_POSITION,PRESENT_POSITION,ON_BOARDING_TIME,STARTING_SALARY_TIME,REMARK,JOB_STATUS,APPLYER_NAME,OA_NAME,EXPERT_ID) values ('".$_SESSION["LOGIN_USER_ID"]."','".$_SESSION["LOGIN_DEPT_ID"]."','$PLAN_NO','$ASSESSING_OFFICER','$ASS_PASS_TIME','$DEPARTMENT','$TYPE','$ADMINISTRATION_LEVEL','$JOB_POSITION','$PRESENT_POSITION','$ON_BOARDING_TIME','$STARTING_SALARY_TIME','$REMARK','$JOB_STATUS','$APPLYER_NAME','$OA_NAME','$EXPERT_ID')";
  exequery(TD::conn(),$query);
}
Message(_("提示"),_("招聘录用信息录入成功！"));

$query = "SELECT * from HR_STAFF_INFO WHERE USER_ID='$OA_NAME'";
$cursor=exequery(TD::conn(),$query);
$USER_COUNT = mysql_num_rows($cursor);
?>
<div align="center">
   <input type="button" value="<?=_("返回")?>" class="BigButton" title="<?=_("继续新建招聘录用信息")?>" onclick="location='new.php'">&nbsp;&nbsp;
<?
if($USER_COUNT==0)
{
?>
   <input type="button" value="<?=_("建立人事档案")?>" class="BigButton" title="<?=_("建立人事档案")?>" onClick=window.open('new_staff.php?EXPERT_ID=<?=$EXPERT_ID?>','','height=700,width=900,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=300,top=270,resizable=yes');>&nbsp;&nbsp;
<?
}
?>
</div>

</body>
</html>
