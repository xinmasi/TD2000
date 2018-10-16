<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_field.php");
include_once("inc/utility_org.php");
include_once("inc/check_type.php");
include_once("check_priv.inc.php");

//每次导出登录id
if($is_leave==1)
{
    $FIELDMSGNAME=_("用户名,").$FIELDMSGNAME._("离职时间,");
    $FIELDMSG="USER_ID,".$FIELDMSG."GET_OUT_TIME,";
}
else
{
    $FIELDMSGNAME=_("用户名,").$FIELDMSGNAME;
    $FIELDMSG="USER_ID,".$FIELDMSG;
}
if(MYOA_IS_UN == 1)
    $OUTPUT_HEAD = explode(",",$FIELDMSG);//"NAME,MINISTRATION,NICK_NAME,EMAIL,MOBIL_NO,TEL_NO,QQ,MSN,SEX,BIRTHDAY,MATE,CHILD,HOME_POSTCODE,HOME_ADD,HOME_PHONE,DEPT_NAME,DEPT_POSTCODE,DEPT_ADD,OFFICE_PHONE,COMPONY_FAX,NOTES,".$FIELDNAME;
else
    $OUTPUT_HEAD = explode(",",$FIELDMSGNAME);//_("姓名").","._("职务").","._("昵称").","._("电子邮件地址").","._("手机").","._("小灵通").","._("QQ").","._("MSN").","._("性别").","._("生日").","._("配偶").","._("子女").","._("家庭邮编").","._("家庭所在街道").","._("家庭电话").","._("公司").","._("公司所在地邮政编码").","._("公司所在街道").","._("工作电话").","._("工作传真").","._("附注").",".$FIELDNAME;
require_once ('inc/ExcelWriter.php');

$objExcel = new ExcelWriter();
$objExcel->setFileName(_("人事档案"));
$objExcel->addHead($OUTPUT_HEAD);

$STYLE_ARRAY=explode(",",$FIELDMSGNAME);
$ARRAY_COUNT=sizeof($STYLE_ARRAY);
$COUNT=0;
if($STYLE_ARRAY[$ARRAY_COUNT-1]=="") $ARRAY_COUNT--;


$CUR_DATE=date("Y-m-d");

if($is_leave==1)
{
    $WHERE_STR.=" AND b.DEPT_ID=0 ";
    $file_name=_("离职人员档案");
}
elseif($is_leave==0)
{
    $WHERE_STR.=" AND b.DEPT_ID!=0 ";
    $file_name=_("在职人员档案");
}

$user_info_arr = array();
$query = "select UID,BYNAME,USER_ID from user ";
$cursor = exequery(TD::conn(), $query);
while($row = mysql_fetch_array($cursor, MYSQL_ASSOC))
{
    $user_info_arr[$row['USER_ID']] = $row['BYNAME'];
}


$CONDITION_STR="";
//------------------------合法性校验------------------------
if($AGE_MIN!="")
{
    if($urlstr=="")$urlstr="AGE_MIN=".$AGE_MIN;
    else $urlstr=$urlstr."&AGE_MIN=".$AGE_MIN;
    $STAFF_AGE=intval($AGE_MIN);
    if(!is_int($STAFF_AGE)||$STAFF_AGE<=0)
    {
        Message(_("错误"),_("年龄应为正整数！"));
        Button_Back();
        exit;
    }
    $YEAR_MIN=date("Y",time())-$STAFF_AGE;
    $YEAR_MIN.=date("-m-d",time());
    $CONDITION_STR.=" and a.STAFF_BIRTH<='$YEAR_MIN'";
}
if($AGE_MAX!="")
{
    if($urlstr=="")$urlstr="AGE_MAX=".$AGE_MAX;
    else $urlstr=$urlstr."&AGE_MAX=".$AGE_MAX;
    $STAFF_AGE=intval($AGE_MAX);
    if(!is_int($STAFF_AGE)||$STAFF_AGE<=0)
    {
        Message(_("错误"),_("年龄应为正整数！"));
        Button_Back();
        exit;
    }
    $YEAR_MAX=date("Y",time())-$STAFF_AGE;
    $YEAR_MAX.=date("-m-d",time());
    $CONDITION_STR.=" and a.STAFF_BIRTH>='$YEAR_MAX'";
}
//------------------------ 生成条件字符串 ------------------
if ($TO_ID!="")
{
    if ($TO_ID!="ALL_DEPT")
    {
        $DEPT_ID=$TO_ID;
        if (substr($DEPT_ID,-1)==",")
            $DEPT_ID=substr($DEPT_ID,0,-1);
        $DEPT_ID="(".$DEPT_ID.")";
        $CONDITION_STR.=" and a.DEPT_ID in $DEPT_ID";
    }
}
if($USER_PRIV!="")
    $CONDITION_STR.=" and c.USER_PRIV='$USER_PRIV'";
if($STAFF_NAME!="")
    $CONDITION_STR.=" and a.STAFF_NAME like '%".$STAFF_NAME."%'";
if($STAFF_E_NAME!="")
    $CONDITION_STR.=" and a.STAFF_E_NAME like '%".$STAFF_E_NAME."%'";
if($WORK_STATUS!="")
    $CONDITION_STR.=" and a.WORK_STATUS='$WORK_STATUS'";
if($STAFF_NO!="")
    $CONDITION_STR.=" and a.STAFF_NO like '%".$STAFF_NO."%'";
if($WORK_NO!="")
    $CONDITION_STR.=" and a.WORK_NO like '%".$WORK_NO."%'";
if($STAFF_SEX!="")
    $CONDITION_STR.=" and a.STAFF_SEX='$STAFF_SEX'";
if($WORK_JOB!="")
    $CONDITION_STR.=" and a.WORK_JOB='$WORK_JOB'";
if($STAFF_CARD_NO!="")
    $CONDITION_STR.=" and a.STAFF_CARD_NO like '%".$STAFF_CARD_NO."%'";
if($BIRTHDAY_MIN!="")
    $CONDITION_STR.=" and a.STAFF_BIRTH>='$BIRTHDAY_MIN'";
if($BIRTHDAY_MAX!="")
    $CONDITION_STR.=" and a.STAFF_BIRTH<='$BIRTHDAY_MAX'";
if($STAFF_NATIONALITY!="")
    $CONDITION_STR.=" and a.STAFF_NATIONALITY like '%".$STAFF_NATIONALITY."%'";
if($STAFF_NATIVE_PLACE!="")
    $CONDITION_STR.=" and a.STAFF_NATIVE_PLACE='$STAFF_NATIVE_PLACE'";
if($STAFF_NATIVE_PLACE2!="")
    $CONDITION_STR.=" and a.STAFF_NATIVE_PLACE2 like '%".$STAFF_NATIVE_PLACE2."%'";
if($STAFF_DOMICILE_PLACE!="")
    $CONDITION_STR.=" and a.STAFF_DOMICILE_PLACE like '%".$STAFF_DOMICILE_PLACE."%'";
if($WORK_TYPE!="")
    $CONDITION_STR.=" and a.WORK_TYPE like '%".$WORK_TYPE."%'";
if($STAFF_MARITAL_STATUS!="")
    $CONDITION_STR.=" and a.STAFF_MARITAL_STATUS='$STAFF_MARITAL_STATUS'";
if($STAFF_HEALTH!="")
    $CONDITION_STR.=" and a.STAFF_HEALTH like '%".$STAFF_HEALTH."%'";
if($STAFF_POLITICAL_STATUS!="")
    $CONDITION_STR.=" and a.STAFF_POLITICAL_STATUS='$STAFF_POLITICAL_STATUS'";
if($ADMINISTRATION_LEVEL!="")
    $CONDITION_STR.=" and a.ADMINISTRATION_LEVEL like '%".$ADMINISTRATION_LEVEL."%'";
if($STAFF_OCCUPATION!="")
    $CONDITION_STR.=" and a.STAFF_OCCUPATION='$STAFF_OCCUPATION'";
if($COMPUTER_LEVEL!="")
    $CONDITION_STR.=" and a.COMPUTER_LEVEL like '%".$COMPUTER_LEVEL."%'";
if($STAFF_HIGHEST_SCHOOL!="")
    $CONDITION_STR.=" and a.STAFF_HIGHEST_SCHOOL='$STAFF_HIGHEST_SCHOOL'";
if($STAFF_HIGHEST_DEGREE!="")
    $CONDITION_STR.=" and a.STAFF_HIGHEST_DEGREE='$STAFF_HIGHEST_DEGREE'";
if($STAFF_MAJOR!="")
    $CONDITION_STR.=" and a.STAFF_MAJOR like '%".$STAFF_MAJOR."%'";
if($GRADUATION_SCHOOL!="")
    $CONDITION_STR.=" and a.GRADUATION_SCHOOL like '%".$GRADUATION_SCHOOL."%'";
if($JOB_POSITION!="")
    $CONDITION_STR.=" and a.JOB_POSITION='$JOB_POSITION'";
if($PRESENT_POSITION!="")
    $CONDITION_STR.=" and a.PRESENT_POSITION='$PRESENT_POSITION'";
if($GRADUATION_MIN!="")
    $CONDITION_STR.=" and a.GRADUATION_DATE>='$GRADUATION_MIN'";
if($GRADUATION_MAX!="")
    $CONDITION_STR.=" and a.GRADUATION_DATE<='$GRADUATION_MAX'";
if($JOIN_PARTY_MIN!="")
    $CONDITION_STR.=" and a.JOIN_PARTY_TIME>='$JOIN_PARTY_MIN'";
if($JOIN_PARTY_MAX!="")
    $CONDITION_STR.=" and a.JOIN_PARTY_TIME<='$JOIN_PARTY_MAX'";
if($BEGINNING_MIN!="")
    $CONDITION_STR.=" and a.JOB_BEGINNING>='$BEGINNING_MIN'";
if($BEGINNING_MAX!="")
    $CONDITION_STR.=" and a.JOB_BEGINNING<='$BEGINNING_MAX'";
if($EMPLOYED_MIN!="")
    $CONDITION_STR.=" and a.DATES_EMPLOYED>='$EMPLOYED_MIN'";
if($EMPLOYED_MAX!="")
    $CONDITION_STR.=" and a.DATES_EMPLOYED<='$EMPLOYED_MAX'";
if($WORK_AGE_MIN!="")
    $CONDITION_STR.=" and a.WORK_AGE>='$WORK_AGE_MIN'";
if($WORK_AGE_MAX!="")
    $CONDITION_STR.=" and a.WORK_AGE<='$WORK_AGE_MAX'";
if($JOB_AGE_MIN!="")
    $CONDITION_STR.=" and a.JOB_AGE>='$JOB_AGE_MIN'";
if($JOB_AGE_MAX!="")
    $CONDITION_STR.=" and a.JOB_AGE<='$JOB_AGE_MAX'";
if($LEAVE_TYPE_MIN!="")
    $CONDITION_STR.=" and a.LEAVE_TYPE>='$LEAVE_TYPE_MIN'";
if($LEAVE_TYPE_MAX!="")
    $CONDITION_STR.=" and a.LEAVE_TYPE<='$LEAVE_TYPE_MAX'";
if($FOREIGN_LANGUAGE1!="")
    $CONDITION_STR.=" and a.FOREIGN_LANGUAGE1='$FOREIGN_LANGUAGE1'";
if($FOREIGN_LANGUAGE2!="")
    $CONDITION_STR.=" and a.FOREIGN_LANGUAGE2='$FOREIGN_LANGUAGE2'";
if($FOREIGN_LANGUAGE3!="")
    $CONDITION_STR.=" and a.FOREIGN_LANGUAGE3='$FOREIGN_LANGUAGE3'";
if($FOREIGN_LEVEL1!="")
    $CONDITION_STR.=" and a.FOREIGN_LEVEL1='$FOREIGN_LEVEL1'";
if($FOREIGN_LEVEL2!="")
    $CONDITION_STR.=" and a.FOREIGN_LEVEL2='$FOREIGN_LEVEL2'";
if($FOREIGN_LEVEL3!="")
    $CONDITION_STR.=" and a.FOREIGN_LEVEL3='$FOREIGN_LEVEL3'";
$HRMS_COUNT=0;
$query="select a.DEPT_ID as HRDEPT_ID,a.*,b.*,c.* from HR_STAFF_INFO a
 LEFT OUTER JOIN USER b ON a.USER_ID = b.USER_ID
 LEFT OUTER JOIN USER_PRIV  c ON b.USER_PRIV=c.USER_PRIV

  where 1=1 ".$CONDITION_STR.$WHERE_STR.field_where_str("HR_STAFF_INFO",$_POST,"a.USER_ID");
//echo $query;exit;
$cursor= exequery(TD::conn(),$query);
$USER_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
    $EXCEL_OUT="";
    $USER_ID=$ROW["USER_ID"];
    $DEF_FIELD_ARRAY=get_field_text("HR_STAFF_INFO",$USER_ID);
    $STYLE_ARRAY=explode(",",$FIELDMSG);
    $ARRAY_COUNT=sizeof($STYLE_ARRAY);
    $COUNT=0;
    if($STYLE_ARRAY[$ARRAY_COUNT-1]=="")$ARRAY_COUNT--;

    $MESSAGE=$USER_ID.",";

    foreach($STYLE_ARRAY as $key)
    {
        $MESSAGE=$ROW[$key];

        if($key=="REMARK")
        {
            $query2 = "SELECT REMARK FROM HR_STAFF_INFO where USER_ID='$USER_ID'";
            $cursor2= exequery(TD::conn(),$query2);
            if($ROW2=mysql_fetch_array($cursor2))
                $MESSAGE=$ROW2["REMARK"];
        }
        if($key=="USER_PRIV")
        {
            $MESSAGE=$ROW["PRIV_NAME"];
        }
        if($key=="STAFF_MARITAL_STATUS")
        {
            if($MESSAGE=="0")    $MESSAGE=_("未婚");
            elseif($MESSAGE=="1")$MESSAGE=_("已婚");
            elseif($MESSAGE=="2")$MESSAGE=_("离异");
            elseif($MESSAGE=="3")$MESSAGE=_("丧偶");
        }
        if($key=="STAFF_SEX")
        {
            if($MESSAGE=="0")    $MESSAGE=_("男");
            elseif($MESSAGE=="1")$MESSAGE=_("女");
        }
        if($key=="STAFF_CARD_NO")
        {
            //身份证号码处理,防止在Excel里以科学计数法显示
            if($MESSAGE!=="")  $MESSAGE="\t".$MESSAGE;
        }
        if($key=="STAFF_MOBILE")
        {
            //手机号码处理,防止在Excel里以科学计数法显示
            if($MESSAGE!=="")  $MESSAGE="\t".$MESSAGE;
        }
        if($key=="STAFF_PHONE")
        {
            //联系电话处理,防止在Excel里以科学计数法显示
            if($MESSAGE!=="")  $MESSAGE="\t".$MESSAGE;
        }
        if($key=="STAFF_LITTLE_SMART")
        {
            //小灵通处理,防止在Excel里以科学计数法显示
            if($MESSAGE!=="")  $MESSAGE="\t".$MESSAGE;
        }
        if($key=="STAFF_MSN")
        {
            //MSN处理,防止在Excel里以科学计数法显示
            if($MESSAGE!=="")  $MESSAGE="\t".$MESSAGE;
        }
        if($key=="STAFF_QQ")
        {
            //QQ处理,防止在Excel里以科学计数法显示
            if($MESSAGE!=="")  $MESSAGE="\t".$MESSAGE;
        }
        if($key=="STAFF_EMAIL")
        {
            //电子邮件处理,防止在Excel里以科学计数法显示
            if($MESSAGE!=="")  $MESSAGE="\t".$MESSAGE;
        }
        if($key=="STAFF_BIRTH"||$key=="JOIN_PARTY_TIME"||$key=="JOB_BEGINNING"||$key=="GRADUATION_DATE"||$key=="DATES_EMPLOYED"||$key=="BEGIN_SALSRY_TIME")
        {
            if($MESSAGE=="0000-00-00") $MESSAGE="";
        }

        if(substr($key,0,7)=="USERDEF")
        {
            $MESSAGE=$DEF_FIELD_ARRAY[$key]["HTML"];
        }
        if($key=="DEPT_ID")
        {
            if($MESSAGE!="")
            {
                if($is_leave==1)
                {
                    $MESSAGE=dept_long_name($ROW["HRDEPT_ID"]);
                }
                else
                {
                    $MESSAGE=dept_long_name($ROW[$key]);
                }
            }
        }
        if($key=="WORK_JOB")
        {
            if($MESSAGE!="") $MESSAGE=get_hrms_code_name($ROW[$key],"POOL_POSITION");
        }
        if($key=="STAFF_NATIVE_PLACE")
        {
            if($MESSAGE!="") $MESSAGE=get_hrms_code_name($ROW[$key],"AREA");
        }
        if($key=="STAFF_POLITICAL_STATUS")
        {
            if($MESSAGE!="") $MESSAGE=get_hrms_code_name($ROW[$key],"STAFF_POLITICAL_STATUS");
        }
        if($key=="WORK_STATUS")
        {
            if($MESSAGE!="") $MESSAGE=get_hrms_code_name($ROW[$key],"WORK_STATUS");
        }
        if($key=="STAFF_TYPE")
        {
            if($MESSAGE!="") $MESSAGE=get_hrms_code_name($ROW[$key],"HR_STAFF_TYPE");
        }
        if($key=="STAFF_HIGHEST_SCHOOL")
        {
            if($MESSAGE!="") $MESSAGE=get_hrms_code_name($ROW[$key],"STAFF_HIGHEST_SCHOOL");
        }
        if($key=="STAFF_HIGHEST_DEGREE")
        {
            if($MESSAGE!="") $MESSAGE=get_hrms_code_name($ROW[$key],"EMPLOYEE_HIGHEST_DEGREE");
        }
        if($key=="STAFF_OCCUPATION")
        {
            if($MESSAGE!="") $MESSAGE=get_hrms_code_name($ROW[$key],"STAFF_OCCUPATION");
        }
        if($key=="PRESENT_POSITION")
        {
            if($MESSAGE!="") $MESSAGE=get_hrms_code_name($ROW[$key],"PRESENT_POSITION");
        }
        if($key=="STAFF_AGE")
        {
            $STAFF_BIRTH = $ROW["STAFF_BIRTH"];
            if($STAFF_BIRTH == "0000-00-00")
                $MESSAGE = '';
            else
            {
                if($STAFF_BIRTH!="")
                {
                    $agearray = explode("-",$STAFF_BIRTH);
                    $cur = explode("-",$CUR_DATE);
                    $year=$agearray[0];
                    $STAFF_AGE=date("Y")-$year-1;
                    if($cur[1] > $agearray[1] || ($cur[1]==$agearray[1] && $cur[2]>=$agearray[2]))
                    {
                        $STAFF_AGE++;
                    }
                }
                else
                {
                    $STAFF_AGE="";
                }
                /*if($STAFF_AGE!="")
                {
                    $STAFF_AGE=$STAFF_AGE-1;
                    $MESSAGE = $STAFF_AGE;
                    $query10="update HR_STAFF_INFO set STAFF_AGE='$STAFF_AGE' where USER_ID='$USER_ID'";
                    exequery(TD::conn(),$query10);
                    }*/
            }
            $MESSAGE = $STAFF_AGE;//date('Y',time())-date('Y',strtotime($STAFF_BIRTH));
        }
        if($key=="WORK_LEVEL")
        {
            if($MESSAGE!="") $MESSAGE=get_hrms_code_name($ROW[$key],"WORK_LEVEL");
        }
        if($key=="RESUME")
        {
            if($MESSAGE!="") $MESSAGE=str_replace("&nbsp;","",strip_tags($ROW[$key]));
        }
        if($key=="USER_ID")
        {
            if($MESSAGE!="") $MESSAGE=$user_info_arr[$ROW[$key]];
        }
        if($key=="GET_OUT_TIME")
        {
            $query3 = "SELECT LAST_VISIT_TIME FROM user where USER_ID='$USER_ID'";
            $cursor3= exequery(TD::conn(),$query3);
            if($ROW3=mysql_fetch_array($cursor3))
                $MESSAGE=$ROW3["LAST_VISIT_TIME"];
        }

        $EXCEL_OUT.=format_cvs($MESSAGE).",";
    }
    //echo $EXCEL_OUT;exit;
    $objExcel->addRow($EXCEL_OUT);
}
ob_end_clean();

$objExcel->Save();

/*
Header("Cache-control: private");
Header("Content-type: application/vnd.ms-excel");
Header("Accept-Ranges: bytes");
Header("Accept-Length: ".strlen($EXCEL_OUT));
Header("Content-Disposition: attachment; ".get_attachment_filename($file_name.".csv"));

if(MYOA_IS_UN == 1)
   echo chr(0xEF).chr(0xBB).chr(0xBF);

echo $EXCEL_OUT;
*/
?>