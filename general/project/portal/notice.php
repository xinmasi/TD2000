<?
/**
*   proj_notice.php�ļ�
*
*   �ļ�����������
*   1��ҳ����Ϣ��ʾ��
*
*   @author  Τ��
*
*   @edit_time  2013/09/16
*
*/
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
?>
<body>
<br><br><br><br>
<?
Message("", _("���ð��ݲ����Ŵ˹��ܣ������ڴ���ʽ��"));
if($_GET['type']=='back')
{
    Button_Back();
}
?>
</body>
</html>