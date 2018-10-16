<?
include_once("inc/auth.inc.php");
include_once("inc/utility_file.php");
include_once("inc/check_type.php");
include_once("inc/utility_sms1.php");
include_once("inc/utility_sms2.php");

$HTML_PAGE_TITLE = _("新建需求信息");
include_once("inc/header.inc.php");
?>

<body class="bodycolor">
<?
$CUR_TIME=date("Y-m-d H:i:s",time());
//-----------------校验-------------------------------------
if($REQU_TIME!="" && !is_date($REQU_TIME))
{
    Message("",_("用工日期应为日期型，如：1999-01-01"));
    Button_Back();
    exit;
}
//--------- 上传附件 ----------
if(count($_FILES)>1)
{
    $ATTACHMENTS=upload();

    $ATTACHMENT_ID=$ATTACHMENTS["ID"];
    $ATTACHMENT_NAME=$ATTACHMENTS["NAME"];
}

$ATTACHMENT_ID.=copy_sel_attach($ATTACH_NAME,$ATTACH_DIR,$DISK_ID);
$ATTACHMENT_NAME.=$ATTACH_NAME;

$query="insert into  HR_RECRUIT_REQUIREMENTS (
        CREATE_USER_ID,
        CREATE_DEPT_ID,
        REQU_DEPT,
        REQU_JOB,
        REQU_NUM,
        REQU_REQUIRES,
        REQU_TIME,
        PETITIONER,
        REMARK,
        REQU_NO,
        ATTACHMENT_ID,
        ATTACHMENT_NAME,
        ADD_TIME)
      values
      ( '".$_SESSION["LOGIN_USER_ID"]."',
      	'".$_SESSION["LOGIN_DEPT_ID"]."',
      	'$REQU_DEPT',
      	'$REQU_JOB',
      	'$REQU_NUM',
      	'$REQU_REQUIRES',
      	'$REQU_TIME',
      	'".$_SESSION["LOGIN_USER_ID"]."',
      	'$REMARK',
      	'$REQU_NO',
      	'$ATTACHMENT_ID',
      	'$ATTACHMENT_NAME',
      	'$CUR_TIME')";
exequery(TD::conn(),$query);
$req_num=mysql_insert_id();

//------- 事务提醒 --------
$query="select * from HR_MANAGER where DEPT_ID='".$_SESSION["LOGIN_DEPT_ID"]."'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
    $DEPT_HR_MANAGER=$ROW["DEPT_HR_MANAGER"];
$TMP_ARRAY = explode(",",substr($DEPT_HR_MANAGER,0,-1));
for($I=0;$I< count($TMP_ARRAY);$I++)
{
    $REMAND_USERS.=$TMP_ARRAY[$I].',';
}

$REMAND_USERS = td_trim($REMAND_USERS);
if($REMAND_USERS!="" && $SMS_REMIND=="on" && $req_num!=0)
{
    $REMIND_URL="ipanel/hr/requirements_detail.php?REQUIREMENTS_ID=".$req_num;
    $SMS_CONTENT=_("请查看招聘需求").$REQU_NO;
    send_sms($SEND_TIME,$_SESSION["LOGIN_USER_ID"],$REMAND_USERS,60,$SMS_CONTENT,$REMIND_URL,$req_num);
}

if($REMAND_USERS!="" && $SMS2_REMIND=="on")
{
    $SMS_CONTENT=_("OA招聘需求:请查看招聘需求").$REQU_NO;
    send_mobile_sms_user($SEND_TIME,$_SESSION["LOGIN_USER_ID"],$REMAND_USERS,$SMS_CONTENT,60);
}
Message("",_("成功增加需求信息！"));
?>
<br><center><input type="button" value="<?=_("返回")?>" class="BigButton" onClick="location.href='new.php'"></center>
</body>
</html>
