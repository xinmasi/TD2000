<?
include_once("inc/auth.inc.php");
include_once("inc/utility_file.php");

include_once("inc/header.inc.php");
?>




<body class="bodycolor">

<?
  if($OPERATION==1)
  {
     $MSG_FILE="../../chatroom/chat/msg/".$CHAT_ID.".msg";
     $STOP_FILE="../../chatroom/chat/msg/".$CHAT_ID.".stp";

     $fp = td_fopen($MSG_FILE,"a+");
     fputs($fp,"\n"._("[系统消息] - 聊天室已经关闭  "));
     fclose($fp);

     $fp = td_fopen($STOP_FILE,"w");
     fclose($fp);

     $query="update CHATROOM set STOP='1' where CHAT_ID='$CHAT_ID'";
  }
  else
  {
     $STOP_FILE="../../chatroom/chat/msg/".$CHAT_ID.".stp";
     if(file_exists($STOP_FILE))
     {
        unlink($STOP_FILE);

        $MSG_FILE="../../chatroom/chat/msg/".$CHAT_ID.".msg";
        $fp = td_fopen($MSG_FILE,  "a+");
        fputs($fp,"\n"._("[系统消息] - 聊天室恢复开放  "));
        fclose($fp);
     }

     $query="update CHATROOM set STOP='0' where CHAT_ID='$CHAT_ID'";
  }

  exequery(TD::conn(),$query);

  header("location: index.php");
?>

</body>
</html>
