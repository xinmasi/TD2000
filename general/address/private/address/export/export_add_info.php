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
        $OUTPUT_HEAD = _("����").","._("����").","._("ְλ").","._("�ǳ�").","._("�����ʼ�").","._("�ֻ�").","._("С��ͨ").",QQ,MSN,"._("�Ա�").","._("����").","._("��ż").","._("��Ů").","._("��ͥ�ʱ�").","._("��ͥסַ").","._("��ͥ�绰").","._("��λ����").","._("��λ�ʱ�").","._("��λ��ַ").","._("�����绰").","._("��������").","._("��ע").",".$FIELDNAME;
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
        $OUTPUT_HEAD = _("����").","._("����").","._("ְ��").","._("�ǳ�").","._("�����ʼ�").","._("�ֻ�").","._("С��ͨ").",QQ,MSN,"._("�Ա�").","._("����").","._("��ż").","._("��Ů").","._("��ͥ�ʱ�").","._("��ͥסַ").","._("��ͥ�绰").","._("��λ����").","._("��λ�ʱ�").","._("��λ��ַ").","._("�����绰").","._("��������").","._("��ע").",".$FIELDNAME;
    }
}

require_once ('inc/ExcelWriter.php');

$objExcel = new ExcelWriter();
$objExcel->setFileName(_("ָ����ϵ��"));
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
        $SEX = _("��");
    }
    else if($SEX == "1")
    {
        $SEX = _("Ů");
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
        $GROUP_NAME = _("Ĭ��");
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