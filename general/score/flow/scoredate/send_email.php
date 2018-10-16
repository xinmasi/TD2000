<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
include_once("inc/utility_org.php");
include_once("inc/utility_sms1.php");

$query = "SELECT ITEM_NAME from SCORE_ITEM where GROUP_ID='$GROUP_ID'";
$cursor= exequery(TD::conn(),$query);
$VOTE_COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
    $ITEM_NAME[$VOTE_COUNT]=$ROW["ITEM_NAME"];
    $VOTE_COUNT++;
}

$CONTENT="<table width=\"100%\" class=\"TableList\"><thead class=\"TableHeader\"><td nowrap align=\"center\">"._("姓名")."</td>";
$ARRAY_COUNT=sizeof($ITEM_NAME);
for($I=0;$I<$ARRAY_COUNT;$I++)
    $CONTENT.="<td nowrap align=\"center\">$ITEM_NAME[$I]</td>";
$CONTENT.="<td nowrap align=\"center\">"._("总计")."</td></thead>";

$CONTENT.="<tr><td align=\"center\">".substr(GetUserNameById($USER_ID),0,-1)."</td>";
$MY_AVE=explode(",",$SCORE_STR);
$ARRAY_COUNT=sizeof($MY_AVE);
for($I=0;$I < $ARRAY_COUNT;$I++)
    $CONTENT.="<td align=\"center\">$MY_AVE[$I]</td>";
$CONTENT.="</tr></table>";

$SEND_TIME=time();
$FLOW_TITLE=_("考核结果")."-".$FLOW_TITLE;
$query="INSERT
   INTO EMAIL_BODY (FROM_ID,TO_ID2,COPY_TO_ID,SUBJECT,CONTENT,SEND_TIME,ATTACHMENT_ID,ATTACHMENT_NAME,SEND_FLAG,IMPORTANT)
   SELECT '".$_SESSION["LOGIN_USER_ID"]."' as FROM_ID,'$USER_ID,' as TO_ID2,'' as COPY_TO_ID ,'$FLOW_TITLE' as SUBJECT,'$CONTENT' as CONTENT,
   '$SEND_TIME' as SEND_TIME,'' as ATTACHMENT_ID,'' as ATTACHMENT_NAME,'1' as SEND_FLAG,'0' as IMPORTANT
   from USER where USER_ID='$USER_ID'";
exequery(TD::conn(),$query);

$BODY_ID=mysql_insert_id();
$query="INSERT INTO EMAIL(TO_ID,READ_FLAG,DELETE_FLAG,BOX_ID,BODY_ID) values('$USER_ID','0','0','0','$BODY_ID')";
exequery(TD::conn(),$query);
$ROW_ID=mysql_insert_id();
$REMIND_URL="email/inbox/read_email/?BOX_ID=0&EMAIL_ID=".$ROW_ID;
$SMS_CONTENT=_("请查收邮件！")."\n"._("主题：考核结果");
send_sms("",$_SESSION["LOGIN_USER_ID"],$USER_ID,15,$SMS_CONTENT,$REMIND_URL,$ROW_ID);
?>
    <body class="bodycolor">
<?
Message(_("提示"),_("发送成功"));
Button_Back();
?>