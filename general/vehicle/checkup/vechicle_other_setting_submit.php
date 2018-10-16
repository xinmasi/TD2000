<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("其他设置");
include_once("inc/header.inc.php");

$VEHICLE_HOOKED = intval($VEHICLE_HOOKED);
set_sys_para(array("VEHICLE_HOOKED" => "$VEHICLE_HOOKED"));
message("", _("保存成功"));
Button_Back();
?>
<body class="bodycolor">
</body>
</html>