<?php
/*
 *项目流程发起模块
 *   by 赵富春 2014-1-14
*/
include_once("inc/auth.inc.php");
include_once("inc/flow_hook.php");
include_once("inc/utility_org.php");
include_once("inc/utility_project.php");

$HTML_PAGE_TITLE = _("项目流程发起");
include_once("inc/header.inc.php");

$proj_id = intval($PROJ_ID);
if(empty($proj_id)){
    Message("请联系管理员!");
    exit;
}

$query = "UPDATE PROJ_PROJECT SET PROJ_STATUS = '1' WHERE PROJ_ID = '$proj_id'";
exequery(TD::conn(),$query);


$priv_sql = project_auth_sql();
$query = "select * from proj_project where PROJ_ID = '$proj_id' " . $priv_sql;
$cur = exequery(TD::conn(),$query);
$row = mysql_fetch_array($cur);

$proj_num = $row['PROJ_NUM'];
$proj_name = $row['PROJ_NAME'];
$proj_manager = rtrim(GetUserNameById($row['PROJ_MANAGER']),',');
$proj_type = $row['PROJ_TYPE'];
$proj_description = $row['PROJ_DESCRIPTION'];
if($row['PROJ_DEPT'] == "ALL_DEPT"){
	$proj_dept = "全部部门";
}else{
	$proj_dept = GetDeptNameById($row['PROJ_DEPT']);
}
$proj_start_time = $row['PROJ_START_TIME'];
$proj_end_time = $row['PROJ_END_TIME'];
$proj_leader = rtrim(GetUserNameById($row['PROJ_LEADER']),',');
$proj_user = $row['PROJ_USER'];
$proj_level = $row['PROJ_LEVEL'];
$proj_update_time = $row['proj_update_time'];

$PROJ_OWNER = rtrim(GetUserNameById($row['PROJ_OWNER']),',');
$PROJ_VIEWER = rtrim(GetUserNameById($row['PROJ_VIEWER']),',');
$PROJ_COMMENT = $row['PROJ_COMMENT'];
$COST_TYPE = $row['COST_TYPE'];
$COST_MONEY = $row['COST_MONEY'];
$ATTACHMENT_NAME = $row['ATTACHMENT_NAME'];
$ATTACHMENT_ID = $row['ATTACHMENT_ID'];
$PROJ_LEVEL = $row['PROJ_LEVEL'];

$query = "select CODE_NAME from sys_code where PARENT_NO = 'PROJ_TYPE' and CODE_NO = '$proj_type'";
$cur = exequery(TD::conn(),$query);
$row = mysql_fetch_array($cur);
$proj_type = $row['CODE_NAME'];

$user_array = explode('|',$proj_user);
$user_str = "";
foreach($user_array as $user_id){
    $user_str .= $user_id;
}

$proj_user = GetUserNameById($user_str);

// "KEY" => _("主键"),
// "PROJ_NAME" => _("项目名称"),
// "PROJ_NUM" => _("项目编号"),
// "PROJ_DESCRIPTION" => _("项目描述"),
// "PROJ_TYPE" => _("项目类型"),
// "PROJ_DEPT" => _("参与部门"),
// "proj_update_time" => _("项目更新时间"),
// "PROJ_START_TIME" => _("计划周期开始时间"),
// "PROJ_END_TIME" => _("计划周期结束时间"),
// "PROJ_ACT_END_TIME" => _("实际结束时间"),
// "PROJ_OWNER" => _("项目所有者"),
// "PROJ_LEADER" => _("项目负责人"),
// "PROJ_VIEWER" => _("项目查看者"),
// "PROJ_USER" => _("项目成员"),
// "PROJ_PRIV" => _("项目权限"),
// "PROJ_MANAGER" => _("项目审批者"),
// "PROJ_COMMENT" => _("项目批注"),
// "PROJ_STATUS" => _("项目状态"),
// "COST_TYPE" => _("经费类型"),
// "COST_MONEY" => _("项目经费"),
// "ATTACHMENT_NAME" => _("附件名称"),
// "ATTACHMENT_ID" => _("附件ID"),
// "PROJ_LEVEL" => _("项目级别")

//项目立项申请引擎
$data_array = array(
    "KEY"=>"$proj_id",
    "field"=>"PROJ_ID",
    "PROJ_NUM"=>"$proj_num",
    "PROJ_NAME"=>"$proj_name",
    "PROJ_MANAGER"=>"$proj_manager",
    "PROJ_TYPE"=>"$proj_type",
    "PROJ_LEADER"=>"$proj_leader",
    "PROJ_DESCRIPTION"=>"$proj_description",
    "PROJ_LEVEL"=>"$proj_level",
    "PROJ_USER"=>"$proj_user",
    "PROJ_DEPT"=>"$proj_dept",
    "PROJ_START_TIME"=>"$proj_start_time",
    "PROJ_END_TIME"=>"$proj_end_time",
	"proj_update_time"=>"$proj_update_time",
	"PROJ_OWNER"=>"$PROJ_OWNER",
	"PROJ_VIEWER"=>"$PROJ_VIEWER",
	//"PROJ_COMMENT"=>"$PROJ_COMMENT",
	"COST_TYPE" => "$COST_TYPE",
	//"COST_MONEY"=>"$COST_MONEY",
	"ATTACHMENT_NAME"=>"$ATTACHMENT_NAME",
	"ATTACHMENT_ID"=>"$ATTACHMENT_ID",
	"PROJ_LEVEL"=>"$PROJ_LEVEL"
    );
    
    $config = array("module" => "project_apply_x1");
    run_hook($data_array,$config);

?>