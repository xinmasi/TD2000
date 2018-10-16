<?
include_once("inc/auth.inc.php");
ob_end_clean();

if(MYOA_IS_UN == 1)
   $EXCEL_OUT="ID,NAME,MEMBER,RELATIONSHIP,BIRTHDAY,POLITICS,JOB_OCCUPATION,POST_OF_JOB,PERSONAL_TEL,HOME_TEL,OFFICE_TEL,WORK_UNIT,UNIT_ADDRESS,HOME_ADDRESS,MEMO";
else
   $EXCEL_OUT=array(_("用户名"),_("单位员工"),_("成员姓名"),_("与本人关系"),_("出生日期"),_("政治面貌"),_("职业"),_("担任职务"),_("联系电话（个人）"),_("联系电话（家庭）"),_("联系电话（单位）"),_("工作单位"),_("单位地址"),_("家庭住址"),_("备注"));

require_once ('inc/ExcelWriter.php');

$objExcel = new ExcelWriter();
$objExcel->setFileName(_("员工社会关系"));
$objExcel->addHead($EXCEL_OUT);

$objExcel->Save();
?>