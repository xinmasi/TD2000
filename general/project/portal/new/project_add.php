<?php
include_once("inc/auth.inc.php");
include_once("inc/utility_file.php");
include_once("inc/sys_code_field.php");
include_once("./check_form.php");
include_once("inc/flow_hook.php");
include_once("inc/utility_project.php");
include_once("inc/utility_sms1.php");
mysql_set_charset(MYOA_DB_CHARSET, TD::conn());
$proj_hook = project_hook("project_apply_x1");

$ajax = false;
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')
{
    $ajax = true;
    foreach($_POST as $k =>$v)
    {
        $_POST[$k] = iconv('utf-8',MYOA_CHARSET,$v);
    }
}
    
//基本信息
$proj_num = $_POST["PROJ_NUM"];
$proj_type = $_POST["PROJ_TYPE"];
$proj_name = $_POST["PROJ_NAME"];
$proj_start_time = $_POST["PROJ_START_TIME"];
$proj_end_time = $_POST["PROJ_END_TIME"];
$proj_dept = $_POST["PROJ_DEPT"];
$proj_description = $_POST["PROJ_DESCRIPTION"];
$cost_money = $_POST["COST_MONEY"];
$proj_level = $_POST["PROJ_LEVEL"];

$proj_description = strip_unsafe_tags($proj_description);

if(count($_FILES)>1)
{
    $attachments = upload();
    $proj_description = ReplaceImageSrc($proj_description, $attachments);
    $ATTACHMENT_ID = $ATTACHMENT_ID_OLD.$attachments["ID"];
    $ATTACHMENT_NAME = $ATTACHMENT_NAME_OLD.$attachments["NAME"];
}
else
{
    $ATTACHMENT_ID = $ATTACHMENT_ID_OLD;
    $ATTACHMENT_NAME = $ATTACHMENT_NAME_OLD;
 }

$ATTACHMENT_ID .= copy_sel_attach($ATTACH_NAME,$ATTACH_DIR,$DISK_ID);
$ATTACHMENT_NAME .= $ATTACH_NAME;
$cur_time = date("Y-m-d H:i:s",time());

//干系人
$proj_owner = $_POST["PROJ_OWNER"];
$proj_leader = $_POST["PROJ_LEADER"];
$proj_viewer = $_POST["PROJ_VIEWER"];
$a_proj_role = get_code_array("PROJ_ROLE");
    
foreach($a_proj_role as $k => $v)
{
    $code_no = $k;        
    $proj_priv .= '|'.$code_no;
    $proj_user .= '|'.$_POST["PROJ_USER_".$code_no];
}

//审批状态
$proj_manager = $_POST["PROJ_MANAGER"];
$proj_status = isset($_POST["PROJ_STATUS"]) ? intval($_POST["PROJ_STATUS"]) : 0;
if($proj_status != 1 && $proj_status !=0)
{
    $proj_status = 0;
}

if($proj_status == 1)
{
    $proj_status = check_project_priv($proj_id,$proj_status)==2 ? 2 : $proj_status;
}

//审批人是自己 细节成为办理中状态
//if($proj_manager == $_SESSION['LOGIN_USER_ID'])  不知道是否需要；
//$proj_status = 2;
$proj_name = stripslashes($proj_name);
$s_query_object = "insert into proj_project (PROJ_NUM,PROJ_TYPE,PROJ_NAME,PROJ_START_TIME,PROJ_END_TIME,PROJ_DEPT,PROJ_DESCRIPTION,PROJ_OWNER,PROJ_LEADER,PROJ_VIEWER,PROJ_USER,PROJ_PRIV,PROJ_MANAGER,PROJ_STATUS,COST_MONEY,PROJ_LEVEL) values ('$proj_num','$proj_type','$proj_name','$proj_start_time','$proj_end_time','$proj_dept','$proj_description','$proj_owner','$proj_leader','$proj_viewer','$proj_user','$proj_priv','$proj_manager','$proj_status','$cost_money','$proj_level')";
exequery(TD::conn(), $s_query_object);

//查询insert之后的主键id
$proj_id = mysql_insert_id();
$info = $_SESSION['LOGIN_USER_NAME'] . " 创建了项目: " . $proj_num  ." ". $proj_name;
insert_news($proj_id,$info);

//给审批人发送事务提醒 
if(check_project_priv($proj_id) != 2 && $proj_status != 2 && $proj_status != 0)
{
    $str = _(" 请您审批！");
    $link = "1:project/approve/index1.php?PROJ_ID=" . $proj_id;
    if($proj_hook == 1)
        $str = _(" 我选择的审批人是您请关注");
    send_sms("",$_SESSION['LOGIN_USER_ID'],$proj_manager,42,$info . $str, $link,$proj_id);
}

//插入附加数据
proj_save_field_data($proj_type,$proj_id,$_POST);

//插入全局自定义数据
proj_save_field_data_g($proj_type,$proj_id,$_POST);

//插入自定义数据
proj_save_field_data($proj_type,$proj_id,$_POST);

//项目文档
if($_POST["CREATE_FILE"] == 1)
{
    $sort_name = $_POST["SORT_NAME"];
    $sort_no = $_POST["SORT_NO"];
    //proj_file_sort
    $query_file_sort = "insert into proj_file_sort (SORT_NO,SORT_NAME,PROJ_ID) values ('$sort_no','$sort_name','$proj_id')";
    exequery(TD::conn(), $query_file_sort);
}

//预算明细
$i_select_budget = "SELECT count(ID) as sumid FROM proj_budget_type";
$i_cursor_budget = exequery(TD::conn(), $i_select_budget);
$i_row_budget = mysql_fetch_array($i_cursor_budget);
$i_sumid = $i_row_budget["sumid"];
$i = 0;
while($i < $i_sumid)
{
    $type_name = $_POST["type_name".$i];
    $query_budget = "select id from proj_budget_type where TYPE_NAME = '$type_name'";
    $cursor_budget = exequery(TD::conn(), $query_budget);
    $row_budget = mysql_fetch_array($cursor_budget);
    $type_code = $row_budget["id"];
    $budget = $_POST["BUDGET_AMOUNT_".$i];
    
    if($budget != "" && $budget !=0)
    {
        $a_budget_insert = "insert into proj_budget(type_code, proj_id, budget_amount)values('$type_code', '$proj_id', '$budget')";
        $asd = exequery(TD::conn(), $a_budget_insert);
    }
    $i++;
}

if($_POST["CREATE_TASK"] == 1)
{
    //任务划分
    $task_no = $_POST['TASK_NO'];//任务编号
    $task_name = $_POST['TASK_NAME'];//任务名字
    $task_user = $_POST['TASK_USER'];//任务执行人
    $task_user_name = $_POST['TASK_USER_TO_NAME'];
    
    $task_level = $_POST['TASK_LEVEL'];//任务级别
    $task_start_time = $_POST['TASK_START_TIME'];//任务开始时间
    $task_end_time = $_POST['TASK_END_TIME'];//任务结束时间
    $task_milestone = $_POST['TASK_MILESTONE'];//设置里程碑
    $task_description = $_POST['TASK_DESCRIPTION'];//任务描述
    $remark = $_POST['REMARK'];//任务备注
    $flow_id = $_POST['FLOW_ID'];//关联工作流
    $task_time = floor((strtotime($TASK_END_TIME)-strtotime($TASK_START_TIME))/86400)+1;//工时

    $task_insert_into = "insert into proj_task (TASK_NO, PROJ_ID, TASK_NAME, TASK_USER, TASK_LEVEL, TASK_START_TIME, TASK_END_TIME, TASK_MILESTONE, TASK_DESCRIPTION, REMARK, FLOW_ID_STR, TASK_TIME)
    values 
    ('$task_no', '$proj_id', '$task_name', '$task_user', '$task_level', '$task_start_time', '$task_end_time', '$task_milestone', '$task_description', '$remark', '$flow_id', '$task_time')";
    exequery(TD::conn(), $task_insert_into);
    $info = _("我给 ").$task_user_name._(" 分配了任务 ").$task_name._(" [项目管理]");
    insert_news($proj_id,$info);
}

include_once("inc/header.inc.php");
echo "<br/>";
Message("",_("保存成功！"));

//生成文件
// $PROJ_ID = $proj_id;
// require_once("project_xml.php");
?>

<center>
    <input type="button" class="BigButtonA" value="<?=_("返回")?>" onclick="close_guide()">
</center>
<script>
    function close_guide()
    {
        if(window.parent && window.parent == window)
        {
            window.open('','_self','');
            window.close();
        }
        else
        {
            parent.hide_mask();
            parent.$("#li_<?=$proj_status?>")[0].click();
        }
    }
    //发起流程
    var flow_run_proj = false;
    function project_flow_run(url){
        if(flow_run_proj)
            flow_run_proj.close();
        flow_run_proj = window.open(url,"","status=0,toolbar=no,menubar=no,location=no,scrollbars=yes,resizable=yes,width="+(screen.width-15)+",height="+(screen.height-100)+",left=0,top=0");
    }
    <?
    if($proj_hook == 1 && check_project_priv() != 2 && $PROJ_STATUS == 1){
    ?>
    close_guide();
    project_flow_run("project_flow_run.php?PROJ_ID=<?= $proj_id;?>");    
    <?
    }
    ?>
</script>