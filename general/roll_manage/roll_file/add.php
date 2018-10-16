<?
include_once("inc/auth.inc.php");
include_once("inc/utility.php");
include_once("inc/utility_file.php");
include_once("inc/td_core.php");
include_once("inc/utility_sms1.php");
include_once("inc/utility_sms2.php");

include_once("inc/utility_org.php");
include_once("inc/utility_secu.php");

$secu_arr = check_secure_rule( );
$secu = $secu_arr['SWITCH'];

$HTML_PAGE_TITLE = _("新建文件");
include_once("inc/header.inc.php");
?>

<body class="bodycolor">

<?
//if($DOWNLOAD_YN=="on")
//   $DOWNLOAD_YN = 1;
//else
//   $DOWNLOAD_YN = 0;

$sql1="SELECT * FROM  rms_file WHERE ROLL_ID = '$ROLL_ID' AND (FILE_CODE = '$FILE_CODE' AND FILE_TITLE = '$FILE_TITLE')";
$cur= exequery(TD::conn(),$sql1);
if(mysql_affected_rows()>0)
{
    Message(_("错误"),_("相同案卷文件号或文件名称必须唯一"));
    Button_Back();
    exit;
}


if($DOWNLOAD=="on")
    $DOWNLOAD = 1;
else
    $DOWNLOAD = 0;

if($PRINT=="on")
    $PRINT = 1;
else
    $PRINT = 0;
//--------- 上传附件 ----------
if(count($_FILES)>1)
{
    $ATTACHMENTS=upload();

    $ATTACHMENT_ID=$ATTACHMENT_ID_OLD.$ATTACHMENTS["ID"];
    $ATTACHMENT_NAME=$ATTACHMENT_NAME_OLD.$ATTACHMENTS["NAME"];
}
else
{
    $ATTACHMENT_ID=$ATTACHMENT_ID_OLD;
    $ATTACHMENT_NAME=$ATTACHMENT_NAME_OLD;
}

$ATTACHMENT_ID.=copy_sel_attach($ATTACH_NAME,$ATTACH_DIR,$DISK_ID);
$ATTACHMENT_NAME.=$ATTACH_NAME;
$CUR_TIME=date("Y-m-d H:i:s",time());

setcookie("ROLL_ID_COOKIE", "$ROLL_ID");

//------------------- 新建文件 -----------------------
$query="insert into RMS_FILE(ADD_USER,ADD_TIME,FILE_CODE,FILE_TITLE,FILE_TITLE0,FILE_SUBJECT,SEND_UNIT,SEND_DATE,SECRET,URGENCY,FILE_KIND,FILE_TYPE,FILE_PAGE,PRINT_PAGE,BORROW,REMARK,ATTACHMENT_ID,ATTACHMENT_NAME,BORROW_STATUS,ROLL_ID,ISAUDIT,DOWNLOAD,PRINT) values ('".$_SESSION["LOGIN_USER_ID"]."','$CUR_TIME','$FILE_CODE','$FILE_TITLE','$FILE_TITLE0','$FILE_SUBJECT','$SEND_UNIT','$SEND_DATE','$SECRET','$URGENCY','$FILE_KIND','$FILE_TYPE','$FILE_PAGE','$PRINT_PAGE','$BORROW','$REMARK','$ATTACHMENT_ID','$ATTACHMENT_NAME','$BORROW_STATUS','$ROLL_ID','$ISAUDIT','$DOWNLOAD','$PRINT')";
exequery(TD::conn(),$query);

$FILE_ID=mysql_insert_id();

if($SMS_REMIND=="on")
{
    $query_r = "SELECT RMS_ROLL_ROOM.MANAGE_USER,RMS_ROLL.MANAGER,RMS_ROLL.ROLL_NAME,RMS_FILE.FILE_TITLE from RMS_FILE,RMS_ROLL,RMS_ROLL_ROOM where RMS_FILE.ROLL_ID=RMS_ROLL.ROLL_ID and RMS_ROLL.ROOM_ID=RMS_ROLL_ROOM.ROOM_ID and RMS_FILE.FILE_ID = '$FILE_ID'";
    $cursor_r = exequery(TD::conn(),$query_r);
    if($ROW = mysql_fetch_array($cursor_r))
    {
        $MANAGE_USER = $ROW['MANAGE_USER'];
        $MANAGER = $ROW['MANAGER'];
        $ROLL_NAME = $ROW['ROLL_NAME'];
        $FILE_TITLE = $ROW['FILE_TITLE'];
    }
    $TO_ID=$MANAGE_USER.",".$MANAGER;
    $REMIND_URL="/roll_manage/roll_file/index1.php";
    send_sms("",$_SESSION["LOGIN_USER_ID"],$TO_ID,37,sprintf(_("[%s]在%s里面新建了文件-%s"), $_SESSION["LOGIN_USER_NAME"], $ROLL_NAME, $FILE_TITLE),$REMIND_URL);
    //send_sms("",$_SESSION["LOGIN_USER_ID"],$MANAGER,37,sprintf(_("[%s]在%s里面新建了文件-%s"), $_SESSION["LOGIN_USER_NAME"], $ROLL_NAME, $FILE_TITLE),$REMIND_URL);
}
if($OP==0)
    header("location:modify.php?FILE_ID=$FILE_ID");
else
{

    Message("",_("文件新建成功！"));
    $paras = strpos($_SERVER["HTTP_REFERER"], "?") ? $paras = $_SERVER["HTTP_REFERER"]."&connstatus=1" : $paras = $_SERVER["HTTP_REFERER"]."?connstatus=1";
    if($secu == 1 && ( dongle_optional("SEC_RULE") || tdoa_optional("SEC_RULE")) ){
        $CONTENT=sprintf(_("新建案卷文件:%s"), $FILE_CODE);
        add_secure_log("",$CONTENT);
    }
}
?>
<center>
    <input type="button" class="BigButton" value="<?=_("返回")?>" onClick="window.location.href='<?=$paras?>'"/>
</center>
</body>
</html>
