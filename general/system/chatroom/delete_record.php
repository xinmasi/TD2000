<?
include_once("inc/auth.inc.php");

include_once("inc/header.inc.php");
?>




<body class="bodycolor">
<br>
<?
  //-- 删除文件 --
  $MSG_FILE="../../chatroom/chat/msg/".$CHAT_ID.".msg";

  if(file_exists($MSG_FILE))
  {
     unlink($MSG_FILE);
     Message("",_("聊天记录已删除！"));
  }
  else
     Message("",_("无聊天记录！"));
  
  Button_Back();
?>

</body>
</html>
