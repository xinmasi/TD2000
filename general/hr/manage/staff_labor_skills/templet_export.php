<?
include_once("inc/auth.inc.php");
ob_end_clean();

if(MYOA_IS_UN == 1)
    $EXCEL_OUT="ID,NAME,ABILITY_NAME,SPECIAL_WORK,SKILLS_LEVEL,SKILLS_CERTIFICATE,ISSUE_DATE,EXPIRES,EXPIRE_DATE,ISSUING_AUTHORITY,MEMO";
else
    $EXCEL_OUT=array(_("用户名"),_("单位员工"),_("技能名称"),_("是否特种作业"),_("级别"),_("是否有技能证"),_("发证日期"),_("有效期"),_("到期日期"),_("发证机关/单位"),_("备注"));

require_once ('inc/ExcelWriter.php');

$objExcel = new ExcelWriter();
$objExcel->setFileName(_("员工劳动技能"));
$objExcel->addHead($EXCEL_OUT);

$objExcel->Save();
?>