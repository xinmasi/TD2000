<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_sms1.php");

$HTML_PAGE_TITLE = _("删除工资上报流程");
include_once("inc/header.inc.php");
?>




<body class="bodycolor">

<?
$FLOW_ID = intval($FLOW_ID);
$query="select * from SAL_FLOW where FLOW_ID='$FLOW_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
   $BEGIN_DATE=$ROW["BEGIN_DATE"];
   $CONTENT=$ROW["CONTENT"];

   $SMS_CONTENT=sprintf(_("请进行工资上报！%s备注："), "\n").csubstr($CONTENT,0,100);
}

$query="delete from SAL_FLOW where FLOW_ID='$FLOW_ID'";
exequery(TD::conn(),$query);

$query="delete from SAL_DATA where FLOW_ID='$FLOW_ID'";
exequery(TD::conn(),$query);

delete_remind_sms(4, $_SESSION["LOGIN_USER_ID"], $SMS_CONTENT, $BEGIN_DATE);

header("location: index.php?PAGE_START=$PAGE_START&connstatus=1");
?>
</body>
</html>
