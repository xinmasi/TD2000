<?
include_once("inc/auth.inc.php");

$check_str="";
$query = "SELECT * from USER where USER_ID='$USER_ID' or BYNAME='$USER_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
    $check_str="-ERR";
else
    $check_str="+OK";

if($check_str=="+OK")
{
    $query = "SELECT * from HR_STAFF_INFO where USER_ID='$USER_ID'";
    $cursor= exequery(TD::conn(),$query);
    if($ROW=mysql_fetch_array($cursor))
        $check_str="-ERR1";
    else
        $check_str="+OK";
}

ob_end_clean();
echo $check_str;
?>