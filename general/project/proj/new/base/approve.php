<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("�ύ��������");
include_once("inc/header.inc.php");
include_once("inc/utility.php");
include_once("inc/utility_file.php");
include_once("inc/utility_sms1.php");
include_once("inc/utility_project.php");

$priv = check_project_priv();

?>
<body class="bodycolor">
<?
/**
 * �ύ����ǰ��ִ�б����������Ϊ�û��п����ڽ������޸�����Ŀ������Ϣ֮��ֱ�ӵ�����ύ������ by dq 090630
 */
include_once("./save_proj.php");

//------ /���������� ------



if($PROJ_ID)
{
    $query = "select PROJ_MANAGER FROM PROJ_PROJECT WHERE PROJ_ID='$PROJ_ID'";
    $cursor =  exequery(TD::conn(),$query);
    if($ROW=mysql_fetch_array($cursor))
        $PROJ_MANAGER = $ROW["PROJ_MANAGER"];

    if($priv == 2)
    {
        $CONTENT='<font color="green">'._("ͨ��").'</font> <b>by '.$PROJ_MANAGER." ".$CUR_TIME."</b><br/>"._("�Զ�����ͨ��");
        $CONTENT.="|*|";
        $query = "update PROJ_PROJECT set PROJ_STATUS=2,APPROVE_LOG=CONCAT(APPROVE_LOG,'$CONTENT') WHERE PROJ_ID='$PROJ_ID' AND PROJ_OWNER='".$_SESSION["LOGIN_USER_ID"]."'";
        exequery(TD::conn(),$query);
        Message(_("�ɹ�"),_("����Ŀ������ͨ��!"));
        echo '<div align="center"><input type="button" class="BigButton" value='._("�ر�").' onclick="parent.parent.proj_win_close()"></div>';
        //��������
        $CUR_TIME = date("Y-m-d H:i:s",time());
        $SMS_CONTENT=_("���µ���Ŀ������ͨ��!");
        $REMIND_URL="1:project/proj/";
        send_sms($CUR_TIME,$_SESSION["LOGIN_USER_ID"],$PROJ_MANAGER,42,$SMS_CONTENT,$REMIND_URL);
    }else{
        $query = "update PROJ_PROJECT set PROJ_STATUS=1 WHERE PROJ_ID='$PROJ_ID'";
        /*
        if($_SESSION["LOGIN_USER_PRIV"]!=1)
           $query .=" AND PROJ_OWNER='".$_SESSION["LOGIN_USER_ID"]."'";
           */

        if(project_hook("project_apply_x1") == 1){
            //USE $PROJ_ID
            include_once("general/project/portal/new/project_flow_run.php");
        }else{
            exequery(TD::conn(),$query);
            Message(_("�ɹ�"),_("���ύ�������Ա����!"));
            echo '<div align="center"><input type="button" class="BigButton" value='._("�ر�").' onclick="parent.parent.proj_win_close()"></div>';
        }
        //��������
        $CUR_TIME = date("Y-m-d H:i:s",time());
        $SMS_CONTENT=_("���µ���Ŀ�����ȴ�������");
        $REMIND_URL="1:project/approve/";
        send_sms($CUR_TIME,$_SESSION["LOGIN_USER_ID"],$PROJ_MANAGER,42,$SMS_CONTENT,$REMIND_URL);
    }
}
else
{
    Message(_("����"),_("��Ŀ��δ���棡"));
    echo '<div align="center"><input type="button" class="BigButton" value='._("�ر�").' onclick="parent.parent.proj_win_close()"></div>';
}
?>
</body>
</html>