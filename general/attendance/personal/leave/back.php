<?
include_once("inc/auth.inc.php");
include_once("inc/utility_sms1.php");

$HTML_PAGE_TITLE = _("��������");
include_once("inc/header.inc.php");
?>




<body class="bodycolor">

<?
  $CUR_TIME=date("Y-m-d H:i:s",time());
  $LEAVE_ID=intval($LEAVE_ID);
  $query="update ATTEND_LEAVE set ALLOW='3',DESTROY_TIME='$CUR_TIME' where LEAVE_ID='$LEAVE_ID'";
  exequery(TD::conn(),$query);

  //---------- �������� ----------
  $query = "SELECT * from USER where UID='".$_SESSION["LOGIN_UID"]."'";
  $cursor= exequery(TD::conn(),$query);
  if($ROW=mysql_fetch_array($cursor))
     $USER_NAME=$ROW["USER_NAME"];

  $SMS_CONTENT=$USER_NAME._("�ύ�������룬����ʾ��");
  
  $REMIND_URL="attendance/manage/confirm";

  $query="select * from ATTEND_LEAVE where LEAVE_ID='$LEAVE_ID'";
  $cursor=exequery(TD::conn(),$query);
  if($ROW=mysql_fetch_array($cursor))
  {
    $LEADER_ID=$ROW["LEADER_ID"];
    send_sms("",$_SESSION["LOGIN_USER_ID"],$LEADER_ID,6,$SMS_CONTENT,$REMIND_URL);
  }
  
 if($MOBILE_FLAG=="1")
  {
     $query = "SELECT * from USER where UID='".$_SESSION["LOGIN_UID"]."'";
     $cursor= exequery(TD::conn(),$query);
     if($ROW=mysql_fetch_array($cursor))
     $USER_NAME=$ROW["USER_NAME"];
     $SMS_CONTENT=$USER_NAME._("�ύ�������룬����ʾ��");
     $query="select * from ATTEND_LEAVE where LEAVE_ID='$LEAVE_ID'";
     $cursor=exequery(TD::conn(),$query);
     if($ROW=mysql_fetch_array($cursor))
     {
     $LEADER_ID=$ROW["LEADER_ID"];
     }
    send_mobile_sms_user("",$_SESSION["LOGIN_USER_ID"],$LEADER_ID,$SMS_CONTENT,6);
   }
  header("location: index.php?connstatus=1");
?>

</body>
</html>
