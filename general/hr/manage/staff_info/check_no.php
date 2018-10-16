<?
include_once("inc/auth.inc.php");
ob_end_clean();
$where_str = "";
if($USER_ID!="")
    $where_str = " and USER_ID!='$USER_ID'";

$query = "SELECT * from HR_STAFF_INFO where STAFF_NO='$STAFF_NO'".$where_str;
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
    echo "-ERRNO";
else
    echo "+OK";
?>