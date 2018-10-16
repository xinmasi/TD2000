<?
include_once("inc/auth.inc.php");

$check_str="";
$where_str = "";
if($USER_ID != "")
{
	$where_str = " and USER_ID!='$USER_ID'";
}
$query = "SELECT * FROM hr_staff_info WHERE STAFF_CARD_NO='$idcard'".$where_str;
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
   $check_str="-ERR_CARD";
else
   $check_str="+OK_CARD";

ob_end_clean();
echo $check_str;
?>