<?php


/**
*   details.inc.php�ļ�
*
*   �ļ�����������
*   1����Ŀ���������̨�߼�
*   2����ȡ��Ŀ��Ϣ
*   3���û�IDת��
*
*   @edit_time  2013/09/20
*
*/

//*******��ȡ��ǰ��Ŀ��������Ϣ*******
include_once("inc/auth.inc.php");
include_once("inc/utility_org.php");
include_once("inc/utility_flow.php");

include_once("inc/header.inc.php");
include_once("inc/IWorkflow.class.php");
include_once("inc/utility_file.php");
include_once("general/workflow/plugin/plugin.inc.php");
include_once("general/workflow/list/turn/condition.func.php");
include_once("inc/utility_sms1.php");
include_once("inc/utility_project.php");

include_once("inc/flow_hook.php");

$i_proj_id = isset($_GET["PROJ_ID"]) ? intval($_GET["PROJ_ID"]) : 0;
$s_query_essential = "SELECT * FROM proj_project WHERE proj_id='$i_proj_id'";
$res_cursor_essential = exequery(TD::conn(), $s_query_essential);
$s_status = "";//��Ŀ״̬
$istrue = 0;
$print_user_priv = array();
if($row = mysql_fetch_array($res_cursor_essential))
{

    $istrue = 1;
    $s_user = explode("|",$row["PROJ_USER"]);                                       //��Ŀ��Ա
    $s_priv = explode("|",$row["PROJ_PRIV"]);                                       //��Ŀ��ԱPRIV
	$print_user_priv = array_combine($s_priv,$s_user);
    $s_num = $row["PROJ_NUM"];                                                      //��Ŀ���
    $s_name = $row["PROJ_NAME"];                                                    //��Ŀ����
    $s_owner_id = $row["PROJ_OWNER"];                                               //��Ŀ�����ߣ������ߣ�
    $s_manager_id = $row["PROJ_MANAGER"];                                           //��Ŀ������

    $i_type_id = $row["PROJ_TYPE"];                                                 //��Ŀ���
    $s_dept_id = $row["PROJ_DEPT"];                                                 //���벿��
    $s_start_time = $row["PROJ_START_TIME"];                                        //�ƻ���ʼʱ��
    $s_end_time = $row["PROJ_END_TIME"];                                            //�ƻ�����ʱ��
    $i_time = round((strtotime($s_end_time) - strtotime($s_start_time))/ 3600/ 24);         //��Ŀ����
    $s_description = $row["PROJ_DESCRIPTION"];                                      //��Ŀ����
    $s_attachment_name = $row["ATTACHMENT_NAME"];                                   //��������
    $s_update_time = $row["PROJ_UPDATE_TIME"];                                      //����޸�ʱ��
    $s_act_end_time = $row["PROJ_ACT_END_TIME"];                                    //ʵ�ʽ���ʱ��
    $s_leader_id = $row["PROJ_LEADER"];                                             //��Ŀ������
    $s_viewer_id = $row["PROJ_VIEWER"];                                             //��Ŀ�鿴��
    $s_priv = $row["PROJ_PRIV"];                                                    //��ĿȨ��
    $s_comment = $row["PROJ_COMMENT"];                                              //��Ŀ��ע
    $i_status = $row["PROJ_STATUS"];                                                //��Ŀ״̬
    $i_percent_complete = $row["PROJ_PERCENT_COMPLETE"];                            //��Ŀ��ɱ���
    $s_cost_type = $row["COST_TYPE"];                                               //��������
    $f_cost_money = $row["COST_MONEY"];                                             //��Ŀ����
    $s_level = $row["PROJ_LEVEL"];                                                  //��Ŀ����
    $s_approve_log = $row["APPROVE_LOG"];                                                  //������¼
}
$HTML_PAGE_TITLE=$s_name;
include_once("inc/header.inc.php");
include_once(dirname(__FILE__) . "/../../proj/proj_priv.php");
include_once("inc/utility_all.php");


if($istrue==0)
{
    Message(_("����"),_("δָ����Ŀ��"));
    exit;
}
//*******�༭������������*******
if($s_owner_id == $_SESSION["LOGIN_USER_ID"] || $_SESSION['LOGIN_USER_ID'] == $s_manager_id || $_SESSION['LOGIN_USER_ID'] == $s_leader_id)
{
    $s_dis = "";
}
else
{
    $s_dis = "none";
}

//*******��ȡ��Ŀ�鿴���û�����*******
//$s_viewer = td_trim(GetUserNameById($s_viewer_id));
$s_viewer = GetUserNameById($s_viewer_id);

//*******��ȡ��Ŀ�������û�����*******

$s_leader = td_trim(GetUserNameById($s_leader_id));

//*******��ȡ��Ŀ�������û�����*******

$s_owner = td_trim(GetUserNameById($s_owner_id));

//*******��ȡ��Ŀ�������û�����*******

$s_manager = td_trim(GetUserNameById($s_manager_id));

//*******��ȡ���벿������*******
$s_dept_id = rtrim($s_dept_id);
if($s_dept_id)
{
    if($s_dept_id == "ALL_DEPT")
    {
        $s_dept = _("ȫ�岿��");
    }
    else
    {
        //$s_dept = td_trim(GetDeptNameById($s_dept_id));
        $s_dept = GetDeptNameById($s_dept_id);
    	
    }
}

//*******��ȡ��Ŀ�������*******
if($i_type_id)
{
    $s_query_type = "select CODE_NAME from sys_code where PARENT_NO='PROJ_TYPE' and CODE_NO='$i_type_id'";
    $res_cursor_type = exequery(TD::conn(),$s_query_type);
    while($a_type = mysql_fetch_array($res_cursor_type))
    {
        $s_type = $a_type['CODE_NAME'];
    }
}
//*******��ȡ��Ŀ״̬*******
switch($i_status)
{
    case "0":
        $s_status = _("������ ");
        break;
    case "1":
        $s_status = _("������ ");
        break;
    case "2":
        $s_status = _("������ ");
        break;
    case "3":
        $s_status = _("�Ѱ�� ");
        break;
}

//*******��ȡ��Ŀ����*******
switch($s_level)
{
    case "a":
        $s_level_name = _("A��");
        break;
    case "b":
        $s_level_name = _("B��");
        break;
    case "c":
        $s_level_name = _("C��");
        break;
}

//********�鿴�Ƿ�ӵ��������*******
$s_query_priv_code ="select PRIV_CODE from PROJ_PRIV where PRIV_CODE = 'APPROVE'";
$res_priv_code = exequery(TD::conn(), $s_query_priv_code);
$a_priv_code = mysql_fetch_array($res_priv_code);
$s_priv_code = $a_priv_code[0];
?>
<script>
    if(window.external && typeof window.external.OA_SMS != 'undefined') 
    {
        var h = Math.min(800, screen.availHeight - 100), 
        w = Math.min(1280, screen.availWidth - 180); 
        window.external.OA_SMS(w, h, "SET_SIZE"); 
    }
</script>