<?
include_once("inc/auth.inc.php");
include_once("inc/editor.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
include_once("inc/utility_field.php");
include_once("inc/utility_file.php");
include_once("inc/lunar.class.php");

$connstatus = ($connstatus) ? true : false;
$PARA_ARRAY         = get_sys_para("LEAVE_BY_SENIORITY,ENTRY_RESET_LEAVE");
$entry_reset_leave  = $PARA_ARRAY["ENTRY_RESET_LEAVE"];//�Ƿ�������ְ���ڼ������
$leave_by_seniority = $PARA_ARRAY["LEAVE_BY_SENIORITY"];//�Ƿ���������������
$USER_ID = td_trim($USER_ID);
$COUNT = 0;
$query="select USER_NAME,DEPT_ID,NOT_LOGIN,USER_PRIV,BYNAME from USER where USER_ID='$USER_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
    $USER_DEPT_ID   = $ROW["DEPT_ID"];
    $USER_NAME      = $ROW["USER_NAME"];
    $NOT_LOGIN      = $ROW["NOT_LOGIN"];
    $USER_PRIV      = $ROW["USER_PRIV"];
    $BYNAME         = $ROW["BYNAME"];
    $DEPT_NAME      = substr(GetDeptNameById($USER_DEPT_ID),0,-1);
    
    $COUNT++;
}

$TREE_DEPT_ID = $DEPT_ID;

$query="select * from HR_STAFF_INFO where USER_ID='$USER_ID'";
$cursor= exequery(TD::conn(),$query, $connstatus);
if($ROW=mysql_fetch_array($cursor))
{
    $CREATE_USER_ID         = $ROW["CREATE_USER_ID"];
    $STAFF_NO               = $ROW["STAFF_NO"];
    $WORK_NO                = $ROW["WORK_NO"];
    $WORK_TYPE              = $ROW["WORK_TYPE"];
    $BEFORE_NAME            = $ROW["BEFORE_NAME"];
    $STAFF_E_NAME           = $ROW["STAFF_E_NAME"];
    $STAFF_CARD_NO          = $ROW["STAFF_CARD_NO"];
    $STAFF_SEX              = $ROW["STAFF_SEX"];
    $BLOOD_TYPE             = $ROW["BLOOD_TYPE"];
    $STAFF_BIRTH            = $ROW["STAFF_BIRTH"];
    $IS_LUNAR               = $ROW["IS_LUNAR"];
    //$STAFF_AGE            = $ROW["STAFF_AGE"];
    $STAFF_NATIVE_PLACE     = $ROW["STAFF_NATIVE_PLACE"];
    $STAFF_NATIVE_PLACE2    = $ROW["STAFF_NATIVE_PLACE2"];
    $STAFF_DOMICILE_PLACE   = $ROW["STAFF_DOMICILE_PLACE"];
    $STAFF_NATIONALITY      = $ROW["STAFF_NATIONALITY"];
    $STAFF_MARITAL_STATUS   = $ROW["STAFF_MARITAL_STATUS"];
    $STAFF_POLITICAL_STATUS = $ROW["STAFF_POLITICAL_STATUS"];
    $PHOTO_NAME             = $ROW["PHOTO_NAME"];
    $COMPUTER_LEVEL         = $ROW["COMPUTER_LEVEL"];
    $JOIN_PARTY_TIME        = $ROW["JOIN_PARTY_TIME"];
    $STAFF_PHONE            = $ROW["STAFF_PHONE"];
    $STAFF_MOBILE           = $ROW["STAFF_MOBILE"];
    $STAFF_LITTLE_SMART     = $ROW["STAFF_LITTLE_SMART"];
    $STAFF_EMAIL            = $ROW["STAFF_EMAIL"];
    $STAFF_MSN              = $ROW["STAFF_MSN"];
    $JOB_POSITION           = $ROW["JOB_POSITION"];
    $STAFF_QQ               = $ROW["STAFF_QQ"];
    $HOME_ADDRESS           = $ROW["HOME_ADDRESS"];
    $BANK1                  = $ROW["BANK1"];
    $BANK_ACCOUNT1          = $ROW["BANK_ACCOUNT1"];
    $BANK2                  = $ROW["BANK2"];
    $BANK_ACCOUNT2          = $ROW["BANK_ACCOUNT2"];
    $OTHER_CONTACT          = $ROW["OTHER_CONTACT"];
    $JOB_BEGINNING          = $ROW["JOB_BEGINNING"];
    //$WORK_AGE             = $ROW["WORK_AGE"];
    $BEGIN_SALSRY_TIME      = $ROW["BEGIN_SALSRY_TIME"];
    $STAFF_HEALTH           = $ROW["STAFF_HEALTH"];
    $STAFF_HIGHEST_SCHOOL   = $ROW["STAFF_HIGHEST_SCHOOL"];
    $STAFF_HIGHEST_DEGREE   = $ROW["STAFF_HIGHEST_DEGREE"];
    $GRADUATION_DATE        = $ROW["GRADUATION_DATE"];
    $GRADUATION_SCHOOL      = $ROW["GRADUATION_SCHOOL"];
    $STAFF_MAJOR            = $ROW["STAFF_MAJOR"];
    $FOREIGN_LANGUAGE1      = $ROW["FOREIGN_LANGUAGE1"];
    $FOREIGN_LEVEL1         = $ROW["FOREIGN_LEVEL1"];
    $FOREIGN_LANGUAGE2      = $ROW["FOREIGN_LANGUAGE2"];
    $FOREIGN_LEVEL2         = $ROW["FOREIGN_LEVEL2"];
    $FOREIGN_LANGUAGE3      = $ROW["FOREIGN_LANGUAGE3"];
    $FOREIGN_LEVEL3         = $ROW["FOREIGN_LEVEL3"];
    $STAFF_SKILLS           = $ROW["STAFF_SKILLS"];
    $STAFF_OCCUPATION       = $ROW["STAFF_OCCUPATION"];
    $ADMINISTRATION_LEVEL   = $ROW["ADMINISTRATION_LEVEL"];
    $PRESENT_POSITION       = $ROW["PRESENT_POSITION"];
    $DATES_EMPLOYED         = $ROW["DATES_EMPLOYED"];
    //$JOB_AGE              = $ROW["JOB_AGE"];
    $STAFF_CS               = $ROW["STAFF_CS"];
    $WORK_STATUS            = $ROW["WORK_STATUS"];
    $STAFF_CTR              = $ROW["STAFF_CTR"];
    $REMARK                 = $ROW["REMARK"];
    $STAFF_COMPANY          = $ROW["STAFF_COMPANY"];
    $RESUME                 = $ROW["RESUME"];
    $STAFF_TYPE             = $ROW["STAFF_TYPE"];
    $LEAVE_TYPE             = $ROW["LEAVE_TYPE"];
    $ATTACHMENT_ID          = $ROW["ATTACHMENT_ID"];
    $ATTACHMENT_NAME        = $ROW["ATTACHMENT_NAME"];
    $USERDEF11              = $ROW["USERDEF1"];
    $USERDEF21              = $ROW["USERDEF2"];
    $USERDEF31              = $ROW["USERDEF3"];
    $USERDEF41              = $ROW["USERDEF4"];
    $USERDEF51              = $ROW["USERDEF5"];
    $CERTIFICATE            = $ROW["CERTIFICATE"];
    $SURETY                 = $ROW["SURETY"];
    $BODY_EXAMIM            = $ROW["BODY_EXAMIM"];
    $INSURE                 = $ROW["INSURE"];
    $DEPT_ID                = $ROW["DEPT_ID"];
    $WORK_LEVEL             = $ROW["WORK_LEVEL"];
    $WORK_JOB               = $ROW["WORK_JOB"];
    //��λְ��                
    $IS_EXPERTS             = $ROW["IS_EXPERTS"];
    $EXPERTS_INFO           = $ROW["EXPERTS_INFO"];
    $DIRECTLY_UNDER         = $ROW["DIRECTLY_UNDER"];
    $DIRECTLY_SUPERIOR      = $ROW["DIRECTLY_SUPERIOR"];
    $PART_TIME              = $ROW["PART_TIME"];
    $RESEARCH_RESULTS       = $ROW["RESEARCH_RESULTS"];
    
    $DIRECTLY_UNDER_NAME = substr(GetUserNameById($DIRECTLY_UNDER), 0, -1);
    if($DIRECTLY_UNDER_NAME!="")
        $DIRECTLY_UNDER_NAME=$DIRECTLY_UNDER_NAME.',';
    
    $DIRECTLY_SUPERIOR_NAME = substr(GetUserNameById($DIRECTLY_SUPERIOR), 0, -1);
    if($DIRECTLY_SUPERIOR_NAME!="")
        $DIRECTLY_SUPERIOR_NAME=$DIRECTLY_SUPERIOR_NAME.',';
    
    $DEPT_NAME=substr(GetDeptNameById($DEPT_ID),0,-1);
    $OPERATION=2;
    $STATUS=_("�ѽ���");
    
    if($STAFF_BIRTH!="0000-00-00"){
        //��lunar.php�л�ȡ��Ф
        $ANIMAL = get_animal($STAFF_BIRTH,$IS_LUNAR);
        //��lunar.php�л�ȡ����
        $SIGN = get_zodiac_sign($STAFF_BIRTH,$IS_LUNAR);
    }
}
else
{
    $OPERATION = 1;
    $STATUS = _("δ����");
    $DEPT_ID = $TREE_DEPT_ID;
    $DEPT_NAME = substr(GetDeptNameById($DEPT_ID),0,-1);
}



//---------�鿴�����Ϣ�Ƿ����----------
$query = "SELECT * from  HR_STAFF_CONTRACT where STAFF_NAME='$USER_ID'";
$cursor= exequery(TD::conn(),$query);
$INFO_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
    $INFO_COUNT++;
}
$query = "SELECT * from HR_STAFF_INCENTIVE where STAFF_NAME='$USER_ID' ";
$cursor= exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
    $INFO_COUNT++;
}
$query = "SELECT * from  HR_STAFF_LICENSE where STAFF_NAME='$USER_ID' ";
$cursor= exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
    $INFO_COUNT++;
}
$query = "SELECT * from HR_STAFF_LEARN_EXPERIENCE where STAFF_NAME='$USER_ID' ";
$cursor= exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
    $INFO_COUNT++;
}
$query = "SELECT * from HR_STAFF_WORK_EXPERIENCE where STAFF_NAME='$USER_ID' ";
$cursor= exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
    $INFO_COUNT++;
}
$query = "SELECT * from HR_STAFF_LABOR_SKILLS where STAFF_NAME='$USER_ID' ";
$cursor= exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
    $INFO_COUNT++;
}
$query = "SELECT * from HR_STAFF_RELATIVES where STAFF_NAME ='$USER_ID' ";
$cursor= exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
    $INFO_COUNT++;
}
$query = "SELECT * from HR_STAFF_TRANSFER where TRANSFER_PERSON='$USER_ID' ";
$cursor= exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
    $INFO_COUNT++;
}
$query = "SELECT * from HR_STAFF_LEAVE where LEAVE_PERSON='$USER_ID' ";
$cursor= exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
    $INFO_COUNT++;
}
$query = "SELECT * from HR_STAFF_REINSTATEMENT where REINSTATEMENT_PERSON='$USER_ID' ";
$cursor= exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
    $INFO_COUNT++;
}
$query = "SELECT * from HR_STAFF_TITLE_EVALUATION where BY_EVALU_STAFFS='$USER_ID' ";
$cursor= exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
    $INFO_COUNT++;
}
$query = "SELECT * from HR_STAFF_CARE where BY_CARE_STAFFS='$USER_ID' ";
$cursor= exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
    $INFO_COUNT++;
}

if($NOT_LOGIN=="1")
    $STATUS2="&nbsp;<font color=red>"._("��ֹ��¼")."</font>";
else
    $STATUS2="";

//������ȨOA��¼Ȩ��
$SYS_PARA_ARRAY = get_sys_para("HR_SET_USER_LOGIN");
$YES_OTHER = $SYS_PARA_ARRAY["HR_SET_USER_LOGIN"];

$HTML_PAGE_TITLE = _("���µ����༭");
include_once("inc/header.inc.php");
?>
<link rel="stylesheet"type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/js/validation/validationEngine.jquery.min.css">
<script type="text/javascript" src="/inc/js_lang.php"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/validation/jquery.validationEngine.min.js" type="text/javascript" charset="utf-8"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/utility.js"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>
<script> 
jQuery(document).ready(function(){      
    jQuery("#form1").validationEngine({promptPosition:"centerRight"});
});
</script>
<script>
var upload_limit=<?=MYOA_UPLOAD_LIMIT?>,limit_type="<?=strtolower(MYOA_UPLOAD_LIMIT_TYPE)?>";
function IsValidEmail(str)
{
    var re = /@/;
    return str.match(re)!=null;
}
function CheckForm()
{
    if(document.form1.BYNAME.value=="")
    {
        alert("OA<?=_("�û�������Ϊ��")?>");
        return (false);
    }
    if(document.form1.DEPT_ID.value=="")
    {
        alert("<?=_("���Ų���Ϊ��")?>");
        return (false);
    }
    if(document.form1.STAFF_NAME.value=="")
    {
        alert("<?=_("��������Ϊ��")?>");
        return (false);
    }
    
    var pattern = new RegExp(/^[a-zA-Z ]*$/);
    var re=pattern.test(document.form1.STAFF_E_NAME.value);
    if(!re)
    {
        alert("<?=_("��������ȷ��Ӣ������")?>");
        return (false);
    }
    if (document.form1.STAFF_EMAIL.value!=""&&!IsValidEmail(document.form1.STAFF_EMAIL.value))
    {
        alert("<?=_("��������Ч�ĵ������䣡")?>");
        return (false);
    }
    
    var cursor_file = document.getElementById("cursor_file");
    var file_temp=cursor_file.value,ext_name;
    var Pos;
    Pos=file_temp.lastIndexOf(".")+1;
    ext_name=file_temp.substring(Pos,file_temp.length);
    if(ext_name!="" && ext_name.toLowerCase()!="gif" && ext_name.toLowerCase()!="png" && ext_name.toLowerCase()!="jpg" && ext_name.toLowerCase()!="bmp") 
    {
        alert("��Ƭ�ļ�ֻ����gif��png��jpg��bmp��ʽ!");
        return false;
    }
    document.form1.submit();
}

function checkIdcard()
{
    if (document.form1.STAFF_CARD_NO.value!="")
    {
        var idcard=document.form1.STAFF_CARD_NO.value;
        var Errors=new Array(
            "<?=_("���֤����λ�����ԣ�")?>",
            "<?=_("���֤����������ڳ�����Χ���зǷ��ַ���")?>",
            "<?=_("���֤����У�����")?>",
            "<?=_("���֤�����������������룡")?>"
        );
        var area={11:"<?=_("����")?>",12:"<?=_("���")?>",13:"<?=_("�ӱ�")?>",14:"<?=_("ɽ��")?>",15:"<?=_("���ɹ�")?>",21:"<?=_("����")?>",22:"<?=_("����")?>",23:"<?=_("������")?>",31:"<?=_("�Ϻ�")?>",32:"<?=_("����")?>",33:"<?=_("�㽭")?>",34:"<?=_("����")?>",35:"<?=_("����")?>",36:"<?=_("����")?>",37:"<?=_("ɽ��")?>",41:"<?=_("����")?>",42:"<?=_("����")?>",43:"<?=_("����")?>",44:"<?=_("�㶫")?>",45:"<?=_("����")?>",46:"<?=_("����")?>",50:"<?=_("����")?>",51:"<?=_("�Ĵ�")?>",52:"<?=_("����")?>",53:"<?=_("����")?>",54:"<?=_("����")?>",61:"<?=_("����")?>",62:"<?=_("����")?>",63:"<?=_("�ຣ")?>",64:"<?=_("����")?>",65:"<?=_("�½�")?>",71:"<?=_("̨��")?>",81:"<?=_("���")?>",82:"<?=_("����")?>",91:"<?=_("����")?>"}
        var idcard,Y,JYM;
        var S,M;
        var idcard_array = new Array();
        idcard_array = idcard.split("");
        if(area[parseInt(idcard.substr(0,2))]==null)
        {
            alert(Errors[3]);
            document.getElementById("STAFF_CARD_NO").focus();//form1.STAFF_CARD_NO.focus();
            return false;
        }
        
        switch(idcard.length)
        {
            case 15:
                /*if ((parseInt(idcard.substr(6,2))+1900) % 4 == 0 || ((parseInt(idcard.substr(6,2))+1900) % 100 == 0 && (parseInt(idcard.substr(6,2))+1900) % 4 == 0 ))
                {
                    ereg=/^[1-9][0-9]{5}[0-9]{2}((01|03|05|07|08|10|12)(0[1-9]|[1-2][0-9]|3[0-1])|(04|06|09|11)(0[1-9]|[1-2][0-9]|30)|02(0[1-9]|[1-2][0-9]))[0-9]{3}$/;
                }
                else
                {
                    ereg=/^[1-9][0-9]{5}[0-9]{2}((01|03|05|07|08|10|12)(0[1-9]|[1-2][0-9]|3[0-1])|(04|06|09|11)(0[1-9]|[1-2][0-9]|30)|02(0[1-9]|1[0-9]|2[0-8]))[0-9]{3}$/;
                }*/
                //���֤15λʱ������Ϊʡ��3λ���У�3λ���꣨2λ���£�2λ���գ�2λ��У��λ��3λ������Ϊ����
                var re_fifteen = /^(\d{6})(\d{2})(\d{2})(\d{2})(\d{3})$/;
                var arr_data = idcard.match(re_fifteen); 
                var year = arr_data[2]; 
                var month = arr_data[3]; 
                var day = arr_data[4]; 
                var birthday = new Date('19'+year+'/'+month+'/'+day); 
                var status =  verifyBirthday('19'+year,month,day,birthday);
                
                if(status==false)
                {
                    alert(Errors[1]);
                    //document.form1.STAFF_CARD_NO.focus();
                    return (false);
                }
                else
                {
                    var birth=(parseInt(idcard.substr(6,2))+1900).toString()+"-"+idcard.substr(8,2)+"-"+idcard.substr(10,2);
                    document.form1.STAFF_BIRTH.value=birth;
                    var myDate = new Date();
                    var month = myDate.getMonth()+1;
                    var day = myDate.getDate();
                    var birth_day=idcard.substr(10,2);
                    var birth_month=idcard.substr(8,2);
                    var age=myDate.getYear()-(parseInt(idcard.substr(6,2))+1900);
                    if(birth_month<month || birth_month==month && birth_day<=day)
                    {
                        age++;
                    }
                    document.form1.STAFF_AGE.value=age-1;
                    var sex=parseInt(idcard.substr(14,1));
                    if (sex%2==1) //��
                        document.form1.STAFF_SEX.value="0";
                    else  //Ů
                        document.form1.STAFF_SEX.value="1";
                }
                break;
            case 18:
                /*if (parseInt(idcard.substr(6,4)) % 4 == 0 || (parseInt(idcard.substr(6,4)) % 100 == 0 && parseInt(idcard.substr(6,4))%4 == 0 ))
                {
                    ereg=/^[1-9][0-9]{5}19[0-9]{2}((01|03|05|07|08|10|12)(0[1-9]|[1-2][0-9]|3[0-1])|(04|06|09|11)(0[1-9]|[1-2][0-9]|30)|02(0[1-9]|[1-2][0-9]))[0-9]{3}[0-9Xx]$/;//����������ڵĺϷ���������ʽ
                }
                else
                {
                    ereg=/^[1-9][0-9]{5}19[0-9]{2}((01|03|05|07|08|10|12)(0[1-9]|[1-2][0-9]|3[0-1])|(04|06|09|11)(0[1-9]|[1-2][0-9]|30)|02(0[1-9]|1[0-9]|2[0-8]))[0-9]{3}[0-9Xx]$/;
                }*/
                //���֤18λʱ������Ϊʡ��3λ���У�3λ���꣨4λ���£�2λ���գ�2λ��У��λ��4λ����У��λĩβ����ΪX
                var re_eighteen = /^(\d{6})(\d{4})(\d{2})(\d{2})(\d{3})([0-9]|X)$/; 
                var arr_data = idcard.match(re_eighteen); 
                var year = arr_data[2]; 
                var month = arr_data[3]; 
                var day = arr_data[4]; 
                var birthday = new Date(year+'/'+month+'/'+day); 
                var status = verifyBirthday(year,month,day,birthday);
                
                if(status!=false)
                {
                    S = (parseInt(idcard_array[0]) + parseInt(idcard_array[10])) * 7 + (parseInt(idcard_array[1]) + parseInt(idcard_array[11])) * 9 + (parseInt(idcard_array[2]) + parseInt(idcard_array[12])) * 10 + (parseInt(idcard_array[3]) + parseInt(idcard_array[13])) * 5 + (parseInt(idcard_array[4]) + parseInt(idcard_array[14])) * 8 + (parseInt(idcard_array[5]) + parseInt(idcard_array[15])) * 4 + (parseInt(idcard_array[6]) + parseInt(idcard_array[16])) * 2 + parseInt(idcard_array[7]) * 1 + parseInt(idcard_array[8]) * 6 + parseInt(idcard_array[9]) * 3 ;
                    Y = S % 11;
                    M = "F";
                    JYM = "10X98765432";
                    M = JYM.substr(Y,1);
                    if(!(M == idcard_array[17]))
                    {
                        alert(Errors[2]);
                        //document.form1.STAFF_CARD_NO.focus();
                        return (false);
                        var birth=idcard.substr(6,4)+"-"+idcard.substr(10,2)+"-"+idcard.substr(12,2);
                        document.form1.STAFF_BIRTH.value=birth;
                        var myDate = new Date();
                        var month = myDate.getMonth()+1;
                        var day = myDate.getDate();
                        var birth_day=idcard.substr(12,2);
                        var birth_month=idcard.substr(10,2);
                        var age=myDate.getFullYear()-idcard.substr(6,4);
                        if(birth_month<month || birth_month==month && birth_day<=day)
                        {
                            age++;
                        }
                        document.form1.STAFF_AGE.value=age-1;
                        var sex=parseInt(idcard.substr(16,1));
                        if (sex%2==1) //��
                            document.form1.STAFF_SEX.value="0";
                        else  //Ů
                            document.form1.STAFF_SEX.value="1";
                    }
                    else
                    {
                        var birth=idcard.substr(6,4)+"-"+idcard.substr(10,2)+"-"+idcard.substr(12,2);
                        
                        document.form1.STAFF_BIRTH.value=birth;
                        var myDate = new Date();
                        var month = myDate.getMonth()+1;
                        var day = myDate.getDate();
                        var birth_day=idcard.substr(12,2);
                        var birth_month=idcard.substr(10,2);
                        var age=myDate.getFullYear()-idcard.substr(6,4);
                        if(birth_month<month || birth_month==month && birth_day<=day)
                        {
                            age++;
                        }
                        document.form1.STAFF_AGE.value=age-1;
                        var sex=parseInt(idcard.substr(16,1));
                        if (sex%2==1) //��
                            document.form1.STAFF_SEX.value="0";
                        else  //Ů
                            document.form1.STAFF_SEX.value="1";
                    }
                }
                else
                {
                    alert(Errors[1]);
                    //document.form1.STAFF_CARD_NO.focus();
                    return (false);
                }
                break;
            default:
                alert(Errors[0]);
                //document.form1.STAFF_CARD_NO.focus();
                return (false);
                break;
        }
        //��֤���֤��Ψһ
        _get("check_idcard.php","idcard="+idcard+"&USER_ID=<?=$USER_ID?>", show_msg);
    }
}

function verifyBirthday(year,month,day,birthday)
{
    var now = new Date(); 
    var now_year = now.getFullYear(); 
    //�������Ƿ���� 
    if(birthday.getFullYear() == year && (birthday.getMonth() + 1) == month && birthday.getDate() == day) 
    {
        //�ж���ݵķ�Χ��3�굽100��֮��) 
        var time = now_year - year; 
        if(time >= 3 && time <= 100) 
        {
            return true; 
        }
        return false;
    }
    return false; 
}


function show(targetid,INFO_COUNT)
{
    if(document.getElementById)
    {
        var target1=document.getElementById(targetid);
        if(target1.style.display!="block")
            target1.style.display="block";
        else
            target1.style.display="none";
        
        var target2=document.getElementById("back_ground");
        if(target2.style.display!="block")
            target2.style.display="block";
        else
            target2.style.display="none";
        var bb=document.body;
        target2.style.width = bb.scrollWidth+"px";
        target2.style.height = bb.scrollHeight+"px";
    }
}
function InsertImage(src)
{
    AddImage2Editor('RESUME', src);
}
function delete_attach(ATTACHMENT_ID,ATTACHMENT_NAME)
{
    var msg = sprintf("<?=_("ȷ��Ҫɾ���ļ� '%s' ��")?>", ATTACHMENT_NAME);
    if(window.confirm(msg))
    {
        URL="delete_attach.php?USER_ID=<?=$USER_ID?>&ATTACHMENT_ID="+ATTACHMENT_ID+"&ATTACHMENT_NAME="+URLSpecialChars(ATTACHMENT_NAME);
        window.location=URL;
    }
}
function delete_photo()
{
    msg="<?=_("ȷ��Ҫɾ���ϴ�����Ƭ��")?>?";
    if(window.confirm(msg))
    {
        URL="delete_photo.php?USER_ID=<?=$USER_ID?>";
        window.location=URL;
    }
}
function view_item(USER_ID)
{
    URL="use_item.php?USER_ID="+USER_ID;
    myleft=(screen.availWidth-500)/2;
    window.open(URL,"items","height=360,width=500,status=0,toolbar=no,menubar=no,location=no,scrollbars=yes,top=150,left="+myleft+",resizable=yes");
}

function get_work_age(str)
{
    if("" == str)
    {
        document.form1.WORK_AGE.value = "";
        document.form1.WORK_AGE.readOnly = false;
        document.form1.WORK_AGE.className = "BigInput";
        return false;
    }

    var today= new Date();
    var month = today.getMonth()+1;
    var day = today.getDate();
    var start_day=str.substr(8,2);
    var start_month=str.substr(5,2);
    //var age=today.getFullYear()-(parseInt(str.substr(0,4)));
    if(today.getFullYear()>=parseInt(str.substr(0,4)))
    {
        if(start_month<month || (start_month==month && start_day<=day))
        {
            var age=today.getFullYear()-(parseInt(str.substr(0,4)));
        }
        else
        {
            var age=today.getFullYear()-(parseInt(str.substr(0,4)))-1;
        }
    }
    else
    {
        var age=0;
    }

    if(age > 0)
    {
        document.form1.WORK_AGE.readOnly=true;
        document.form1.WORK_AGE.className="BigStatic";
        document.form1.WORK_AGE.value= age;
    }
    else
    {
        document.form1.WORK_AGE.value = 0;
        document.form1.WORK_AGE.readOnly = false;
        document.form1.WORK_AGE.className = "BigInput";
    }
}

function get_job_age(str)
{
    if("" == str)
    {
        document.form1.JOB_AGE.value = "";
        document.form1.JOB_AGE.readOnly = false;
        document.form1.JOB_AGE.className = "BigInput";
        return false;
    }
    var today= new Date();
    var month = today.getMonth()+1;
    var day = today.getDate();
    var start_day=str.substr(8,2);
    var start_month=str.substr(5,2);
    if(today.getFullYear()>=parseInt(str.substr(0,4)))
    {
        if(start_month<month || (start_month==month && start_day<=day))
        {
            var age=today.getFullYear()-(parseInt(str.substr(0,4)));
        }
        else
        {
            var age=today.getFullYear()-(parseInt(str.substr(0,4)))-1;
        }
    }
    else
    {
        var age=0;
    }
    if(age > 0)
    {
        //document.form1.JOB_AGE.readOnly=true;
        //document.form1.JOB_AGE.className="BigStatic";
        document.form1.JOB_AGE.value= age;
    }
    else
    {
        document.form1.JOB_AGE.value = 0;
        //document.form1.JOB_AGE.readOnly = false;
        //document.form1.JOB_AGE.className = "BigInput";
    }
}

function check_no(str,user_id)
{
    if(str=="")
        return;
    _get("check_no.php","STAFF_NO="+str+"&USER_ID="+user_id, show_msg);
}

function open_pic(AVATAR)
{
    url=AVATAR;
    window.open(url,"<?=$STAFF_NAME?><?=_("��ͷ��")?>","toolbar=0,status=0,menubar=0,scrollbars=yes,resizable=1")
}

function show_msg(req)
{
    if(req.status==200)
    {
        if(req.responseText=="+OK")
        {
            return (true);
        }
        else if(req.responseText=="+OK_CARD")
        {
            get_animal_sign();
            return (true);
        }
        else if(req.responseText=="-ERR_CARD")
        {
            alert("<?=_("�ظ����֤��")?>");
            document.form1.STAFF_CARD_NO.focus();
        }   
        else if(req.responseText=="-ERRNO")
        {
            alert("<?=_("����ظ�")?>");
            document.form1.STAFF_NO.focus();
        }
    }
}

function checkDate()
{
    var birth=document.form1.STAFF_BIRTH.value;
    if("" == birth)
    {
        document.form1.STAFF_AGE.value = "";
        return false;
    }
    var myDate = new Date();
    var month = myDate.getMonth()+1;
    var day = myDate.getDate();
    var birth_day=birth.substr(8,2);
    var birth_month=birth.substr(5,2);
    var age=myDate.getFullYear()-birth.substr(0,4);
    if(birth_month<month || birth_month==month && birth_day<=day)
    {
        age++;
    }
    document.form1.STAFF_AGE.value = 0<age-1 ? age-1 : 0;
}
function get_animal_sign(){
    var IS_LUNAR = document.getElementById("IS_LUNAR").checked?1:0;
    var STAFF_BIRTH = document.getElementById("STAFF_BIRTH").value;
    if(STAFF_BIRTH!="" && STAFF_BIRTH!="0000-00-00"){
        jQuery.post("get_lunar.inc.php","STAFF_BIRTH="+STAFF_BIRTH+"&IS_LUNAR="+IS_LUNAR+"",function(date){
            if(date.length==5){
                document.getElementById("animal_id").value = date.substring(0,1);
                document.getElementById("sign_id").value = date.substring(2,6);
            }
        });
    }
    else
    {
        document.getElementById("animal_id").value = "";
        document.getElementById("sign_id").value = "";
    }
}
</script>

<body class="bodycolor" onLoad="form1.BYNAME.focus();">
<a name="bottom"></a>

<table border="0" width="770" cellspacing="0" cellpadding="3" class="small">
    <tr>
        <td>
            <img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_new.gif" align="absmiddle"><span class="big3"> <?=sprintf(_("��Ա������%s��"), substr(GetUserNameById($USER_ID),0,-1)==""?_("�û���ɾ"):substr(GetUserNameById($USER_ID),0,-1))?>- <?=$STATUS.$STATUS2?></span>&nbsp;&nbsp;
            <a href="javascript:show('other_info','<?=$INFO_COUNT?>');"><?=_("�����Ϣ")?></a>&nbsp;&nbsp;
            <a href="javascript:view_item('<?=$USER_ID?>')"><?=_("�鿴������Ʒ")?></a>
        </td>
    </tr>
</table>

<div id="other_info" name="other_info_name"style="display: none;clear: both;position:absolute;top:60px;left:60px;right:40px;border:solid 1px black;z-index:2;">
    <iframe ID="other_info_iframe" name="iframe_staff" frameborder=0 scrolling=no src="./other_info/?USER_ID=<?=urlencode($USER_ID)?>" width="100%" height="350"></iframe>
</div>

<form enctype="multipart/form-data" action="<?if($OPERATION==1) echo "add.php";else echo "update.php";?>" method="post" name="form1" id="form1">
<table class="TableBlock" width="770" align="center">
    <tr>
        <td nowrap class="TableHeader" colspan="6"><b>&nbsp;<?=_("������Ϣ")?></b></td>
    </tr>
    <tr>
        <td nowrap class="TableData" width="100">OA<?=_("�û�����")?></td>
        <td class="TableData" width="180" <?if(!($YES_OTHER==1 && $COUNT==0)) echo " colspan=4"; ?>>
            <input type="hidden" name="USER_ID" value="<?=$USER_ID?>">
            <input type="text" name="BYNAME" id="BYNAME" value="<?=$BYNAME?>" class="BigStatic" readonly>
        </td>
<?
if($YES_OTHER==1 && $COUNT==0)
{
?>
        <td nowrap class="TableData" width="100" ><?=_("�Ƿ������¼��")?></td>
        <td class="TableData"  width="200" colspan="2" >
            <input type="checkbox" name="YES_OR_NOT"><?=_("��")?>
            <span><font color="blue"><?=_("��ע����Ȩ���ɹ���Ա���ţ�")?></font></span>
        </td>
<?
}
?>
        <td class="TableData" align="center" rowspan="6" colspan="2">
<?
if($PHOTO_NAME=="")
    echo "<center>"._("������Ƭ")."</center>";
else
{
    $URL_ARRAY = attach_url_old('hrms_pic', urlencode($PHOTO_NAME));
    $AVATAR = $URL_ARRAY['view'];
?>
            <div class="avatar"><a href="javascript:open_pic('<?=$AVATAR?>')"><img src="<?=urldecode($AVATAR)?>" width=130></a></div>
<?
}
?>
        </td>
    </tr>
    <tr>
        <td nowrap class="TableData" width="100"><?=_("���ţ�")?></td>
        <td class="TableData">
            <input type="hidden" name="DEPT_ID" value="<?=$DEPT_ID?>">
            <input type="text" name="DEPT_NAME" value="<?=$DEPT_NAME?>" class=BigStatic size=20 maxlength=100 readonly>
<?
if($USER_DEPT_ID==0)
{
?>
            <a href="javascript:;" class="orgAdd" onClick="SelectDeptSingle('','DEPT_ID','DEPT_NAME')"><?=_("ѡ��")?></a>
<?
}
?>
        </td>
<?
$query9 = "SELECT PRIV_NAME from user_priv where USER_PRIV='$USER_PRIV'";
$cursor9= exequery(TD::conn(),$query9);
if($ROW9=mysql_fetch_array($cursor9))
    $PRIV_NAME=$ROW9["PRIV_NAME"];
?>
        <td nowrap class="TableData"> <?=_("��ɫ��")?></td>
        <td class="TableData" colspan=2 width=80%><?=$PRIV_NAME?></td>
    </tr>
    <tr>
        <td nowrap class="TableData" width="100"><?=_("��ţ�")?></td>
        <td class="TableData" width="180"><input type="text" name="STAFF_NO" value="<?=$STAFF_NO?>" class="BigInput" onBlur="check_no(this.value,'<?=$USER_ID?>')" ></td>
        <td nowrap class="TableData"><?=_("���ţ�")?></td>
        <td class="TableData" colspan="2"><input type="text" name="WORK_NO" value="<?=$WORK_NO?>" class="BigInput"></td>
    </tr>
    <tr>
        <td nowrap class="TableData"><?=_("������")?></td>
        <td class="TableData">
            <input type="text" name="STAFF_NAME" value="<?=$USER_NAME?>" class="BigStatic" readonly />
        </td>
        <td nowrap class="TableData"><?=_("��������")?></td>
        <td class="TableData" colspan="2"><input type="text" name="BEFORE_NAME" value="<?=$BEFORE_NAME?>" class="BigInput"></td>
    </tr>
    <tr>
        <td nowrap class="TableData"><?=_("Ӣ������")?></td>
        <td class="TableData" ><input type="text" name="STAFF_E_NAME" value="<?=$STAFF_E_NAME?>" class="BigInput" ></td>
        <td nowrap class="TableData"><?=_("�Ա�")?></td>
        <td class="TableData" colspan="2" title="<?=_("��д�����֤������ֱ������")?>">
            <select name="STAFF_SEX" class="BigSelect">
                <option value="0" <? if($STAFF_SEX=="0") echo "selected";?>><?=_("��")?></option>
                <option value="1" <? if($STAFF_SEX=="1") echo "selected";?>><?=_("Ů")?></option>
            </select>
        </td>
    </tr>
    <tr>
        <td nowrap class="TableData"><?=_("���֤�ţ�")?></td>
        <td class="TableData" >
            <input type="text" name="STAFF_CARD_NO" id="STAFF_CARD_NO" class="BigInput" value="<?=$STAFF_CARD_NO?>" onBlur="checkIdcard()">
        </td>
        <td nowrap class="TableData"><?=_("�������ڣ�")?></td>
        <td class="TableData" colspan="2" title="<?=_("��д�����֤������ֱ������")?>">
            <input type="text" name="STAFF_BIRTH" id="STAFF_BIRTH" size="10" maxlength="10" class="BigInput" value="<?=$STAFF_BIRTH=="0000-00-00"?"":$STAFF_BIRTH;?>" onClick="WdatePicker()" onBlur="checkDate()" onChange="get_animal_sign()"/>
            <input type="checkbox" name="IS_LUNAR" id="IS_LUNAR"  value="1" <?=$IS_LUNAR=="1"?"checked":""?>  onClick="get_animal_sign()">
            <label for="IS_LUNAR"><?=_("��ũ������")?></label>
        </td>
    </tr>
<?
$CUR_DATE = date("Y-m-d",time());
if($STAFF_BIRTH!="0000-00-00" && $STAFF_BIRTH!="")
{
    $agearray = explode("-",$STAFF_BIRTH);
    $cur = explode("-",$CUR_DATE);
    $year=$agearray[0];
    $STAFF_AGE=date("Y")-$year;
    if($cur[1] > $agearray[1] || $cur[1]==$agearray[1] && $cur[2]>=$agearray[2])
    {
        $STAFF_AGE++;
    }
}
else
{
    $STAFF_AGE="";
}

if($STAFF_AGE!="")
{
    $STAFF_AGE = $STAFF_AGE-1;
    $query10="update HR_STAFF_INFO set STAFF_AGE='$STAFF_AGE' where USER_ID='$USER_ID'";
    exequery(TD::conn(),$query10);
}
?>
<?
if($JOB_BEGINNING!="0000-00-00" && $JOB_BEGINNING!="")
{
    $agearray = explode("-",$JOB_BEGINNING);
    $cur      = explode("-",$CUR_DATE);
    $year     = $agearray[0];
    $month    = (int)$agearray[1];
    $day      = (int)$agearray[2];

    if(date("Y")>=$year)
    {
        if((int)date("m")>$month ||((int)date("m")==$month && (int)date("d")>=$day))
        {
            $WORK_AGE=date("Y")-$year;
        }
        else
        {
            $WORK_AGE=date("Y")-$year-1;
        }
    }
    else
    {
        $WORK_AGE=0;
    }
}
else
{
    $WORK_AGE="";
}

if($DATES_EMPLOYED!="0000-00-00" && $DATES_EMPLOYED!="")
{
    $agearray   = explode("-",$DATES_EMPLOYED);
    $cur        = explode("-",$CUR_DATE);
    $year       = $agearray[0];
    $month      = (int)$agearray[1];
    $day        = (int)$agearray[2];

    if(date("Y")>=$year)
    {
        if((int)date("m")>$month ||((int)date("m")==$month && (int)date("d")>=$day))
        {
            $JOB_AGE=date("Y")-$year;
        }
        else
        {
            $JOB_AGE=date("Y")-$year-1;
        }
    }
    else
    {
        $JOB_AGE=0;
    }
}
else
{
    $JOB_AGE="";
}
if($leave_by_seniority=="1")
{   
    $sql = "select leave_day from attend_leave_param where working_years <= '$JOB_AGE' order by working_years DESC";
    $result= exequery(TD::conn(),$sql);
    if($ROW=mysql_fetch_array($result))
    {
        $LEAVE_TYPE = $ROW['leave_day'];
    }
    else
    {
        $LEAVE_TYPE = 0;
    }
}
if($DATES_EMPLOYED=="" || $DATES_EMPLOYED=="0000-00-00" || $entry_reset_leave==0)
{
    
    $cur_year = date("Y",time());
    $annual_leave_days = 0;
    
    $begin_time = $cur_year."-01-01 00:00:01";
    $end_time   = $cur_year."-12-30 23:59:59";
    $query = "SELECT SUM(ANNUAL_LEAVE) from ATTEND_LEAVE where USER_ID='$USER_ID' and (ALLOW='1' or ALLOW='3') and LEAVE_DATE1 >='$begin_time' and LEAVE_DATE1 <='$end_time'";
    $cursor= exequery(TD::conn(),$query);
    if($ROW=mysql_fetch_array($cursor))
        $annual_leave_days=$ROW[0];
    if($annual_leave_days=="")
        $annual_leave_days="0";
}
else
{
    $str   = strtok($DATES_EMPLOYED,"-");
    $year  = $str;
    $str   = strtok("-");
    $month = $str;
    $str   = strtok(" ");
    $day   = $str;
    
    $cur_year  = date("Y",time());
    $cur_month = date("m",time());
    $cur_day   = date("d",time());
    $cur_time  = date("Y-m-d H:i:s",time());
    
    $annual_leave_days = 0;
    
    if((int)$cur_month>(int)$month || ((int)$cur_month==(int)$month && (int)$cur_day>(int)$day))
    {
        $begin_time = $cur_year."-".$month."-".$day." 00:00:01";
        $cur_years  = $cur_year+1;
        $end_time   = $cur_years."-".$month."-".$day." 00:00:01";;
        
    }else
    {
        $cur_years  = $cur_year-1;
        $begin_time = $cur_years."-".$month."-".$day." 00:00:01";
        $end_time   = $cur_year."-".$month."-".$day." 00:00:01";;
    }
    
    $query = "SELECT SUM(ANNUAL_LEAVE) from ATTEND_LEAVE where USER_ID='$USER_ID' and (ALLOW='1' or ALLOW='3') and LEAVE_DATE1 >='$begin_time' and LEAVE_DATE1 <='$end_time'";
    $cursor= exequery(TD::conn(),$query);
    if($ROW=mysql_fetch_array($cursor))
        $annual_leave_days=$ROW[0];
    if($annual_leave_days=="")
        $annual_leave_days="0";
}

//���ʣ��ʱ��

$annual_leaves = number_format(($LEAVE_TYPE-$annual_leave_days), 1, '.', ' ');
if($annual_leaves < 0)
    $annual_leaves = 0;
//�����˰�����������
/*if($leave_by_seniority=='1' && $entry_reset_leave=="1")
{   
    $sql = "select leave_day from attend_leave_param where working_years > '$JOB_AGE' order by working_years asc";
    $result= exequery(TD::conn(),$sql);
    if($ROW=mysql_fetch_array($result))
    {
        $LEAVE_TYPE = $ROW['leave_day'];
    }
}
//--- �������ݼ�ʹ�� ---
$CUR_DATE = date("Y-m-d",time());
$BEGIN_TIME = substr($CUR_DATE,0,4)."-01-01 00:00:01";
$END_TIME = substr($CUR_DATE,0,4)."-12-30 23:59:59";
$query = "SELECT SUM(ANNUAL_LEAVE) from ATTEND_LEAVE where USER_ID='$USER_ID' and (ALLOW='1' or ALLOW='3') and LEAVE_DATE1 >='$BEGIN_TIME' and LEAVE_DATE1 <='$END_TIME'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
    $ANNUAL_LEAVE_DAYS=$ROW[0];
if($ANNUAL_LEAVE_DAYS=="")
    $ANNUAL_LEAVE_DAYS="0";

$ANNUAL_LEAVE_LEFT=number_format(($LEAVE_TYPE-$ANNUAL_LEAVE_DAYS), 1, '.', ' ');*/
?>
    <tr>
        <td nowrap class="TableData"><?=_("���䣺")?></td>
        <td class="TableData" title="<?=_("��д�����֤������ֱ������")?>"><input type="text" name="STAFF_AGE" size="4" class="BigInput" value="<?=$STAFF_AGE?>"><?=_("��")?></td>
        <td nowrap class="TableData" width="100"><?=_("���ݼ٣�")?></td>
        <td class="TableData" colspan="3">
            <input type="text" name="LEAVE_TYPE" size="4" maxlength="4" value="<?=$LEAVE_TYPE?>" <?if($leave_by_seniority=='1'){?>readonly class="BigStatic"<?}else{?>class="BigInput"<?}?>><?=sprintf(_("�죬������������%s�죬ʣ��%s��"), $annual_leave_days, $annual_leaves)?>
        </td>
    </tr>
    <tr>
        <td nowrap class="TableData"><?=_("���᣺")?></td>
        <td class="TableData" colspan="5" nowrap>
            <select name="STAFF_NATIVE_PLACE" class="BigSelect" title="<?=_("������ڡ�������Դ���á�->��HRMS�������á�ģ�����á�")?>">
                <option value="" <? if($STAFF_NATIVE_PLACE=="") echo "selected";?>></option>
                <?=hrms_code_list("AREA",$STAFF_NATIVE_PLACE);?>
            </select>
            <input type="text" name="STAFF_NATIVE_PLACE2" style="width:326px;height: 24px;" value="<?=$STAFF_NATIVE_PLACE2?>" >
        </td>
    </tr>
    <tr>
        <td nowrap class="TableData"><?=_("��Ф")?></td>
        <td class="TableData" title="<?=_("��Ф")?>">
            <input type="text" value="<?=$ANIMAL?>" id="animal_id" class="BigStatic" readonly />
        </td>
        <td nowrap class="TableData"><?=_("����")?></td>
        <td class="TableData" colspan="3" title="<?=_("����")?>">
            <input type="text" value="<?=$SIGN?>" id="sign_id" class="BigStatic" readonly />
        </td>
    </tr>
    <tr>
        <td nowrap class="TableData" width="100"><?=_("Ѫ��")?></td>
        <td class="TableData" >
            <select name="BLOOD_TYPE" class="BigSelect">
                <option value=""  <? if($BLOOD_TYPE=="")   echo "selected";?>></option>
                <option value="A" <? if($BLOOD_TYPE=="A")  echo "selected";?>><?=_("A")?></option>
                <option value="B" <? if($BLOOD_TYPE=="B")  echo "selected";?>><?=_("B")?></option>
                <option value="O" <? if($BLOOD_TYPE=="O")  echo "selected";?>><?=_("O")?></option>
                <option value="AB" <? if($BLOOD_TYPE=="AB")echo "selected";?>><?=_("AB")?></option>
            </select>
        </td>
        <td nowrap class="TableData"><?=_("���壺")?></td>
        <td class="TableData" colspan="3"><input type="text" name="STAFF_NATIONALITY" class="BigInput" value="<?=$STAFF_NATIONALITY?>"></td>
    </tr>
    <tr>
        <td nowrap class="TableData"><?=_("����״����")?></td>
        <td class="TableData" width="180">
            <select name="STAFF_MARITAL_STATUS" class="BigSelect">
                <option value="" <? if($STAFF_MARITAL_STATUS=="") echo "selected";?>></option>
                <option value="0" <? if($STAFF_MARITAL_STATUS=="0") echo "selected";?>><?=_("δ��")?></option>
                <option value="1" <? if($STAFF_MARITAL_STATUS=="1") echo "selected";?>><?=_("�ѻ�")?></option>
                <option value="2" <? if($STAFF_MARITAL_STATUS=="2") echo "selected";?>><?=_("����")?></option>
                <option value="3" <? if($STAFF_MARITAL_STATUS=="3") echo "selected";?>><?=_("ɥż")?></option>
            </select>
        </td>
        <td nowrap class="TableData" width="100"><?=_("����״����")?></td>
        <td class="TableData" colspan="3"><input type="text" name="STAFF_HEALTH" value="<?=$STAFF_HEALTH?>" class="BigInput"></td>
    </tr>
    <tr>
        <td nowrap class="TableData"><?=_("������ò��")?></td>
        <td class="TableData" width="180">
            <select name="STAFF_POLITICAL_STATUS" class="BigSelect" title="<?=_("������ò���ڡ�������Դ���á�->��HRMS�������á�ģ�����á�")?>">
                <option value="" <? if($STAFF_POLITICAL_STATUS=="") echo "selected";?>></option>
                <?=hrms_code_list("STAFF_POLITICAL_STATUS",$STAFF_POLITICAL_STATUS); ?>
            </select>
        </td>
        <td nowrap class="TableData" width="100"><?=_("�뵳ʱ�䣺")?></td>
        <td class="TableData"  colspan="3">
            <input type="text" name="JOIN_PARTY_TIME" size="10" maxlength="10" class="BigInput" value="<?=$JOIN_PARTY_TIME=="0000-00-00"?"":$JOIN_PARTY_TIME;?>" onClick="WdatePicker()"/>
        </td>
    </tr>
    <tr>
        <td nowrap class="TableData"><?=_("�������")?></td>
        <td class="TableData" >
            <select name="STAFF_TYPE" class="BigSelect" title="<?=_("���������ڡ�������Դ���á�->��HRMS�������á�ģ�����á�")?>">
                <option value="" <? if($STAFF_TYPE=="") echo "selected";?>></option>
                <?=hrms_code_list("HR_STAFF_TYPE",$STAFF_TYPE);?>
            </select>
        </td>
        <td nowrap class="TableData" width="100"><?=_("�������ڵأ�")?></td>
        <td class="TableData"  width="180" colspan="3"><input type="text" name="STAFF_DOMICILE_PLACE" value="<?=$STAFF_DOMICILE_PLACE?>"  size="40" class="BigInput"></td>
    </tr>
<?
if($PHOTO_NAME=="")
    $PHOTO_STR=_("��Ƭ�ϴ���");
else
    $PHOTO_STR=_("��Ƭ���ģ�");
?>
    <tr>
        <td nowrap class="TableData" width="100"><?=$PHOTO_STR?></td>
        <td class="TableData"  width="180" colspan="5">
            <input type="file" name="ATTACHMENT" id="cursor_file" size="40"  class="BigInput" title="<?=_("ѡ�񸽼��ļ�")?>" >&nbsp;<span>��Ƭ�ļ�ֻ����gif��png��jpg��bmp��ʽ!</span>
<?
if($PHOTO_NAME!="")
{
?>
            <br><a href=#this onClick="delete_photo();"><?=_("ɾ����Ƭ")?></a>
<?
}
?>
        </td>
    </tr>
    <tr>
        <td nowrap class="TableHeader" colspan="6"><b>&nbsp;<?=_("ְλ�������ϵ��ʽ��")?></b></td>
    </tr>
    <tr>
        <td nowrap class="TableData" width="100"><?=_("���֣�")?></td>
        <td class="TableData"  width="180"><input type="text" name="WORK_TYPE" class="BigInput" value="<?=$WORK_TYPE?>"></td>
        <td nowrap class="TableData" width="100"><?=_("��������")?></td>
        <td class="TableData"  width="180"><input type="text" name="ADMINISTRATION_LEVEL" class="BigInput" value="<?=$ADMINISTRATION_LEVEL?>"></td>
        <td nowrap class="TableData" width="100"><?=_("Ա�����ͣ�")?></td>
        <td class="TableData">
            <select name="STAFF_OCCUPATION" class="BigSelect" title="<?=_("Ա�����Ϳ��ڡ�������Դ���á�->��HRMS�������á�ģ�����á�")?>">
                <option value="" <? if($STAFF_OCCUPATION=="") echo "selected";?>></option>
                <?=hrms_code_list("STAFF_OCCUPATION",$STAFF_OCCUPATION);?>
            </select>
        </td>
    </tr>
    <tr>
        <td nowrap class="TableData" width="100"><?=_("ְ��")?></td>
        <td class="TableData"  width="180"><input type="text" name="JOB_POSITION" class="BigInput" value="<?=$JOB_POSITION?>"></td>
        <td nowrap class="TableData" width="100"><?=_("ְ�ƣ�")?></td>
        <td class="TableData"  width="180">
            <select name="PRESENT_POSITION" class="BigSelect" title="<?=_("ְ�ƿ��ڡ�������Դ���á�->��HRMS�������á�ģ�����á�")?>">
                <option value="" <? if($PRESENT_POSITION=="") echo "selected";?>></option>
                <?=hrms_code_list("PRESENT_POSITION",$PRESENT_POSITION);?>
            </select>
        </td>
        <td nowrap class="TableData" width="100"><?=_("��ְʱ�䣺")?></td>
        <td class="TableData"  width="180">
            <input type="text" name="DATES_EMPLOYED" size="10" maxlength="10" class="BigInput" value="<?=$DATES_EMPLOYED=="0000-00-00"?"":$DATES_EMPLOYED;?>" onClick="WdatePicker()" onChange="get_job_age(this.value)"/>
        </td>
    </tr>
    <tr>
        <td nowrap class="TableData" width="100"><?=_("ְ�Ƽ���")?></td>
        <td class="TableData">
            <select name="WORK_LEVEL" class="BigSelect" title="<?=_("ְ�Ƽ�����ڡ�������Դ���á�->��HRMS�������á�ģ�����á�")?>">
                <option value="" <? if($WORK_LEVEL=="") echo "selected";?>></option>
                <?=hrms_code_list("WORK_LEVEL",$WORK_LEVEL);?>
            </select>
        </td>
        <td nowrap class="TableData" width="100"><?=_("ӦƸ��λ��")?></td>
        <td nowrap class="TableData">
            <select name="WORK_JOB" class="BigSelect">
                <option value=""></option>
                <?=hrms_code_list("POOL_POSITION","$WORK_JOB");?>
            </select>
        </td>
<?
$query11 = "SELECT * from USER_EXT where USER_ID='$USER_ID'";
$cursor11= exequery(TD::conn(),$query11);
if($ROW11=mysql_fetch_array($cursor11))
{
    $DUTY_TYPE=$ROW11['DUTY_TYPE'];
}
?>
        <td nowrap class="TableData" width="100"><?=_("�����Ű����ͣ�")?></td>
        <td nowrap class="TableData">
            <select name="DUTY_TYPE" class="BigSelect">
<?
$query = "SELECT * from ATTEND_CONFIG order by DUTY_TYPE";
$cursor= exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
    $DUTY_TYPE1=$ROW["DUTY_TYPE"];
    $DUTY_NAME=$ROW["DUTY_NAME"];
?>
                <option value="<?=$DUTY_TYPE1?>" <? if($DUTY_TYPE1==$DUTY_TYPE)echo "selected";?>><?=$DUTY_NAME?></option>
<?
}
?>
                <option value="99"  <? if($DUTY_TYPE1=="99")echo "selected";?>><?=_("�ְ���")?></option>
            </select>
        </td>
    </tr>
    <tr>

        <td nowrap class="TableData" width="100"><?=_("����λ���䣺")?></td>
        <td class="TableData"  width="180"><input type="text" name="JOB_AGE" class="BigStatic" value="<?=$JOB_AGE?>"  readonly></td>
        <td nowrap class="TableData" width="100"><?=_("��нʱ�䣺")?></td>
        <td class="TableData"  width="180">
            <input type="text" name="BEGIN_SALSRY_TIME" size="10" maxlength="10" class="BigInput" value="<?=$BEGIN_SALSRY_TIME=="0000-00-00"?"":$BEGIN_SALSRY_TIME;?>" onClick="WdatePicker()"/>
        </td>
        <td nowrap class="TableData" width="100"><?=_("��ְ״̬��")?></td>
        <td class="TableData"  width="180">
            <select name="WORK_STATUS" class="BigSelect" title="<?=_("��ְ״̬���ڡ�������Դ���á�->��HRMS�������á�ģ�����á�")?>">
                <option value="" <? if($WORK_STATUS=="") echo "selected";?>></option>
                <?=hrms_code_list("WORK_STATUS",$WORK_STATUS);?>
            </select>
        </td>
    </tr>
    <tr>

        <td nowrap class="TableData" width="100"><?=_("�ܹ��䣺")?></td>
        <td class="TableData"  width="180"><input type="text" name="WORK_AGE" value="<?=$WORK_AGE?>" class="BigInput"></td>
        <td nowrap class="TableData" width="100"><?=_("�μӹ���ʱ�䣺")?></td>
        <td class="TableData"  width="180">
            <input type="text" name="JOB_BEGINNING" size="10" maxlength="10" class="BigInput" value="<?=$JOB_BEGINNING=="0000-00-00"?"":$JOB_BEGINNING;?>" onClick="WdatePicker()" onChange="get_work_age(this.value)"/>
        </td>
        <td nowrap class="TableData" width="100"><?=_("��ϵ�绰��")?></td>
        <td class="TableData"  width="180"><input type="text" name="STAFF_PHONE" class="BigInput" value="<?=$STAFF_PHONE?>"></td>
    </tr>
    <tr>
        <td nowrap class="TableData" width="100"><?=_("�ֻ����룺")?></td>
        <td class="TableData"  width="180"><input type="text" name="STAFF_MOBILE" class="BigInput" value="<?=$STAFF_MOBILE?>"></td>
        <td nowrap class="TableData" width="100"><?=_("MSN��")?></td>
        <td class="TableData"  width="180" colspan="3"><input type="text" name="STAFF_MSN" class="BigInput" value="<?=$STAFF_MSN?>"></td>
    </tr>
    <tr>
        <td nowrap class="TableData" width="100"><?=_("�����ʼ���")?></td>
        <td class="TableData"  width="180"><input type="text" name="STAFF_EMAIL" class="BigInput validate[required,custom[email]]" data-prompt-position="centerRight:5,-4" value="<?=$STAFF_EMAIL?>" data-prompt-position="centerRight:15,-4" ></td>
        <td nowrap class="TableData" width="100"><?=_("��ͥ��ַ��")?></td>
        <td class="TableData"  width="180" colspan="3"><input type="text" name="HOME_ADDRESS" size="50" value="<?=$HOME_ADDRESS?>" class="BigInput"></td>
    </tr>
    <tr>
        <td nowrap class="TableData" width="100"><?=_("QQ��")?></td>
        <td class="TableData"  width="180"><input type="text" name="STAFF_QQ" class="BigInput validate[custom[number],maxSize[11],minSize[5]]" data-prompt-position="centerRight:5,-4" value="<?=$STAFF_QQ?>"></td>
        <td nowrap class="TableData" width="100"><?=_("������ϵ��ʽ��")?></td>
        <td class="TableData"  width="180" colspan="3"><input type="text" name="OTHER_CONTACT" size="50" value="<?=$OTHER_CONTACT?>" class="BigInput"></td>
    </tr>
    <tr>
        <td nowrap class="TableData"> <?=_("�Ƿ�Ϊר�ң�")?></td>
        <td class="TableData">
            <input type="radio" name="batch" value="1" <?if($IS_EXPERTS==1) echo "checked"?>/><label for="1" style="margin-right: 10px;"><?=_("��")?></label>
            <input type="radio" name="batch" value="0" <?if($IS_EXPERTS!=1) echo "checked"?>/><label for="0"><?=_("��")?>
        </td>
        <td nowrap class="TableData"> <?=_("�о�����")?></td>
        <td class="TableData"  width="180" colspan="3">
            <textarea rows="3" cols="30" name="EXPERTS_INFO" id="EXPERTS_INFO"><?=$EXPERTS_INFO?></textarea>
        </td>
    </tr>
    <tr>
        <td nowrap class="TableData"> <?=_("����ְ��")?></td>
        <td class="TableData">
            <textarea rows="3" cols="30" name="PART_TIME" id="PART_TIME"><?=$PART_TIME?></textarea>
        </td>
        <td nowrap class="TableData"> <?=_("�ɹ����ܣ�")?></td>
        <td class="TableData"  width="180" colspan="3">
            <textarea rows="3" cols="30" name="RESEARCH_RESULTS" id="RESEARCH_RESULTS"><?=$RESEARCH_RESULTS?></textarea>
        </td>
    </tr>
    <tr>
        <td nowrap class="TableData"> <?=_("ֱ���ϼ���")?></td>
        <td nowrap class="TableData">
            <input type="hidden" name="COPY_TO_ID1" value="<?=$DIRECTLY_SUPERIOR?>">
            <textarea cols=21 name="COPY_TO_NAME1" rows=2 class="BigStatic" wrap="yes" readonly><?=$DIRECTLY_SUPERIOR_NAME?></textarea>
            <a href="javascript:;" class="orgAdd" onClick="SelectUser('60','','COPY_TO_ID1', 'COPY_TO_NAME1')"><?=_("ѡ��")?></a>
            <a href="javascript:;" class="orgClear" onClick="ClearUser('COPY_TO_ID1', 'COPY_TO_NAME1')"><?=_("���")?></a>
        </td>
        <td nowrap class="TableData"> <?=_("ֱ���¼���")?></td>
        <td nowrap class="TableData"width="180" colspan="3">
            <input type="hidden" name="COPY_TO_ID" value="<?=$DIRECTLY_UNDER?>">
            <textarea cols=21 name="COPY_TO_NAME" rows=2 class="BigStatic" wrap="yes" readonly><?=$DIRECTLY_UNDER_NAME?></textarea>
            <a href="javascript:;" class="orgAdd" onClick="SelectUser('60','','COPY_TO_ID', 'COPY_TO_NAME')"><?=_("ѡ��")?></a>
            <a href="javascript:;" class="orgClear" onClick="ClearUser('COPY_TO_ID', 'COPY_TO_NAME')"><?=_("���")?></a>
        </td>
    </tr>
    <tr>
        <td nowrap class="TableData" width="100"><?=_("������1��")?></td>
        <td class="TableData"  width="180"><input type="text" name="BANK1" class="BigInput" value="<?=$BANK1?>"></td>
        <td nowrap class="TableData" width="100"><?=_("�˻�1��")?></td>
        <td class="TableData"  width="180" colspan="3"><input type="text" name="BANK_ACCOUNT1" size="50" value="<?=$BANK_ACCOUNT1?>" class="BigInput"></td>
    </tr>
    <tr>
        <td nowrap class="TableData" width="100"><?=_("������2��")?></td>
        <td class="TableData"  width="180"><input type="text" name="BANK2" class="BigInput" value="<?=$BANK2?>"></td>
        <td nowrap class="TableData" width="100"><?=_("�˻�2��")?></td>
        <td class="TableData"  width="180" colspan="3"><input type="text" name="BANK_ACCOUNT2" size="50" value="<?=$BANK_ACCOUNT2?>" class="BigInput"></td>
    </tr>
    <tr>
        <td nowrap class="TableHeader" colspan="6"><b>&nbsp;<?=_("����������")?></b></td>
    </tr>
    <tr>
        <td nowrap class="TableData" width="100"><?=_("ѧ����")?></td>
        <td class="TableData"  width="180">
            <select name="STAFF_HIGHEST_SCHOOL" class="BigSelect" title="<?=_("ѧ�����ڡ�������Դ���á�->��HRMS�������á�ģ�����á�")?>">
                <option value="" <? if($STAFF_HIGHEST_SCHOOL=="") echo "selected";?>></option>
                <?=hrms_code_list("STAFF_HIGHEST_SCHOOL",$STAFF_HIGHEST_SCHOOL);?>
            </select>
        </td>
        <td nowrap class="TableData" width="100"><?=_("ѧλ��")?></td>
        <td class="TableData"  width="180">
            <select name="STAFF_HIGHEST_DEGREE" class="BigSelect" title="<?=_("ѧλ���ڡ�������Դ���á�->��HRMS�������á�ģ�����á�")?>">
                <option value="" <? if($STAFF_HIGHEST_DEGREE=="") echo "selected";?>></option>
                <?=hrms_code_list("EMPLOYEE_HIGHEST_DEGREE",$STAFF_HIGHEST_DEGREE);?>
            </select>
        </td>
        <td nowrap class="TableData" width="100"><?=_("��ҵʱ�䣺")?></td>
        <td class="TableData"  width="180">
            <input type="text" name="GRADUATION_DATE" size="10" maxlength="10" class="BigInput" value="<?=$GRADUATION_DATE=="0000-00-00"?"":$GRADUATION_DATE;?>" onClick="WdatePicker()"/>
        </td>
    </tr>
    <tr>
        <td nowrap class="TableData" width="100"><?=_("��ҵѧУ��")?></td>
        <td class="TableData"  width="180"><input type="text" name="GRADUATION_SCHOOL" value="<?=$GRADUATION_SCHOOL?>" class="BigInput"></td>
        <td nowrap class="TableData" width="100"><?=_("רҵ��")?></td>
        <td class="TableData"  width="180"><input type="text" name="STAFF_MAJOR" value="<?=$STAFF_MAJOR?>" class="BigInput"></td>
        <td nowrap class="TableData" width="100"><?=_("�����ˮƽ��")?></td>
        <td class="TableData"  width="180"><input type="text" name="COMPUTER_LEVEL" value="<?=$COMPUTER_LEVEL?>" class="BigInput"></td>
    </tr>
    <tr>
        <td nowrap class="TableData" width="100"><?=_("��������1��")?></td>
        <td class="TableData"  width="180"><input type="text" name="FOREIGN_LANGUAGE1" value="<?=$FOREIGN_LANGUAGE1?>" class="BigInput"></td>
        <td nowrap class="TableData" width="100"><?=_("��������2��")?></td>
        <td class="TableData"  width="180"><input type="text" name="FOREIGN_LANGUAGE2" value="<?=$FOREIGN_LANGUAGE2?>" class="BigInput"></td>
        <td nowrap class="TableData" width="100"><?=_("��������3��")?></td>
        <td class="TableData"  width="180"><input type="text" name="FOREIGN_LANGUAGE3" value="<?=$FOREIGN_LANGUAGE3?>" class="BigInput"></td>
    </tr>
    <tr>
        <td nowrap class="TableData" width="100"><?=_("����ˮƽ1��")?></td>
        <td class="TableData"  width="180"><input type="text" name="FOREIGN_LEVEL1" value="<?=$FOREIGN_LEVEL1?>" class="BigInput"></td>
        <td nowrap class="TableData" width="100"><?=_("����ˮƽ2��")?></td>
        <td class="TableData"  width="180"><input type="text" name="FOREIGN_LEVEL2" value="<?=$FOREIGN_LEVEL2?>" class="BigInput"></td>
        <td nowrap class="TableData" width="100"><?=_("����ˮƽ3��")?></td>
        <td class="TableData"  width="180"><input type="text" name="FOREIGN_LEVEL3" value="<?=$FOREIGN_LEVEL3?>" class="BigInput"></td>
    </tr>
    <tr>
        <td nowrap class="TableData" width="100"><?=_("�س���")?></td>
        <td class="TableData"  width="180" colspan="5"><input type="text" name="STAFF_SKILLS" value="<?=$STAFF_SKILLS?>" size="80" class="BigInput"></td>
    </tr>
    <tr>
        <td nowrap class="TableHeader" colspan="3"><b>&nbsp;<?=_("ְ�������")?></b></td>
        <td nowrap class="TableHeader" colspan="3"><b>&nbsp;<?=_("������¼��")?></b></td>
    </tr>
    <tr>
        <td class="TableData" colspan="3"><textarea cols="45" name="CERTIFICATE" rows="3" class="BigInput" wrap="on"><?=$CERTIFICATE?></textarea></td>
        <td class="TableData" colspan="3"><textarea cols="45" name="SURETY" rows="3" class="BigInput" wrap="on"><?=$SURETY?></textarea></td>
    </tr>
    <tr>
        <td nowrap class="TableHeader" colspan="3"><b>&nbsp;<?=_("�籣���������")?></b></td>
        <td nowrap class="TableHeader" colspan="3"><b>&nbsp;<?=_("����¼��")?></b></td>
    </tr>
    <tr>
        <td class="TableData" colspan="3"><textarea cols="45" name="INSURE" rows="3" class="BigInput" wrap="on"><?=$INSURE?></textarea></td>
        <td class="TableData" colspan="3"><textarea cols="45" name="BODY_EXAMIM" rows="3" class="BigInput" wrap="on"><?=$BODY_EXAMIM?></textarea></td>
    </tr>
    <tr>
        <td colspan="6">
            <?=get_field_table(get_field_html("HR_STAFF_INFO","$USER_ID"))?>
        </td>
    </tr>
    <tr>
        <td nowrap align="left" colspan="6" class="TableHeader"><?=_("��ע��")?></td>
    </tr>
    <tr>
        <td nowrap class="TableData" colspan="6"><textarea name="REMARK" cols="95" rows="3" class="BigInput" value=""><?=$REMARK?></textarea></td>
    </tr>
    <tr>
        <td nowrap class="TableHeader" colspan="6"><b>&nbsp;<?=_("�����ĵ���")?></b></td>
    </tr>
    <tr>
        <td class="TableData" colspan="6"><?=attach_link($ATTACHMENT_ID,$ATTACHMENT_NAME,1,1,1,1,1,1,1,1);?></td>
    </tr>
    <tr height="25">
        <td nowrap class="TableData"><?=_("����ѡ��")?></td>
        <td class="TableData" colspan="6">
            <script>ShowAddFile('1');</script>
        </td>
    </tr>
    <tr>
        <td nowrap class="TableHeader" colspan="6"><?=_("������")?></td>
    </tr>
    <tr>
        <td class="TableData" colspan="6">
<?
$editor = new Editor('RESUME') ;
$editor->Height = '450';
$editor->Config["StartupFocus"]="false";
$editor->Config = array("EditorAreaStyles" => "body{font-size:20pt;}","model_type" => "14");
//$editor->Config = array('model_type' => '14') ;
$editor->Value = $RESUME ;
$editor->Create() ;
?>
        </td>
    </tr>
    <tr align="center" class="TableControl">
        <td colspan=6 nowrap>
            <input type="button" value="<?=_("����")?>" class="BigButton" onClick="CheckForm();">&nbsp;
<?
if($USER_DEPT_ID=='0' || $USER_DEPT_ID=='')
{
?>
    <input type="button" value="<?=_("��ְ")?>" class="BigButton" onClick="location='../staff_reinstatement/new.php?USER_ID=<?=$USER_ID?>';">
<?
}
else
{
?>
    <input type="button" value="<?=_("��ְ")?>" class="BigButton" onClick="location='../staff_leave/new.php?USER_ID=<?=$USER_ID?>';">
<?
}
?>
        </td>
    </tr>
</table>
<input type="hidden" value="<?=$ATTACHMENT_ID?>" name="ATTACHMENT_ID_OLD">
<input type="hidden" value="<?=$ATTACHMENT_NAME?>" name="ATTACHMENT_NAME_OLD">
<div id="back_ground" style="display: none;position:absolute;TOP: 0px; left:0px;right:0px; z-index:1; width:100%; height:100%;background: #000; filter: alpha(opacity=50); -moz-opacity:0.5;opacity:0.5;"></div>
</form>
<a id="bottom1" href="#bottom"></a>
</body>
</html>
<script type="text/javascript">
jQuery("#STAFF_CARD_NO").blur(function(){
    var USER_ID=jQuery("input[name=BYNAME]").val();
    var STAFF_CARD_NO=jQuery("#STAFF_CARD_NO").val();
    if(STAFF_CARD_NO !="")
    {
        jQuery.post("chek_card.php",
        {
            USER_ID:USER_ID,
            STAFF_CARD_NO:STAFF_CARD_NO
        },
        function(data){
            if(data==1)
            {
                jQuery("#chek_card_id").text("���û�Ϊ�������û�");
            }
            else
            {
                jQuery("#chek_card_id").text("");
            }
        })
    }
})

var obj_a = document.getElementById("bottom1");
if(document.all) //for IE
    obj_a.click();
else if(document.createEvent)   //for FF
{
    var ev = document.createEvent('HTMLEvents');
    ev.initEvent('click', false, true);
    obj_a.dispatchEvent(ev);
}
</script>