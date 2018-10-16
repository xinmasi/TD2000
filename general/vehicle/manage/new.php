<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");
include_once("inc/utility_org.php");

$PARA_ARRAY = get_sys_para("SMS_REMIND");
$PARA_VALUE=$PARA_ARRAY["SMS_REMIND"];
$REMIND_ARRAY = explode("|", $PARA_VALUE);
$SMS_REMIND = $REMIND_ARRAY[0];
$SMS2_REMIND = $REMIND_ARRAY[1];
$SMS3_REMIND = $REMIND_ARRAY[2];

$HTML_PAGE_TITLE = _("��ӳ�����Ϣ");
include_once("inc/header.inc.php");
?>
<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>

<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>
<script type="text/javascript" src="/inc/js_lang.php"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>
<script Language="JavaScript"> 
function CheckForm() 
{ 
    var specialStr  = /^[^\\\,\/\<\>]+$/;
    var fieldsNum = jQuery('#tableFields').find('tr').length;
    
    if(document.form1.V_NUM.value=="")
    {
        alert("<?=_("���ƺŲ���Ϊ�գ�")?>"); 
        jQuery('input[name="V_NUM"]').focus();
        return false; 
    }

    if(document.form1.V_PRICE.value != "" && isNaN(document.form1.V_PRICE.value))
    {
        alert("<?=_("����۸�ӦΪ���֣�")?>"); 
        return false;
    }
    
    if(document.form1.V_TAX.value != "" && isNaN(document.form1.V_TAX.value))
    {
        alert("<?=_("����˰�۸�ӦΪ���֣�")?>"); 
        return false;
    }
    
    if(document.form1.V_PAY.value != "" && isNaN(document.form1.V_PAY.value))
    {
        alert("<?=_("�����֧ӦΪ���֣�")?>"); 
        return false;
    }
    
    if(document.form1.V_MILEAGE.value != "" && isNaN(document.form1.V_MILEAGE.value))
    {
        alert("<?=_("��ʼ���ӦΪ���֣�")?>"); 
        return false;
    }
    
    for(count=0; count<fieldsNum; count++)
    {
        if(jQuery('input[name="newTitles_'+count+'"]').length)
        {
            var newTitlesValue = jQuery('input[name="newTitles_'+count+'"]').val();
            var newFieldsValue = jQuery('input[name="newFields_'+count+'"]').val();
            
            if(jQuery.trim(newTitlesValue) == '')
            {
                alert("<?=_("�Զ����ֶα��ⲻ��Ϊ�գ�")?>");
                jQuery('input[name="newTitles_'+count+'"]').focus();
                return false;
            }
            else if(newTitlesValue.search(specialStr) != 0)
            {
                alert("<?=_("�Զ����ֶα��ⲻ�ܺ��������ַ���")?>");
                jQuery('input[name="newTitles_'+count+'"]').focus();
                return false;
            }
            else if(newFieldsValue.search(specialStr) != 0 && newFieldsValue != '')
            {
                alert("<?=_("�Զ����ֶ����ݲ��ܺ��������ַ���")?>");
                jQuery('input[name="newFields_'+count+'"]').focus();
                return false;
            }

        }
    }

    form1.submit();
}

function adddriver()
{
    var doc,num,tmpl;
    doc = $('#driver')[0];
    num = doc.getElementsByTagName('p').length;
    
    tmpl = 	"<p><input type='hidden' class='driver' name='RECORDER_ID"+num+"' value=''>"+
    "<input type='text' name='V_DRIVER"+num+"'  size='10'  class='BigInput' maxlength='20' title='<?=_('˾��')?>'>&nbsp;"+
    "<a href = 'javascript:;' onClick =\"SelectUserSingle('90','','RECORDER_ID"+num+"','V_DRIVER"+num+"')\"><?=_('ѡ��')?></a>&nbsp;"+
    "<a href = 'javascript:;' onClick =\"ClearUser('RECORDER_ID"+num+"','V_DRIVER"+num+"')\"><?=_('���')?></a>&nbsp;&nbsp;&nbsp;"+
    "<?=_('�ֻ����룺')?><input type='text' name='V_PHONE_NO"+num+"' size='11' maxlength='25' class='BigInput'>&nbsp;"+
    "</p>";
    
    $(doc).append(tmpl);
}

function deleteFields(obj)
{
    jQuery(obj).parent().parent().remove();
}

function editFields(num)
{
    jQuery('input[name="newTitles_'+num+'"]').attr('type','text');
    jQuery('label[name="titlesMsg_'+num+'"]').remove();
    jQuery('input[name="newTitles_'+num+'"]').focus();
}

function addNewFields()
{   
    var userDefined = jQuery('#tableFields').find('tr').last().prev().prev();
    var fieldsNum = jQuery('#tableFields').find('tr').length;

    var fieldsTmpl = '<tr>'+
                        '<td nowrap class="TableData"><input type="text" name="newTitles_'+fieldsNum+'" size="10" maxlength="10" class="BigInput" value=""></td>'+
                        '<td class="TableData" colspan="2">'+
                            '<input type="text" name="newFields_'+fieldsNum+'" size="40" maxlength="60" class="BigInput" value="">'+
                            '<input type="hidden" name="newNumbers_'+fieldsNum+'" value="'+fieldsNum+'">'+
                            '<a style="float:right; margin:0 10px 0 10px;" href="javascript:;" onclick="deleteFields(this)"><?=_("ɾ��")?></a>'+
                            '<a style="float:right;" href="javascript:;" onclick="editFields('+fieldsNum+')"><?=_("�޸�")?></a>'+   
                        '</td>'+
                    '</tr>';
                
    userDefined.after(fieldsTmpl);
    
    jQuery('input[name="newTitles_'+fieldsNum+'"]').bind('blur',function(){
        var specialStr  = /^[^\\\,\/\<\>]+$/;
        var titlesObj = jQuery('input[name="newTitles_'+fieldsNum+'"]');
        var fieldsObj = jQuery('input[name="newFields_'+fieldsNum+'"]');
        var newTitlesValue = jQuery('input[name="newTitles_'+fieldsNum+'"]').val();
        // var errorMsg = '<label name="errorLabel_'+fieldsNum+'" style="color:red;"><?=_("*�Զ����ֶα��ⲻ��Ϊ�գ�")?></label>';
        // var errorMsg1 = '<label name="errorLabel1_'+fieldsNum+'" style="color:red;"><?=_("*�Զ����ֶα��ⲻ�ܺ��������ַ���")?></label>';
        var titlesMsg = '<label name="titlesMsg_'+fieldsNum+'">'+<?=_(newTitlesValue)?>+'</lable>';

        if(jQuery.trim(newTitlesValue) == "")
        { 
            if(jQuery('label[name="errorLabel_'+fieldsNum+'"]').length)
            {
                jQuery('label[name="errorLabel_'+fieldsNum+'"]').remove();
            }
            else if(jQuery('label[name="errorLabel1_'+fieldsNum+'"]').length)
            {
                jQuery('label[name="errorLabel1_'+fieldsNum+'"]').remove();
            }
            // fieldsObj.after(errorMsg);
        }
        else if(jQuery.trim(newTitlesValue).search(specialStr) != 0 && jQuery.trim(newTitlesValue) != "")
        {
            if(jQuery('label[name="errorLabel_'+fieldsNum+'"]').length)
            {
                jQuery('label[name="errorLabel_'+fieldsNum+'"]').remove();
            }
            else if(jQuery('label[name="errorLabel1_'+fieldsNum+'"]').length)
            {
                jQuery('label[name="errorLabel1_'+fieldsNum+'"]').remove();
            }
            // fieldsObj.after(errorMsg1);
        }
        else
        {
            titlesObj.attr('type','hidden');
            titlesObj.after(titlesMsg);
            
            if(jQuery('label[name="errorLabel_'+fieldsNum+'"]').length)
            {
                jQuery('label[name="errorLabel_'+fieldsNum+'"]').remove();
            }
            else if(jQuery('label[name="errorLabel1_'+fieldsNum+'"]').length)
            {
                jQuery('label[name="errorLabel1_'+fieldsNum+'"]').remove();
            }
        }
        
    });
    
    jQuery('input[name="newFields_'+fieldsNum+'"]').bind('focus',function(){
        var titlesObj = jQuery('input[name="newTitles_'+fieldsNum+'"]');
        var fieldsObj = jQuery('input[name="newFields_'+fieldsNum+'"]');
        var newTitlesValue = jQuery('input[name="newTitles_'+fieldsNum+'"]').val();
        // var errorMsg = '<label name="errorLabel_'+fieldsNum+'" style="color:red;"><?=_("*�Զ����ֶα��ⲻ��Ϊ�գ�")?></label>';
        if(jQuery.trim(newTitlesValue) == "")
        {
            if(jQuery('label[name="errorLabel_'+fieldsNum+'"]').length)
            {
                jQuery('label[name="errorLabel_'+fieldsNum+'"]').remove();
            }
            // fieldsObj.after(errorMsg);
        }
    });
    
    jQuery('input[name="newFields_'+fieldsNum+'"]').bind('blur',function(){
        var specialStr  = /^[^\\\,\/\<\>]+$/;
        var fieldsObj = jQuery('input[name="newFields_'+fieldsNum+'"]');
        var newFieldsValue = jQuery('input[name="newFields_'+fieldsNum+'"]').val();
        // var errorMsg = '<label name="errorLabel2_'+fieldsNum+'" style="color:red;"><?=_("*�Զ����ֶ����ݲ��ܺ��������ַ���")?></label>';
        
        if(newFieldsValue.search(specialStr) != 0 && jQuery.trim(newFieldsValue) != '')
        {
            if(jQuery('label[name="errorLabel2_'+fieldsNum+'"]').length)
            {
                jQuery('label[name="errorLabel2_'+fieldsNum+'"]').remove();
            }
            // fieldsObj.after(errorMsg);
        }
        else
        {
            if(jQuery('label[name="errorLabel2_'+fieldsNum+'"]').length)
            {
                jQuery('label[name="errorLabel2_'+fieldsNum+'"]').remove();
            }
        }

    });
}

function editTitlesBlur(obj)
{
    var specialStr  = /^[^\\\,\/\<\>]+$/;
    var editTitlesValue = jQuery(obj).val();
    
    editTitlesValue = jQuery.trim(editTitlesValue);
    
    var editfieldNumbers = jQuery(obj).parent().parent().find('input[name="fieldNumbers"]').val();
    var titlesObj = jQuery('input[name="newTitles_'+editfieldNumbers+'"]');
    var fieldsObj = jQuery('input[name="newFields_'+editfieldNumbers+'"]');
    // var errorMsg = '<label name="errorLabel_'+editfieldNumbers+'" style="color:red;"><?=_("*�Զ����ֶα��ⲻ��Ϊ�գ�")?></label>';
    // var errorMsg1 = '<label name="errorLabel1_'+editfieldNumbers+'" style="color:red;"><?=_("*�Զ����ֶα��ⲻ�ܺ��������ַ���")?></label>';
    var titlesMsg = '<label name="titlesMsg_'+editfieldNumbers+'">'+<?=_(editTitlesValue)?>+'</lable>';

    if(editTitlesValue == "")
    { 
        if(jQuery('label[name="errorLabel_'+editfieldNumbers+'"]').length)
        {
            jQuery('label[name="errorLabel_'+editfieldNumbers+'"]').remove();
        }
        else if(jQuery('label[name="errorLabel1_'+editfieldNumbers+'"]').length)
        {
            jQuery('label[name="errorLabel1_'+editfieldNumbers+'"]').remove();
        }
        // fieldsObj.after(errorMsg);
    }
    else if(editTitlesValue.search(specialStr) != 0 && editTitlesValue != "")
    {
        if(jQuery('label[name="errorLabel_'+editfieldNumbers+'"]').length)
        {
            jQuery('label[name="errorLabel_'+editfieldNumbers+'"]').remove();
        }
        else if(jQuery('label[name="errorLabel1_'+editfieldNumbers+'"]').length)
        {
            jQuery('label[name="errorLabel1_'+editfieldNumbers+'"]').remove();
        }
        // fieldsObj.after(errorMsg1);
    }
    else
    {
        titlesObj.attr('type','hidden');
        titlesObj.after(titlesMsg);
        
        if(jQuery('label[name="errorLabel_'+editfieldNumbers+'"]').length)
        {
            jQuery('label[name="errorLabel_'+editfieldNumbers+'"]').remove();
        }
        else if(jQuery('label[name="errorLabel1_'+editfieldNumbers+'"]').length)
        {
            jQuery('label[name="errorLabel1_'+editfieldNumbers+'"]').remove();
        }
    }
}

function editFieldsBlur(obj)
{
    var specialStr  = /^[^\\\,\/\<\>]+$/;
    var editFieldsValue = jQuery(obj).val();
    editFieldsValue = jQuery.trim(editFieldsValue);

    var editFieldsNumbers = jQuery(obj).parent().parent().find('input[name="fieldNumbers"]').val();
    var fieldsObj = jQuery('input[name="newFields_'+editFieldsNumbers+'"]');
    // var errorMsg = '<label name="errorLabel2_'+editFieldsNumbers+'" style="color:red;"><?=_("*�Զ����ֶ����ݲ��ܺ��������ַ���")?></label>';
        
    if(editFieldsValue.search(specialStr) != 0 && editFieldsValue != '')
    {
        if(jQuery('label[name="errorLabel2_'+editFieldsNumbers+'"]').length)
        {
            jQuery('label[name="errorLabel2_'+editFieldsNumbers+'"]').remove();
        }
        // fieldsObj.after(errorMsg);
    }
    else
    {
        if(jQuery('label[name="errorLabel2_'+editFieldsNumbers+'"]').length)
        {
            jQuery('label[name="errorLabel2_'+editFieldsNumbers+'"]').remove();
        }
    }
}
</script>
<html>
<body class="bodycolor">

<?
//�������ֲ�ѯ��user_id
function userName2UserId($user_name){
    $user_id = "";
    $query  = "SELECT USER_ID from user where USER_NAME='$user_name'";
    $cursor = exequery(TD::conn(),$query);
    if($ROW = mysql_fetch_array($cursor)){
        $user_id = $ROW["USER_ID"];
    }
    return $user_id;
}
if($V_ID!="")
{
    $query = "SELECT * from VEHICLE where V_ID='$V_ID'";
    $cursor= exequery(TD::conn(),$query);
    if($ROW=mysql_fetch_array($cursor))
    {
        $V_MODEL            = $ROW["V_MODEL"];
        $V_NUM              = $ROW["V_NUM"];
        $V_DRIVER           = td_trim($ROW["V_DRIVER"]);
        $V_PHONE_NO         = $ROW["V_PHONE_NO"];
        $V_TYPE             = $ROW["V_TYPE"];
        $V_DISPLACEMENT     = $ROW["V_DISPLACEMENT"];  //����
        $V_COLOR            = $ROW["V_COLOR"];  //������ɫ
        $V_SEATING          = $ROW["V_SEATING"];    //��λ��
        $V_FRAME            = $ROW["V_FRAME"];  //���ܺź�6λ
        $V_CERTIFICATION    = $ROW["V_CERTIFICATION"];  //�������Ǽ�֤���7λ
        $V_NATURE           = $ROW["V_NATURE"]; //��������
        $V_DEPART           = $ROW["V_DEPART"]; //���ܲ���
        $V_ONWER            = $ROW["V_ONWER"];  //����������
        $V_CARUSER          = $ROW["V_CARUSER"];    //����ʹ����
        $V_TAX              = $ROW["V_TAX"];    //����˰�۸�
        $V_PAY              = $ROW["V_PAY"];    //�����֧
        $V_MILEAGE          = $ROW["V_MILEAGE"];    //��ʼ���
        $V_RECORD           = $ROW["V_RECORD"]; //�����������
        $V_BACKRECORD       = $ROW["V_BACKRECORD"]; //�����������
        $V_DEPART_PHONE     = $ROW["V_DEPART_PHONE"];   //���ܲ����ֻ�����
        $V_ONWER_PHONE      = $ROW["V_ONWER_PHONE"];   //�����������ֻ�����
        $V_CARUSER_PHONE    = $ROW["V_CARUSER_PHONE"];   //����ʹ�����ֻ�����
        $V_DATE             = $ROW["V_DATE"];
        $V_PRICE            = $ROW["V_PRICE"];
        $V_ENGINE_NUM       = $ROW["V_ENGINE_NUM"];
        $V_STATUS           = $ROW["V_STATUS"];
        $V_REMARK           = $ROW["V_REMARK"];
        $ATTACHMENT_ID      = $ROW["ATTACHMENT_ID"];
        $ATTACHMENT_NAME    = $ROW["ATTACHMENT_NAME"];
        $DEPT_RANGE         = $ROW["DEPT_RANGE"];
        $USER_RANGE         = $ROW["USER_RANGE"];
        $V_TITLES           = $ROW["V_TITLES"]; //�Զ����ֶα���
        $V_FIELDS           = $ROW["V_FIELDS"]; //�Զ����ֶ�����
        $V_NUMBERS          = $ROW["V_NUMBERS"]; //�Զ����ֶ�����

        $V_MOT              = $ROW["V_MOT"];    //���
        $V_INSURE           = $ROW["V_INSURE"]; //��ǿ��
        $V_MOT_SMS          = $ROW["V_MOT_SMS"];    //��쵽����������
        $V_INSURE_SMS       = $ROW["V_INSURE_SMS"]; //��ǿ�յ�����������
        $V_BINSURE          = $ROW["V_BINSURE"];    //��ҵ��
        $V_BINSURE_SMS       = $ROW["V_BINSURE_SMS"];   //��ҵ�յ�����������
        
        $V_MOT = ($V_MOT=='0000-00-00') ? '' : $V_MOT;
        $V_INSURE = ($V_INSURE=='0000-00-00') ? '' : $V_INSURE;
        $V_MOT_SMS = ($V_MOT_SMS=='0000-00-00') ? '' : $V_MOT_SMS;
        $V_INSURE_SMS = ($V_INSURE_SMS=='0000-00-00') ? '' : $V_INSURE_SMS;
        $V_BINSURE = ($V_BINSURE=='0000-00-00') ? '' : $V_BINSURE;
        $V_BINSURE_SMS = ($V_BINSURE_SMS=='0000-00-00') ? '' : $V_BINSURE_SMS;
        
        if($DEPT_RANGE=="ALL_DEPT")
        {
            $DEPT_RANGE_DESC = _("ȫ�岿��");
        }
        else
        {
            $DEPT_RANGE_DESC = GetDeptNameById($DEPT_RANGE);
        }
        $USER_RANGE_DESC = GetUserNameById($USER_RANGE);
        
        if($V_DATE=="0000-00-00")
        {
            $V_DATE="";
        }
    }
}
?>

<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
    <tr>
        <td class="Big"><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_new.gif" HEIGHT="20"><span class="big3"> <?=_("������Ϣ")?></span>
        </td>
    </tr>
</table>
<form enctype="multipart/form-data" action="add.php" method="post" name="form1">
<table class="TableBlock" align="center" width="70%" id="tableFields">
    <tr>
        <td nowrap class="TableData" width="80"> <?=_("���ƺţ�")?></td>
        <td class="TableData">
            <input type="text" name="V_NUM" size="20" maxlength="100" class="BigInput" value="<?=$V_NUM?>">
        </td>
        <td class="TableData" width="250" rowspan="6">
<?
if($ATTACHMENT_NAME=="")
{
    echo "<center>"._("������Ƭ")."</center>";
}
else
{
    $URL_ARRAY = attach_url($ATTACHMENT_ID,$ATTACHMENT_NAME);
?>
    <a href="<?=$URL_ARRAY["view"]?>" title="<?=_("����鿴�Ŵ�ͼƬ")?>" target="_blank"><img src="<?=$URL_ARRAY["view"]?>" width='250' border=1 alt="<?=_("�ļ�����")?><?=$ATTACHMENT_NAME?>"></a>
<?
}
?>
        </td>
    </tr>
    <tr>
        <td nowrap class="TableData"> <?=_("�����ͺţ�")?></td>
        <td class="TableData">
            <input type="text" name="V_MODEL" size="20" maxlength="100" class="BigInput" value="<?=$V_MODEL?>">
        </td>
   </tr>
   
   <tr>
        <td nowrap class="TableData"> <?=_("������Ϣ��")?></td>
        <td class="TableData">
            <?=_("������")?>&nbsp;&nbsp;&nbsp;&nbsp; <input type="text" style="margin-bottom:5px;" name="V_DISPLACEMENT" size="11" maxlength="15" class="BigInput" value="<?=$V_DISPLACEMENT?>"><br>
            <?=_("������ɫ��")?> <input type="text" style="margin-bottom:5px;" name="V_COLOR" size="11" maxlength="15" class="BigInput" value="<?=$V_COLOR?>"><br>
            <?=_("��λ����")?>&nbsp;&nbsp; <input type="text" name="V_SEATING" size="11" maxlength="15" class="BigInput" value="<?=$V_SEATING?>">
        </td>
   </tr>
    
    <tr>
        <td nowrap class="TableData"> <?=_("��������Ϣ��")?></td>
        <td class="TableData">
            <?=_("���ܺź�6λ��")?>&nbsp;&nbsp; <input type="text" style="margin-bottom:5px;" name="V_FRAME" size="12" maxlength="6" class="BigInput" value="<?=$V_FRAME?>"><br>
            <?=_("�Ǽ�֤���7λ��")?> <input type="text" name="V_CERTIFICATION" size="12" maxlength="7" class="BigInput" value="<?=$V_CERTIFICATION?>">
        </td>
    </tr>
    
    <tr>
        <td nowrap class="TableData"> <?=_("�������ţ�")?></td>
        <td class="TableData">
            <input type="text" name="V_ENGINE_NUM" size="20" maxlength="100" class="BigInput" value="<?=$V_ENGINE_NUM?>">
        </td>
    </tr>
    
    <tr>
        <td nowrap class="TableData"> <?=_("�������ʣ�")?></td>
        <td class="TableData">        
            <select name="V_NATURE" class="BigSelect">
                <option value=""></option>
                <?=code_list("VEHICLE_NATURE",$V_NATURE)?>
            </select>&nbsp;<?=_("�������Ϳ���ϵͳ����->��ϵͳ�������á�ģ������")?>
        </td>
    </tr>
    
    <tr>
        <td nowrap class="TableData"> <?=_("�������ͣ�")?></td>
        <td class="TableData" colspan="2">        
            <select name="V_TYPE" class="BigSelect">
                <option value=""></option>
                <?=code_list("VEHICLE_TYPE",$V_TYPE)?>
            </select>&nbsp;<?=_("�������Ϳ���ϵͳ����->��ϵͳ�������á�ģ������")?>
        </td>
    </tr>
    
    <tr>
        <td nowrap class="TableData"> <?=_("���ܲ��ţ�")?></td>
        <td class="TableData" colspan="2">
            <div id="depart">
                <input type='hidden' class='depart' name='RECORDER_DEPART' value=''>
                <input type='text' name='V_DEPART' id='V_DEPART'  size='20'  value = '<?=_($V_DEPART)?>' class='BigInput' maxlength='20' title="<?=_('���ܲ���')?>">
                <a href = "javascript:void(0);" onClick ="SelectDeptSingle('','RECORDER_DEPART','V_DEPART')"><?=_('ѡ��')?></a>
                <a href = "javascript:void(0);" onClick ="ClearUser('RECORDER_DEPART','V_DEPART')"><?=_('���')?></a>&nbsp;&nbsp;
                <?=_('�ֻ����룺')?><input type='text' name='V_DEPART_PHONE' size='11' maxlength='25' class='BigInput' value='<?=_($V_DEPART_PHONE)?>'>	
            </div>
        </td>
    </tr>
    
    <tr>
        <td nowrap class="TableData"> <?=_("���������ˣ�")?></td>
        <td class="TableData" colspan="2">
            <div id="onwer">
                <input type='hidden' class='onwer' name='RECORDER_ONWER' value='<?=userName2UserId($V_ONWER)?>'>
                <input type='text' name='V_ONWER' id='V_ONWER'  size='10'  value = '<?=_($V_ONWER)?>' class='BigInput' maxlength='20' title="<?=_('����������')?>">
                <a href = "javascript:void(0);" onClick ="SelectUserSingle('90','','RECORDER_ONWER','V_ONWER')"><?=_('ѡ��')?></a>
                <a href = "javascript:void(0);" onClick ="ClearUser('RECORDER_ONWER','V_ONWER')"><?=_('���')?></a>&nbsp;&nbsp;
                <?=_('�ֻ����룺')?><input type='text' name='V_ONWER_PHONE' size='11' maxlength='25' class='BigInput' value='<?=_($V_ONWER_PHONE)?>'>	
            </div>
        </td>
    </tr>
    
    <tr>
        <td nowrap class="TableData"> <?=_("����ʹ���ˣ�")?></td>
        <td class="TableData" colspan="2">
            <div id="caruser">
                <input type='hidden' class='caruser' name='RECORDER_CARUSER' value='<?=userName2UserId($V_CARUSER)?>'>
                <input type='text' name='V_CARUSER' id='V_CARUSER'  size='10'  value = '<?=_($V_CARUSER)?>' class='BigInput' maxlength='20' title="<?=_('����ʹ����')?>">
                <a href = "javascript:void(0);" onClick ="SelectUserSingle('90','','RECORDER_CARUSER','V_CARUSER')"><?=_('ѡ��')?></a>
                <a href = "javascript:void(0);" onClick ="ClearUser('RECORDER_CARUSER','V_CARUSER')"><?=_('���')?></a>&nbsp;&nbsp;
                <?=_('�ֻ����룺')?><input type='text' name='V_CARUSER_PHONE' size='11' maxlength='25' class='BigInput' value='<?=_($V_CARUSER_PHONE)?>'>	
            </div>
        </td>
    </tr>
    
    <tr>
        <td nowrap class="TableData"> <?=_("˾����")?></td>
        <td class="TableData" colspan="2">
            <div id="driver">
<?
        if(empty($V_DRIVER))
        {
            echo "
            <p>
            <input type='hidden' class='driver' name='RECORDER_ID0' value=''>
            <input type='text' name='V_DRIVER0'  size='10'  value = '' class='BigInput' maxlength='20' title="._('˾��').">	       
            <a href = 'javascript:void(0);' onClick =\"SelectUserSingle('90','','RECORDER_ID0','V_DRIVER0')\">"._('ѡ��')."</a>
            <a href = 'javascript:void(0);' onClick =\"ClearUser('RECORDER_ID0','V_DRIVER0')\">"._('���')."</a>&nbsp;&nbsp;
            "._('�ֻ����룺')."<input type='text' name='V_PHONE_NO0' size='11' maxlength='25' class='BigInput' value=''>".
            "<button style='float:right' onclick='adddriver()' type='button'>"._('���˾��')."</button>";						
        }
        else
        {
            $driverarr = explode(',',$V_DRIVER);
            $phoarr = explode(',',$V_PHONE_NO);
            $num = 0;
            foreach($driverarr as $dvalue){
                if(!empty($dvalue))
                {
                    echo "
                    <p>
                    <input type='hidden' class='driver' name='RECORDER_ID".$num."' value='".userName2UserId($V_ONWER)."'>
                    <input type='text' name='V_DRIVER".$num."'  size='10'  value = '".$dvalue."' class='BigInput' maxlength='20' title="._('˾��').">	       
                    <a href = 'javascript:void(0);' onClick =\"SelectUserSingle('90','','RECORDER_ID".$num."','V_DRIVER".$num."')\">"._('ѡ��')."</a>
                    <a href = 'javascript:void(0);' onClick =\"ClearUser('RECORDER_ID".$num."','V_DRIVER".$num."')\">"._('���')."</a>&nbsp;&nbsp;
                    "._('�ֻ����룺')."<input type='text' name='V_PHONE_NO".$num."' size='11' maxlength='25' class='BigInput' value='".$phoarr[$num]."'>";
                    echo $num == 0 ? "<button style='float:right' onclick='adddriver()' type='button'>"._('���˾��')."</button>" : '';
                    
                    $num++;
                }
            }
        }
?>
		        </p>
            </div>
        </td>
    </tr>
    <tr>
        <td nowrap class="TableData"> <?=_("����۸�")?></td>
        <td class="TableData" colspan="2">
            <input type="text" name="V_PRICE" size="12" maxlength="25" class="BigInput" value="<?=$V_PRICE?>">
        </td>
    </tr>
    
    <tr>
        <td nowrap class="TableData"> <?=_("����˰�۸�")?></td>
        <td class="TableData" colspan="2">
            <input type="text" name="V_TAX" size="12" maxlength="25" class="BigInput" value="<?=$V_TAX?>">
        </td>
    </tr>
    
    <tr>
        <td nowrap class="TableData"> <?=_("�����֧��")?></td>
        <td class="TableData" colspan="2">
            <input type="text" name="V_PAY" size="12" maxlength="25" class="BigInput" value="<?=$V_PAY?>">
        </td>
    </tr>
<?
if($ATTACHMENT_NAME=="")
{
    $PHOTO_STR=_("������Ƭ�ϴ���");
}
else
{
    $PHOTO_STR=_("������Ƭ���ģ�");
}
?>
    <tr>
        <td nowrap class="TableData"> <?=$PHOTO_STR?></td>
        <td class="TableData" colspan="2">
            <input type="file" name="ATTACHMENT" size="40" class="BigInput" title="<?=_("ѡ�񸽼��ļ�")?>">
        </td>
    </tr>
    <tr>
        <td nowrap class="TableData"> <?=_("�������ڣ�")?></td>
        <td class="TableData" colspan="2">
            <input type="text" name="V_DATE" size="12" maxlength="10" class="BigInput" value="<?=$V_DATE?>" onClick="WdatePicker()">
        </td>
    </tr>
    
    <tr>
        <td nowrap class="TableData"> <?=_("��ʼ��̣�")?></td>
        <td class="TableData" colspan="2">
            <input type="text" name="V_MILEAGE" size="12" maxlength="10" class="BigInput" value="<?=$V_MILEAGE?>">
        </td>
    </tr>
    
    <tr>
        <td nowrap class="TableData"> <?=_("������ڣ�")?></td>
        <td class="TableData" colspan="2">
            <input type="text" name="V_MOT" size="12" maxlength="10" class="BigInput" value="<?=$V_MOT?>" onClick="WdatePicker()">
            &nbsp;&nbsp;&nbsp;&nbsp;<?=_("��������ʱ�䣺")?>
            <input type="text" name="V_MOT_SMS" size="12" maxlength="10" class="BigInput" value="<?=$V_MOT_SMS?>" onClick="WdatePicker()">
            
            <?
            if (find_id($SMS3_REMIND, 9))
            {
            ?>
                <input type="checkbox" name="V_MOT_SMS1" id="V_MOT_SMS1" <?if(find_id($SMS_REMIND,"9")) echo " checked";?>><label for="V_MOT_SMS1"><?=_("��������������Ϣ")?></label>&nbsp;
            <?
            }
            
            $query = "select * from SMS2_PRIV";
            $cursor=exequery(TD::conn(),$query);
            if($ROW=mysql_fetch_array($cursor))
            {
                $TYPE_PRIV=$ROW["TYPE_PRIV"];
                $SMS2_REMIND_PRIV=$ROW["SMS2_REMIND_PRIV"];
            }
            
            if(find_id($TYPE_PRIV,9) && find_id($SMS2_REMIND_PRIV, $_SESSION["LOGIN_USER_ID"])) //����ģ���Ƿ������ֻ�����
            {
            ?>
                <input type="checkbox" name="V_MOT_SMS2" id="V_MOT_SMS2" <?if(find_id($SMS2_REMIND,"9")) echo " checked";?>><label for="V_MOT_SMS2"><?=_("ʹ���ֻ���������")?></label>
            <?
            }
            ?>
            
        </td>
    </tr>
    <tr>
        <td nowrap class="TableData"> <?=_("��ǿ�����ڣ�")?></td>
        <td class="TableData" colspan="2">
            <input type="text" name="V_INSURE" size="12" maxlength="10" class="BigInput" value="<?=$V_INSURE?>" onClick="WdatePicker()">
            &nbsp;&nbsp;&nbsp;&nbsp;<?=_("��������ʱ�䣺")?>
            <input type="text" name="V_INSURE_SMS" size="12" maxlength="10" class="BigInput" value="<?=$V_INSURE_SMS?>" onClick="WdatePicker()">
            <?=sms_remind(9);?>
        </td>
    </tr>
    <tr>
        <td nowrap class="TableData"> <?=_("��ҵ�����ڣ�")?></td>
        <td class="TableData" colspan="2">
            <input type="text" name="V_BINSURE" size="12" maxlength="10" class="BigInput" value="<?=$V_BINSURE?>" onClick="WdatePicker()">
            &nbsp;&nbsp;&nbsp;&nbsp;<?=_("��������ʱ�䣺")?>
            <input type="text" name="V_BINSURE_SMS" size="12" maxlength="10" class="BigInput" value="<?=$V_BINSURE_SMS?>" onClick="WdatePicker()">
            
            <?
            if (find_id($SMS3_REMIND, 9))
            {
            ?>
                <input type="checkbox" name="V_BINSURE_SMS1" id="V_BINSURE_SMS1" <?if(find_id($SMS_REMIND,"9")) echo " checked";?>><label for="V_BINSURE_SMS1"><?=_("��������������Ϣ")?></label>&nbsp;
            <?
            }
            
            $query = "select * from SMS2_PRIV";
            $cursor=exequery(TD::conn(),$query);
            if($ROW=mysql_fetch_array($cursor))
            {
                $TYPE_PRIV=$ROW["TYPE_PRIV"];
                $SMS2_REMIND_PRIV=$ROW["SMS2_REMIND_PRIV"];
            }
            
            if(find_id($TYPE_PRIV,9) && find_id($SMS2_REMIND_PRIV, $_SESSION["LOGIN_USER_ID"])) //����ģ���Ƿ������ֻ�����
            {
            ?>
                <input type="checkbox" name="V_BINSURE_SMS2" id="V_BINSURE_SMS2" <?if(find_id($SMS2_REMIND,"9")) echo " checked";?>><label for="V_BINSURE_SMS2"><?=_("ʹ���ֻ���������")?></label>
            <?
            }
            ?>
            
        </td>
    </tr>
    <tr>
        <td nowrap class="TableData"> <?=_("����Ȩ��(����)��")?></td>
        <td class="TableData" colspan="2">
            <input type="hidden" name="DEPT_RANGE" value="<?=$DEPT_RANGE?>">
            <textarea cols=40 name=DEPT_RANGE_NAME rows=2 class="BigStatic" wrap="yes" readonly><?=$DEPT_RANGE_DESC?></textarea>
            <a href="javascript:;" class="orgAdd" onClick="SelectDept('','DEPT_RANGE','DEPT_RANGE_NAME')"><?=_("���")?></a>
            <a href="javascript:;" class="orgClear" onClick="ClearUser('DEPT_RANGE','DEPT_RANGE_NAME')"><?=_("���")?></a>
        </td>
    </tr>
    <tr>
        <td nowrap class="TableData"> <?=_("����Ȩ��(��Ա)��")?></td>
        <td class="TableData" colspan="2">
            <input type="hidden" name="USER_RANGE" value="<?=$USER_RANGE?>">
            <textarea cols=40 name="USER_RANGE_NAME" rows=2 class="BigStatic" wrap="yes" readonly><?=$USER_RANGE_DESC?></textarea>
            <a href="javascript:;" class="orgAdd" onClick="SelectUser('90','','USER_RANGE', 'USER_RANGE_NAME')"><?=_("���")?></a>
            <a href="javascript:;" class="orgClear" onClick="ClearUser('USER_RANGE', 'USER_RANGE_NAME')"><?=_("���")?></a>
            &nbsp;&nbsp;<?=_("��ʾ������Ȩ��Ϊ��������")?>
        </td>
    </tr>
    <tr>
        <td nowrap class="TableData"> <?=_("��ǰ״̬��")?></td>
        <td class="TableData" colspan="2">
            <select name="V_STATUS" class="BigSelect">
                <option value="0" <? if($V_STATUS=="0") echo "selected";?>><?=_("����")?></option>
                <option value="1" <? if($V_STATUS=="1") echo "selected";?>><?=_("��")?></option>
                <option value="2" <? if($V_STATUS=="2") echo "selected";?>><?=_("ά����")?></option>
                <option value="3" <? if($V_STATUS=="3") echo "selected";?>><?=_("����")?></option>
            </select>
        </td>
    </tr>
    
    <tr>
        <td nowrap class="TableData"> <?=_("�������������")?></td>
        <td class="TableData" colspan="2">
            <textarea name="V_RECORD" class="BigInput" cols="57" rows="3"><?=$V_RECORD?></textarea>
        </td>
    </tr>
    
    <tr>
        <td nowrap class="TableData"> <?=_("�����軹��¼��")?></td>
        <td class="TableData" colspan="2">
            <textarea name="V_BACKRECORD" class="BigInput" cols="57" rows="3"><?=$V_BACKRECORD?></textarea>
        </td>
    </tr>
    
    <tr>
        <td nowrap class="TableData"> <?=_("��ע��")?></td>
        <td class="TableData" colspan="2">
            <textarea name="V_REMARK" class="BigInput" cols="57" rows="3"><?=$V_REMARK?></textarea>
        </td>
    </tr>
<?
    if($V_TITLES != "")
    {
        $V_TITLES = substr($V_TITLES,0,-1);
        $V_FIELDS = substr($V_FIELDS,0,-1);
        $V_NUMBERS = substr($V_NUMBERS,0,-1);
        $editWord = _("�޸�");
        // $deleteWord = _("ɾ��");

        $titlesArray = explode(",", $V_TITLES);
        $fieldsArray = explode(",", $V_FIELDS);
        $numbersArray = explode(",", $V_NUMBERS);

        for($count=0; $count<count($titlesArray); $count++)
        {
            $titlesArray[$count] = $titlesArray[$count] ? $titlesArray[$count] : "";
            $fieldsArray[$count] = $fieldsArray[$count] ? $fieldsArray[$count] : "";
            $numbersArray[$count] = $numbersArray[$count] ? $numbersArray[$count] : "";
            
            $fieldsTmpl =   '<tr>'.
                                '<td nowrap class="TableData"><input type="text" name="newTitles_'.$numbersArray[$count].'" size="10" maxlength="10" class="BigInput" onblur="editTitlesBlur(this)" value="'.$titlesArray[$count].'"></td>'.
                                '<td class="TableData" colspan="2">'.
                                    '<input type="text" name="newFields_'.$numbersArray[$count].'" size="40" maxlength="60" class="BigInput" onblur="editFieldsBlur(this)" value="'.$fieldsArray[$count].'">'.
                                    '<input type="hidden" name="newNumbers_'.$numbersArray[$count].'" value="'.$numbersArray[$count].'">'.
                                    '<input type="hidden" name="fieldNumbers" value="'.$numbersArray[$count].'">'.
                                    // '<a style="float:right; margin:0 10px 0 10px;" href="javascript:;" onclick="deleteFields(this)">'.$deleteWord.'</a>'.
                                    '<a style="float:right; margin:0 10px 0 10px;" href="javascript:;" onclick="editFields('.$numbersArray[$count].')">'.$editWord.'</a>'.
                                '</td>'.
                            '</tr>';
        
            echo $fieldsTmpl;
        }
    }
    
?>
    <tr>
        <td nowrap class="TableData" colspan="3">
            <input type="button" value="<?=_("������ֶ�")?>" onClick="addNewFields()" class="BigButton">
        </td>
   </tr>
    
    <tr class="TableControl">
        <td nowrap colspan="3" align="center">
            <input type="hidden" value="<?=$V_ID?>" name="V_ID">
            <input type="hidden" value="<?=$ATTACHMENT_ID?>" name="ATTACHMENT_ID_OLD">
            <input type="hidden" value="<?=$ATTACHMENT_NAME?>" name="ATTACHMENT_NAME_OLD">
            <input type="button" value="<?=_("����")?>" class="BigButton" onClick="CheckForm();">&nbsp;&nbsp;
            <input type="reset" value="<?=_("����")?>" class="BigButton">&nbsp;&nbsp;
<?
if($V_ID!="")
{
?>
            <input type="button" value="<?=_("����")?>" class="BigButton" onClick="location='manage.php'">
<?
}
?>
        </td>
    </tr>
</table>
</form>

</body>
</html>