<?php
/**
*   edit_details.inc.php文件
*
*   文件内容描述：
*   1、项目详情区块修改逻辑
*   2、预算资金修改
*
*   @edit_time  2013/09/20
*
*/
include_once("inc/auth.inc.php");
include_once("inc/utility_file.php");
include_once("../../proj/proj_priv.php");
include_once("inc/utility_project.php");
include_once("inc/utility_sms1.php");
include_once("inc/header.inc.php");
include_once("inc/sys_code_field.php");




$i_proj_id = isset($_GET["PROJ_ID"]) ? intval($_GET["PROJ_ID"]) : 0;
$i_status=isset($_GET["PROJ_STATUS"]) ? intval($_GET["PROJ_STATUS"]) : 0;
if(!project_update_priv($i_proj_id)){
    Message(_("错误"),_("无权修改该项目!"));
    Button_Back();
    exit;
}


if(count($_FILES)>1)
{
    $ATTACHMENTS = upload();
    $ATTACHMENT_ID = $ATTACHMENTS['ID'];
    $ATTACHMENT_NAME = $ATTACHMENTS['NAME'];
}
   


//*******获取$_POST中的元素*******
$s_proj_num = $_POST["PROJ_NUM"];                       //项目编号
$i_proj_type = $_POST["PROJ_TYPE"];                     //类型
$s_proj_name = $_POST["PROJ_NAME"];                     //名称
$d_proj_start_time = $_POST["PROJ_START_TIME"];         //开始时间
$d_proj_end_time = $_POST["PROJ_END_TIME"];             //结束时间
$s_proj_dept = $_POST["PROJ_DEPT"];                     //参与部门
$s_proj_description = $_POST["PROJ_DESCRIPTION"];       //项目描述
$s_proj_level = $_POST["PROJ_LEVEL"];                   //项目等级
$s_proj_leader = $_POST["PROJ_LEADER"];                 //负责人
$s_proj_viewer = $_POST["PROJ_VIEWER"];                 //查看者
$s_proj_owner =$_POST["PROJ_USER_TO_ID"];               //创建者
$f_cost_money = $_POST["COST_MONEY"];                   //总预算资金
//----zfc----
$f_cost_manager = $_POST["PROJ_MANAGER"];                   //审批人变更

$ATTACHMENT_ID_OLD = $_POST["ATTACHMENT_ID_OLD"];           //附件
$ATTACHMENT_NAME_OLD = $_POST["ATTACHMENT_NAME_OLD"];           //附件

$ATTACHMENT_ID = $ATTACHMENT_ID_OLD . $ATTACHMENT_ID;
$ATTACHMENT_NAME = $ATTACHMENT_NAME_OLD . $ATTACHMENT_NAME;

$query = "UPDATE proj_project ";
$query .= "SET PROJ_NAME = '$s_proj_name', PROJ_NUM = '$s_proj_num',";
$query .= " PROJ_DESCRIPTION = '$s_proj_description', PROJ_TYPE = '$i_proj_type',";
$query .= " PROJ_DEPT = '$s_proj_dept', PROJ_OWNER = '$s_proj_owner',";
$query .= " PROJ_START_TIME = '$d_proj_start_time', PROJ_END_TIME = '$d_proj_end_time',";
$query .= " PROJ_LEADER = '$s_proj_leader', PROJ_VIEWER = '$s_proj_viewer',";
$query .= " PROJ_LEVEL='$s_proj_level',COST_MONEY='$f_cost_money',";
$query .= " PROJ_MANAGER='$f_cost_manager',";
$query .= " ATTACHMENT_ID='$ATTACHMENT_ID',";
$query .= " ATTACHMENT_NAME='$ATTACHMENT_NAME'";
//保存时审核 zfc 2013.12.3   除了项目审批人修改项目 和 免审批权限  2014-1-3

////spz 2016.10.24 针对项目的全局.自定义字段
   //先删除所有已有的数据再重新更新
   proj_del_field_data($PROJ_ID);
   
   //更新附加数据到 equip_field_date
   proj_save_field_data($i_proj_type ,$PROJ_ID,$_POST);

   //插入全局自定义数据
   proj_save_field_data_g($i_proj_type,$PROJ_ID,$_POST);

///
if($i_status !="0"){

if(check_project_priv($i_proj_id) != 2 && $f_cost_manager != $_SESSION["LOGIN_USER_ID"])
{
    $query .= " ,PROJ_STATUS = '1'";  
    //**********给审批人发送事务提醒**************
    $CUR_TIME = date("Y-m-d H:i:s", time());
    $SMS_CONTENT = $_SESSION['LOGIN_USER_NAME'] . " 修改了项目: " .$s_proj_num." " .$s_proj_name . " 请您审批！";
    $REMIND_URL = "1:project/approve/index1.php?PROJ_ID=" . $i_proj_id;
    send_sms($CUR_TIME, $_SESSION["LOGIN_USER_ID"], $f_cost_manager, 42, $SMS_CONTENT, $REMIND_URL,$i_proj_id);
}

}
$query .= "  WHERE PROJ_ID = '$i_proj_id'";
exequery(TD::conn(), $query);

//*******预算资金修改*******
$num = intval($_POST['type_num']);
$i = 0;
while($i < $num){
    $s_type_id = $_POST["type_id".$i];
    $budget = $_POST["BUDGET_AMOUNT_".$i];    
    $query = "update proj_budget set budget_amount='$budget' where type_code ='$s_type_id' and proj_id='$i_proj_id'";
    if(empty($_POST["z_budget_id".$i])){
        $query = "insert into proj_budget(type_code,budget_amount,proj_id) values('$s_type_id','$budget','$i_proj_id')";
    }
    exequery(TD::conn(), $query);  
    $i++;
}



// $i_select_budget = "SELECT count(ID) as sumid FROM proj_budget_type";
// $i_cursor_budget = exequery(TD::conn(), $i_select_budget);
// $i_row_budget = mysql_fetch_array($i_cursor_budget);
// $i_sumid = $i_row_budget["sumid"];
// $i = 0;
// while($i < $i_sumid)
// {
    // $s_type_name = $_POST["type_name".$i];
    // $s_query_budget = "select id from proj_budget_type where TYPE_NAME = '".addslashes($s_type_name)."'";
    // $s_cursor_budget = exequery(TD::conn(), $s_query_budget);
    // $a_row_budget = mysql_fetch_array($s_cursor_budget);
    // $i_type_code = $a_row_budget["id"];
    // if(!$i_type_code)
    // {
    // $i++;
        // continue;
    // }
    // $budget = $_POST["BUDGET_AMOUNT_".$i];
    // $s_type_budget = "select budget_amount from proj_budget where TYPE_CODE ='$i_type_code' and PROJ_ID = '$i_proj_id'";

    // $s_cursor_type_budget = exequery(TD::conn(), $s_type_budget);
    // $a_row_type_budget = mysql_fetch_array($s_cursor_type_budget);
    // $i_type_budget = $a_row_type_budget["budget_amount"];
    // if($i_type_budget)
    // {
        // $update="UPDATE proj_budget SET BUDGET_AMOUNT = '$budget' where TYPE_CODE ='$i_type_code' and PROJ_ID = '$i_proj_id'";
        // exequery(TD::conn(), $update);
    // }
    // else
    // {
    	// $insert ="delete from proj_budget where type_code='$i_type_code' and proj_id='$i_proj_id';";
        // exequery(TD::conn(), $insert);
    	// $insert ="insert into proj_budget(type_code, proj_id, budget_amount)values('$i_type_code', '$i_proj_id', '$budget')";
        // exequery(TD::conn(), $insert);
    // }
    // $i++;
// }

insert_news($i_proj_id,$_SESSION['LOGIN_USER_NAME'] . " 修改了项目: " .$s_proj_num." " .$s_proj_name  . " [项目管理]");



header("location:proj_detail.php?VALUE=1&PROJ_ID=$i_proj_id");
?>