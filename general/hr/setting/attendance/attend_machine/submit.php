<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("���ڻ����ݿ�����");
include_once("inc/header.inc.php");
?>

<body class="bodycolor">
<?
/*
$ACCESS_PATH//�ļ�·��
$DATABASE_IP//������IP
$DATABASE_PORT//���ڻ����ݿ�˿�
$DATABASE_NAME//���ݿ�����
$DATABASE_USER//�û���
$DATABASE_PASS//����
$DUTY_TABLE//���ݴ洢�����
$DUTY_USER//�û��ֶ���
$DUTY_TIME//��ʱ���ֶ���
*/

if($DATABASE_TYPE =="sqlserver")
{
	$DATABASE_IP   = $DATABASE_IP_SQL;
	$DATABASE_PORT = $DATABASE_PORT_SQL;
	$DATABASE_NAME = $DATABASE_NAME_SQL;//���ݿ�����
	$DATABASE_USER = $DATABASE_USER_SQL;
	$DATABASE_PASS = $DATABASE_PASS_SQL;//����
	
	$DUTY_TABLE    = $DUTY_TABLE_SQL;//���ݴ洢�����
	$DUTY_USER     = $DUTY_USER_SQL;//�û��ֶ���
	$DUTY_TIME     = $DUTY_TIME_SQL;//��ʱ���ֶ���
}

$query="update ATTEND_MACHINE set MACHINE_BRAND='$MACHINE_BRAND',DATABASE_TYPE='$DATABASE_TYPE',ACCESS_PATH='$ACCESS_PATH',DATABASE_IP='$DATABASE_IP',DATABASE_PORT='$DATABASE_PORT',DATABASE_USER='$DATABASE_USER',DATABASE_PASS='$DATABASE_PASS',DUTY_TABLE='$DUTY_TABLE',DUTY_USER='$DUTY_USER',DUTY_TIME='$DUTY_TIME',DATABASE_NAME='$DATABASE_NAME' where MACHINEID=1";
exequery(TD::conn(),$query);

header("location: index.php");
?>
</body>
</html>
