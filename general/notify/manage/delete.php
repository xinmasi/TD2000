<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");
include_once("inc/utility_sms1.php");
include_once("inc/utility_cache.php");

include_once("inc/header.inc.php");
?>




<body class="bodycolor">

<?
$PARA_ARRAY=get_sys_para("NOTIFY_EDIT_PRIV");
if($DELETE_STR=="")
   $DELETE_STR=0;
elseif(substr($DELETE_STR,-1,1)==",")
   $DELETE_STR=substr($DELETE_STR,0,-1);
$query="select BEGIN_DATE,SUBJECT,ATTACHMENT_ID,ATTACHMENT_NAME from NOTIFY where NOTIFY_ID in ($DELETE_STR)";
$cursor= exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
  $BEGIN_DATE=$ROW["BEGIN_DATE"];
  $SUBJECT1=$ROW["SUBJECT"];
  $SUMMARY=$ROW["SUMMARY"];
  $ATTACHMENT_ID=$ROW["ATTACHMENT_ID"];
  $ATTACHMENT_NAME=$ROW["ATTACHMENT_NAME"];
  $BEGIN_DATE=date("Y-m-d",$BEGIN_DATE);

  $SMS_CONTENT=_("请查看公告通知！")."\n"._("标题：").csubstr($SUBJECT1,0,100);
  if($SUMMARY)
    $SMS_CONTENT .= "\n"._("内容简介：") . $SUMMARY;
  $BEGIN_DATE=$BEGIN_DATE." 08:00:00";
  delete_remind_sms(1, $_SESSION["LOGIN_USER_ID"], $SMS_CONTENT, $BEGIN_DATE);

  if($ATTACHMENT_NAME!="")
     delete_attach($ATTACHMENT_ID,$ATTACHMENT_NAME);
}  
$query="delete from NOTIFY where NOTIFY_ID in ($DELETE_STR)";
exequery(TD::conn(),$query);

add_log(15,_("删除公告通知，标题：").$SUBJECT1,$_SESSION["LOGIN_USER_ID"]);

if($SEARCH==1)
{
    header("location: search.php?start=$start&SEARCH=1&SEND_TIME_MIN=$SEND_TIME_MIN&SEND_TIME_MAX=$SEND_TIME_MAX&SUBJECT=$SUBJECT&CONTENT=$CONTENT&FORMAT=$FORMAT&TYPE_ID=$TYPE_ID&PUBLISH=$PUBLISH&TOP=$TOP&TO_ID=$TO_ID&STAT=$STAT&IS_MAIN=1");
}
else
{
   if($FROM!=2)
      header("location: index1.php?start=$start&IS_MAIN=1");
   else
      header("location:../auditing/audited.php?start=$start&IS_MAIN=1");
}
?>

</body>
</html>
