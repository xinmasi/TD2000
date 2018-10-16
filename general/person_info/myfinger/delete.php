<?
include_once("inc/auth.inc.php");
ob_end_clean();

include_once("finger/config.php");
//echo $HostName;exit;
$XMiddle = new COM("XNMiddleCom.XNMiddleComObj") or die("error!");
$XMiddle -> InitAgent($ProductID,$PacketType,$HostName,$Port,$Persistent);
$XMiddle -> ConnectServer();
$XMiddle -> SetFPDeviceType($DeviceType);
$result = $XMiddle -> DeleteUserByPwd($USER_ID,$AuthUser,$AuthPwd);
echo $result;
?>