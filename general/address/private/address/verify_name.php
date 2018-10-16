<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

$query = "SELECT ADD_ID,USER_ID FROM address WHERE USER_ID='".$_SESSION["LOGIN_USER_ID"]."' AND PSN_NAME='".$_GET['username']."'";
$cursor= exequery(TD::conn(),$query);
$COUNT = mysql_num_rows($cursor);
if($COUNT)
{
    echo "yes";exit;
}else
{
    echo "no";exit;
}
?>