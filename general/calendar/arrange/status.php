<?
include_once("inc/auth.inc.php");
include_once("inc/utility_calendar.php");
$query = "UPDATE CALENDAR SET OVER_STATUS='$OVER_STATUS' WHERE  (USER_ID='".$_SESSION["LOGIN_USER_ID"]."' or find_in_set('".$_SESSION["LOGIN_USER_ID"]."',TAKER) or find_in_set('".$_SESSION["LOGIN_USER_ID"]."',OWNER)) and CAL_ID='$CAL_ID'";
$cursor = exequery(TD::conn(),$query);

if($OVER_STATUS==1)
{
	delete_taskcenter('CALENDAR',$CAL_ID);
}
ob_end_clean();
echo $cursor ? "1" : "0";
?>
