<?
include_once("inc/auth.inc.php");
ob_end_clean();

if(MYOA_IS_UN == 1)
   $EXCEL_OUT="PLAN_NAME,POSITION,NAME,SEX,BIRTH,AGE,NATIONALITY,PHONE,E_mail,MAJOR,HIGHEST_SCHOOL,HIGHEST_DEGREE,GRADUATION_DATE,GRADUATION_SCHOOL,EXPECTED_SALARY,RESIDENCE_PLACE,NATIVE_PLACE,DOMICILE_PLACE,MARITAL_STATUS,POLITICAL_STATUS,HEALTH,JOB_BEGINNING,JOB_CATEGORY,JOB_INDUSTRY,JOB_INTENSION,WORK_CITY,START_WORKING,FOREIGN_LANGUAGE1,FOREIGN_LANGUAGE2,FOREIGN_LANGUAGE3,FOREIGN_LEVEL1,FOREIGN_LEVEL2,FOREIGN_LEVEL3,COMPUTER_LEVEL,RECRU_CHANNEL,SKILLS,CAREER_SKILLS,WORK_EXPERIENCE,PROJECT_EXPERIENCE,MEMO";
else
   $EXCEL_OUT=array(_("�ƻ�����"),_("��λ"),_("ӦƸ������"),_("�Ա�"),_("��������"),_("����"),_("����"),_("��ϵ�绰"),"E_mail",_("��ѧרҵ"),_("ѧ��"),_("ѧλ"),_("��ҵʱ��"),_("��ҵѧУ"),_("����нˮ(˰ǰ)"),_("�־�ס����"),_("����"),_("�������ڵ�"),_("����״��"),_("������ò"),_("����״��"),_("�μӹ���ʱ��"),_("������������"),_("����������ҵ"),_("��������ְҵ"),_("������������"),_("����ʱ��"),_("��������1"),_("��������2"),_("��������3"),_("����ˮƽ1"),_("����ˮƽ2"),_("����ˮƽ3"),_("�����ˮƽ"),_("��Ƹ����"),_("�س�"),_("ְҵ����"),_("��������"),_("��Ŀ����"),_("��ע"));

require_once ('inc/ExcelWriter.php');

$objExcel = new ExcelWriter();
$objExcel->setFileName(_("�˲ŵ���ģ��"));
$objExcel->addHead($EXCEL_OUT);

$objExcel->Save();
?>