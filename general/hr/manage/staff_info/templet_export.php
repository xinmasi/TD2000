<?
include_once("inc/auth.inc.php");
ob_end_clean();

$query = "select * from FIELDSETTING where TABLENAME='HR_STAFF_INFO'";
$cursor= exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
    $FIELDNAME.=$ROW["FIELDNAME"].",";
    $FIELDNO.=$ROW["FIELDNO"].",";
}

if(MYOA_IS_UN == 1)
{
    $EXCEL_OUT="NAME,ID,DEPT_NAME,USER_PRIV_NAME,BEFORE_NAME,ENGLISH_NAME,SEX,STAFF_NO,WORK_NO,WORK_JOB,CARD_NO,BIRTH,AGE,NATIVE_PLACE(PROVINCE),NATIVE_PLACE(CITY),NATIONALITY,MARITAL_STATUS,POLITICAL_STATUS,WORK_STATUS,JOIN_PARTY_TIME,PHONE,MOBILE,MSN,QQ,EMAIL,ADDRESS,JOB_BEGINNING,OTHER_CONTACT,WORK_AGE,HEALTH,DOMICILE_PLACE,DIFFERENT_PLACE,DOMICILE_TYPE,DATES_EMPLOYED,BANK1,BANK_ACCOUNT1,BANK2,BANK_ACCOUNT2,HIGHEST_SCHOOL,HIGHEST_DEGREE,GRADUATION_DATE,MAJOR,GRADUATION_SCHOOL,COMPUTER_LEVEL,FOREIGN_LANGUAGE1,FOREIGN_LANGUAGE2,FOREIGN_LANGUAGE3,FOREIGN_LEVEL1,FOREIGN_LEVEL2,FOREIGN_LEVEL3,SKILLS,WORK_TYPE,ADMINISTRATION_LEVEL,OCCUPATION,JOB_POSITION,PRESENT_POSITION,PRESENT_POSITION_LEVEL,JOB_AGE,BEGIN_SALSRY_TIME,LEAVE_TYPE,RESUME,SURETY,CERTIFICATE,INSURE,BODY_EXAMIM,MEMO".$FIELDNO;
}
else
{
    $EXCEL_OUT_REGULAR=array(_("����"),_("�û���"),_("����"),_("��ɫ"),_("������"),_("Ӣ����"),_("�Ա�"),_("���"),_("����"),_("��λ"),_("���֤����"),_("��������"),_("����"),_("���ᣨʡ�ݣ�"),_("���ᣨ���У�"),_("����"),_("����״��"),_("������ò"),_("��ְ״̬"),_("�뵳ʱ��"),_("��ϵ�绰"),_("�ֻ�����"),_("MSN"),_("QQ"),_("�����ʼ�"),_("��ͥ��ַ"),_("�μӹ���ʱ��"),_("������ϵ��ʽ"),_("�ܹ���"),_("����״��"),_("�������ڵ�"),_("��ػ���"),_("�������"),_("��ְʱ��"),_("������1"),_("�����˻�1"),_("������2"),_("�����˻�2"),_("ѧ��"),_("ѧλ"),_("��ҵʱ��"),_("רҵ"),_("��ҵԺУ"),_("�����ˮƽ"),_("��������1"),_("��������2"),_("��������3"),_("����ˮƽ1"),_("����ˮƽ2"),_("����ˮƽ3"),_("�س�"),_("����"),_("��������"),_("Ա������"),_("ְ��"),_("ְ��"),_("ְ�Ƽ���"),_("����λ����"),_("��нʱ��"),_("���ݼ�"),_("����"),_("������¼"),_("ְ�����"),_("�籣�������"),_("����¼"),_("��ע"));
    $EXCEL_OUT_CUSTOM=explode(",",_("$FIELDNAME"));
    $EXCEL_OUT=array_merge($EXCEL_OUT_REGULAR,$EXCEL_OUT_CUSTOM);
}

require_once ('inc/ExcelWriter.php');

$objExcel = new ExcelWriter();
$objExcel->setFileName(_("���µ���ģ��"));
$objExcel->addHead($EXCEL_OUT);

$objExcel->Save();
?>