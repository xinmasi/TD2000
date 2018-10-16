<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_sms2.php");
include_once("inc/utility_sms1.php");

$HTML_PAGE_TITLE = _("添加批注");
include_once("inc/header.inc.php");
?>

<body class="bodycolor">

<?
$query="insert into PROJ_COMMENT (PROJ_ID,WRITE_TIME,CONTENT,WRITER) values ('$PROJ_ID','$WRITE_TIME','$CONTENT','".$_SESSION["LOGIN_USER_ID"]."')";
exequery(TD::conn(),$query);

$query = "SELECT PROJ_NAME,PROJ_USER from PROJ_PROJECT  where PROJ_ID='$PROJ_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
    $PROJ_NAME=$ROW["PROJ_NAME"];
    $PROJ_USER=$ROW["PROJ_USER"];
}
$PROJ_USER=str_replace("|",",",$PROJ_USER);

$SMS_CONTENT=_("项目[$PROJ_NAME]有新的批注，请查看。");

$REMIND_URL="1:project/proj/comment/?PROJ_ID=".$PROJ_ID;

if($SMS_REMIND=="on")
    send_sms($CUR_TIME,$_SESSION["LOGIN_USER_ID"],$PROJ_USER,42,$SMS_CONTENT,$REMIND_URL,$PROJ_ID);

if($SMS2_REMIND=="on")
    send_mobile_sms_user($CUR_TIME,$_SESSION["LOGIN_USER_ID"],$PROJ_USER,$SMS_CONTENT,42);

header("location: index.php?PROJ_ID=$PROJ_ID");
?>

</body>
</html>