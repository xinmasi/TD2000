<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");

$query = "update PROJ_PROJECT set PROJ_ACT_END_TIME = '',PROJ_STATUS='2' WHERE PROJ_ID='$PROJ_ID'";
exequery(TD::conn(),$query);

//����Ŀ�µ�ȫ������ġ�����״̬���ֶζ�����Ϊ�������С� by dq 090629
$query = "update PROJ_TASK set TASK_STATUS = '0' WHERE PROJ_ID='$PROJ_ID'";
exequery(TD::conn(),$query);


header("location:proj_finished.php");
?>