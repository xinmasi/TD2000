<?
include_once("inc/auth.inc.php");
include_once("../../proj/proj_priv.php");
include_once("inc/utility_project.php");
include_once("inc/header.inc.php");
$PROJ_STATUS = isset($_GET['STATUS']) ? intval($_GET['STATUS']) :"3";
$TASK_STATUS = isset($_GET['TYPE']) ? intval($_GET['TYPE']) :"1";

$i_proj_id = isset($_GET["PROJ_ID"]) ? intval($_GET["PROJ_ID"]) : 0;
if(!project_update_priv($i_proj_id)){
    Message(_("����"),_("��Ȩ��������Ŀ!"));
    Button_Back();
    exit;
}

$CUR_DATE=date("Y-m-d",time());
$query = "update PROJ_PROJECT set PROJ_ACT_END_TIME = '$CUR_DATE',PROJ_STATUS='".$PROJ_STATUS."' WHERE PROJ_ID='$i_proj_id'";
exequery(TD::conn(),$query);

//����Ŀ�µ�ȫ������ġ�����״̬���ֶζ�����Ϊ���ѽ����� by dq 090629
$query = "update PROJ_TASK set TASK_STATUS = '".$TASK_STATUS."' WHERE PROJ_ID='$i_proj_id'";
exequery(TD::conn(),$query);

header("location:proj_progression.php?VALUE=2&PROJ_ID=$i_proj_id");
?>