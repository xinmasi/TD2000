<?
include_once("inc/auth.inc.php");
ob_end_clean();
include_once("finger/config.php");
$XMiddle = new COM("XNMiddleCom.XNMiddleComObj") or die("error!");
$XMiddle -> InitAgent($ProductID,$PacketType,$HostName,$Port,$Persistent);
$XMiddle -> ConnectServer();
$XMiddle -> SetFPDeviceType($DeviceType);

if($fingerData=="")
{
  $IsUserExisted = $XMiddle -> IsUserExisted($_GET[USER_ID]);
  if($IsUserExisted!=0)
  {
     $result = $XMiddle -> AddUserByPwd($USER_ID,$AuthUser,$AuthPwd);
     echo $result;
     exit;
  }
  else
  {
     echo _("用户已存在");
     exit;
  }
}
else
{
	$result = $XMiddle ->ExecutePacket($_POST[fingerData]);
  echo $result;
  exit; 
}
?>