<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("���ÿ�ʼ����ʱ��");
include_once("inc/header.inc.php");
?>


<script src="/module/DatePicker/WdatePicker.js"></script>

<?
$query="update HR_INTEGRAL_ITEM_TYPE set CALCULATE_TIME='$CALCULATE_TIME'";
exequery(TD::conn(),$query);

Message(_("��ʾ"),_("��ʼ����ʱ�����óɹ���"));
header("location:auto.php");
?>