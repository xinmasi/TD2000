<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");
include_once("inc/utility_field.php");
include_once("inc/utility_cache.php");
include_once("general/system/log/annual_leave_log.php");
if(strstr($BYNAME,"/")||strstr($BYNAME,"\\")||strstr($BYNAME,".."))
{
    Message(_("错误"),_("OA用户名包含非法字符！"));
    exit;
}

include_once("inc/header.inc.php");

?>

<body class="bodycolor">
<?
//上传照片
$PHOTO_NAME0 = $_FILES["ATTACHMENT"]["name"];
$ATTACHMENT = $_FILES["ATTACHMENT"]["tmp_name"];
if($PHOTO_NAME0!="")
{
    $FULL_PATH = MYOA_ATTACH_PATH."hrms_pic";
    if(!file_exists($FULL_PATH))
        @mkdir($FULL_PATH,0700);
    
    $PHOTO_NAME = $USER_ID.substr($PHOTO_NAME0,strrpos($PHOTO_NAME0,"."));
    $FILENAME=MYOA_ATTACH_PATH."hrms_pic/".$PHOTO_NAME;
    td_copy($ATTACHMENT,$FILENAME);
    if(file_exists($ATTACHMENT))
    {
        unlink($ATTACHMENT);
    }
    
    if(!file_exists($FILENAME))
    {
        Message(_("附件上传失败"),_("原因：附件文件为空或文件名太长，或附件大于30兆字节，或文件路径不存在！"));
        Button_Back();
        exit;
    }
}

if(count($_FILES)>1)
{
    $ATTACHMENTS=upload("ATTACHMENT1");
    
    $ATTACHMENT_ID=$ATTACHMENT_ID_OLD.$ATTACHMENTS["ID"];
    $ATTACHMENT_NAME=$ATTACHMENT_NAME_OLD.$ATTACHMENTS["NAME"];
}
else
{
    $ATTACHMENT_ID=$ATTACHMENT_ID_OLD;
    $ATTACHMENT_NAME=$ATTACHMENT_NAME_OLD;
}
$ATTACHMENT_ID.=copy_sel_attach($ATTACH_NAME1,$ATTACH_DIR1,$DISK_ID1);
$ATTACHMENT_NAME.=$ATTACH_NAME1;
$C = preg_match('/<img.*?\ssrc=\\\"\/inc\/attach.php\?(.*)MODULE=upload_temp/i',$RESUME);
$RESUME = replace_attach_url($RESUME);
if($C==1)
{
    $ATTACHMENT_ID=move_attach($ATTACHMENT_ID,$ATTACHMENT_NAME,"","",true).",";
}
//------------------- 人事档案信息 -----------------------
if($YES_OR_NOT=="on")
{
    $YES_OR_NOT=1;
    $NOT_LOGIN=0;
}
else
{
    $YES_OR_NOT=0;
    $NOT_LOGIN=1;
}
//------------------- 岗位职责与专家信息 -----------------------
if($batch=="1")
{
    $IS_EXPERTS=1;
}
else
{
    $IS_EXPERTS=0;
}

//----------信息判断-------------------------
if(find_id($COPY_TO_ID1,$USER_ID) or find_id($COPY_TO_ID,$USER_ID))
{
    Message(_("错误"),_("直属上级和直属下级中包含有本人。"));
    Button_Back();
    exit();
}

$COPY_TO_ID_ARRAY=  explode(',', trim($COPY_TO_ID,','));
foreach($COPY_TO_ID_ARRAY as $value)
{
    if(find_id($COPY_TO_ID1,$value))
    {
        Message(_("错误"),_("直属上级和直属下级有重复的数据。"));
        Button_Back();
        exit();
    }
    else
    {
        $query_info="select * from hr_staff_info where USER_ID='$value'";
        $cursor_info= exequery(TD::conn(),$query_info);
        if($ROW=mysql_fetch_array($cursor_info))
        {
            $DIRECTLY_UNDER=$ROW["DIRECTLY_UNDER"];
            $DIRECTLY_UNDER_ARRAY=explode(',', trim($DIRECTLY_UNDER,','));
            foreach ($DIRECTLY_UNDER_ARRAY as $value1)
            {
                if(find_id($COPY_TO_ID1,$value))
                {
                    Message(_("错误"),_("直属上级和下级的下级有重复的数据。"));
                    Button_Back();
                    exit();
                }
            }
        }
    }
}

$CUR_TIME=date("Y-m-d H:i:s",time());
//-- 保存 --
$IS_LUNAR=$IS_LUNAR=="1"?"1":"0";
$sql = "select LEAVE_TYPE from hr_staff_info where USER_ID='$USER_ID'";
$result = exequery(TD::conn(),$sql);
if($row = mysql_fetch_array($result))
{
    $leave_type_old = $row['LEAVE_TYPE'];
}
$query="update HR_STAFF_INFO set CREATE_USER_ID='".$_SESSION["LOGIN_USER_ID"]."',DEPT_ID='$DEPT_ID',STAFF_NO='$STAFF_NO',WORK_NO='$WORK_NO',WORK_TYPE='$WORK_TYPE',STAFF_NAME='$STAFF_NAME',BEFORE_NAME='$BEFORE_NAME',STAFF_E_NAME='$STAFF_E_NAME',STAFF_CARD_NO='$STAFF_CARD_NO',    STAFF_SEX='$STAFF_SEX',BLOOD_TYPE='$BLOOD_TYPE',STAFF_BIRTH='$STAFF_BIRTH',STAFF_NATIVE_PLACE='$STAFF_NATIVE_PLACE',STAFF_NATIVE_PLACE2='$STAFF_NATIVE_PLACE2',STAFF_DOMICILE_PLACE='$STAFF_DOMICILE_PLACE',STAFF_NATIONALITY='$STAFF_NATIONALITY',STAFF_MARITAL_STATUS='$STAFF_MARITAL_STATUS',STAFF_POLITICAL_STATUS='$STAFF_POLITICAL_STATUS',JOIN_PARTY_TIME='$JOIN_PARTY_TIME',STAFF_PHONE='$STAFF_PHONE',STAFF_MOBILE='$STAFF_MOBILE',STAFF_LITTLE_SMART='$STAFF_LITTLE_SMART',STAFF_EMAIL='$STAFF_EMAIL',STAFF_MSN='$STAFF_MSN',STAFF_QQ='$STAFF_QQ',HOME_ADDRESS='$HOME_ADDRESS',OTHER_CONTACT='$OTHER_CONTACT',JOB_BEGINNING='$JOB_BEGINNING',WORK_AGE='$WORK_AGE',STAFF_HEALTH='$STAFF_HEALTH',IS_LUNAR='$IS_LUNAR',STAFF_HIGHEST_SCHOOL='$STAFF_HIGHEST_SCHOOL',STAFF_HIGHEST_DEGREE='$STAFF_HIGHEST_DEGREE',GRADUATION_DATE='$GRADUATION_DATE',GRADUATION_SCHOOL='$GRADUATION_SCHOOL',STAFF_MAJOR='$STAFF_MAJOR',COMPUTER_LEVEL='$COMPUTER_LEVEL',FOREIGN_LANGUAGE1='$FOREIGN_LANGUAGE1',FOREIGN_LEVEL1='$FOREIGN_LEVEL1',FOREIGN_LANGUAGE2='$FOREIGN_LANGUAGE2',FOREIGN_LEVEL2='$FOREIGN_LEVEL2',FOREIGN_LANGUAGE3='$FOREIGN_LANGUAGE3',FOREIGN_LEVEL3='$FOREIGN_LEVEL3',STAFF_SKILLS='$STAFF_SKILLS',JOB_POSITION='$JOB_POSITION',PRESENT_POSITION='$PRESENT_POSITION',DATES_EMPLOYED='$DATES_EMPLOYED',JOB_AGE='$JOB_AGE',BEGIN_SALSRY_TIME='$BEGIN_SALSRY_TIME',WORK_STATUS='$WORK_STATUS',STAFF_CS='$STAFF_CS',STAFF_CTR='$STAFF_CTR',REMARK='$REMARK',STAFF_COMPANY='$STAFF_COMPANY',RESUME='$RESUME',       STAFF_OCCUPATION='$STAFF_OCCUPATION',ATTACHMENT_ID='$ATTACHMENT_ID',ATTACHMENT_NAME='$ATTACHMENT_NAME',ADMINISTRATION_LEVEL='$ADMINISTRATION_LEVEL',STAFF_TYPE='$STAFF_TYPE',LEAVE_TYPE='$LEAVE_TYPE',USERDEF1='$USERDEF1',USERDEF2='$USERDEF2',USERDEF3='$USERDEF3',USERDEF4='$USERDEF4',USERDEF5='$USERDEF5',CERTIFICATE='$CERTIFICATE',SURETY='$SURETY',INSURE='$INSURE',BODY_EXAMIM='$BODY_EXAMIM',LAST_UPDATE_TIME='$CUR_TIME',WORK_LEVEL='$WORK_LEVEL',WORK_JOB='$WORK_JOB',BANK1='$BANK1',BANK_ACCOUNT1='$BANK_ACCOUNT1',BANK2='$BANK2',BANK_ACCOUNT2='$BANK_ACCOUNT2',IS_EXPERTS='$IS_EXPERTS',EXPERTS_INFO='$EXPERTS_INFO',DIRECTLY_UNDER='$COPY_TO_ID',DIRECTLY_SUPERIOR='$COPY_TO_ID1',PART_TIME='$PART_TIME',RESEARCH_RESULTS='$RESEARCH_RESULTS'";
if($PHOTO_NAME!="")
    $query.=",PHOTO_NAME='$PHOTO_NAME'";

$query.=" where USER_ID='$USER_ID'";
exequery(TD::conn(),$query);
if($leave_type_old!=$LEAVE_TYPE)
{
    $log_data = array(
            "src" => array(
                "annualleave_old" => $leave_type_old
                ),
			"des" => array(
			        "annualleave" => $LEAVE_TYPE
			    )
	);
	addAnnualLeaveLog($uid,$log_data,2);
}
$query="select SEX from USER where USER_ID='$USER_ID'";
$cursor = exequery(TD::conn(),$query);
if($ROW = mysql_fetch_array($cursor))
{
    $SEX_OLD = $ROW['SEX'];
    
    if($SEX_OLD != $STAFF_SEX)
        set_sys_para(array("ORG_UPDATE" => date("Y-m-d H:i:s")));
}

$where_str = "";

$sql = "SELECT UID FROM user WHERE (AVATAR=0 or AVATAR=1) and USER_ID='$USER_ID'";
$cur = exequery(TD::conn(),$sql);
if(mysql_affected_rows()>0)
{
	$where_str = ",AVATAR='$STAFF_SEX' ";
}

$query="update USER set SEX='$STAFF_SEX',BIRTHDAY='$STAFF_BIRTH',IS_LUNAR='$IS_LUNAR'".$where_str." where USER_ID='$USER_ID'";
exequery(TD::conn(),$query);

$query2="update USER_EXT set DUTY_TYPE='$DUTY_TYPE' where USER_ID='$USER_ID'";
exequery(TD::conn(),$query2);

save_field_data("HR_STAFF_INFO",$USER_ID,$_POST);

cache_users();

Message(_("提示"),_("档案已保存。"));

$paras = strpos($_SERVER["HTTP_REFERER"], "?") ? $paras = $_SERVER["HTTP_REFERER"]."&connstatus=1" : $paras = $_SERVER["HTTP_REFERER"]."?connstatus=1";
?>
<center>
    <input type="button" class="BigButton" value="<?=_("返回")?>" onClick="window.location.href='<?echo "staff_info.php?USER_ID=$USER_ID&connstatus=1"; ?>'"/>
</center>
</body>
</html>
