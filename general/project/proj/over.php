<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");

$CUR_DATE=date("Y-m-d",time());

$query = "update PROJ_PROJECT set PROJ_ACT_END_TIME = '$CUR_DATE',PROJ_STATUS='3' WHERE PROJ_ID='$PROJ_ID'";
exequery(TD::conn(),$query);

//����Ŀ�µ�ȫ������ġ�����״̬���ֶζ�����Ϊ���ѽ����� by dq 090629
$query = "update PROJ_TASK set TASK_STATUS = '1' WHERE PROJ_ID='$PROJ_ID'";
exequery(TD::conn(),$query);

header("location:proj_doing.php");
?>