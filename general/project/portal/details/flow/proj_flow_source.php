<?
include_once("inc/auth.inc.php");
include_once("inc/flow_hook.php");
$HTML_PAGE_TITLE = _("��Դ������������");
include_once("inc/header.inc.php");
?>
<?php 
$source_name = "";//��Դ����
$source_type = "";//��Դ����
$source_start_time = "";//��Դ���뿪ʼʱ��
$source_end_time = "";//��Դ�������ʱ��
$source_count = "";//��Դ����
$source_price = "";//��Դ����
$source_money = "";//��Դ�ܼ�
$type_id = "";//��Դ����id

$proj_id = $_GET["PROJ_ID"];//��ȡ����ĿID,��IDͨ��ǰ̨����

//����Ŀ��Դ��(proj_source)�У���������
$query = "insert into proj_source(source_name,source_type,source_start_time,source_end_time,
source_count,source_price,source_money,type_id,proj_id) 
values ('$source_name','$source_type','$source_start_time','$source_end_time','$source_count',
'$source_price','$source_money','$type_id','$proj_id')";
//ִ��sql���
$cursor= exequery(TD::conn(),$query);
//��ȡǰһ�β�������id
$ROW_ID = mysql_insert_id();
//����һ�����飬��Ŷ�Ӧ��ϵ
$data_array = array("KEY"=>"$ROW_ID","field"=>"id","source_name"=>"$source_name",
		"source_type"=>"$source_type","source_start_time"=>"$source_start_time",
		"source_end_time"=>"$source_end_time","source_count"=>"$source_count",
		"source_price"=>"$source_price","source_money"=>"$source_money",
		"type_id"=>"$type_id","proj_id"=>"$proj_id");
//����һ�����飬��Ӧ���Ǳ��� ����Ŀ��Դ�����
$config= array("module"=>"proj_source");
//����һ������
run_hook($data_array,$config);
?>


