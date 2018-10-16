<?
include_once("inc/auth.inc.php");
ob_end_clean();
include_once("finger/config.php");

$XMiddle = new COM("XNMiddleCom.XNMiddleComObj") or die("error!");
$XMiddle -> InitAgent($ProductID,$PacketType,$HostName,$Port,$Persistent);
$XMiddle -> ConnectServer();
$XMiddle -> SetFPDeviceType($DeviceType);
$result = $XMiddle -> GetEnrolledFingers($_GET[USER_ID]);
if($result==0)
   $strEnrollFingers = $XMiddle -> GetEnrolledFingersInResult();
echo $strEnrollFingers;
?>
