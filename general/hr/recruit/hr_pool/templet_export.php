<?
include_once("inc/auth.inc.php");
ob_end_clean();

if(MYOA_IS_UN == 1)
   $EXCEL_OUT="PLAN_NAME,POSITION,NAME,SEX,BIRTH,AGE,NATIONALITY,PHONE,E_mail,MAJOR,HIGHEST_SCHOOL,HIGHEST_DEGREE,GRADUATION_DATE,GRADUATION_SCHOOL,EXPECTED_SALARY,RESIDENCE_PLACE,NATIVE_PLACE,DOMICILE_PLACE,MARITAL_STATUS,POLITICAL_STATUS,HEALTH,JOB_BEGINNING,JOB_CATEGORY,JOB_INDUSTRY,JOB_INTENSION,WORK_CITY,START_WORKING,FOREIGN_LANGUAGE1,FOREIGN_LANGUAGE2,FOREIGN_LANGUAGE3,FOREIGN_LEVEL1,FOREIGN_LEVEL2,FOREIGN_LEVEL3,COMPUTER_LEVEL,RECRU_CHANNEL,SKILLS,CAREER_SKILLS,WORK_EXPERIENCE,PROJECT_EXPERIENCE,MEMO";
else
   $EXCEL_OUT=array(_("计划名称"),_("岗位"),_("应聘人姓名"),_("性别"),_("出生日期"),_("年龄"),_("民族"),_("联系电话"),"E_mail",_("所学专业"),_("学历"),_("学位"),_("毕业时间"),_("毕业学校"),_("期望薪水(税前)"),_("现居住城市"),_("籍贯"),_("户口所在地"),_("婚姻状况"),_("政治面貌"),_("健康状况"),_("参加工作时间"),_("期望工作性质"),_("期望从事行业"),_("期望从事职业"),_("期望工作城市"),_("到岗时间"),_("外语语种1"),_("外语语种2"),_("外语语种3"),_("外语水平1"),_("外语水平2"),_("外语水平3"),_("计算机水平"),_("招聘渠道"),_("特长"),_("职业技能"),_("工作经验"),_("项目经验"),_("备注"));

require_once ('inc/ExcelWriter.php');

$objExcel = new ExcelWriter();
$objExcel->setFileName(_("人才档案模板"));
$objExcel->addHead($EXCEL_OUT);

$objExcel->Save();
?>