<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/check_type.php");
include_once("inc/utility_file.php");
include_once("inc/utility_field.php");
include_once("inc/utility_sms1.php");
include_once("inc/utility_cache.php");

if($LEAVE_PERSON=="admin")
{
    Message("",_("���ܶ�admin�û�������ְ����"));
    Button_Back();
    exit;
}

$HTML_PAGE_TITLE = _("Ա����ְ��Ϣ�޸ı���");
include_once("inc/header.inc.php");
?>

<body class="bodycolor">

<?
if(count($_FILES)>1)
{
    $ATTACHMENTS=upload();
    $CONTENT=ReplaceImageSrc($CONTENT, $ATTACHMENTS);
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
//-----------------�Ϸ���У��-------------------------------------

if($QUIT_TIME_PLAN!="" && !is_date($QUIT_TIME_PLAN))
{
    Message("",_("����ְ����ӦΪ�����ͣ��磺1999-01-01"));
    Button_Back();
    exit;
}
if($QUIT_TIME_FACT!="" && !is_date($QUIT_TIME_FACT))
{
    Message("",_("ʵ����ְ����ӦΪ�����ͣ��磺1999-01-01"));
    Button_Back();
    exit;
}
if($LAST_SALARY_TIME!="" && !is_date($LAST_SALARY_TIME))
{
    Message("",_("���ʽ�ֹ����ӦΪ�����ͣ��磺1999-01-01"));
    Button_Back();
    exit;
}
if($APPLICATION_DATE!="" && !is_date($APPLICATION_DATE))
{
    Message("",_("��������ӦΪ�����ͣ��磺1999-01-01"));
    Button_Back();
    exit;
}
if($SALARY=="")
    $SALARY=0;
$query="select DEPT_ID,USER_NAME from USER where USER_ID='$LEAVE_PERSON'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
    $DEPT_ID=$ROW["DEPT_ID"];
    $USER_NAME=$ROW["USER_NAME"];
}
if($LEAVE=="ack")
{
$query="update USER set DEPT_ID='0',NOT_LOGIN='1' where USER_ID='$LEAVE_PERSON'";
exequery(TD::conn(),$query);

set_sys_para(array("ORG_UPDATE" => date("Y-m-d H:i:s")));

cache_users();

$WORK_STATUS=$QUIT_TYPE==""?"":'0'.($QUIT_TYPE+1);
$query="update HR_STAFF_INFO set DEPT_ID='$LEAVE_DEPT', WORK_STATUS='$WORK_STATUS' where USER_ID='$LEAVE_PERSON'";
exequery(TD::conn(),$query);

$query="UPDATE HR_STAFF_LEAVE SET QUIT_TIME_PLAN='$QUIT_TIME_PLAN',QUIT_TYPE='$QUIT_TYPE',QUIT_REASON='$QUIT_REASON',LAST_SALARY_TIME='$LAST_SALARY_TIME',TRACE='$TRACE',REMARK='$REMARK',QUIT_TIME_FACT='$QUIT_TIME_FACT',LEAVE_PERSON='$LEAVE_PERSON',MATERIALS_CONDITION='$MATERIALS_CONDITION',POSITION='$POSITION',ATTACHMENT_ID='$ATTACHMENT_ID',ATTACHMENT_NAME='$ATTACHMENT_NAME',APPLICATION_DATE='$APPLICATION_DATE',LEAVE_DEPT='$LEAVE_DEPT',LAST_UPDATE_TIME='$CUR_TIME',SALARY='$SALARY' WHERE LEAVE_ID = '$LEAVE_ID'";
exequery(TD::conn(),$query);
//��¼ϵͳ��־
add_log(23,$USER_NAME._("������ְ"),$_SESSION["LOGIN_USER_ID"]);
//������������û�
if($NOTIFY=="on")
{
    $SMS_CONTENT=_("Ա��").$USER_NAME._("(").$LEAVE_DEPT_NAME._(")�Ѱ�����ְ����!");
    if($TO_ID!="")
        send_sms("",$_SESSION["LOGIN_USER_ID"],$TO_ID,64,$SMS_CONTENT,"ipanel/user/user_info.php?USER_ID=".$LEAVE_PERSON,$LEAVE_PERSON);
}
?>
<script type="text/javascript">
    window.resizeTo(500,200);
</script>
<?
Message("",_("�û�").$USER_NAME._("(").$LEAVE_DEPT_NAME._(")��ְ�Ѱ������!"));
}
Message("",_("�޸ĳɹ���"));
parse_str($_SERVER["HTTP_REFERER"], $tmp_url);
$paras = strpos($_SERVER["HTTP_REFERER"], "?") ? isset($tmp_url["connstatus"]) ? $_SERVER["HTTP_REFERER"] : $_SERVER["HTTP_REFERER"]."&connstatus=1" : $paras = $_SERVER["HTTP_REFERER"]."?connstatus=1";

?>

<center>
    <input type="button" class="BigButton" value="<?=_("����")?>" onclick="window.location.href='<?=$paras?>'"/>
</center>
</body>
</html>
