<?
include_once("inc/auth.inc.php");

include_once("inc/header.inc.php");
?>




<body class="bodycolor">
<br>
<?
  //-- ɾ���ļ� --
  $MSG_FILE="../../chatroom/chat/msg/".$CHAT_ID.".msg";

  if(file_exists($MSG_FILE))
  {
     unlink($MSG_FILE);
     Message("",_("�����¼��ɾ����"));
  }
  else
     Message("",_("�������¼��"));
  
  Button_Back();
?>

</body>
</html>
