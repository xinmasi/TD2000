<?
include_once("inc/auth.inc.php");
include_once("inc/utility_file.php");
include_once("inc/check_type.php");

if(strstr($EMPLOYEE_NAME,"/")||strstr($EMPLOYEE_NAME,"\\")||strstr($EMPLOYEE_NAME,".."))
{
	 Message(_("����"),_("ӦƸ�����������Ƿ��ַ���"));
	 exit;
}

$HTML_PAGE_TITLE = _("�½��˲ŵ���");
include_once("inc/header.inc.php");
?>




<body class="bodycolor">
<?
//�ϴ���Ƭ
$PHOTO_NAME0 = $_FILES["ATTACHMENT"]["name"];
if($PHOTO_NAME0!="")
{
 	$FULL_PATH = MYOA_ATTACH_PATH."recruit_pic";
 	if(!file_exists($FULL_PATH))
 	    @mkdir($FULL_PATH,0700);
 	$PHOTO_NAME = $EMPLOYEE_NAME.substr($PHOTO_NAME0,strrpos($PHOTO_NAME0,"."));
    $FILENAME=MYOA_ATTACH_PATH."recruit_pic/".$PHOTO_NAME;
    td_copy($_FILES["ATTACHMENT"]["tmp_name"],$FILENAME);
    if(file_exists($ATTACHMENT))
    {
        unlink($ATTACHMENT);
    }

    if(!file_exists($FILENAME))
    {
        Message(_("�����ϴ�ʧ��"),_("ԭ�򣺸����ļ�Ϊ�ջ��ļ���̫�����򸽼�����30���ֽڣ����ļ�·�������ڣ�"));
        Button_Back();
        exit;
    }
}

if(count($_FILES)>1)
{
   $ATTACHMENTS=upload("ATTACHMENT1");

   $ATTACHMENT_ID=$ATTACHMENT_ID_OLD.$ATTACHMENTS["ID"];
   $ATTACHMENT_NAME=$ATTACHMENT_NAME_OLD.$ATTACHMENTS["NAME"];
}
else
{
 $ATTACHMENT_ID=$ATTACHMENT_ID_OLD;
 $ATTACHMENT_NAME=$ATTACHMENT_NAME_OLD;
}
$ATTACHMENT_ID.=copy_sel_attach($ATTACH_NAME1,$ATTACH_DIR1,$DISK_ID1);
$ATTACHMENT_NAME.=$ATTACH_NAME1;

$CUR_TIME=date("Y-m-d H:i:s",time());
//-----------------�Ϸ���У��-------------------------------------
if($EMPLOYEE_BIRTH!="" && !is_date($EMPLOYEE_BIRTH))
{
   Message("",_("��������ӦΪ�����ͣ��磺1999-01-01"));
   Button_Back();
   exit;
}
if($JOB_BEGINNING!="" && !is_date($JOB_BEGINNING))
{
   Message("",_("�μӹ���ʱ��ӦΪ�����ͣ��磺1999-01-01"));
   Button_Back();
   exit;
}
if($GRADUATION_DATE!="" && !is_date($GRADUATION_DATE))
{
   Message("",_("��ҵʱ��ӦΪ�����ͣ��磺1999-01-01"));
   Button_Back();
   exit;
}
      
$query="insert into HR_RECRUIT_POOL (
  CREATE_USER_ID,
  CREATE_DEPT_ID,
  PLAN_NO,
  PLAN_NAME,
  POSITION,
  ADD_TIME,
  EMPLOYEE_NAME,
  EMPLOYEE_SEX,
  EMPLOYEE_BIRTH,
  EMPLOYEE_NATIVE_PLACE,
  EMPLOYEE_NATIVE_PLACE2,
  EMPLOYEE_DOMICILE_PLACE,
  EMPLOYEE_MARITAL_STATUS,
  EMPLOYEE_POLITICAL_STATUS,
  EMPLOYEE_NATIONALITY,
  EMPLOYEE_PHONE,
  EMPLOYEE_EMAIL,
  JOB_BEGINNING,
  EMPLOYEE_HEALTH,
  JOB_INTENSION,
  FOREIGN_LANGUAGE1,
  FOREIGN_LEVEL1,
  FOREIGN_LANGUAGE2,
  FOREIGN_LEVEL2,
  FOREIGN_LANGUAGE3,
  FOREIGN_LEVEL3,
  EMPLOYEE_HIGHEST_SCHOOL,
  EMPLOYEE_HIGHEST_DEGREE,
  GRADUATION_DATE,
  GRADUATION_SCHOOL,
  EMPLOYEE_MAJOR,
  COMPUTER_LEVEL,
  EMPLOYEE_SKILLS,
  CAREER_SKILLS,
  WORK_EXPERIENCE,
  PROJECT_EXPERIENCE,
  RESUME,
  REMARK,
  ATTACHMENT_ID,
  ATTACHMENT_NAME,
  START_WORKING,
  EXPECTED_SALARY,
  WORK_CITY,
  JOB_INDUSTRY,
  JOB_CATEGORY,
  RESIDENCE_PLACE,
  EMPLOYEE_AGE,
  RECRU_CHANNEL,
  PHOTO_NAME
) values (
'".$_SESSION["LOGIN_USER_ID"]."',
'".$_SESSION["LOGIN_DEPT_ID"]."',
'$PLAN_NO',
'$PLAN_NAME',
'$POSITION',
'$CUR_TIME',
'$EMPLOYEE_NAME',
'$EMPLOYEE_SEX',
'$EMPLOYEE_BIRTH',
'$EMPLOYEE_NATIVE_PLACE',
'$EMPLOYEE_NATIVE_PLACE2',
'$EMPLOYEE_DOMICILE_PLACE',
'$EMPLOYEE_MARITAL_STATUS',
'$EMPLOYEE_POLITICAL_STATUS',
'$EMPLOYEE_NATIONALITY',
'$EMPLOYEE_PHONE',
'$EMPLOYEE_EMAIL',
'$JOB_BEGINNING',
'$EMPLOYEE_HEALTH',
'$JOB_INTENSION',
'$FOREIGN_LANGUAGE1',
'$FOREIGN_LEVEL1',
'$FOREIGN_LANGUAGE2',
'$FOREIGN_LEVEL2',
'$FOREIGN_LANGUAGE3',
'$FOREIGN_LEVEL3',
'$EMPLOYEE_HIGHEST_SCHOOL',
'$EMPLOYEE_HIGHEST_DEGREE',
'$GRADUATION_DATE',
'$GRADUATION_SCHOOL',
'$EMPLOYEE_MAJOR',
'$COMPUTER_LEVEL',
'$EMPLOYEE_SKILLS',
'$CAREER_SKILLS',
'$WORK_EXPERIENCE',
'$PROJECT_EXPERIENCE',
'$RESUME',
'$REMARK',
'$ATTACHMENT_ID',
'$ATTACHMENT_NAME',
'$START_WORKING',
'$EXPECTED_SALARY',
'$WORK_CITY',
'$JOB_INDUSTRY',
'$JOB_CATEGORY',
'$RESIDENCE_PLACE',
'$EMPLOYEE_AGE',
'$RECRU_CHANNEL',
'$PHOTO_NAME')";
exequery(TD::conn(),$query);

Message(_("��ʾ"),_("�˲ŵ�����Ϣ¼��ɹ���"));
?>
<br><center><input type="button" value="<?=_("����")?>" class="BigButton" onClick="location.href='new.php'"></center>
</body>
</html>
