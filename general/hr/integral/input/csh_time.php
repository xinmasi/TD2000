<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("设置开始积分时间");
include_once("inc/header.inc.php");
?>


<script src="/module/DatePicker/WdatePicker.js"></script>

<?
$query="update HR_INTEGRAL_ITEM_TYPE set CALCULATE_TIME='$CALCULATE_TIME'";
exequery(TD::conn(),$query);

Message(_("提示"),_("开始积分时间设置成功！"));
header("location:auto.php");
?>