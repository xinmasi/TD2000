<?
include_once("inc/auth.inc.php");

$check_str="";
$query = "SELECT IS_BLACKLIST from hr_staff_leave where STAFF_CARD_NO='$STAFF_CARD_NO'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
    $check_str=1;

ob_end_clean();
echo $check_str;
?>