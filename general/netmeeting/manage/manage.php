<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
include_once("inc/utility_file.php");

$CUR_TIME=date("Y-m-d H:i:s",time());

if($OPERATION==1)
   $query="update NETMEETING set BEGIN_TIME='$CUR_TIME' where MEET_ID='$MEET_ID'";
else if($OPERATION==2)
{
   $MSG_FILE=MYOA_ATTACH_PATH."netmeeting/".$MEET_ID.".msg";
   $STOP_FILE=MYOA_ATTACH_PATH."netmeeting/".$MEET_ID.".stp";

   $fp = td_fopen($MSG_FILE,"a+");
   fputs($fp,"\n"._("[ϵͳ��Ϣ] - �����ټ����Ѿ���������  "));
   fclose($fp);

   $fp = td_fopen($STOP_FILE,"w");
   fclose($fp);

   $query="update NETMEETING set STOP='1' where MEET_ID='$MEET_ID'";
}
else
{
   $STOP_FILE=MYOA_ATTACH_PATH."netmeeting/".$MEET_ID.".stp";
   if(file_exists($STOP_FILE))
   {
      unlink($STOP_FILE);

      $MSG_FILE=MYOA_ATTACH_PATH."netmeeting/".$MEET_ID.".msg";
      $fp = td_fopen($MSG_FILE,  "a+");
      fputs($fp,"\n"._("[ϵͳ��Ϣ] - �����������"));
      fclose($fp);
   }

   $query="update NETMEETING set STOP='0' where MEET_ID='$MEET_ID'";
}

exequery(TD::conn(),$query);

header("location: index.php");
?>
