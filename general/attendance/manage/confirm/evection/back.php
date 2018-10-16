<?
include_once("inc/auth.inc.php");
include_once("inc/utility.php");
include_once("inc/utility_sms1.php");

$HTML_PAGE_TITLE = _("´ú¹éÀ´");
include_once("inc/header.inc.php");
?>




<body class="bodycolor">
<?
$SELECTED_STR = td_trim($SELECTED_STR);
if($SELECTED_STR != "")
{
   $query="update ATTEND_EVECTION set ALLOW='1',STATUS='2' where EVECTION_ID in ($SELECTED_STR)";
   exequery(TD::conn(),$query);
}
if($EVECTION_ID != "")
{
   $query="update ATTEND_EVECTION set ALLOW='1',STATUS='2' where EVECTION_ID = '$EVECTION_ID'";
   exequery(TD::conn(),$query);
}

header("location: ./evection_back.php?connstatus=1");
?>

</body>
</html>
