<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
include_once("inc/utility_file.php");
include_once("sql_inc.php");

$query="select * from NOTIFY where NOTIFY_ID='$NOTIFY_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
    $SUBJECT=$ROW["SUBJECT"];
    $CONTENT=$ROW["CONTENT"];
    $BEGIN_DATE=$ROW["BEGIN_DATE"];
    $END_DATE=$ROW["END_DATE"];
    $ATTACHMENT_ID=$ROW["ATTACHMENT_ID"];
    $ATTACHMENT_NAME=$ROW["ATTACHMENT_NAME"];
    $PRINT=$ROW["PRINT"];
    $DOWNLOAD=$ROW["DOWNLOAD"];
    $FORMAT=$ROW["FORMAT"];
    $TOP=$ROW["TOP"];
    $TYPE_ID=$ROW["TYPE_ID"];
    $COMPRESS_CONTENT=@gzuncompress($ROW["COMPRESS_CONTENT"]);        
    $COMPRESS_CONTENT = bin2hex(gzcompress($COMPRESS_CONTENT));
}

$ATTACHMENT_ID_ARRAY=explode(",",$ATTACHMENT_ID);
$ATTACHMENT_NAME_ARRAY=explode("*",$ATTACHMENT_NAME);
$ARRAY_COUNT=sizeof($ATTACHMENT_ID_ARRAY);
for($I=0;$I<$ARRAY_COUNT;$I++)
{
  if($ATTACHMENT_ID_ARRAY[$I]=="")
     continue;

  $ATTACHMENT_ID_NEW.=copy_attach($ATTACHMENT_ID_ARRAY[$I],$ATTACHMENT_NAME_ARRAY[$I],"","",true).",";
  $ATTACHMENT_NAME_NEW.=$ATTACHMENT_NAME_ARRAY[$I]."*";
}

$CONTENT=addslashes($CONTENT);

$CUR_TIME=date("Y-m-d H:i:s",time());
$CUR_DATE=date("Y-m-d",time());
$CUR_DATE_U=time();
$DATA="FROM_ID,SUBJECT,CONTENT,SEND_TIME,BEGIN_DATE,END_DATE,ATTACHMENT_ID,ATTACHMENT_NAME,PRINT,DOWNLOAD,FORMAT,TOP,TYPE_ID,PUBLISH,COMPRESS_CONTENT,IS_FW,FROM_DEPT";
$DATA_VALUE="'".$_SESSION["LOGIN_USER_ID"]."','$SUBJECT','$CONTENT','$CUR_TIME','$CUR_DATE_U','$END_DATE','$ATTACHMENT_ID_NEW','$ATTACHMENT_NAME_NEW','$PRINT','$DOWNLOAD','$FORMAT','$TOP','$TYPE_ID','0',0x$COMPRESS_CONTENT,'1','".$_SESSION["LOGIN_DEPT_ID"]."'";
$NOTIFY_ID=insert_notify($DATA,$DATA_VALUE);

header("location: modify.php?FROM=1&NOTIFY_ID=$NOTIFY_ID&FW_FLAG=1");
?>