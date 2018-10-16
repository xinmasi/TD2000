<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");


$query  = "SELECT * FROM hr_staff_contract WHERE STAFF_NAME != ''";
$cursor = exequery(TD::conn(),$query);
while($row = mysql_fetch_array($cursor))
{
	$sql = "UPDATE hr_staff_contract SET USER_NAME=(SELECT BYNAME FROM user WHERE USER_ID='{$row['STAFF_NAME']}') WHERE CONTRACT_ID = '{$row['CONTRACT_ID']}';";
	//file_put_contents("1.txt",$sql."\r\n",FILE_APPEND);
	exequery(TD::conn(),$sql);
}

Message("",_("数据同步成功"));
Button_Back();
//header("location: index1.php?PAGE_START=$PAGE_START&connstatus=1");
?>
