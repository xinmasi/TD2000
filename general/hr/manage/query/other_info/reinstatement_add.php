<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");
include_once("inc/check_type.php");
include_once("inc/utility_cache.php");

$HTML_PAGE_TITLE = _("Ա����ְ��Ϣ");
include_once("inc/header.inc.php");
?>
<body class="bodycolor">
<?
$CUR_TIME=date("Y-m-d H:i:s",time());
//-----------------У��-------------------------------------
if($APPLICATION_DATE!="" && !is_date($APPLICATION_DATE))
{
   Message("",_("��������ӦΪ�����ͣ��磺1999-01-01"));
   Button_Back();
   exit;
}
if($REAPPOINTMENT_TIME_PLAN!="" && !is_date($REAPPOINTMENT_TIME_PLAN))
{
   Message("",_("�⸴ְ����ӦΪ�����ͣ��磺1999-01-01"));
   Button_Back();
   exit;
}
if($REAPPOINTMENT_TIME_FACT!="" && !is_date($REAPPOINTMENT_TIME_FACT))
{
   Message("",_("ʵ�ʸ�ְ����ӦΪ�����ͣ��磺1999-01-01"));
   Button_Back();
   exit;
}
if($FIRST_SALARY_TIME!="" && !is_date($FIRST_SALARY_TIME))
{
   Message("",_("���ʻָ�����ӦΪ�����ͣ��磺1999-01-01"));
   Button_Back();
   exit;
}
//--------- �ϴ����� ----------
if(count($_FILES)>1)
{
   $ATTACHMENTS=upload();
   $CONTENT=ReplaceImageSrc($CONTENT, $ATTACHMENTS);

   $ATTACHMENT_ID=$ATTACHMENTS["ID"];
   $ATTACHMENT_NAME=$ATTACHMENTS["NAME"];
}

$ATTACHMENT_ID.=copy_sel_attach($ATTACH_NAME,$ATTACH_DIR,$DISK_ID);
$ATTACHMENT_NAME.=$ATTACH_NAME;
//------------------- ����ְ��������Ϣ -----------------------
$query="insert into HR_STAFF_REINSTATEMENT(CREATE_USER_ID,CREATE_DEPT_ID,REAPPOINTMENT_TIME_FACT,REAPPOINTMENT_TYPE,REAPPOINTMENT_STATE,REMARK,REINSTATEMENT_PERSON,REAPPOINTMENT_TIME_PLAN,NOW_POSITION,APPLICATION_DATE,FIRST_SALARY_TIME,MATERIALS_CONDITION,ATTACHMENT_ID,ATTACHMENT_NAME,REAPPOINTMENT_DEPT,ADD_TIME,LAST_UPDATE_TIME) values ('{$_SESSION['LOGIN_USER_ID']}','{$_SESSION['LOGIN_DEPT_ID']}','$REAPPOINTMENT_TIME_FACT','$REAPPOINTMENT_TYPE','$REAPPOINTMENT_STATE','$REMARK','$REINSTATEMENT_PERSON','$REAPPOINTMENT_TIME_PLAN','$NOW_POSITION','$APPLICATION_DATE','$FIRST_SALARY_TIME','$MATERIALS_CONDITION','$ATTACHMENT_ID','$ATTACHMENT_NAME','$REAPPOINTMENT_DEPT','$CUR_TIME','$CUR_TIME')";
exequery(TD::conn(),$query);

$query="update USER set DEPT_ID='$REAPPOINTMENT_DEPT',NOT_LOGIN='0' where USER_ID='$REINSTATEMENT_PERSON'";
exequery(TD::conn(),$query);
  
$query="update HR_STAFF_INFO  set DEPT_ID='$REAPPOINTMENT_DEPT', WORK_STATUS='01' where USER_ID='$REINSTATEMENT_PERSON'";
exequery(TD::conn(),$query);

$query="update HR_STAFF_LEAVE set IS_REINSTATEMENT='1' where LEAVE_PERSON='$REINSTATEMENT_PERSON'";
exequery(TD::conn(),$query);

set_sys_para(array("ORG_UPDATE" => date("Y-m-d H:i:s"))); 

cache_users();

Message("",_("�ɹ�����Ա����ְ��Ϣ��"));
Button_Back();
?>
</body>
</html>
