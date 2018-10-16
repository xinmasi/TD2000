<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");
include_once("inc/check_type.php");
include_once("inc/utility_sms1.php");
include_once("inc/utility_sms2.php");
include_once("inc/utility_cache.php");

$HTML_PAGE_TITLE = _("人事调动信息");
include_once("inc/header.inc.php");
?>

<body class="bodycolor" topmargin="5">

<?
$CUR_TIME=date("Y-m-d H:i:s",time());
//-----------------校验-------------------------------------
if($TRANSFER_PERSON=="admin")
{
    Message("",_("系统管理员不允许调动！"));
    Button_Back();
    exit;
}
if($TRANSFER_DATE!="" && !is_date($TRANSFER_DATE))
{
    Message("",_("调动日期应为日期型，如：1999-01-01"));
    Button_Back();
    exit;
}
if($TRANSFER_EFFECTIVE_DATE!="" && !is_date($TRANSFER_EFFECTIVE_DATE))
{
    Message("",_("调动生效日期应为日期型，如：1999-01-01"));
    Button_Back();
    exit;
}
//--------- 上传附件 ----------
if(count($_FILES)>1)
{
    $ATTACHMENTS=upload();
    $CONTENT=ReplaceImageSrc($CONTENT, $ATTACHMENTS);

    $ATTACHMENT_ID=$ATTACHMENTS["ID"];
    $ATTACHMENT_NAME=$ATTACHMENTS["NAME"];
}

$ATTACHMENT_ID.=copy_sel_attach($ATTACH_NAME,$ATTACH_DIR,$DISK_ID);
$ATTACHMENT_NAME.=$ATTACH_NAME;
//------------事务提醒-----------
$query = "SELECT DEPT_HR_MANAGER from HR_MANAGER where DEPT_ID='$TRAN_DEPT_BEFORE'";
$cursor= exequery(TD::conn(),$query);
$DEPT_HR_MANAGER1="";
if($ROW=mysql_fetch_array($cursor))
    $DEPT_HR_MANAGER1 = $ROW["DEPT_HR_MANAGER"];
$query = "SELECT DEPT_HR_MANAGER from HR_MANAGER where DEPT_ID='$TRAN_DEPT_AFTER'";
$cursor= exequery(TD::conn(),$query);
$DEPT_HR_MANAGER2="";
if($ROW=mysql_fetch_array($cursor))
    $DEPT_HR_MANAGER2 = $ROW["DEPT_HR_MANAGER"];
$TMP_ARRAY = explode(",",$DEPT_HR_MANAGER1);
for($I=0;$I<=count($TMP_ARRAY);$I++)
{
    if($TMP_ARRAY[$I]!=""&&!find_id($DEPT_HR_MANAGER2,$TMP_ARRAY[$I]))
        $DEPT_HR_MANAGER2.=$TMP_ARRAY[$I].',';
}
$query="insert into HR_STAFF_TRANSFER(CREATE_USER_ID,CREATE_DEPT_ID,TRANSFER_PERSON,TRANSFER_DATE,TRANSFER_EFFECTIVE_DATE,TRANSFER_TYPE,TRAN_COMPANY_BEFORE,TRAN_DEPT_BEFORE,TRAN_POSITION_BEFORE,TRAN_COMPANY_AFTER,TRAN_DEPT_AFTER,TRAN_POSITION_AFTER,TRAN_REASON,MATERIALS_CONDITION,REMARK,ATTACHMENT_ID,ATTACHMENT_NAME,ADD_TIME,LAST_UPDATE_TIME) values ('".$_SESSION["LOGIN_USER_ID"]."','".$_SESSION["LOGIN_DEPT_ID"]."','$TRANSFER_PERSON','$TRANSFER_DATE','$TRANSFER_EFFECTIVE_DATE','$TRANSFER_TYPE','$TRAN_COMPANY_BEFORE','$TRAN_DEPT_BEFORE','$TRAN_POSITION_BEFORE','$TRAN_COMPANY_AFTER','$TRAN_DEPT_AFTER','$TRAN_POSITION_AFTER','$TRAN_REASON','$MATERIALS_CONDITION','$REMARK','$ATTACHMENT_ID','$ATTACHMENT_NAME','$CUR_TIME','$CUR_TIME')";
exequery(TD::conn(),$query);
$TRANSFER_ID = mysql_insert_id();

$query = "update USER SET DEPT_ID='$TRAN_DEPT_AFTER' where USER_ID = '$TRANSFER_PERSON'";
exequery(TD::conn(),$query);

set_sys_para(array("ORG_UPDATE" => date("Y-m-d H:i:s")));

cache_users();

$query = "update  HR_STAFF_INFO SET DEPT_ID='$TRAN_DEPT_AFTER',JOB_POSITION='$TRAN_POSITION_AFTER' where USER_ID = '$TRANSFER_PERSON'";
exequery(TD::conn(),$query);
//------- 更新员工角色 --------
if($role != "")
{
    $query2 = "SELECT * from  user_priv WHERE USER_PRIV = '$role'";
    $cursor2= exequery(TD::conn(),$query2);
    if($ROW2=mysql_fetch_array($cursor2))
    {
        $PRIV_NO = $ROW2["PRIV_NO"];
        $PRIV_NAME= $ROW2["PRIV_NAME"];
    }
    $query="UPDATE user SET USER_PRIV='$role',USER_PRIV_NO='$PRIV_NO',USER_PRIV_NAME='$PRIV_NAME' WHERE USER_ID = '$PERSON'";
    exequery(TD::conn(),$query);
}

if($SMS_REMIND=="on" && $DEPT_HR_MANAGER2!="")
{
    $REMIND_URL="ipanel/hr/transfer_detail.php?TRANSFER_ID=".$TRANSFER_ID;
    $SMS_CONTENT=_("请查看人事调动信息");
    send_sms($SEND_TIME,$_SESSION["LOGIN_USER_ID"],$DEPT_HR_MANAGER2,56,$SMS_CONTENT,$REMIND_URL,$TRANSFER_ID);
}
Message("",_("成功增加人事调动信息！"));

?>
<br><center><input type="button" value="<?=_("返回")?>" class="BigButton" onClick="location.href='transfer_new.php'"></center>
</body>
</html>
