<?
include_once("inc/auth.inc.php");
include_once("inc/flow_hook.php");

$HTML_PAGE_TITLE = _("项目科目实际费用申请");
include_once("inc/header.inc.php");
?>
<?php 

//表单上会有一个科目类别名称(type_name)
$type_name = "";
//根据科目名称查找type_code（就是表中的id）
$query_type_code = "select id from proj_budget_type where type_name='$type_name'";
$arr_type_code = exequery(TD::conn(), $query_type_code);
$row = mysql_fetch_array($arr_type_code);
$type_code =$row[0];//科目类别  int类型，值存在proj_budget_type表中
$real_amount = "";//科目的实际资金
$date = "";//审批结束日期
$proj_id = $_GET["PROJ_ID"];//获取到项目ID,此ID通过前台传入

//向项目科目实际资金表(proj_budget_real)中添加数据。
$query = "insert into proj_budget_real(type_code,real_amount,proj_id,date) values ('$type_code','$real_amount','$date','$proj_id')";
//执行sql语句
exequery(TD::conn(),$query);
//获取前一次插入语句的id
$ROW_ID = mysql_insert_id();
//创建一个数组，存放对应关系
$date_array = array("KEY" => "$ROW_ID","field"=>"id","type_code" => "$type_code","real_amount" => "$real_amount",
		"date" => "$date","proj_id" => "$proj_id","type_name" => "$type_name");
//创建一个数组，对应的是表名 （项目资源申请表）
$config= array("module"=>"proj_budget_real");
//调用一个方法
run_hook($data_array,$config);

?>