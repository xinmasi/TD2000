<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");
include_once("inc/utility_sms2.php");
include_once("inc/utility_sms1.php");

$CUR_DATE=date("Y-m-d",time());

$query = "update PROJ_TASK set TASK_STATUS = '1',TASK_ACT_END_TIME='$CUR_DATE' WHERE TASK_ID='$TASK_ID'";
exequery(TD::conn(),$query);
//����Ƿ�Ϊ���������ǰ������
$query = "select PROJ_ID,TASK_ID,TASK_NAME,TAST_CONSTRAIN,TASK_USER,TASK_TIME,TASK_START_TIME from PROJ_TASK WHERE PRE_TASK='$TASK_ID'";
$curosr = exequery(TD::conn(),$query);
while($ROW = mysql_fetch_array($curosr))
{
    $PROJ_ID = $ROW['PROJ_ID'];
    $TASK_ID_NEW = $ROW['TASK_ID'];
    $TASK_NAME = $ROW['TASK_NAME'];
    $TASK_USER = $ROW['TASK_USER'];
    $TASK_START_TIME = $ROW['TASK_START_TIME'];

    if($ROW['TAST_CONSTRAIN'] == 1){
        //δ��ʼ��������ǰ��ʼ �Ϻ���Ӻ�ִ�� ���½���ʱ��Ϳ�ʼʱ��
        $t = $ROW['TASK_TIME'];
        //�������۽���ʱ�� �ֿ�ʼʱ��+ԭִ������ = ���۽���ʱ��
        $TASK_ENT_TIME = date('Y-m-d',strtotime("+$t day",strtotime($CUR_DATE)));
        $query = "update PROJ_TASK set TASK_START_TIME='$CUR_DATE',TASK_END_TIME='$TASK_ENT_TIME' WHERE TASK_ID='$TASK_ID_NEW'";
        exequery(TD::conn(),$query);
        $USER_ID_STR = $TASK_USER;
        $USER_ID_STR .= "";
        //����
        $SMS_CONTENT=sprintf(_("����[%s]��ǰ�������ѽ���,������ʼ����!"), $TASK_NAME);
        $REMIND_URL="1:project/proj/task/task_detail.php?PROJ_ID=".$PROJ_ID."&TASK_ID=".$TASK_ID;
        send_sms("",$_SESSION["LOGIN_USER_ID"],$USER_ID_STR,42,$SMS_CONTENT,$REMIND_URL,$PROJ_ID);
    }

}
//header("location:task_doing.php");
?>