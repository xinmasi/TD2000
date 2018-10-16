<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
include_once("inc/check_type.php");
ob_end_clean();

if(MYOA_IS_UN == 1)
    $OUTPUT_HEAD = "PLAN_NAME,POSITION,NAME,SEX,BIRTH,AGE,NATIONALITY,PHONE,E_mail,MAJOR,HIGHEST_SCHOOL,HIGHEST_DEGREE,GRADUATION_DATE,GRADUATION_SCHOOL,EXPECTED_SALARY,RESIDENCE_PLACE,NATIVE_PLACE,DOMICILE_PLACE,MARITAL_STATUS,POLITICAL_STATUS,HEALTH,JOB_BEGINNING,JOB_CATEGORY,JOB_INDUSTRY,JOB_INTENSION,WORK_CITY,START_WORKING,FOREIGN_LANGUAGE1,FOREIGN_LANGUAGE2,FOREIGN_LANGUAGE3,FOREIGN_LEVEL1,FOREIGN_LEVEL2,FOREIGN_LEVEL3,COMPUTER_LEVEL,SKILLS,CAREER_SKILLS,WORK_EXPERIENCE,PROJECT_EXPERIENCE,ADD_TIME,RESUME,RECRU_CHANNEL,MEMO";
else
    $OUTPUT_HEAD =_("�ƻ�����").","._("��λ").","._("ӦƸ������").","._("�Ա�").","._("��������").","._("����").","._("����").","._("��ϵ�绰").","."E_mail".","._("��ѧרҵ").","._("ѧ��").","._("ѧλ").","._("��ҵʱ��").","._("��ҵѧУ").","._("����нˮ(˰ǰ)").","._("�־�ס����").","._("����").","._("�������ڵ�").","._("����״��").","._("������ò").","._("����״��").","._("�μӹ���ʱ��").","._("������������").","._("����������ҵ").","._("��������ְҵ").","._("������������").","._("����ʱ��").","._("��������1").","._("��������2").","._("��������3").","._("����ˮƽ1").","._("����ˮƽ2").","._("����ˮƽ3").","._("�����ˮƽ").","._("�س�").","._("ְҵ����").","._("��������").","._("��Ŀ����").","._("�Ǽ�ʱ��").","._("����").","._("��Ƹ����").","._("��ע");

require_once ('inc/ExcelWriter.php');
$objExcel = new ExcelWriter();
$objExcel->setFileName(_("�˲ſ���Ϣ"));
$objExcel->addHead($OUTPUT_HEAD);

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

$CONDITION_STR = hr_priv("").$CONDITION_STR;
$query = "SELECT * from HR_RECRUIT_POOL where".$CONDITION_STR."order by ADD_TIME desc";
$cursor=exequery(TD::conn(),$query);
$POOL_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
    $POOL_COUNT++;
    $PLAN_NO=$ROW["PLAN_NO"];
    $query1 = "SELECT PLAN_NAME from HR_RECRUIT_PLAN where PLAN_NO='$PLAN_NO'";
    $cursor1= exequery(TD::conn(),$query1);
    if($ROW1=mysql_fetch_array($cursor1))
        $PLAN_NAME=format_cvs($ROW1["PLAN_NAME"]);       //�ƻ�����

    $POSITION=$ROW["POSITION"];
    $POSITION_NAME=format_cvs(get_hrms_code_name($POSITION,"POOL_POSITION"));//��λ
    $EMPLOYEE_NAME=format_cvs($ROW["EMPLOYEE_NAME"]);//ӦƸ������
    $EMPLOYEE_SEX=$ROW["EMPLOYEE_SEX"];//�Ա�
    if($EMPLOYEE_SEX=="0")
        $EMPLOYEE_SEX=format_cvs("��");
    else
        $EMPLOYEE_SEX=format_cvs("Ů");
    $EMPLOYEE_BIRTH=format_cvs($ROW["EMPLOYEE_BIRTH"]);//��������
    $EMPLOYEE_AGE=format_cvs($ROW["EMPLOYEE_AGE"]);//����
    $EMPLOYEE_NATIONALITY=format_cvs($ROW["EMPLOYEE_NATIONALITY"]);// ����
    $EMPLOYEE_PHONE=format_cvs($ROW["EMPLOYEE_PHONE"]);// ��ϵ�绰
    $EMPLOYEE_EMAIL=format_cvs($ROW["EMPLOYEE_EMAIL"]);// E_MAIL
    $EMPLOYEE_MAJOR=$ROW["EMPLOYEE_MAJOR"];
    $EMPLOYEE_MAJOR_NAME=format_cvs(get_hrms_code_name($EMPLOYEE_MAJOR,"POOL_EMPLOYEE_MAJOR"));// רҵ
    $EMPLOYEE_HIGHEST_SCHOOL=$ROW["EMPLOYEE_HIGHEST_SCHOOL"];
    $EMPLOYEE_HIGHEST_SCHOOL=format_cvs(get_hrms_code_name($EMPLOYEE_HIGHEST_SCHOOL,"STAFF_HIGHEST_SCHOOL"));// ѧ��
    $EMPLOYEE_HIGHEST_DEGREE=$ROW["EMPLOYEE_HIGHEST_DEGREE"];
    $EMPLOYEE_HIGHEST_DEGREE=format_cvs(get_hrms_code_name($EMPLOYEE_HIGHEST_DEGREE,"EMPLOYEE_HIGHEST_DEGREE"));// ѧλ
    $GRADUATION_DATE=format_cvs($ROW["GRADUATION_DATE"]);// ��ҵʱ��
    $GRADUATION_SCHOOL=format_cvs($ROW["GRADUATION_SCHOOL"]);// ��ҵѧУ
    $EXPECTED_SALARY =format_cvs($ROW["EXPECTED_SALARY"]);//����нˮ
    $RESIDENCE_PLACE=format_cvs($ROW["RESIDENCE_PLACE"]);// �־�ס����
    $EMPLOYEE_NATIVE_PLACE =format_cvs($ROW["EMPLOYEE_NATIVE_PLACE"]);
    $EMPLOYEE_NATIVE_PLACE=format_cvs(get_hrms_code_name($EMPLOYEE_NATIVE_PLACE,"AREA"));//����
    $EMPLOYEE_DOMICILE_PLACE=format_cvs($ROW["EMPLOYEE_DOMICILE_PLACE"]);// �������ڵ�
    $EMPLOYEE_MARITAL_STATUS=$ROW["EMPLOYEE_MARITAL_STATUS"];// ����״��
    if($EMPLOYEE_MARITAL_STATUS=="1")
        $EMPLOYEE_MARITAL_STATUS=format_cvs("�ѻ�");
    else if($EMPLOYEE_MARITAL_STATUS=="2")
        $EMPLOYEE_MARITAL_STATUS=format_cvs("����");
    else if($EMPLOYEE_MARITAL_STATUS=="3")
        $EMPLOYEE_MARITAL_STATUS=format_cvs("ɥż");
    else
        $EMPLOYEE_MARITAL_STATUS=format_cvs("δ��");
    $EMPLOYEE_POLITICAL_STATUS=format_cvs($ROW["EMPLOYEE_POLITICAL_STATUS"]);
    $EMPLOYEE_POLITICAL_STATUS=format_cvs(get_hrms_code_name($EMPLOYEE_POLITICAL_STATUS,"STAFF_POLITICAL_STATUS"));// ������ò
    $EMPLOYEE_HEALTH=format_cvs($ROW["EMPLOYEE_HEALTH"]);// ����״��
    $JOB_BEGINNING=format_cvs($ROW["JOB_BEGINNING"]);// �μӹ���ʱ��
    $JOB_CATEGORY=format_cvs($ROW["JOB_CATEGORY"]);
    $JOB_CATEGORY=format_cvs(get_hrms_code_name($JOB_CATEGORY,"JOB_CATEGORY"));// ������������
    $JOB_INDUSTRY=format_cvs($ROW["JOB_INDUSTRY"]);// ����������ҵ
    $JOB_INTENSION=format_cvs($ROW["JOB_INTENSION"]);// ��������ְҵ
    $WORK_CITY=format_cvs($ROW["WORK_CITY"]);// ������������
    $START_WORKING=$ROW["START_WORKING"];// ����ʱ��
    if($START_WORKING=="0")
        $START_WORKING=format_cvs("1������");
    else if($START_WORKING=="1")
        $START_WORKING=format_cvs("1������");
    else if($START_WORKING=="2")
        $START_WORKING=format_cvs("1~3����");
    else if($START_WORKING=="3")
        $START_WORKING=format_cvs("3���º�");
    else
        $START_WORKING=format_cvs("��ʱ����");
    $FOREIGN_LANGUAGE1=format_cvs($ROW["FOREIGN_LANGUAGE1"]);// ��������1
    $FOREIGN_LANGUAGE2=format_cvs($ROW["FOREIGN_LANGUAGE2"]);// ��������2
    $FOREIGN_LANGUAGE3=format_cvs($ROW["FOREIGN_LANGUAGE3"]); //��������3
    $FOREIGN_LEVEL1=format_cvs($ROW["FOREIGN_LEVEL1"]);// ����ˮƽ1
    $FOREIGN_LEVEL2=format_cvs($ROW["FOREIGN_LEVEL2"]);// ����ˮƽ2
    $FOREIGN_LEVEL3=format_cvs($ROW["FOREIGN_LEVEL3"]);// ����ˮƽ3
    $COMPUTER_LEVEL=format_cvs($ROW["COMPUTER_LEVEL"]);// �����ˮƽ
    $EMPLOYEE_SKILLS=format_cvs($ROW["EMPLOYEE_SKILLS"]);// �س�
    $CAREER_SKILLS =format_cvs($ROW["CAREER_SKILLS"]);//ְҵ����
    $WORK_EXPERIENCE=format_cvs($ROW["WORK_EXPERIENCE"]);// ��������
    $PROJECT_EXPERIENCE=format_cvs($ROW["PROJECT_EXPERIENCE"]);// ��Ŀ����
    $ADD_TIME=format_cvs($ROW["ADD_TIME"]); //�Ǽ�ʱ��
    $RESUME=format_cvs($ROW["RESUME"]);// ����
    $RECRU_CHANNEL=format_cvs($ROW["RECRU_CHANNEL"]);//��Ƹ����
    $RECRU_CHANNEL=format_cvs(get_hrms_code_name($RECRU_CHANNEL,"PLAN_DITCH"));
    $REMARK=format_cvs($ROW["REMARK"]);// ��ע

    $RESUME = strip_tags($RESUME);
    $RESUME = str_replace("&nbsp;","  ",$RESUME);



    $OUTPUT = $PLAN_NAME.",". $POSITION_NAME.",".$EMPLOYEE_NAME.",".$EMPLOYEE_SEX.",".$EMPLOYEE_BIRTH.",".$EMPLOYEE_AGE.",".$EMPLOYEE_NATIONALITY.",".$EMPLOYEE_PHONE.",".$EMPLOYEE_EMAIL.",".$EMPLOYEE_MAJOR_NAME.",".$EMPLOYEE_HIGHEST_SCHOOL.",".$EMPLOYEE_HIGHEST_DEGREE.",".$GRADUATION_DATE.",".$GRADUATION_SCHOOL.",".$EXPECTED_SALARY .",".$RESIDENCE_PLACE.",".$EMPLOYEE_NATIVE_PLACE .",".$EMPLOYEE_DOMICILE_PLACE.",".$EMPLOYEE_MARITAL_STATUS.",".$EMPLOYEE_POLITICAL_STATUS.",".$EMPLOYEE_HEALTH.",".$JOB_BEGINNING.",".$JOB_CATEGORY.",".$JOB_INDUSTRY.",".$JOB_INTENSION.",".$WORK_CITY.",".$START_WORKING.",".$FOREIGN_LANGUAGE1.",".$FOREIGN_LANGUAGE2.",".$FOREIGN_LANGUAGE3.",".$FOREIGN_LEVEL1.",".$FOREIGN_LEVEL2.",".$FOREIGN_LEVEL3.",".$COMPUTER_LEVEL.",".$EMPLOYEE_SKILLS.",".$CAREER_SKILLS.",".$WORK_EXPERIENCE.",".$PROJECT_EXPERIENCE.",".$ADD_TIME.",".$RESUME.",".$RECRU_CHANNEL.",".$REMARK;

    $objExcel->addRow($OUTPUT);
}
$objExcel->Save();
?>