<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_field.php");

$HTML_PAGE_TITLE = _("��������");
include_once("inc/header.inc.php");
?>

<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/js/bootstrap/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/js/bootstrap/tag/bootstrap.tag.css">
<link rel="stylesheet" type="text/css"  href="<?=MYOA_JS_SERVER?>/static/modules/address/new_add.css"/>
<body>

<?
if(strtolower(substr($FILE_NAME,-3))!="xls")
{
?>
<div style="margin-left: 17%;margin-top: 100px;width: 50%;padding: 60px;border: 1px solid #DDD;font-weight: bold;color: #555;font-size: 20px;text-align: center;">
    <?=_("ֻ�ܵ���Excel�ļ���")?>
</div>
<div align="center">
    <br>
    <button type="button" class="btn btn-info" style="width:70px;" onClick="location='import.php';"><?=_("����")?></button>
</div>
<?
    exit;
}
if(MYOA_IS_UN == 1)
{
    $title=array("GROUP_ID"=>"GROUP_ID","NAME"=>"PSN_NAME","FIRST_NAME"=>"FIRST_NAME","LAST_NAME"=>"LAST_NAME","SEX"=>"SEX","NICK_NAME"=>"NICK_NAME","EMAIL"=>"EMAIL",
             "HOME_ADD"=>"ADD_HOME","HOME_PHONE"=>"TEL_NO_HOME","MOBIL_NO"=>"MOBIL_NO","TEL_NO"=>"BP_NO","QQ"=>"OICQ_NO","MSN"=>"ICQ_NO","BIRTHDAY"=>"BIRTHDAY",
             "HOME_POSTCODE"=>"POST_NO_HOME","DEPT_POSTCODE"=>"POST_NO_DEPT","DEPT_ADD"=>"ADD_DEPT","MINISTRATION"=>"MINISTRATION","OFFICE_PHONE"=>"TEL_NO_DEPT",
             "COMPONY_FAX"=>"FAX_NO_DEPT","MATE"=>"MATE","CHILD"=>"CHILD","DEPT_NAME"=>"DEPT_NAME","NOTES"=>"NOTES",
    );
    $fieldAttr = array("BIRTHDAY" => "date");
}
else
{
    $title=array(_("����")=>"GROUP_ID",_("����")=>"PSN_NAME",_("��")=>"FIRST_NAME",_("��")=>"LAST_NAME",_("�Ա�")=>"SEX",_("�ǳ�")=>"NICK_NAME",_("�����ʼ���ַ")=>"EMAIL",_("�����ʼ�")=>"EMAIL",
             _("סլ���ڽֵ�")=>"ADD_HOME",_("��ͥ���ڽֵ�")=>"ADD_HOME",_("��ͥסַ")=>"ADD_HOME",_("סլ��ַ �ֵ�")=>"ADD_HOME",_("��ͥ�绰1")=>"TEL_NO_HOME",_("סլ�绰")=>"TEL_NO_HOME",_("��ͥ�绰")=>"TEL_NO_HOME",
             _("�ֻ�")=>"MOBIL_NO",_("�ƶ��绰")=>"MOBIL_NO",_("С��ͨ")=>"BP_NO",_("������")=>"BP_NO","QQ"=>"OICQ_NO","MSN"=>"ICQ_NO",_("����")=>"BIRTHDAY",
             _("��ͥ���ڵ���������")=>"POST_NO_HOME",_("סլ���ڵص���������")=>"POST_NO_HOME",_("סլ��ַ ��������")=>"POST_NO_HOME",_("��ͥ�ʱ�")=>"POST_NO_HOME",
             _("��˾���ڵ���������")=>"POST_NO_DEPT",_("��˾���ڵص���������")=>"POST_NO_DEPT",_("��λ�ʱ�")=>"POST_NO_DEPT",_("�����ַ ��������")=>"POST_NO_DEPT",_("��˾���ڽֵ�")=>"ADD_DEPT",_("��λ��ַ")=>"ADD_DEPT",_("�����ַ �ֵ�")=>"ADD_DEPT",
             _("ְλ")=>"MINISTRATION",_("ְ��")=>"MINISTRATION",_("�칫�绰1")=>"TEL_NO_DEPT",_("ҵ��绰")=>"TEL_NO_DEPT",_("����绰")=>"TEL_NO_DEPT",_("�����绰")=>"TEL_NO_DEPT",
             _("��˾����")=>"FAX_NO_DEPT",_("ҵ����")=>"FAX_NO_DEPT",_("��������")=>"FAX_NO_DEPT",_("��ż")=>"MATE",_("��Ů")=>"CHILD",_("��˾")=>"DEPT_NAME",_("��λ����")=>"DEPT_NAME",_("��ע")=>"NOTES",_("��ע")=>"NOTES",
    );
    $fieldAttr = array(_("����") => "date");
}

$ext_title = get_field_title("ADDRESS");

$EXCEL_FILE = $_FILES['EXCEL_FILE']['tmp_name'];

require_once ('inc/ExcelReader.php');
$objExcel = new ExcelReader($EXCEL_FILE, array_merge($title, $ext_title), $fieldAttr);
$ROW_COUNT = 0;
while($DATA=$objExcel->getNextRow())
{   
    if(array_key_exists("PSN_NAME",$DATA) )
    {

    if($DATA['PSN_NAME'] == ""){
        continue;
    }

    $ROW_COUNT++;
    $EXT_DATA = get_field_row("ADDRESS", $DATA, $ext_title);
    $ID_STR = "";
    $VALUE_STR  = "";
    $STR_UPDATE = "";
    reset($title);
    foreach($title as $key)
    {
     
        if(find_id($ID_STR, $key))
            continue;
        
        if($key=="FIRST_NAME" || $key=="LAST_NAME" || $key=="PSN_NAME" && $DATA["PSN_NAME"]=="")
        {
            continue;
        }
        else if($key=="GROUP_ID")
        {
            //���������������ڣ�����Ĭ�Ϸ���
            $query_group  = "SELECT GROUP_ID FROM address_group WHERE GROUP_NAME='".$DATA[$key]."' AND USER_ID='".$_SESSION["LOGIN_USER_ID"]."'";
            $cursor_group = exequery(TD::conn(),$query_group);
            if($row_group = mysql_fetch_array($cursor_group))
            {
                $DATA[$key] = $row_group[0];
            }
            else
            {
                $DATA[$key] = 0;
            }
        }
        else if($key=="SEX")
        {
            if($DATA[$key]==_("Ů"))
                $DATA[$key]    = "1";
            else if($DATA[$key]==_("��"))
                $DATA[$key]    = "0";
            else
                $DATA[$key]    = "";
        }

        else if($key=="BIRTHDAY")
        {
            $DATA[$key] = ($DATA[$key] == '1970-01-01') ? "" : $DATA[$key];
        }
        
        $ID_STR.=$key.",";
        $VALUE_STR.="'".$DATA[$key]."',";
        $STR_UPDATE.="$key='$DATA[$key]',";
    }
    
    if (substr($STR_UPDATE,-1)==",")
        $STR_UPDATE=substr($STR_UPDATE,0,-1);
    
    if(!find_id($ID_STR, "PSN_NAME"))
    {
        $ID_STR.="PSN_NAME,";
        $VALUE_STR.="'".$DATA["LAST_NAME"].$DATA["FIRST_NAME"]."',";
    }
    
    $ID_STR		= trim($ID_STR,",");
    $VALUE_STR	= trim($VALUE_STR,",");
    $query="SELECT ADD_ID FROM address WHERE PSN_NAME='".$DATA["PSN_NAME"]."' AND MOBIL_NO='".$DATA["MOBIL_NO"]."' AND EMAIL='".$DATA["EMAIL"]."'  AND USER_ID='".$_SESSION["LOGIN_USER_ID"]."'";
    $cursor= exequery(TD::conn(),$query);
    if($ROW=mysql_fetch_array($cursor))
    {
    $ADD_ID	   = $ROW["ADD_ID"];
    $query1    = "UPDATE address SET ".$STR_UPDATE." WHERE ADD_ID='".$ADD_ID."'";
    $cursor1   = exequery(TD::conn(),$query1);
    }else{
    $query="INSERT INTO address (USER_ID,PSN_NO,".$ID_STR.") VALUES ('".$_SESSION["LOGIN_USER_ID"]."','$ROW_COUNT',".$VALUE_STR.");";
    exequery(TD::conn(),$query);
    $ADD_ID=mysql_insert_id();
    }
    //�����Զ����ֶ�
    if(count($EXT_DATA) > 0)
    {
        save_field_data("ADDRESS",$ADD_ID,$EXT_DATA);
    }
    }
    else{
          ?>
<div style="margin-left: 17%;margin-top: 100px;width: 50%;padding: 60px;border: 1px solid #DDD;font-weight: bold;color: #555;font-size: 20px;text-align: center;">
    <?=_("�����ļ�������Ҫ��")?>
</div>
<div align="center">
    <br>
    <button type="button" class="btn btn-info" style="width:70px;" onClick="location='import.php';"><?=_("����")?></button>
</div>
<?
    exit;
    }
}
if(file_exists($EXCEL_FILE))
    @unlink($EXCEL_FILE);

$MSG = sprintf(_("������%d������!"), $ROW_COUNT);
//Message("",$MSG);

?>
<div style="margin-left: 17%;margin-top: 100px;width: 50%;padding: 60px;border: 1px solid #DDD;font-weight: bold;color: #555;font-size: 20px;text-align: center;">
    <?=$MSG?>
</div>
<div align="center">
    <br>
    <button type="button" class="btn btn-info" style="width:70px;" onClick="location='import.php';"><?=_("����")?></button>
</div>

</body>
</html>