<?
include_once("inc/auth.inc.php");
include_once("inc/flow_hook.php");

$HTML_PAGE_TITLE = _("��Ŀ��Ŀʵ�ʷ�������");
include_once("inc/header.inc.php");
?>
<?php 

//���ϻ���һ����Ŀ�������(type_name)
$type_name = "";
//���ݿ�Ŀ���Ʋ���type_code�����Ǳ��е�id��
$query_type_code = "select id from proj_budget_type where type_name='$type_name'";
$arr_type_code = exequery(TD::conn(), $query_type_code);
$row = mysql_fetch_array($arr_type_code);
$type_code =$row[0];//��Ŀ���  int���ͣ�ֵ����proj_budget_type����
$real_amount = "";//��Ŀ��ʵ���ʽ�
$date = "";//������������
$proj_id = $_GET["PROJ_ID"];//��ȡ����ĿID,��IDͨ��ǰ̨����

//����Ŀ��Ŀʵ���ʽ��(proj_budget_real)��������ݡ�
$query = "insert into proj_budget_real(type_code,real_amount,proj_id,date) values ('$type_code','$real_amount','$date','$proj_id')";
//ִ��sql���
exequery(TD::conn(),$query);
//��ȡǰһ�β�������id
$ROW_ID = mysql_insert_id();
//����һ�����飬��Ŷ�Ӧ��ϵ
$date_array = array("KEY" => "$ROW_ID","field"=>"id","type_code" => "$type_code","real_amount" => "$real_amount",
		"date" => "$date","proj_id" => "$proj_id","type_name" => "$type_name");
//����һ�����飬��Ӧ���Ǳ��� ����Ŀ��Դ�����
$config= array("module"=>"proj_budget_real");
//����һ������
run_hook($data_array,$config);

?>