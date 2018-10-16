<?
header("pragma:no-cache");
include_once("inc/auth.inc.php");
include_once("inc/utility_file.php");
ob_end_clean();

$CUR_TIME=date("Y-m-d H:i:s",time());
$MSG_FILE=MYOA_ATTACH_PATH."chatroom/".$CHAT_ID.".msg";

if(!file_exists($MSG_FILE))
{
   $fp=td_fopen($MSG_FILE,"r");
   flock ($fp,"LOCK_EX");
   fclose($fp);
}
$SCREEN_HEIGHT=20;
$LINES=file($MSG_FILE);
$LINES_COUNT=count($LINES);
$LINES_START=$LINES_COUNT-$SCREEN_HEIGHT;
if($LINES_START<0)
	$LINES_START=0;

for($I=$LINES_START;$I<$LINES_COUNT;$I++)
{
    $STR=substr($LINES[$I],0,strlen($LINES[$I])-1);
    $TO_STR=substr($STR,0,strpos($STR,"@+#"));
    $TO_ID="";
    $FROM_ID="";

    if($TO_STR!="")
    {
       $TO_STR=strtok($TO_STR,",");
       $TO_ID=$TO_STR;
       $TO_STR=strtok(",");
       $FROM_ID=$TO_STR;
       $POS=strpos($STR,"@+#");
       $STR=substr($STR,$POS+3);
    }

    if($STR!="")
    {
      if($TO_ID=="" || $TO_ID==$_SESSION["LOGIN_USER_ID"] || $FROM_ID==$_SESSION["LOGIN_USER_ID"])
      {
        $OUT_PUT="<span>".$STR."</span><br>";

        $OUT_PUT=str_replace(chr(34),_("¡±"),$OUT_PUT);
        
        echo $OUT_PUT;

      }
    }
}
?>



