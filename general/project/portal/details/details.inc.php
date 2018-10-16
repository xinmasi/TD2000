<?php


/**
*   details.inc.php文件
*
*   文件内容描述：
*   1、项目详情区块后台逻辑
*   2、获取项目信息
*   3、用户ID转换
*
*   @edit_time  2013/09/20
*
*/

//*******获取当前项目的所有信息*******
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
$s_status = "";//项目状态
$istrue = 0;
$print_user_priv = array();
if($row = mysql_fetch_array($res_cursor_essential))
{

    $istrue = 1;
    $s_user = explode("|",$row["PROJ_USER"]);                                       //项目成员
    $s_priv = explode("|",$row["PROJ_PRIV"]);                                       //项目成员PRIV
	$print_user_priv = array_combine($s_priv,$s_user);
    $s_num = $row["PROJ_NUM"];                                                      //项目编号
    $s_name = $row["PROJ_NAME"];                                                    //项目名称
    $s_owner_id = $row["PROJ_OWNER"];                                               //项目创建者（所有者）
    $s_manager_id = $row["PROJ_MANAGER"];                                           //项目审批人

    $i_type_id = $row["PROJ_TYPE"];                                                 //项目类别
    $s_dept_id = $row["PROJ_DEPT"];                                                 //参与部门
    $s_start_time = $row["PROJ_START_TIME"];                                        //计划开始时间
    $s_end_time = $row["PROJ_END_TIME"];                                            //计划结束时间
    $i_time = round((strtotime($s_end_time) - strtotime($s_start_time))/ 3600/ 24);         //项目工期
    $s_description = $row["PROJ_DESCRIPTION"];                                      //项目描述
    $s_attachment_name = $row["ATTACHMENT_NAME"];                                   //附件名称
    $s_update_time = $row["PROJ_UPDATE_TIME"];                                      //最后修改时间
    $s_act_end_time = $row["PROJ_ACT_END_TIME"];                                    //实际结束时间
    $s_leader_id = $row["PROJ_LEADER"];                                             //项目负责人
    $s_viewer_id = $row["PROJ_VIEWER"];                                             //项目查看者
    $s_priv = $row["PROJ_PRIV"];                                                    //项目权限
    $s_comment = $row["PROJ_COMMENT"];                                              //项目批注
    $i_status = $row["PROJ_STATUS"];                                                //项目状态
    $i_percent_complete = $row["PROJ_PERCENT_COMPLETE"];                            //项目完成比例
    $s_cost_type = $row["COST_TYPE"];                                               //经费类型
    $f_cost_money = $row["COST_MONEY"];                                             //项目经费
    $s_level = $row["PROJ_LEVEL"];                                                  //项目级别
    $s_approve_log = $row["APPROVE_LOG"];                                                  //审批记录
}
$HTML_PAGE_TITLE=$s_name;
include_once("inc/header.inc.php");
include_once(dirname(__FILE__) . "/../../proj/proj_priv.php");
include_once("inc/utility_all.php");


if($istrue==0)
{
    Message(_("错误"),_("未指定项目！"));
    exit;
}
//*******编辑链接显隐设置*******
if($s_owner_id == $_SESSION["LOGIN_USER_ID"] || $_SESSION['LOGIN_USER_ID'] == $s_manager_id || $_SESSION['LOGIN_USER_ID'] == $s_leader_id)
{
    $s_dis = "";
}
else
{
    $s_dis = "none";
}

//*******提取项目查看者用户名称*******
//$s_viewer = td_trim(GetUserNameById($s_viewer_id));
$s_viewer = GetUserNameById($s_viewer_id);

//*******提取项目负责人用户名称*******

$s_leader = td_trim(GetUserNameById($s_leader_id));

//*******提取项目创建者用户名称*******

$s_owner = td_trim(GetUserNameById($s_owner_id));

//*******提取项目审批人用户名称*******

$s_manager = td_trim(GetUserNameById($s_manager_id));

//*******提取参与部门名称*******
$s_dept_id = rtrim($s_dept_id);
if($s_dept_id)
{
    if($s_dept_id == "ALL_DEPT")
    {
        $s_dept = _("全体部门");
    }
    else
    {
        //$s_dept = td_trim(GetDeptNameById($s_dept_id));
        $s_dept = GetDeptNameById($s_dept_id);
    	
    }
}

//*******提取项目类别名称*******
if($i_type_id)
{
    $s_query_type = "select CODE_NAME from sys_code where PARENT_NO='PROJ_TYPE' and CODE_NO='$i_type_id'";
    $res_cursor_type = exequery(TD::conn(),$s_query_type);
    while($a_type = mysql_fetch_array($res_cursor_type))
    {
        $s_type = $a_type['CODE_NAME'];
    }
}
//*******提取项目状态*******
switch($i_status)
{
    case "0":
        $s_status = _("立项中 ");
        break;
    case "1":
        $s_status = _("审批中 ");
        break;
    case "2":
        $s_status = _("进行中 ");
        break;
    case "3":
        $s_status = _("已办结 ");
        break;
}

//*******提取项目级别*******
switch($s_level)
{
    case "a":
        $s_level_name = _("A级");
        break;
    case "b":
        $s_level_name = _("B级");
        break;
    case "c":
        $s_level_name = _("C级");
        break;
}

//********查看是否拥有审批人*******
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