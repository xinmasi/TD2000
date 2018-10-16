<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_sms1.php");

include_once("inc/header.inc.php");
?>




<body class="bodycolor">

<?
$CAL_ID=intval($CAL_ID);
$query="select * from CALENDAR where CAL_ID='$CAL_ID' and MANAGER_ID='".$_SESSION["LOGIN_USER_ID"]."'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
   $CAL_TIME=$ROW["CAL_TIME"];
   $CONTENT=$ROW["CONTENT"];

   $SMS_CONTENT=_("请查看日程安排！")."\n"._("内容：").csubstr($CONTENT,0,100);
}

$query="delete from CALENDAR where CAL_ID='$CAL_ID' and MANAGER_ID='".$_SESSION["LOGIN_USER_ID"]."'";
exequery(TD::conn(),$query);

delete_remind_sms(5, $_SESSION["LOGIN_USER_ID"], $SMS_CONTENT, $CAL_TIME);

header("location: user_list.php?YEAR=$YEAR&MONTH=$MONTH&BEGIN_DAY=$BEGIN_DAY&END_DAY=$END_DAY&USER_ID=$USER_ID");
?>

</body>
</html>
