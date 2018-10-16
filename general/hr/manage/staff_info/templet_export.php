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
    $EXCEL_OUT_REGULAR=array(_("姓名"),_("用户名"),_("部门"),_("角色"),_("曾用名"),_("英文名"),_("性别"),_("编号"),_("工号"),_("岗位"),_("身份证号码"),_("出生日期"),_("年龄"),_("籍贯（省份）"),_("籍贯（城市）"),_("民族"),_("婚姻状况"),_("政治面貌"),_("在职状态"),_("入党时间"),_("联系电话"),_("手机号码"),_("MSN"),_("QQ"),_("电子邮件"),_("家庭地址"),_("参加工作时间"),_("其他联系方式"),_("总工龄"),_("健康状况"),_("户口所在地"),_("异地户口"),_("户口类别"),_("入职时间"),_("开户行1"),_("个人账户1"),_("开户行2"),_("个人账户2"),_("学历"),_("学位"),_("毕业时间"),_("专业"),_("毕业院校"),_("计算机水平"),_("外语语种1"),_("外语语种2"),_("外语语种3"),_("外语水平1"),_("外语水平2"),_("外语水平3"),_("特长"),_("工种"),_("行政级别"),_("员工类型"),_("职务"),_("职称"),_("职称级别"),_("本单位工龄"),_("起薪时间"),_("年休假"),_("简历"),_("担保记录"),_("职务情况"),_("社保缴纳情况"),_("体检记录"),_("备注"));
    $EXCEL_OUT_CUSTOM=explode(",",_("$FIELDNAME"));
    $EXCEL_OUT=array_merge($EXCEL_OUT_REGULAR,$EXCEL_OUT_CUSTOM);
}

require_once ('inc/ExcelWriter.php');

$objExcel = new ExcelWriter();
$objExcel->setFileName(_("人事档案模板"));
$objExcel->addHead($EXCEL_OUT);

$objExcel->Save();
?>