<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_field.php");
ob_end_clean();

$query = "select * from FIELDSETTING where TABLENAME='ADDRESS'";
$cursor= exequery(TD::conn(),$query);
$J=0;
while($ROW=mysql_fetch_array($cursor))
{
    $FIELDNAME.=$ROW["FIELDNAME"].",";
}

if($type == 0) //Foxmail
{
    if(MYOA_IS_UN == 1)
    {
        $OUTPUT_HEAD = "GROUP_NAME,NAME,MINISTRATION,NICK_NAME,EMAIL,MOBIL_NO,TEL_NO,QQ,MSN,SEX,BIRTHDAY,MATE,CHILD,HOME_POSTCODE,HOME_ADD,HOME_PHONE,DEPT_NAME,DEPT_POSTCODE,DEPT_ADD,OFFICE_PHONE,COMPONY_FAX,NOTES,".$FIELDNAME;
    }
    else
    {
        $OUTPUT_HEAD = _("分组").","._("姓名").","._("职位").","._("昵称").","._("电子邮件").","._("手机").","._("小灵通").",QQ,MSN,"._("性别").","._("生日").","._("配偶").","._("子女").","._("家庭邮编").","._("家庭住址").","._("家庭电话").","._("单位名称").","._("单位邮编").","._("单位地址").","._("工作电话").","._("工作传真").","._("备注").",".$FIELDNAME;
    }
}
else //outlook
{
    if(MYOA_IS_UN == 1)
    {
        $OUTPUT_HEAD = "GROUP_NAME,NAME,MINISTRATION,NICK_NAME,EMAIL,MOBIL_NO,TEL_NO,QQ,MSN,SEX,BIRTHDAY,MATE,CHILD,HOME_POSTCODE,HOME_ADD,HOME_PHONE,DEPT_NAME,DEPT_POSTCODE,DEPT_ADD,OFFICE_PHONE,COMPONY_FAX,NOTES,".$FIELDNAME;
    }
    else
    {
        $OUTPUT_HEAD = _("分组").","._("姓名").","._("职务").","._("昵称").","._("电子邮件").","._("手机").","._("小灵通").",QQ,MSN,"._("性别").","._("生日").","._("配偶").","._("子女").","._("家庭邮编").","._("家庭住址").","._("家庭电话").","._("单位名称").","._("单位邮编").","._("单位地址").","._("工作电话").","._("工作传真").","._("备注").",".$FIELDNAME;
    }
}

require_once ('inc/ExcelWriter.php');

$objExcel = new ExcelWriter();
$objExcel->setFileName(_("指定联系人"));
$objExcel->addHead($OUTPUT_HEAD);

$query = "SELECT * from ADDRESS where 1=1 and find_in_set(ADD_ID,'$show_add_id_str') order by GROUP_ID";
$cursor= exequery(TD::conn(),$query);
while($ROW = mysql_fetch_array($cursor))
{
    $ADD_ID          = $ROW["ADD_ID"];
    $GROUP_ID        = $ROW["GROUP_ID"];
    $PSN_NAME        = format_cvs($ROW["PSN_NAME"]);
    $SEX             = format_cvs($ROW["SEX"]);
    
    if($SEX == "0")
    {
        $SEX = _("男");
    }
    else if($SEX == "1")
    {
        $SEX = _("女");
    }
    else
    {
        $SEX = "";
    }
    
    $BIRTHDAY        = format_cvs($ROW["BIRTHDAY"]);
                       
    $NICK_NAME       = format_cvs($ROW["NICK_NAME"]);
    $MINISTRATION    = format_cvs($ROW["MINISTRATION"]);
    $MATE            = format_cvs($ROW["MATE"]);
    $CHILD           = format_cvs($ROW["CHILD"]);
                       
    $DEPT_NAME       = format_cvs($ROW["DEPT_NAME"]);
    $ADD_DEPT        = format_cvs($ROW["ADD_DEPT"]);
    $POST_NO_DEPT    = format_cvs($ROW["POST_NO_DEPT"]);
    $TEL_NO_DEPT     = format_cvs($ROW["TEL_NO_DEPT"]);
    $FAX_NO_DEPT     = format_cvs($ROW["FAX_NO_DEPT"]);
                       
    $ADD_HOME        = format_cvs($ROW["ADD_HOME"]);
    $POST_NO_HOME    = format_cvs($ROW["POST_NO_HOME"]);
    $TEL_NO_HOME     = format_cvs($ROW["TEL_NO_HOME"]);
    $MOBIL_NO        = format_cvs($ROW["MOBIL_NO"]);
    $BP_NO           = format_cvs($ROW["BP_NO"]);
    $EMAIL           = format_cvs($ROW["EMAIL"]);
    $OICQ_NO         = format_cvs($ROW["OICQ_NO"]);
    $ICQ_NO          = format_cvs($ROW["ICQ_NO"]);
    $NOTES           = "\"".format_cvs($ROW["NOTES"])."\"";
    
    if($GROUP_ID == 0)
    {
        $GROUP_NAME = _("默认");
    }
    else
    {
        $query2 = "select GROUP_NAME from ADDRESS_GROUP where GROUP_ID='$GROUP_ID'";
        $cursor2= exequery(TD::conn(), $query2);
        if($ROW2 = mysql_fetch_array($cursor2))
        {
            $GROUP_NAME = $ROW2[0];
        }
    }
   
    $DEF_FIELD_ARRAY = array();
    $DEF_FIELD_ARRAY = get_field_text("ADDRESS", $ADD_ID);
    $EXCEL_OUT="";
    while(list($key, $value) = each($DEF_FIELD_ARRAY))
    {
        $EXCEL_OUT .= $value["HTML"].",";
    }
    
    $OUTPUT = "$GROUP_NAME,$PSN_NAME,$MINISTRATION,$NICK_NAME,$EMAIL,$MOBIL_NO,$BP_NO,$OICQ_NO,$ICQ_NO,$SEX,$BIRTHDAY,$MATE,$CHILD,$POST_NO_HOME,$ADD_HOME,$TEL_NO_HOME,$DEPT_NAME,$POST_NO_DEPT,$ADD_DEPT,$TEL_NO_DEPT,$FAX_NO_DEPT,$NOTES,".$EXCEL_OUT;
    
    $objExcel->addRow($OUTPUT);
}

$objExcel->Save();
?>