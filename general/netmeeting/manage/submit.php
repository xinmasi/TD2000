<?
include_once("inc/auth.inc.php");
include_once("inc/utility_sms1.php");
include_once("inc/check_type.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("增加参会人员");
include_once("inc/header.inc.php");
?>



<body class="bodycolor">
<?
$BEGIN_TIME=date("Y-m-d H:i:s",time());
$query = "SELECT SUBJECT,TO_ID from NETMEETING where MEET_ID='$MEET_ID'";
$cursor= exequery(TD::conn(),$query);
if ($ROW=mysql_fetch_array($cursor))
{
   $SUBJECT=$ROW["SUBJECT"];
   $OLD_TO_ID=$ROW["TO_ID"];
}

$NEW_ADD_ID="";

$ARR_TO_ID=explode(",",$TO_ID);
for($I=0;$I<sizeof($ARR_TO_ID);$I++)
{
   if ($ARR_TO_ID[$I]!="")
   {
      if(!strstr($OLD_TO_ID,$ARR_TO_ID[$I]))
         $NEW_ADD_ID.=$ARR_TO_ID[$I].",";
   }
}

$NEW_TO_ID=$OLD_TO_ID.$NEW_ADD_ID;

$query="update NETMEETING set TO_ID='$NEW_TO_ID' where MEET_ID='$MEET_ID'";
exequery(TD::conn(),$query);

$SMS_CONTENT=_("请出席网络会议！")."\n"._("议题：").csubstr($SUBJECT,0,100);

if ($NEW_ADD_ID!="")
{
   if($SMS_REMIND=="on")
      send_sms($BEGIN_TIME,$_SESSION["LOGIN_USER_ID"],$NEW_ADD_ID,3,$SMS_CONTENT,$REMIND_URL);

   if($SMS2_REMIND=="on")
      send_mobile_sms_user($BEGIN_TIME,$_SESSION["LOGIN_USER_ID"],$NEW_ADD_ID,$SMS_CONTENT,3);
}

header("location: index.php");
?>
</body>
</html>
