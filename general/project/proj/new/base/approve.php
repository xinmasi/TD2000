<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("提交立项申请");
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
 * 提交审批前先执行保存操作，因为用户有可能在界面上修改了项目基本信息之后，直接点击“提交审批” by dq 090630
 */
include_once("./save_proj.php");

//------ /保存操作完成 ------



if($PROJ_ID)
{
    $query = "select PROJ_MANAGER FROM PROJ_PROJECT WHERE PROJ_ID='$PROJ_ID'";
    $cursor =  exequery(TD::conn(),$query);
    if($ROW=mysql_fetch_array($cursor))
        $PROJ_MANAGER = $ROW["PROJ_MANAGER"];

    if($priv == 2)
    {
        $CONTENT='<font color="green">'._("通过").'</font> <b>by '.$PROJ_MANAGER." ".$CUR_TIME."</b><br/>"._("自动免审通过");
        $CONTENT.="|*|";
        $query = "update PROJ_PROJECT set PROJ_STATUS=2,APPROVE_LOG=CONCAT(APPROVE_LOG,'$CONTENT') WHERE PROJ_ID='$PROJ_ID' AND PROJ_OWNER='".$_SESSION["LOGIN_USER_ID"]."'";
        exequery(TD::conn(),$query);
        Message(_("成功"),_("该项目免审批通过!"));
        echo '<div align="center"><input type="button" class="BigButton" value='._("关闭").' onclick="parent.parent.proj_win_close()"></div>';
        //事务提醒
        $CUR_TIME = date("Y-m-d H:i:s",time());
        $SMS_CONTENT=_("有新的项目免审批通过!");
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
            Message(_("成功"),_("已提交给相关人员审批!"));
            echo '<div align="center"><input type="button" class="BigButton" value='._("关闭").' onclick="parent.parent.proj_win_close()"></div>';
        }
        //事务提醒
        $CUR_TIME = date("Y-m-d H:i:s",time());
        $SMS_CONTENT=_("有新的项目审批等待您处理。");
        $REMIND_URL="1:project/approve/";
        send_sms($CUR_TIME,$_SESSION["LOGIN_USER_ID"],$PROJ_MANAGER,42,$SMS_CONTENT,$REMIND_URL);
    }
}
else
{
    Message(_("错误"),_("项目尚未保存！"));
    echo '<div align="center"><input type="button" class="BigButton" value='._("关闭").' onclick="parent.parent.proj_win_close()"></div>';
}
?>
</body>
</html>