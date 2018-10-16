<?
include_once("inc/auth.inc.php");
ob_end_clean();

$where_str = "";
if($CONTRACT_ID!="")
   $where_str = " and CONTRACT_ID != '$CONTRACT_ID'";

$query = "SELECT * from HR_STAFF_CONTRACT where STAFF_CONTRACT_NO='$STAFF_CONTRACT_NO'" .$where_str;
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
   echo "-ERR";
else
   echo "+OK";
?>