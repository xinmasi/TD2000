<?
include_once("inc/auth.inc.php");
include_once("inc/editor.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
include_once("inc/check_type.php");
include_once("inc/utility_field.php");
include_once("inc/utility_file.php");
include_once("inc/lunar.class.php");

$HTML_PAGE_TITLE = _("�½����µ���");
include_once("inc/header.inc.php");

$query = "SELECT * from HR_RECRUIT_POOL where EXPERT_ID='$EXPERT_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
    $POOL_COUNT++;
    
    $EXPERT_ID                      = $ROW["EXPERT_ID"];
    $DEPT_ID                        = $ROW["DEPT_ID"];	
    $PLAN_NO                        = $ROW["PLAN_NO"];   
    $POSITION                       = $ROW["POSITION"];
    $STAFF_NAME                     = $ROW["EMPLOYEE_NAME"];
    $STAFF_SEX                      = $ROW["EMPLOYEE_SEX"];
    $STAFF_BIRTH                    = $ROW["EMPLOYEE_BIRTH"];
    $STAFF_NATIVE_PLACE             = $ROW["EMPLOYEE_NATIVE_PLACE"];
    $STAFF_NATIVE_PLACE2            = $ROW["EMPLOYEE_NATIVE_PLACE2"];
    $STAFF_DOMICILE_PLACE           = $ROW["EMPLOYEE_DOMICILE_PLACE"];
    $STAFF_NATIONALITY              = $ROW["EMPLOYEE_NATIONALITY"];
    $STAFF_MARITAL_STATUS           = $ROW["EMPLOYEE_MARITAL_STATUS"];
    $STAFF_POLITICAL_STATUS         = $ROW["EMPLOYEE_POLITICAL_STATUS"];
    $STAFF_PHONE                    = $ROW["EMPLOYEE_PHONE"];
    $STAFF_EMAIL                    = $ROW["EMPLOYEE_EMAIL"];
    $JOB_BEGINNING                  = $ROW["JOB_BEGINNING"];
    $STAFF_HEALTH                   = $ROW["EMPLOYEE_HEALTH"];   
    $STAFF_HIGHEST_SCHOOL           = $ROW["EMPLOYEE_HIGHEST_SCHOOL"];
    $STAFF_HIGHEST_DEGREE           = $ROW["EMPLOYEE_HIGHEST_DEGREE"];
    $GRADUATION_DATE                = $ROW["GRADUATION_DATE"];
    $GRADUATION_SCHOOL              = $ROW["GRADUATION_SCHOOL"];
    $STAFF_MAJOR                    = $ROW["EMPLOYEE_MAJOR"];
    $COMPUTER_LEVEL                 = $ROW["COMPUTER_LEVEL"];
    $FOREIGN_LANGUAGE1              = $ROW["FOREIGN_LANGUAGE1"];
    $FOREIGN_LEVEL1                 = $ROW["FOREIGN_LEVEL1"];
    $FOREIGN_LANGUAGE2              = $ROW["FOREIGN_LANGUAGE2"];
    $FOREIGN_LEVEL2                 = $ROW["FOREIGN_LEVEL2"];
    $FOREIGN_LANGUAGE3              = $ROW["FOREIGN_LANGUAGE3"];
    $FOREIGN_LEVEL3                 = $ROW["FOREIGN_LEVEL3"];	
    $STAFF_SKILLS                   = $ROW["EMPLOYEE_SKILLS"];   
    $RESUME                         = $ROW["RESUME"];
    $JOB_INTENSION                  = $ROW["JOB_INTENSION"];
    $CAREER_SKILLS                  = $ROW["CAREER_SKILLS"];
    $WORK_EXPERIENCE                = $ROW["WORK_EXPERIENCE"];
    $PROJECT_EXPERIENCE             = $ROW["PROJECT_EXPERIENCE"];
    $RESIDENCE_PLACE                = $ROW["RESIDENCE_PLACE"];
    $JOB_CATEGORY                   = $ROW["JOB_CATEGORY"];
    $JOB_INDUSTRY                   = $ROW["JOB_INDUSTRY"];
    $WORK_CITY                      = $ROW["WORK_CITY"];
    $EXPECTED_SALARY                = $ROW["EXPECTED_SALARY"];
    $START_WORKING                  = $ROW["START_WORKING"];
    $REMARK                         = $ROW["REMARK"];
    $ATTACHMENT_ID                  = $ROW["ATTACHMENT_ID"];
    $ATTACHMENT_NAME                = $ROW["ATTACHMENT_NAME"];
    
    $ADD_TIME                       = $ROW["ADD_TIME"];
    $USERDEF11                      = $ROW["USERDEF1"];
    $USERDEF21                      = $ROW["USERDEF2"];
    $USERDEF31                      = $ROW["USERDEF3"];
    $USERDEF41                      = $ROW["USERDEF4"];
    $USERDEF51                      = $ROW["USERDEF5"]; 
}

$query = "SELECT * from HR_RECRUIT_RECRUITMENT where EXPERT_ID='$EXPERT_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
    $DEPARTMENT             = $ROW["DEPARTMENT"];
    $STAFF_OCCUPATION       = $ROW["TYPE"];
    $ADMINISTRATION_LEVEL   = $ROW["ADMINISTRATION_LEVEL"];
    $JOB_POSITION           = $ROW["JOB_POSITION"]; 	
    $PRESENT_POSITION       = $ROW["PRESENT_POSITION"];
    $DATES_EMPLOYED         = $ROW["ON_BOARDING_TIME"];
    $BEGIN_SALSRY_TIME      = $ROW["STARTING_SALARY_TIME"];
    $OA_NAME                = $ROW["OA_NAME"];
    
    $STAFF_DEPT_NAME = td_trim(GetDeptNameById($DEPARTMENT));
}

if($_SESSION['LOGIN_USER_ID'] != 'admin')
{
    $sql = "SELECT ID FROM hr_manager WHERE DEPT_ID='$DEPARTMENT' and (find_in_set('".$_SESSION['LOGIN_USER_ID']."',DEPT_HR_MANAGER) or find_in_set('".$_SESSION['LOGIN_USER_ID']."',DEPT_HR_SPECIALIST))";
    $cursor1 = exequery(TD::conn(),$sql);
    if(!$row=mysql_fetch_array($cursor1))
    {
        Message(_("����"),_("û���½����µ���Ȩ�ޣ�����ϵϵͳ����Ա"));
        exit;
    }
}

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
}

//������ȨOA��¼Ȩ��
$SYS_PARA_ARRAY = get_sys_para("HR_SET_USER_LOGIN");
$YES_OTHER=$SYS_PARA_ARRAY["HR_SET_USER_LOGIN"];
?>

<script src="<?=MYOA_JS_SERVER?>/static/js/module.js?v=<?=MYOA_SYS_VERSION?>"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/utility.js"></script>
<script type="text/javascript" src="<?=MYOA_JS_SERVER?>/static/js/attach.js"></script>
<script language="javascript" src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.5.1/jquery.min.js"></script>
<script src="/module/DatePicker/WdatePicker.js"></script>
<link rel="stylesheet"type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/js/validation/validationEngine.jquery.min.css">
<script type="text/javascript" src="/inc/js_lang.php"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/jquery-1.10.2/jquery.min.js"></script>
<script src="<?=MYOA_JS_SERVER?>/static/js/validation/jquery.validationEngine.min.js" type="text/javascript" charset="utf-8"></script>
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
    var STAFF_NAME_INFO = document.form1.STAFF_NAME.value;
    var USER_ID_INFO = document.form1.BYNAME.value;
    
    if(USER_ID_INFO.trim()=="")
    {
        alert("<?=_("������OA�û���")?>");
        return false;
    }
    
    if(STAFF_NAME_INFO.trim()=="")
    {
        alert("<?=_("����������")?>");
        return false;
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
function check_user(id)
{
    if(id=="")
        return;
    document.getElementById("byname_msg").innerHTML="<img src='<?=MYOA_STATIC_SERVER?>/static/images/loading_16.gif' align='absMiddle'> <?=_("����У����Ժ򡭡�")?>";
    _get("check_user.php","USER_ID="+id, show_msg);
}
function show_msg(req)
{
    if(req.status==200)
    {
        if(req.responseText=="+OK")
            document.getElementById("byname_msg").innerHTML="<img src='<?=MYOA_STATIC_SERVER?>/static/images/correct.gif' align='absMiddle'>";
        else if(req.responseText=="-ERR")
        {
            document.getElementById("byname_msg").innerHTML="<img src='<?=MYOA_STATIC_SERVER?>/static/images/error.gif' align='absMiddle'> <?=_("���û����Ѵ���")?>";
            document.form1.BYNAME.focus();
        }
        else if(req.responseText=="-ERR1")
        {
            document.getElementById("byname_msg").innerHTML="<img src='<?=MYOA_STATIC_SERVER?>/static/images/error.gif' align='absMiddle'> <?=_("���û����Ѵ����ڵ�����")?>";
            document.form1.BYNAME.focus();
        }else if(req.responseText=="+OK_CARD")
        {
            document.getElementById("card_id_msg").innerHTML="<img src='<?=MYOA_STATIC_SERVER?>/static/images/correct.gif' align='absMiddle'>";
        }
        else if(req.responseText=="-ERR_CARD")
        {
            document.getElementById("card_id_msg").innerHTML="<img src='<?=MYOA_STATIC_SERVER?>/static/images/error.gif' align='absMiddle'> <?=_("�ظ����֤��")?>";
            document.form1.STAFF_CARD_NO.focus();
        }else if(req.responseText=="-ERRNO")
		{
			alert("<?=_("����ظ�")?>");
			document.form1.STAFF_NO.focus();
		}
    }
    else
    {
        document.getElementById("byname_msg").innerHTML="<img src='<?=MYOA_STATIC_SERVER?>/static/images/error.gif' align='absMiddle'> <?=_("����")?>"+req.status;
    }
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
            document.form1.STAFF_CARD_NO.focus();
            return (false);
        }
        
        switch(idcard.length)
        {
            case 15:
               /* if ((parseInt(idcard.substr(6,2))+1900) % 4 == 0 || ((parseInt(idcard.substr(6,2))+1900) % 100 == 0 && (parseInt(idcard.substr(6,2))+1900) % 4 == 0 ))
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
                    document.form1.STAFF_CARD_NO.focus();
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
                        document.form1.STAFF_CARD_NO.focus();
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
                    document.form1.STAFF_CARD_NO.focus();
                    return (false);
                }
	            break;
	        default:
                alert(Errors[0]);
                document.form1.STAFF_CARD_NO.focus();
                return (false);
                break;
        }
	
        //��֤���֤��Ψһ
        _get("check_idcard.php","idcard="+idcard, show_msg);
		get_animal_sign();
    }else
    {
        document.getElementById("card_id_msg").innerHTML="";
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

function get_work_age(str)
{
    if("" == str)
    {
        document.form1.WORK_AGE.value = "";
        return false;
    }
    var today=new Date();
    var during = today.getFullYear() - str.substring(0,4);
    if(during > 0)
        document.form1.WORK_AGE.value= during;
    else
        document.form1.WORK_AGE.value = 0;
}

function get_job_age(str)
{
    if("" == str)
    {
        document.form1.JOB_AGE.value = "";
        return false;
    }
    var today=new Date();
    var during = today.getFullYear() - str.substring(0,4);
    if(during > 0)
        document.form1.JOB_AGE.value= during;
    else
        document.form1.JOB_AGE.value = 0;
}

function check_no(str)
{
    if(str=="")
        return;
    _get("check_no.php","STAFF_NO="+str, show_msg);
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
function open_pic(AVATAR)
{
    url=AVATAR;
    window.open(url,"<?=$STAFF_NAME?><?=_("��ͷ��")?>","toolbar=0,status=0,menubar=0,scrollbars=yes,resizable=1")
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

<body class="bodycolor" onLoad="document.form1.BYNAME.focus();get_animal_sign();">

<table border="0" width="770" cellspacing="0" cellpadding="3" class="small">
    <tr>
        <td><img src="<?=MYOA_STATIC_SERVER?>/static/images/notify_new.gif" align="absmiddle"><span class="big3"> <?=_("�½����µ���")?></span>&nbsp;&nbsp;</td>
    </tr>
</table>

<form enctype="multipart/form-data" action="add_staff.php" method="post" name="form1" id="form1" onsubmit="return CheckForm();">
<table class="TableBlock" width="770" align="center">
    <tr>
        <td nowrap class="TableHeader" colspan="6"><b>&nbsp;<?=_("������Ϣ")?></b></td>
    </tr>
    <tr>
        <td nowrap class="TableData" width="100">OA<?=_("�û�����")?></td>
        <td class="TableData" width="180" <? if(!($YES_OTHER==1)) echo "colspan=4"; ?>>
            <input type="text" name="BYNAME" id="BYNAME" value="<?=$OA_NAME?>" class="BigIput validate[required]" size="10" maxlength="20" data-prompt-position="centerRight:5,-4" onBlur="check_user(this.value)">&nbsp;<span id="byname_msg" name="byname_msg"></span>&nbsp;&nbsp;&nbsp;<span id="chek_card_id" style="color: red; font-weight: bold;"></span>
        </td>
<?
if($YES_OTHER==1)
{
?>
        <td nowrap class="TableData" width="100"><?=_("�Ƿ������¼��")?></td>
        <td class="TableData"  width="200" colspan="2">
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
    $URL_ARRAY = attach_url_old('hrms_pic', $PHOTO_NAME);
    $AVATAR = $URL_ARRAY['view'];
?>
            <div class="avatar"><a href="javascript:open_pic('<?=$AVATAR?>')"><img src="<?=$AVATAR?>" width=130></a></div>
<?
}
?>
        </td>
    </tr>
    <tr>
        <td nowrap class="TableData" width="100"><?=_("���ţ�")?></td>
        <td class="TableData" width="180">
            <input type="hidden" name="DEPT_ID" value="<?=$DEPARTMENT?>">
            <input type="text" name="DEPT_NAME" id="DEPT_NAME" class="BigStatic" value="<?=$STAFF_DEPT_NAME?>" readonly>
        </td>
        <td nowrap class="TableData"> <?=_("��ɫ��")?></td>
        <td class="TableData" colspan=2 width=80%>
            <select name="USER_PRIV" class="BigSelect" title="<?=_("��ɫ���ڡ�ϵͳ����->����֯�������á�->����ɫ��Ȩ�޹���ģ�����á�")?>">
<?
$query = "SELECT * from USER_PRIV order by PRIV_NO desc";
$cursor= exequery(TD::conn(),$query, true);
while($ROW=mysql_fetch_array($cursor))
{
    if($_SESSION["LOGIN_USER_PRIV"]=="1")
    {
?>
                <option value=<?=$ROW["USER_PRIV"]?>><?=$ROW["PRIV_NAME"]?>&nbsp&nbsp;</option>
<?
    }
    else
    {
        $query2 = "SELECT * from  hr_role_manage WHERE FIND_IN_SET('".$_SESSION["LOGIN_USER_ID"]."',HR_ROLE_MANAGE);";
        $cursor2= exequery(TD::conn(),$query2);
        while($ROW2=mysql_fetch_array($cursor2))
        {
            $NEW_NAME_ARRAY=  explode(',', $ROW2["HR_USER_PRIV"]);
            if(in_array($ROW["USER_PRIV"],$NEW_NAME_ARRAY))
            {
                $NEW_NAME="USER_P".$ROW["USER_PRIV"];
                $$NEW_NAME=1;
            }
        }
?>
                <option value=<?=$ROW["USER_PRIV"]?>  class="<?$NEW_NAME="USER_P".$ROW["USER_PRIV"]; if($$NEW_NAME != 1) echo _("xinxiyinchneg") ?>"><?=$ROW["PRIV_NAME"]?>&nbsp&nbsp;</option>
<?
    }
}
?>
            </select>
        </td>
    </tr>
    <tr>
        <td nowrap class="TableData" width="100"><?=_("��ţ�")?></td>
        <td class="TableData" width="180"><input type="text" name="STAFF_NO" id="STAFF_NO" class="BigInput validate[custom[number],maxSize[6]]" data-prompt-position="centerRight:5,-4"  onBlur="check_no(this.value)" ></td>
        <td nowrap class="TableData"><?=_("���ţ�")?></td>
        <td class="TableData" colspan="2"><input type="text" name="WORK_NO" id="WORK_NO" class="BigInput"></td>
    </tr>
    <tr>
        <td nowrap class="TableData"><?=_("������")?></td>
        <td class="TableData"><input type="text" name="STAFF_NAME" id="STAFF_NAME" value="<?=$STAFF_NAME?>" class="BigInput"></td>
        <td nowrap class="TableData"><?=_("��������")?></td>
        <td class="TableData" colspan="2"><input type="text" name="BEFORE_NAME" id="BEFORE_NAME" class="BigInput"></td>
    </tr>
    <tr>
        <td nowrap class="TableData"><?=_("Ӣ������")?></td>
        <td class="TableData"><input type="text" name="STAFF_E_NAME" id="STAFF_E_NAME" class="BigInput"></td>
        <td nowrap class="TableData"><?=_("���֤�ţ�")?></td>
        <td class="TableData"  colspan="2"><input type="text" name="STAFF_CARD_NO" id="STAFF_CARD_NO" class="BigInput" onBlur="checkIdcard()">&nbsp;<span id="card_id_msg" name="card_id_msg"></span></td>
    </tr>
    <tr>
        <td nowrap class="TableData"><?=_("�������ڣ�")?></td>
        <td class="TableData" title="<?=_("��д�����֤������ֱ������")?>">
            <input type="text" name="STAFF_BIRTH" id="STAFF_BIRTH" size="10" maxlength="10" class="BigInput" value="<?=$STAFF_BIRTH?>" onClick="WdatePicker()" onBlur="checkDate()" onChange="get_animal_sign()"/>
            <input type="checkbox" name="IS_LUNAR" id="IS_LUNAR" value="1" onClick="get_animal_sign()"><label for="IS_LUNAR"><?=_("��ũ������")?></label>
        </td>
        <td nowrap class="TableData"><?=_("���䣺")?></td>
        <td class="TableData" colspan="2" title="<?=_("��д�����֤������ֱ������")?>"><input type="text" name="STAFF_AGE" id="STAFF_AGE" size="4" value="<?=$STAFF_AGE?>" class="BigInput"><?=_("��")?></td>
    </tr>
    <tr>
        <td nowrap class="TableData"><?=_("�Ա�")?></td>
        <td class="TableData" title="<?=_("��д�����֤������ֱ������")?>">
            <select name="STAFF_SEX" class="BigSelect" >
                <option value="0" <? if($STAFF_SEX==0) echo "selected";?>><?=_("��")?></option>
                <option value="1" <? if($STAFF_SEX==1) echo "selected";?>><?=_("Ů")?></option>
            </select>
        </td>
        <td nowrap class="TableData"><?=_("���᣺")?></td>
        <td class="TableData" colspan="3" nowrap>
            <select name="STAFF_NATIVE_PLACE" class="BigSelect">
                <option value="" <? if($STAFF_NATIVE_PLACE=="") echo "selected";?>></option>
                <?=hrms_code_list("AREA",$STAFF_NATIVE_PLACE);?>
            </select>
            <input type="text" name="STAFF_NATIVE_PLACE2" style="height: 24px;" value="<?=$STAFF_NATIVE_PLACE2?>" >
        </td>
    </tr>
    <tr>
        <td nowrap class="TableData"><?=_("��Ф")?></td>
        <td class="TableData" title="<?=_("��Ф")?>">
        <input type="text" value="<?=$ANIMAL?>" id="animal_id" class="BigStatic" readonly /></td>
        <td nowrap class="TableData"><?=_("����")?></td>
        <td class="TableData" colspan="3" title="<?=_("����")?>">
        <input type="text" value="<?=$SIGN?>" id="sign_id" class="BigStatic" readonly /></td>
    </tr>
    <tr>
        <td nowrap class="TableData" ><?=_("���壺")?></td>
        <td class="TableData"><input type="text" name="STAFF_NATIONALITY" id="STAFF_NATIONALITY" value="<?=$STAFF_NATIONALITY?>" class="BigInput"></td>
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
        <td nowrap class="TableData"><?=_("����״����")?></td>
        <td class="TableData">
            <select name="STAFF_MARITAL_STATUS" class="BigSelect">
                <option value=""  <? if($STAFF_MARITAL_STATUS=="") echo "selected";?>></option>
                <option value="0"  <? if($STAFF_MARITAL_STATUS=="0") echo "selected";?>><?=_("δ��")?></option>
                <option value="1"  <? if($STAFF_MARITAL_STATUS=="1") echo "selected";?>><?=_("�ѻ�")?></option>
                <option value="2"  <? if($STAFF_MARITAL_STATUS=="2") echo "selected";?>><?=_("����")?></option>
                <option value="3"  <? if($STAFF_MARITAL_STATUS=="3") echo "selected";?>><?=_("ɥż")?></option>
            </select>
        </td>
    </tr>
    <tr>
        <td nowrap class="TableData" width="100"><?=_("����״����")?></td>
        <td class="TableData"  ><input type="text" name="STAFF_HEALTH" id="STAFF_HEALTH" class="BigInput" value="<?=$STAFF_HEALTH?>"></td>
        <td nowrap class="TableData" width="100"><?=_("���ݼ٣�")?></td>
        <td class="TableData" colspan="3"><input type="text" name="LEAVE_TYPE" size="3" maxlength="3" class="BigInput" value="<?=$LEAVE_TYPE?>"><?=_("��")?></td>
    </tr>
    <tr>
        <td nowrap class="TableData"><?=_("������ò��")?></td>
        <td class="TableData" width="180">
            <select name="STAFF_POLITICAL_STATUS" class="BigSelect" title="<?=_("������Դ����-hrms���������ж���")?>">
                <option value="" <? if($POLITICS=="") echo "selected";?>></option>
                <? echo hrms_code_list("STAFF_POLITICAL_STATUS",$STAFF_POLITICAL_STATUS); ?>
            </select>
        </td>
        <td nowrap class="TableData" width="100"><?=_("�뵳ʱ�䣺")?></td>
        <td class="TableData"  colspan="3">
            <input type="text" name="JOIN_PARTY_TIME" size="10" maxlength="10" class="BigInput" value="<?=$JOIN_PARTY_TIME?>" onClick="WdatePicker()"/>
        </td>
    </tr>
    <tr>
        <td nowrap class="TableData"><?=_("�������")?></td>
        <td class="TableData">
            <select name="STAFF_TYPE" class="BigSelect" title="<?=_("������Դ����-hrms���������ж���")?>">
                <option value="" <? if($STAFF_TYPE=="") echo "selected";?>></option>
                <?=hrms_code_list("HR_STAFF_TYPE",$STAFF_TYPE);?>
            </select>
        </td>
        <td nowrap class="TableData" width="100"><?=_("�������ڵأ�")?></td>
        <td class="TableData" width="180" colspan="3"><input type="text" name="STAFF_DOMICILE_PLACE" id="STAFF_DOMICILE_PLACE" size="40" class="BigInput" value="<?=$STAFF_DOMICILE_PLACE?>"></td>
    </tr>
    <tr>
<?
if($PHOTO_NAME=="")
    $PHOTO_STR=_("��Ƭ�ϴ���");
else
    $PHOTO_STR=_("��Ƭ���ģ�");
?>
        <td nowrap class="TableData" width="100"><?=$PHOTO_STR?></td>
        <td class="TableData"  width="180" colspan="5">
            <input type="file" id="cursor_file" name="ATTACHMENT" size="40"  class="BigInput" title="<?=_("ѡ�񸽼��ļ�")?>" >
        </td>
    </tr>
    <tr>
        <td nowrap class="TableHeader" colspan="6"><b>&nbsp;<?=_("ְλ�������ϵ��ʽ��")?></b></td>
    </tr>
    <tr>
        <td nowrap class="TableData" width="100"><?=_("���֣�")?></td>
        <td class="TableData"  width="180"><input type="text" name="WORK_TYPE" id="WORK_TYPE" class="BigInput"></td>
        <td nowrap class="TableData" width="100"><?=_("��������")?></td>
        <td class="TableData"  width="180"><input type="text" name="ADMINISTRATION_LEVEL" id="ADMINISTRATION_LEVEL" class="BigInput"></td>
        <td nowrap class="TableData" width="100"><?=_("Ա�����ͣ�")?></td>
        <td class="TableData">
            <select name="STAFF_OCCUPATION" class="BigSelect" title="<?=_("������Դ����-hrms���������ж���")?>">
                <option value="" <? if($STAFF_OCCUPATION=="") echo "selected";?>></option>
                <?=hrms_code_list("STAFF_OCCUPATION",$STAFF_OCCUPATION);?>
            </select>
        </td>
    </tr>
    <tr>
        <td nowrap class="TableData" width="100"><?=_("ְ��")?></td>
        <td class="TableData"  width="180"><input type="text" name="JOB_POSITION" id="JOB_POSITION" class="BigInput"></td>
        <td nowrap class="TableData" width="100"><?=_("ְ�ƣ�")?></td>
        <td class="TableData"  width="180">
            <select name="PRESENT_POSITION" class="BigSelect" title="<?=_("������Դ����-hrms���������ж���")?>">
                <option value="" <? if($PRESENT_POSITION=="") echo "selected";?>></option>
                <?=hrms_code_list("PRESENT_POSITION",$TECH_POST);?>
            </select>
        </td>
        <td nowrap class="TableData" width="100"><?=_("��ְʱ�䣺")?></td>
        <td class="TableData"  width="180">
            <input type="text" name="DATES_EMPLOYED" size="10" maxlength="10" class="BigInput" value="<?=$DATES_EMPLOYED?>" onClick="WdatePicker()" onChange="get_job_age(this.value)"/>
        </td>
    </tr>
    <tr>
        <td nowrap class="TableData" width="100"><?=_("ְ�Ƽ���")?></td>
        <td class="TableData">
            <select name="WORK_LEVEL" class="BigSelect" title="<?=_("����������ڡ�������Դ���á�->��HRMS�������á�ģ�����á�")?>">
                <option value="" <? if($WORK_LEVEL=="") echo "selected";?>></option>
                <?=hrms_code_list("WORK_LEVEL",$WORK_LEVEL);?>
            </select>
        </td>
        <td nowrap class="TableData" width="100"><?=_("��λ��")?></td>
        <td nowrap class="TableData">
            <select name="WORK_JOB" class="BigSelect">
                <option value=""></option>
                <?=hrms_code_list("POOL_POSITION","$POSITION");?>
            </select>
        </td>
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
                <option value="<?=$DUTY_TYPE1?>"><?=$DUTY_NAME?></option>
<?
}
?>
                <option value="99" <? if($DUTY_TYPE1=="99") echo "selected";?>><?=_("�ְ���")?></option>
            </select>
        </td>
    </tr>
    <tr>
        <td nowrap class="TableData" width="100"><?=_("����λ���䣺")?></td>
        <td class="TableData"  width="180"><input type="text" name="JOB_AGE" id="JOB_AGE" class="BigInput"></td>
        <td nowrap class="TableData" width="100"><?=_("��нʱ�䣺")?></td>
        <td class="TableData"  width="180">
            <input type="text" name="BEGIN_SALSRY_TIME" size="10" maxlength="10" class="BigInput" value="<?=$BEGIN_SALSRY_TIME?>" onClick="WdatePicker()"/>
        </td>
        <td nowrap class="TableData" width="100"><?=_("��ְ״̬��")?></td>
        <td class="TableData"  width="180">
            <select name="WORK_STATUS" class="BigSelect" title="<?=_("������Դ����-hrms���������ж���")?>">
                <option value=""></option>
                <?=hrms_code_list("WORK_STATUS",$WORK_STATUS);?>
            </select>
        </td>
    </tr>
    <tr>
        <td nowrap class="TableData" width="100"><?=_("�ܹ��䣺")?></td>
        <td class="TableData"  width="180"><input type="text" name="WORK_AGE" id="WORK_AGE" class="BigInput"></td>
        <td nowrap class="TableData" width="100"><?=_("�μӹ���ʱ�䣺")?></td>
        <td class="TableData"  width="180">
            <input type="text" name="JOB_BEGINNING" size="10" maxlength="10" class="BigInput" value="<?=$JOB_BEGINNING?>" onClick="WdatePicker()" onChange="get_work_age(this.value)"/>
        </td>
        <td nowrap class="TableData" width="100"><?=_("��ϵ�绰��")?></td>
        <td class="TableData"  width="180"><input type="text" name="STAFF_PHONE" id="STAFF_PHONE" class="BigInput validate[custom[phone]]" data-prompt-position="centerRight:5,-4"></td>
    </tr>
    <tr>
        <td nowrap class="TableData" width="100"><?=_("�ֻ����룺")?></td>
        <td class="TableData"  width="180"><input type="text" name="STAFF_MOBILE" id="STAFF_MOBILE" class="BigInput validate[custom[phone]]" data-prompt-position="centerRight:5,-4" value="<?=$STAFF_PHONE?>"></td>
        <td nowrap class="TableData" width="100"><?=_("MSN��")?></td>
        <td class="TableData"  width="180" colspan="3"><input type="text" name="STAFF_MSN" id="STAFF_MSN" class="BigInput"></td>
    </tr>
    <tr>
        <td nowrap class="TableData" width="100"><?=_("�����ʼ���")?></td>
        <td class="TableData"  width="180"><input type="text" name="STAFF_EMAIL" id="STAFF_EMAIL" class="BigInput validate[custom[email]]" data-prompt-position="centerRight:5,-4" value="<?=$STAFF_EMAIL?>"></td>
        <td nowrap class="TableData" width="100"><?=_("��ͥ��ַ��")?></td>
        <td class="TableData"  width="180" colspan="3"><input type="text" name="HOME_ADDRESS" size="50" id="HOME_ADDRESS" class="BigInput"></td>
    </tr>
    <tr>
        <td nowrap class="TableData" width="100"><?=_("QQ��")?></td>
        <td class="TableData"  width="180"><input type="text" name="STAFF_QQ" id="STAFF_QQ" class="BigInput validate[custom[number],maxSize[11],minSize[5]]" data-prompt-position="centerRight:5,-4" ></td>
        <td nowrap class="TableData" width="100"><?=_("������ϵ��ʽ��")?></td>
        <td class="TableData"  width="180" colspan="3"><input type="text" name="OTHER_CONTACT" size="50"  id="OTHER_CONTACT" class="BigInput"></td>
    </tr>
    <tr>
        <td nowrap class="TableData"> <?=_("�Ƿ�Ϊר�ң�")?></td>
        <td class="TableData">
            <input type="radio" name="batch" value="1" /><label for="1" style="margin-right: 10px;"><?=_("��")?></label><input type="radio" name="batch" value="0" checked /><label for="0"><?=_("��")?></label>
        </td>
        <td nowrap class="TableData"> <?=_("�о�����")?></td>
        <td class="TableData"  width="180" colspan="3">
            <textarea rows="3" cols="30" name="EXPERTS_INFO" id="EXPERTS_INFO"></textarea>
        </td>
    </tr>
    <tr>
        <td nowrap class="TableData"> <?=_("����ְ��")?></td>
        <td class="TableData">
            <textarea rows="3" cols="30" name="PART_TIME" id="PART_TIME"></textarea>
        </td>
        <td nowrap class="TableData"> <?=_("�ɹ����ܣ�")?></td>
        <td class="TableData"  width="180" colspan="3">
            <textarea rows="3" cols="30" name="RESEARCH_RESULTS" id="RESEARCH_RESULTS"></textarea>
        </td>
    </tr>
    <tr>
        <td nowrap class="TableData"> <?=_("ֱ���ϼ���")?></td>
        <td nowrap class="TableData">
            <input type="hidden" name="COPY_TO_ID1" value="">
            <textarea cols=21 name="COPY_TO_NAME1" rows=2 class="BigStatic" wrap="yes" readonly></textarea>
            <a href="javascript:;" class="orgAdd" onClick="SelectUser('','COPY_TO_ID1', 'COPY_TO_NAME1')"><?=_("ѡ��")?></a>
            <a href="javascript:;" class="orgClear" onClick="ClearUser('COPY_TO_ID1', 'COPY_TO_NAME1')"><?=_("���")?></a>
        </td>
        <td nowrap class="TableData"> <?=_("ֱ���¼���")?></td>
        <td nowrap class="TableData"width="180" colspan="3">
            <input type="hidden" name="COPY_TO_ID" value="">
            <textarea cols=21 name="COPY_TO_NAME" rows=2 class="BigStatic" wrap="yes" readonly></textarea>
            <a href="javascript:;" class="orgAdd" onClick="SelectUser('','COPY_TO_ID', 'COPY_TO_NAME')"><?=_("ѡ��")?></a>
            <a href="javascript:;" class="orgClear" onClick="ClearUser('COPY_TO_ID', 'COPY_TO_NAME')"><?=_("���")?></a>
        </td>
    </tr>
    <tr>
        <td nowrap class="TableData" width="100"><?=_("������1��")?></td>
        <td class="TableData"  width="180"><input type="text" name="BANK1" class="BigInput" value=""></td>
        <td nowrap class="TableData" width="100"><?=_("�˻�1��")?></td>
        <td class="TableData"  width="180" colspan="3"><input type="text" name="BANK_ACCOUNT1" size="50" value="" class="BigInput"></td>
    </tr>
    <tr>
        <td nowrap class="TableData" width="100"><?=_("������2��")?></td>
        <td class="TableData"  width="180"><input type="text" name="BANK2" class="BigInput" value=""></td>
        <td nowrap class="TableData" width="100"><?=_("�˻�2��")?></td>
        <td class="TableData"  width="180" colspan="3"><input type="text" name="BANK_ACCOUNT2" size="50" value="" class="BigInput"></td>
    </tr>
    <tr>
        <td nowrap class="TableHeader" colspan="6"><b>&nbsp;<?=_("����������")?></b></td>
    </tr>
    <tr>
        <td nowrap class="TableData" width="100"><?=_("ѧ����")?></td>
        <td class="TableData"  width="180">
            <select name="STAFF_HIGHEST_SCHOOL" class="BigSelect"  title="<?=_("������Դ����-hrms���������ж���")?>">
                <option value=""><?=_("��ѡ��")?></option>
                <?=hrms_code_list("STAFF_HIGHEST_SCHOOL","$STAFF_HIGHEST_SCHOOL");?>
            </select>
        </td>
        <td nowrap class="TableData" width="100"><?=_("ѧλ��")?></td>
        <td class="TableData"  width="180">
            <select name="STAFF_HIGHEST_DEGREE" class="BigSelect" title="<?=_("������Դ����-hrms���������ж���")?>">
                <option value="" <? if($STAFF_HIGHEST_SCHOOL=="") echo "selected";?>><?=_("��ѡ��")?></option>
                <?=hrms_code_list("EMPLOYEE_HIGHEST_DEGREE","$STAFF_HIGHEST_DEGREE");?>
            </select>
        </td>
        <td nowrap class="TableData" width="100"><?=_("��ҵʱ�䣺")?></td>
        <td class="TableData"  width="180">
            <input type="text" name="GRADUATION_DATE" size="10" maxlength="10" class="BigInput" value="<?=$GRADUATION_DATE?>" onClick="WdatePicker()"/>
        </td>
    </tr>
    <tr>
        <td nowrap class="TableData" width="100"><?=_("��ҵѧУ��")?></td>
        <td class="TableData"  width="180"><input type="text" name="GRADUATION_SCHOOL" id="GRADUATION_SCHOOL" value="<?=$GRADUATION_SCHOOL?>" class="BigInput"></td>
        <td nowrap class="TableData" width="100"><?=_("רҵ��")?></td>
        <td class="TableData"  width="180"><input type="text" name="STAFF_MAJOR" id="STAFF_MAJOR" value="<?=get_hrms_code_name($STAFF_MAJOR,"POOL_EMPLOYEE_MAJOR")?>" class="BigInput"></td>
        <td nowrap class="TableData" width="100"><?=_("�����ˮƽ��")?></td>
        <td class="TableData"  width="180"><input type="text" name="COMPUTER_LEVEL" id="COMPUTER_LEVEL" value="<?=$COMPUTER_LEVEL?>" class="BigInput"></td>
    </tr>
    <tr>
        <td nowrap class="TableData" width="100"><?=_("��������1��")?></td>
        <td class="TableData"  width="180"><input type="text" name="FOREIGN_LANGUAGE1" id="FOREIGN_LANGUAGE1" value="<?=$FOREIGN_LANGUAGE1?>" class="BigInput"></td>
        <td nowrap class="TableData" width="100"><?=_("��������2��")?></td>
        <td class="TableData"  width="180"><input type="text" name="FOREIGN_LANGUAGE2" id="FOREIGN_LANGUAGE2" value="<?=$FOREIGN_LANGUAGE2?>" class="BigInput"></td>
        <td nowrap class="TableData" width="100"><?=_("��������3��")?></td>
        <td class="TableData"  width="180"><input type="text" name="FOREIGN_LANGUAGE3" id="FOREIGN_LANGUAGE3" value="<?=$FOREIGN_LANGUAGE3?>" class="BigInput"></td>
    </tr>
    <tr>
        <td nowrap class="TableData" width="100"><?=_("����ˮƽ1��")?></td>
        <td class="TableData"  width="180"><input type="text" name="FOREIGN_LEVEL1" id="FOREIGN_LEVEL1" value="<?=$FOREIGN_LEVEL1?>" class="BigInput"></td>
        <td nowrap class="TableData" width="100"><?=_("����ˮƽ2��")?></td>
        <td class="TableData"  width="180"><input type="text" name="FOREIGN_LEVEL2" id="FOREIGN_LEVEL2" value="<?=$FOREIGN_LEVEL2?>" class="BigInput"></td>
        <td nowrap class="TableData" width="100"><?=_("����ˮƽ3��")?></td>
        <td class="TableData"  width="180"><input type="text" name="FOREIGN_LEVEL3" id="FOREIGN_LEVEL3" value="<?=$FOREIGN_LEVEL3?>" class="BigInput"></td>
    </tr>
    <tr>
        <td nowrap class="TableData" width="100"><?=_("�س���")?></td>
        <td class="TableData"  width="180" colspan="5"><input type="text" name="STAFF_SKILLS" value="<?=$STAFF_SKILLS?>" size="80" id="STAFF_SKILLS" class="BigInput"></td>
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
        <td colspan="6" >
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
        <td nowrap class="TableHeader" colspan="6">&nbsp;<?=_("������")?></td>
    </tr>
    <tr>
        <td nowrap class="TableData" colspan="6">
<?
$editor = new Editor('RESUME') ;
$editor->Height = '450';
$editor->Value = $RESUME ;
$editor->Config = array("EditorAreaStyles" => "body{font-size:20pt;}","model_type" => "14");
//$editor->Config = array('model_type' => '14') ;
$editor->Create() ;
?>
        </td>
    </tr>
    <tr align="center" class="TableControl">
        <td colspan=6 nowrap>
            <input type="button" value="<?=_("����")?>" class="BigButton" onClick="CheckForm();">
     <input type="button" value="<?=_("�ر�")?>" class="BigButton" title="<?=_("�رմ���")?>" onClick="window.close();">
    </td>
  </tr>             
</table>
<form>
<script type="text/javascript">
$(document).ready(function(){
    $(".xinxiyinchneg").remove();
});
$("#STAFF_CARD_NO").blur(function(){
    var USER_ID=$("input[name=BYNAME]").val();
    var STAFF_CARD_NO=$("#STAFF_CARD_NO").val();
    if(STAFF_CARD_NO !="")
    {
        $.post("chek_card.php",
        {
            USER_ID:USER_ID,
            STAFF_CARD_NO:STAFF_CARD_NO
        },
        function(data){
            if(data==1)
            {
                $("#chek_card_id").text("���û�Ϊ�������û�");
            }
            else
            {
                $("#chek_card_id").text("");
            }
        })
    }
})
</script>
</body>
</html>