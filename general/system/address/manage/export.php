<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_field.php");
ob_end_clean();

$query = "select * from ADDRESS_GROUP where GROUP_ID='$GROUP_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
    $GROUP_NAME=$ROW["GROUP_NAME"];

if($GROUP_ID==0)
    $GROUP_NAME=_("默认");

$query = "select * from FIELDSETTING where TABLENAME='ADDRESS'";
$cursor= exequery(TD::conn(),$query);
$J=0;
while($ROW=mysql_fetch_array($cursor))
    $FIELDNAME.=$ROW["FIELDNAME"].",";

if($TYPE==0) //Foxmail
{
    if(MYOA_IS_UN == 1)
        $OUTPUT_HEAD = "NAME,MINISTRATION,NICK_NAME,EMAIL,MOBIL_NO,TEL_NO,QQ,MSN,SEX,BIRTHDAY,MATE,CHILD,HOME_POSTCODE,HOME_ADD,HOME_PHONE,DEPT_NAME,DEPT_POSTCODE,DEPT_ADD,OFFICE_PHONE,COMPONY_FAX,NOTES,".$FIELDNAME;
    else
        $OUTPUT_HEAD = _("姓名").","._("职位").","._("昵称").","._("电子邮件地址").","._("手机").","._("传呼机").","._("QQ").","._("MSN").","._("性别").","._("生日").","._("配偶").","._("子女").","._("家庭所在地邮政编码").","._("家庭所在街道").","._("家庭电话1").","._("公司").","._("公司所在地邮政编码").","._("公司所在街道").","._("办公电话1").","._("公司传真").","._("附注").",".$FIELDNAME;
}
else //outlook
{
    if(MYOA_IS_UN == 1)
        $OUTPUT_HEAD = "NAME,MINISTRATION,NICK_NAME,EMAIL,MOBIL_NO,TEL_NO,QQ,MSN,SEX,BIRTHDAY,MATE,CHILD,HOME_POSTCODE,HOME_ADD,HOME_PHONE,DEPT_NAME,DEPT_POSTCODE,DEPT_ADD,OFFICE_PHONE,COMPONY_FAX,NOTES,".$FIELDNAME;
    else
        $OUTPUT_HEAD = _("姓名").","._("职位").","._("昵称").","._("电子邮件地址").","._("手机").","._("传呼机").","._("QQ").","._("MSN").","._("性别").","._("生日").","._("配偶").","._("子女").","._("家庭所在地邮政编码").","._("家庭所在街道").","._("家庭电话1").","._("公司").","._("公司所在地邮政编码").","._("公司所在街道").","._("办公电话1").","._("公司传真").","._("附注").",".$FIELDNAME;
}

require_once ('inc/ExcelWriter.php');

$objExcel = new ExcelWriter();
$objExcel->setFileName($GROUP_NAME);
$objExcel->addHead($OUTPUT_HEAD);

//$query = "SELECT * from ADDRESS where USER_ID = '".$_SESSION["LOGIN_USER_ID"]."' and GROUP_ID='$GROUP_ID'";
$query = "SELECT * from ADDRESS where GROUP_ID='$GROUP_ID'";
$cursor= exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
    $ADD_ID=$ROW["ADD_ID"];
    $PSN_NAME=format_cvs($ROW["PSN_NAME"]);
    $SEX=format_cvs($ROW["SEX"]);
    if($SEX=="0")
        $SEX=_("男");
    else if($SEX=="1")
        $SEX=_("女");
    else
        $SEX="";
    $BIRTHDAY=format_cvs($ROW["BIRTHDAY"]);

    $NICK_NAME=format_cvs($ROW["NICK_NAME"]);
    $MINISTRATION=format_cvs($ROW["MINISTRATION"]);
    $MATE=format_cvs($ROW["MATE"]);
    $CHILD=format_cvs($ROW["CHILD"]);

    $DEPT_NAME=format_cvs($ROW["DEPT_NAME"]);
    $ADD_DEPT=format_cvs($ROW["ADD_DEPT"]);
    $POST_NO_DEPT=format_cvs($ROW["POST_NO_DEPT"]);
    $TEL_NO_DEPT=format_cvs($ROW["TEL_NO_DEPT"]);
    $FAX_NO_DEPT=format_cvs($ROW["FAX_NO_DEPT"]);

    $ADD_HOME=format_cvs($ROW["ADD_HOME"]);
    $POST_NO_HOME=format_cvs($ROW["POST_NO_HOME"]);
    $TEL_NO_HOME=format_cvs($ROW["TEL_NO_HOME"]);
    $MOBIL_NO=format_cvs($ROW["MOBIL_NO"]);
    $BP_NO=format_cvs($ROW["BP_NO"]);
    $EMAIL=format_cvs($ROW["EMAIL"]);
    $OICQ_NO=format_cvs($ROW["OICQ_NO"]);
    $ICQ_NO=format_cvs($ROW["ICQ_NO"]);

    $NOTES="\"".format_cvs($ROW["NOTES"])."\"";

    $DEF_FIELD_ARRAY=array();
    $DEF_FIELD_ARRAY=get_field_text("ADDRESS", $ADD_ID);
    $EXCEL_OUT="";
    while(list($key, $value) = each($DEF_FIELD_ARRAY))
        $EXCEL_OUT.=$value["HTML"].",";

    $OUTPUT =  "$PSN_NAME,$MINISTRATION,$NICK_NAME,$EMAIL,$MOBIL_NO,$BP_NO,$OICQ_NO,$ICQ_NO,$SEX,$BIRTHDAY,$MATE,$CHILD,$POST_NO_HOME,$ADD_HOME,$TEL_NO_HOME,$DEPT_NAME,$POST_NO_DEPT,$ADD_DEPT,$TEL_NO_DEPT,$FAX_NO_DEPT,$NOTES,".$EXCEL_OUT;

    $objExcel->addRow($OUTPUT);
}

$objExcel->Save();
?>