<?
include_once("inc/auth.inc.php");
include_once("inc/utility_file.php");

$HTML_PAGE_TITLE = _("停止会议");
include_once("inc/header.inc.php");
?>




<body class="bodycolor" topmargin="8">

<?
 $query = "SELECT * from NETMEETING where FROM_ID='".$_SESSION["LOGIN_USER_ID"]."' and MEET_ID='$MEET_ID'";
 $cursor= exequery(TD::conn(),$query);
 if(!$ROW=mysql_fetch_array($cursor))
 {
 	  message(_("提示"),_("非法操作"));
 	 	exit;
 	}   

 $MSG_FILE=MYOA_ATTACH_PATH."netmeeting/".$MEET_ID.".msg";
 $STOP_FILE=MYOA_ATTACH_PATH."netmeeting/".$MEET_ID.".stp";

 $fp = td_fopen($MSG_FILE,"a+");
 fputs($fp,"\n"._("[系统消息] - 会议召集人已经结束会议  "));
 flock ($fp,2);
 fclose($fp);

 $fp = td_fopen($STOP_FILE,"w");
 fclose($fp);


 $query="update NETMEETING set STOP='1' where MEET_ID='$MEET_ID'";
 exequery(TD::conn(),$query);
?>

<center>
  <input type="button" value="<?=_("离开会场")?>" class="SmallButton" onclick="parent.location='../'">
</center>

<script>
   parent.chat_view.location.reload();
</script>

</body>
</html>
