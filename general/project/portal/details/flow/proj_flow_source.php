<?
include_once("inc/auth.inc.php");
include_once("inc/flow_hook.php");
$HTML_PAGE_TITLE = _("资源管理工作流处理");
include_once("inc/header.inc.php");
?>
<?php 
$source_name = "";//资源名字
$source_type = "";//资源类型
$source_start_time = "";//资源申请开始时间
$source_end_time = "";//资源申请结束时间
$source_count = "";//资源数量
$source_price = "";//资源单价
$source_money = "";//资源总价
$type_id = "";//资源类型id

$proj_id = $_GET["PROJ_ID"];//获取到项目ID,此ID通过前台传入

//向项目资源表(proj_source)中，插入数据
$query = "insert into proj_source(source_name,source_type,source_start_time,source_end_time,
source_count,source_price,source_money,type_id,proj_id) 
values ('$source_name','$source_type','$source_start_time','$source_end_time','$source_count',
'$source_price','$source_money','$type_id','$proj_id')";
//执行sql语句
$cursor= exequery(TD::conn(),$query);
//获取前一次插入语句的id
$ROW_ID = mysql_insert_id();
//创建一个数组，存放对应关系
$data_array = array("KEY"=>"$ROW_ID","field"=>"id","source_name"=>"$source_name",
		"source_type"=>"$source_type","source_start_time"=>"$source_start_time",
		"source_end_time"=>"$source_end_time","source_count"=>"$source_count",
		"source_price"=>"$source_price","source_money"=>"$source_money",
		"type_id"=>"$type_id","proj_id"=>"$proj_id");
//创建一个数组，对应的是表名 （项目资源申请表）
$config= array("module"=>"proj_source");
//调用一个方法
run_hook($data_array,$config);
?>


