<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/check_type.php");
include_once("inc/utility_file.php");
include_once("inc/utility_cache.php");

$HTML_PAGE_TITLE = _("员工复职信息修改保存");
include_once("inc/header.inc.php");
?>



<body class="bodycolor">
<?
if(count($_FILES)>1)
{
   $ATTACHMENTS=upload();
   $CONTENT=ReplaceImageSrc($CONTENT, $ATTACHMENTS);
   $ATTACHMENT_ID=$ATTACHMENT_ID_OLD.$ATTACHMENTS["ID"];
   $ATTACHMENT_NAME=$ATTACHMENT_NAME_OLD.$ATTACHMENTS["NAME"];
}
else
{
   $ATTACHMENT_ID=$ATTACHMENT_ID_OLD;
   $ATTACHMENT_NAME=$ATTACHMENT_NAME_OLD;
}

$ATTACHMENT_ID.=copy_sel_attach($ATTACH_NAME,$ATTACH_DIR,$DISK_ID);
$ATTACHMENT_NAME.=$ATTACH_NAME;

$C = preg_match('/<img.*?\ssrc=\\\"\/inc\/attach.php\?(.*)MODULE=upload_temp/i',$REAPPOINTMENT_STATE);
$REAPPOINTMENT_STATE = replace_attach_url($REAPPOINTMENT_STATE);
if($C==1)
{
    $ATTACHMENT_ID=move_attach($ATTACHMENT_ID,$ATTACHMENT_NAME,"","",true).",";
}

$CUR_TIME=date("Y-m-d H:i:s",time());
//-----------------合法性校验-------------------------------------
if($APPLICATION_DATE!="" && !is_date($APPLICATION_DATE))
{
   Message("",_("申请日期应为日期型，如：1999-01-01"));
   Button_Back();
   exit;
}
if($REAPPOINTMENT_TIME_PLAN!="" && !is_date($REAPPOINTMENT_TIME_PLAN))
{
   Message("",_("拟复职日期应为日期型，如：1999-01-01"));
   Button_Back();
   exit;
}
if($REAPPOINTMENT_TIME_FACT!="" && !is_date($REAPPOINTMENT_TIME_FACT))
{
   Message("",_("实际复职日期应为日期型，如：1999-01-01"));
   Button_Back();
   exit;
}
if($FIRST_SALARY_TIME!="" && !is_date($FIRST_SALARY_TIME))
{
   Message("",_("工资恢复日期应为日期型，如：1999-01-01"));
   Button_Back();
   exit;
}

$query="UPDATE HR_STAFF_REINSTATEMENT SET REINSTATEMENT_PERSON='$REINSTATEMENT_PERSON',REAPPOINTMENT_TYPE='$REAPPOINTMENT_TYPE',APPLICATION_DATE='$APPLICATION_DATE',NOW_POSITION='$NOW_POSITION',REAPPOINTMENT_TIME_PLAN='$REAPPOINTMENT_TIME_PLAN',REAPPOINTMENT_TIME_FACT='$REAPPOINTMENT_TIME_FACT',FIRST_SALARY_TIME='$FIRST_SALARY_TIME',MATERIALS_CONDITION='$MATERIALS_CONDITION',REMARK='$REMARK',REAPPOINTMENT_STATE='$REAPPOINTMENT_STATE',ATTACHMENT_ID='$ATTACHMENT_ID',ATTACHMENT_NAME='$ATTACHMENT_NAME',REAPPOINTMENT_DEPT='$REAPPOINTMENT_DEPT',LAST_UPDATE_TIME='$CUR_TIME' WHERE REINSTATEMENT_ID = '$REINSTATEMENT_ID'";
exequery(TD::conn(),$query);

$query="update USER set DEPT_ID='$REAPPOINTMENT_DEPT',NOT_LOGIN='0',NOT_MOBILE_LOGIN='0' where USER_ID='$REINSTATEMENT_PERSON'";
exequery(TD::conn(),$query);

$query="update HR_STAFF_INFO  set DEPT_ID='$REAPPOINTMENT_DEPT' where USER_ID='$REINSTATEMENT_PERSON'";
exequery(TD::conn(),$query);

set_sys_para(array("ORG_UPDATE" => date("Y-m-d H:i:s")));

cache_users();

header("location:index1.php?REINSTATEMENT_ID=$REINSTATEMENT_ID&connstatus=1")

?>
</body>
</html>
