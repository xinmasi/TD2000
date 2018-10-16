<?
include_once("inc/auth.inc.php");
include_once("inc/check_type.php");
include_once("inc/utility_all.php");
include_once("inc/utility_sms1.php");

$HTML_PAGE_TITLE = _("固定资产维护记录保存");
include_once("inc/header.inc.php");
?>



<body class="bodycolor">
<?
$ADD_TIME=date("Y-m-d H:i:s");
$query1="select USER_ID from USER where USER_NAME='$TO_NAME'";
$cursor1= exequery(TD::conn(),$query1);
$NUM=mysql_num_rows($cursor1);
if($NUM==0)
{
   $TO_ID = $TO_NAME;
}
if($TO_ID=="")
  $TO_ID=$_SESSION["LOGIN_USER_ID"];

   
if($REMIND_TIME=="")
{
   $REMIND_TIME = $KEEP_TIME . " 08:00:00";
}

if($KEEP_ID=="")
   $query="insert into CP_ASSET_KEEP (CPTL_ID,KEEP_TYPE,KEEP_USER,KEEP_TIME,REMIND_TIME,REMARK,ADD_TIME,ADD_USER) values ('$CPTL_ID','$KEEP_TYPE','$TO_ID','$KEEP_TIME','$REMIND_TIME','$REMARK','$ADD_TIME','".$_SESSION["LOGIN_USER_ID"]."')";
else
   $query="update CP_ASSET_KEEP set CPTL_ID='$CPTL_ID',KEEP_TYPE='$KEEP_TYPE',KEEP_USER='$TO_ID',KEEP_TIME='$KEEP_TIME',REMIND_TIME='$REMIND_TIME',REMARK='$REMARK',ADD_TIME='$ADD_TIME',ADD_USER='".$_SESSION["LOGIN_USER_ID"]."' where KEEP_ID='$KEEP_ID'";
exequery(TD::conn(),$query);

Message("",_("维护记录保存成功！！"));

if($KEEP_TIME=="")
{
   $KEEP_TIME=date("Y-m-d");
}

if($NUM>0)
{
   $SMS_CONTENT = sprintf(_("[%s]提醒你[%s]在%s日需要维护"), $_SESSION["LOGIN_USER_NAME"], $CPTL_NAME, $KEEP_TIME);
   $REMIND_URL="asset/manage/keep.php?CPTL_ID=$CPTL_ID&KEEP_ID=$KEEP_ID";
   if($SMS_REMIND=="on")
   {
      send_sms($REMIND_TIME,$_SESSION["LOGIN_USER_ID"],$TO_ID,47,$SMS_CONTENT,$REMIND_URL,$CPTL_ID);
   }
   if($SMS2_REMIND=="on")
   {
      if($TO_ID!="")
         send_mobile_sms_user($REMIND_TIME,$_SESSION["LOGIN_USER_ID"],$TO_ID,$SMS_CONTENT,47);
   }
}
header("location:keep.php?CPTL_ID=$CPTL_ID&KEEP_ID='$KEEP_ID'");
?>
</body>
</html>
