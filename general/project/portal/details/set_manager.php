<?php 
/**
*   set_manager.php�ļ�
*
*   �ļ����������� 
*   1����Ŀ���������������������߼�
*   
*   @edit_time  2013/09/20
*
*/
include_once("inc/auth.inc.php");
include_once("../../proj/proj_priv.php");
include_once("inc/utility_project.php");
include_once("inc/utility_sms1.php");//��������
$priv = check_project_priv();

$i_proj_id = isset($_GET["PROJ_ID"]) ? intval($_GET["PROJ_ID"]) : 0;
$s_manager = $_GET["PROJ_MANAGER"]; 
$user_id = $_SESSION['LOGIN_USER_ID'];
$CUR_TIME = date("Y-m-d H:i");
$s_name = $_GET["PROJ_NAME"];
$s_num = $_GET["PROJ_NUM"];
if($priv == 2)
{
    $CONTENT='<font color="green">'._("ͨ��").'</font> <b>by '.$PROJ_MANAGER." ".$CUR_TIME."</b><br/>"._("�Զ�����ͨ��");
    $CONTENT.="|*|";
   $query = "update PROJ_PROJECT set PROJ_STATUS=2,APPROVE_LOG=CONCAT(APPROVE_LOG,'$CONTENT') WHERE PROJ_ID='$PROJ_ID' AND PROJ_OWNER='".$_SESSION["LOGIN_USER_ID"]."'";
   exequery(TD::conn(),$query);
}
elseif($s_manager != "choose")
{
    $query = "UPDATE proj_project SET PROJ_STATUS ='1',PROJ_MANAGER = '$s_manager' WHERE PROJ_ID = '$i_proj_id' and PROJ_OWNER = '$user_id'";
    exequery(TD::conn(),$query);
}

//��������
$REMIND_URL = "1:project/approve/index1.php?PROJ_ID=" . $proj_id;
send_sms($CUR_TIME, $user_id, $s_manager, 42, $_SESSION['LOGIN_USER_NAME'] ." ��������Ŀ: " . $s_num ." ". $s_name ." ����������", $REMIND_URL,$proj_id);

if(project_hook("project_apply") != 1)
{
    header("location:proj_progression.php?VALUE=2&PROJ_ID=$i_proj_id");
}else{
    //������
    header("location: ../new/project_flow_run.php?PROJ_ID=$i_proj_id");
}
?>