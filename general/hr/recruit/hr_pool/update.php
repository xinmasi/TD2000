<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/check_type.php");
include_once("inc/utility_file.php");

if(strstr($EMPLOYEE_NAME,"/")||strstr($EMPLOYEE_NAME,"\\")||strstr($EMPLOYEE_NAME,".."))
{
	 Message(_("错误"),_("应聘人姓名包含非法字符。"));
	 exit;
}


$HTML_PAGE_TITLE = _("人事档案修改保存");
include_once("inc/header.inc.php");
?>



<body class="bodycolor">
<?
//上传照片
$PHOTO_NAME0 = $_FILES["ATTACHMENT"]["name"];
if($PHOTO_NAME0!="")
{
 	 $FULL_PATH = MYOA_ATTACH_PATH."recruit_pic";
 	 if(!file_exists($FULL_PATH))
 	    @mkdir($FULL_PATH,0700);
 	 $PHOTO_NAME = $EMPLOYEE_NAME.substr($PHOTO_NAME0,strrpos($PHOTO_NAME0,"."));
   $FILENAME=MYOA_ATTACH_PATH."recruit_pic/".$PHOTO_NAME;
   td_copy($_FILES["ATTACHMENT"]["tmp_name"],$FILENAME);
   unlink($ATTACHMENT);

   if(!file_exists($FILENAME))
   {
      Message(_("附件上传失败"),_("原因：附件文件为空或文件名太长，或附件大于30兆字节，或文件路径不存在！"));
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
//-----------------合法性校验-------------------------------------
if($EMPLOYEE_BIRTH!="" && !is_date($EMPLOYEE_BIRTH))
{
   Message("",_("出生日期应为日期型，如：1999-01-01"));
   Button_Back();
   exit;
}
if($JOB_BEGINNING!="" && !is_date($JOB_BEGINNING))
{
   Message("",_("参加工作时间应为日期型，如：1999-01-01"));
   Button_Back();
   exit;
}
if($GRADUATION_DATE!="" && !is_date($GRADUATION_DATE))
{
   Message("",_("毕业时间应为日期型，如：1999-01-01"));
   Button_Back();
   exit;
}

$query="UPDATE HR_RECRUIT_POOL SET 
  			PLAN_NO='$PLAN_NO',
  			PLAN_NAME='$PLAN_NAME',
  			POSITION='$POSITION',
  			EMPLOYEE_NAME='$EMPLOYEE_NAME',
  			EMPLOYEE_SEX='$EMPLOYEE_SEX',
  			EMPLOYEE_BIRTH='$EMPLOYEE_BIRTH',
  			EMPLOYEE_NATIVE_PLACE='$EMPLOYEE_NATIVE_PLACE',
  			EMPLOYEE_NATIVE_PLACE2='$EMPLOYEE_NATIVE_PLACE2',
  			EMPLOYEE_DOMICILE_PLACE='$EMPLOYEE_DOMICILE_PLACE',
  			EMPLOYEE_NATIONALITY='$EMPLOYEE_NATIONALITY',
  			EMPLOYEE_MARITAL_STATUS='$EMPLOYEE_MARITAL_STATUS',
  			EMPLOYEE_POLITICAL_STATUS='$EMPLOYEE_POLITICAL_STATUS',
  			EMPLOYEE_PHONE='$EMPLOYEE_PHONE',
  			EMPLOYEE_EMAIL='$EMPLOYEE_EMAIL',
  			JOB_BEGINNING='$JOB_BEGINNING',
  			EMPLOYEE_HEALTH='$EMPLOYEE_HEALTH',
  			EMPLOYEE_HIGHEST_SCHOOL='$EMPLOYEE_HIGHEST_SCHOOL',
  			EMPLOYEE_HIGHEST_DEGREE='$EMPLOYEE_HIGHEST_DEGREE',
  			GRADUATION_DATE='$GRADUATION_DATE',
  			GRADUATION_SCHOOL='$GRADUATION_SCHOOL',
  			EMPLOYEE_MAJOR='$EMPLOYEE_MAJOR',
  			COMPUTER_LEVEL='$COMPUTER_LEVEL',
  			FOREIGN_LANGUAGE1='$FOREIGN_LANGUAGE1',
  			FOREIGN_LEVEL1='$FOREIGN_LEVEL1',
  			FOREIGN_LANGUAGE2='$FOREIGN_LANGUAGE2',
  			FOREIGN_LEVEL2='$FOREIGN_LEVEL2',
  			FOREIGN_LANGUAGE3='$FOREIGN_LANGUAGE3',
  			FOREIGN_LEVEL3='$FOREIGN_LEVEL3',
  			EMPLOYEE_SKILLS='$EMPLOYEE_SKILLS',
  			RESUME='$RESUME',
  			JOB_INTENSION='$JOB_INTENSION',
  			CAREER_SKILLS='$CAREER_SKILLS',
  			WORK_EXPERIENCE='$WORK_EXPERIENCE',
  			PROJECT_EXPERIENCE='$PROJECT_EXPERIENCE',
  			RESIDENCE_PLACE='$RESIDENCE_PLACE',
  			JOB_CATEGORY='$JOB_CATEGORY',
  			JOB_INDUSTRY='$JOB_INDUSTRY',
  			WORK_CITY='$WORK_CITY',
  			EXPECTED_SALARY='$EXPECTED_SALARY',
  			START_WORKING='$START_WORKING',
  			REMARK='$REMARK',
  			RECRU_CHANNEL='$RECRU_CHANNEL',
  			ATTACHMENT_ID='$ATTACHMENT_ID',
  			ATTACHMENT_NAME='$ATTACHMENT_NAME'"; 
if($PHOTO_NAME!="")
$query.=",PHOTO_NAME='$PHOTO_NAME'";
$query.=" where EXPERT_ID='$EXPERT_ID'";
exequery(TD::conn(),$query);
header("location:index1.php?EXPERT_ID=$EXPERT_ID&connstatus=1")
?>
</body>
</html>
