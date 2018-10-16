<?
include_once("inc/auth.inc.php");
include_once("inc/utility_sms1.php");

include_once("inc/header.inc.php");
?>




<body class="bodycolor">
<?
$PIECES = explode(",", $TO_ID);
$PIECES_COUNT=sizeof($PIECES);
if($PIECES[$PIECES_COUNT-1]=="")$PIECES_COUNT--;
for($I=0;$I<$PIECES_COUNT;$I++)
{
   if($PIECES[$I]=="ALL_DEPT")
      $query = "select * from USER where NOT_LOGIN='0'";
   else   
      $query = "select * from USER where DEPT_ID='$PIECES[$I]'";
   $cursor=exequery(TD::conn(),$query);
   while($ROW=mysql_fetch_array($cursor))
   {
      $USER_ID1=$ROW["USER_ID"];
      if($_SESSION["LOGIN_USER_ID"]==$USER_ID1)
         continue;
      $TO_USER.=$USER_ID1.",";
   }
}

$PIECES = explode(",", $PRIV_ID);
$PIECES_COUNT=sizeof($PIECES);
if($PIECES[$PIECES_COUNT-1]=="")$PIECES_COUNT--;
for($I=0;$I<$PIECES_COUNT;$I++)
{
   $query = "select * from USER where USER_PRIV='$PIECES[$I]'";
   $cursor=exequery(TD::conn(),$query);
   while($ROW=mysql_fetch_array($cursor))
   {
      $USER_ID1=$ROW["USER_ID"];
      if($_SESSION["LOGIN_USER_ID"]==$USER_ID1)
         continue;
      $TO_USER.=$USER_ID1.",";
   }
}

$PIECES = explode(",", $TO_ID3);
$PIECES_COUNT=sizeof($PIECES);
if($PIECES[$PIECES_COUNT-1]=="")$PIECES_COUNT--;
for($I=0;$I<$PIECES_COUNT;$I++)
{
   $query = "select * from USER where USER_ID='$PIECES[$I]'";
   $cursor=exequery(TD::conn(),$query);
   while($ROW=mysql_fetch_array($cursor))
   {
      $USER_ID1=$ROW["USER_ID"];
      if($_SESSION["LOGIN_USER_ID"]==$USER_ID1)
         continue;
      $TO_USER.=$USER_ID1.",";
   }
}

$query="insert into BBS_BOARD(BOARD_NAME,DEPT_ID,ANONYMITY_YN,WELCOME_TEXT,BOARD_HOSTER,BOARD_NO,PRIV_ID,USER_ID,CATEGORY,LOCK_DAYS_BEFORE,NEED_CHECK) values ('$BOARD_NAME','$TO_ID','$ANONYMITY_YN','$WELCOME_TEXT','$COPY_TO_ID','$BOARD_NO','$PRIV_ID','$TO_ID3','$CATEGORY','$LOCK_DAYS_BEFORE','$NEED_CHECK')";
exequery(TD::conn(),$query);
$ROW_ID=mysql_insert_id();

if($SMS_REMIND=="on" && $TO_USER!="")
   send_sms("",$_SESSION["LOGIN_USER_ID"],$TO_USER,18,$BOARD_NAME._("内部讨论区欢迎您的光临！"),$REMIND_URL);
if($SMS2_REMIND=="on" && $TO_USER!="")
   send_mobile_sms_user("",$_SESSION["LOGIN_USER_ID"],$TO_USER,$BOARD_NAME._("内部讨论区欢迎您的光临！"),18);

header("location: index.php?IS_MAIN=1");
?>

</body>
</html>