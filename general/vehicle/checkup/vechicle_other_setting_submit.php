<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("��������");
include_once("inc/header.inc.php");

$VEHICLE_HOOKED = intval($VEHICLE_HOOKED);
set_sys_para(array("VEHICLE_HOOKED" => "$VEHICLE_HOOKED"));
message("", _("����ɹ�"));
Button_Back();
?>
<body class="bodycolor">
</body>
</html>