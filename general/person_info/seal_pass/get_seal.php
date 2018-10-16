<?
include_once("inc/auth.inc.php");
ob_end_clean();

$query = "select SEAL_DATA FROM SEAL WHERE ID='$ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
   echo $ROW[0];
?>