<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");
include_once("inc/check_type.php");
include_once("inc/utility_sms1.php");
include_once("inc/utility_cache.php");

$HTML_PAGE_TITLE = _("Ա����ְ��Ϣ");
include_once("inc/header.inc.php");
?>

<body class="bodycolor">

<?
if($LEAVE_PERSON=="admin")
{
    Message("",_("���ܶ�admin�û�������ְ����"));
    Button_Back();
    exit;
}
$CUR_TIME=date("Y-m-d H:i:s",time());
//-----------------У��-------------------------------------
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
//--------- �ϴ����� ----------
if(count($_FILES)>1)
{
    $ATTACHMENTS=upload();
    $CONTENT=ReplaceImageSrc($CONTENT, $ATTACHMENTS);

    $ATTACHMENT_ID=$ATTACHMENTS["ID"];
    $ATTACHMENT_NAME=$ATTACHMENTS["NAME"];
}

$ATTACHMENT_ID.=copy_sel_attach($ATTACH_NAME,$ATTACH_DIR,$DISK_ID);
$ATTACHMENT_NAME.=$ATTACH_NAME;

//------------------- ������ְ��Ϣ -----------------------
$query="select DEPT_ID,USER_NAME from USER where USER_ID='$LEAVE_PERSON'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
    $DEPT_ID=$ROW["DEPT_ID"];
    $USER_NAME=$ROW["USER_NAME"];
}
if($LEAVE=="ack")
{
    $query="insert into HR_STAFF_LEAVE(CREATE_USER_ID,CREATE_DEPT_ID,QUIT_TIME_PLAN,QUIT_TYPE,QUIT_REASON,LAST_SALARY_TIME,TRACE,REMARK,QUIT_TIME_FACT,LEAVE_PERSON,MATERIALS_CONDITION,POSITION,ATTACHMENT_ID,ATTACHMENT_NAME,APPLICATION_DATE,LEAVE_DEPT,ADD_TIME,LAST_UPDATE_TIME,SALARY) values ('".$_SESSION["LOGIN_USER_ID"]."','".$_SESSION["LOGIN_DEPT_ID"]."','$QUIT_TIME_PLAN','$QUIT_TYPE','$QUIT_REASON','$LAST_SALARY_TIME','$TRACE','$REMARK','$QUIT_TIME_FACT','$LEAVE_PERSON','$MATERIALS_CONDITION','$POSITION','$ATTACHMENT_ID','$ATTACHMENT_NAME','$APPLICATION_DATE','$LEAVE_DEPT','$CUR_TIME','$CUR_TIME','$SALARY')";
    exequery(TD::conn(),$query);

    $query="update USER set DEPT_ID='0',NOT_LOGIN='1' where USER_ID='$LEAVE_PERSON'";
    exequery(TD::conn(),$query);

    set_sys_para(array("ORG_UPDATE" => date("Y-m-d H:i:s")));

    cache_users();

    $WORK_STATUS=$QUIT_TYPE==""?"":'0'.($QUIT_TYPE+1);
    $query="select * from HR_STAFF_INFO where USER_ID='$LEAVE_PERSON'";
    $cursor= exequery(TD::conn(),$query);
    if(!$ROW=mysql_fetch_array($cursor))
        $query="insert into HR_STAFF_INFO(CREATE_USER_ID,CREATE_DEPT_ID,USER_ID,DEPT_ID,STAFF_NAME,WORK_STATUS) values ('".$_SESSION["LOGIN_USER_ID"]."','".$_SESSION["LOGIN_DEPT_ID"]."','$LEAVE_PERSON','$LEAVE_DEPT','$LEAVE_PERSON_NAME1','$WORK_STATUS')";
    else
        $query="update HR_STAFF_INFO  set DEPT_ID='$LEAVE_DEPT', WORK_STATUS='$WORK_STATUS' where USER_ID='$LEAVE_PERSON'";
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

    Message("",_("�û�").$USER_NAME._("(").$LEAVE_DEPT_NAME._(")��ְ�Ѱ������!"));

}
?>
<center>
    <input type="button" value="<?=_("�ر�")?>" class="BigButton" onClick="window.close();" title="<?=_("�رմ���")?>">
</center>
</body>
</html>
