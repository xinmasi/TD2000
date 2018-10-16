<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");
include_once("inc/utility_field.php");

include_once("inc/header.inc.php");
?>



<body class="bodycolor">
<?
//-- 保存 --

$query="update HR_RECRUIT_RECRUITMENT set PLAN_NO='$PLAN_NO',APPLYER_NAME='$APPLYER_NAME',JOB_STATUS='$JOB_STATUS',ASSESSING_OFFICER='$ASSESSING_OFFICER',ASS_PASS_TIME='$ASS_PASS_TIME',DEPARTMENT='$DEPARTMENT',TYPE='$TYPE',ADMINISTRATION_LEVEL='$ADMINISTRATION_LEVEL',JOB_POSITION='$JOB_POSITION',PRESENT_POSITION='$PRESENT_POSITION',ON_BOARDING_TIME='$ON_BOARDING_TIME',STARTING_SALARY_TIME='$STARTING_SALARY_TIME',REMARK='$REMARK',OA_NAME='$OA_NAME'";
$query.=" where RECRUITMENT_ID='$RECRUITMENT_ID'";
exequery(TD::conn(),$query);
//echo $query;exit;
Message(_("提示"),$APPLYER_NAME._(" 的招聘录入信息已保存。"));
header("location: index1.php?connstatus=1");
?>
</body>
</html>
