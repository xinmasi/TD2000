<?php
/*
 *��Ŀ���̷���ģ��
 *   by �Ը��� 2014-1-14
*/
include_once("inc/auth.inc.php");
include_once("inc/flow_hook.php");
include_once("inc/utility_org.php");
include_once("inc/utility_project.php");

$HTML_PAGE_TITLE = _("��Ŀ���̷���");
include_once("inc/header.inc.php");

$proj_id = intval($PROJ_ID);
if(empty($proj_id)){
    Message("����ϵ����Ա!");
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
	$proj_dept = "ȫ������";
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

// "KEY" => _("����"),
// "PROJ_NAME" => _("��Ŀ����"),
// "PROJ_NUM" => _("��Ŀ���"),
// "PROJ_DESCRIPTION" => _("��Ŀ����"),
// "PROJ_TYPE" => _("��Ŀ����"),
// "PROJ_DEPT" => _("���벿��"),
// "proj_update_time" => _("��Ŀ����ʱ��"),
// "PROJ_START_TIME" => _("�ƻ����ڿ�ʼʱ��"),
// "PROJ_END_TIME" => _("�ƻ����ڽ���ʱ��"),
// "PROJ_ACT_END_TIME" => _("ʵ�ʽ���ʱ��"),
// "PROJ_OWNER" => _("��Ŀ������"),
// "PROJ_LEADER" => _("��Ŀ������"),
// "PROJ_VIEWER" => _("��Ŀ�鿴��"),
// "PROJ_USER" => _("��Ŀ��Ա"),
// "PROJ_PRIV" => _("��ĿȨ��"),
// "PROJ_MANAGER" => _("��Ŀ������"),
// "PROJ_COMMENT" => _("��Ŀ��ע"),
// "PROJ_STATUS" => _("��Ŀ״̬"),
// "COST_TYPE" => _("��������"),
// "COST_MONEY" => _("��Ŀ����"),
// "ATTACHMENT_NAME" => _("��������"),
// "ATTACHMENT_ID" => _("����ID"),
// "PROJ_LEVEL" => _("��Ŀ����")

//��Ŀ������������
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