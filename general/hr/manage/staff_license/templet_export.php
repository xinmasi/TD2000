<?
include_once("inc/auth.inc.php");
ob_end_clean();

if(MYOA_IS_UN == 1)
   $EXCEL_OUT="ID,NAME,LICENSE_TYPE,LICENSE_NO,LICENSE_NAME,GET_LICENSE_DATE,EFFECTIVE_DATE,STATUS,EXPIRATION_PERIOD,EXPIRE_DATE,NOTIFIED_BODY,LICENSE_DEPT,MEMO";
else
   $EXCEL_OUT=array(_("用户名"),_("单位员工"),_("证照类型"),_("证照编号"),_("证照名称"),_("取证日期"),_("生效日期"),_("状态"),_("是否期限限制"),_("到期日期"),_("发证机构"),_("部门"),_("备注"));

require_once ('inc/ExcelWriter.php');
$objExcel = new ExcelWriter();
$objExcel->setFileName(_("证照管理信息"));
$objExcel->addHead($EXCEL_OUT);

$objExcel->Save();
?>