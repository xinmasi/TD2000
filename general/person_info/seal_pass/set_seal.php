<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_sms1.php");
$CUR_TIME=date("Y-m-d H:i:s",time());
ob_end_clean();

//判断此印章是否其他人有使用权限
$query = "select SEAL_ID,SEAL_NAME,USER_STR FROM SEAL WHERE ID='$ID'";
$cursor = exequery(TD::conn(),$query);
if($ROW = mysql_fetch_array($cursor));
{
	$SEAL_ID = $ROW[0];
    $SEAL_NAME = $ROW[1];
    $USER_STR = $ROW[2];
}
if(strlen($USER_STR) > strlen($_SESSION["LOGIN_USER_ID"])+1)
{
	$SMS_CONTENT =sprintf(_("印章:<%s>密码已经修改，新的密码为：%s"),$SEAL_NAME,$PASS);
  	send_sms($SEND_TIME,$_SESSION["LOGIN_USER_ID"],$USER_STR,0,$SMS_CONTENT,"");
}

$query = "update SEAL set SEAL_DATA='$SEAL_DATA' WHERE ID='$ID'";
exequery(TD::conn(),$query);

//写入日志
$IP_ADD = get_client_ip();
$query = "insert into SEAL_LOG (S_ID,LOG_TYPE,LOG_TIME,RESULT,IP_ADD,USER_ID) VALUES ('$SEAL_ID','modifyseal','$CUR_TIME','".sprintf(_("印章[%s]修改密码成功"), $SEAL_NAME)."','$IP_ADD','".$_SESSION["LOGIN_USER_ID"]."')";
exequery(TD::conn(),$query);

?>