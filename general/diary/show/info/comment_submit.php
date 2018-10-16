<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");
include_once("inc/utility_sms1.php");
include_once("inc/utility_sms2.php");

$HTML_PAGE_TITLE = _("日志修改");
include_once("inc/header.inc.php");
?>




<body class="bodycolor" topmargin="5">
<br>
<?
//--------- 上传附件 ----------
if(count($_FILES)>1)
{
   $ATTACHMENTS=upload();
   $CONTENT=ReplaceImageSrc($CONTENT, $ATTACHMENTS);

   $ATTACHMENT_ID=$ATTACHMENTS["ID"];
   $ATTACHMENT_NAME=$ATTACHMENTS["NAME"];
}

$ATTACHMENT_ID.=copy_sel_attach($ATTACH_NAME,$ATTACH_DIR,$DISK_ID);
$ATTACHMENT_NAME.=$ATTACH_NAME;

$CONTENT=str_replace("http://".$HTTP_HOST."/inc/attach.php?","/inc/attach.php?",$CONTENT);
$CONTENT=str_replace("http://".$HTTP_HOST."/module/editor/plugins/smiley/images/","/module/editor/plugins/smiley/images/",$CONTENT);  
if($TOP=="on")
  $TOP='1';
else
  $TOP='0';

//------------------- 保存 -----------------------
$query = "SELECT * from DIARY where DIA_ID='$DIA_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
   $SUBJECT1=$ROW["SUBJECT"];
   $NOTAGS_CONTENT=$ROW["CONTENT"];    
   $CONTENT1=@gzuncompress($ROW["COMPRESS_CONTENT"]);
   if($CONTENT1=="")   
      $CONTENT1=$NOTAGS_CONTENT;   
   $DIA_DATE=$ROW["DIA_DATE"];
   $DIA_DATE=strtok($DIA_DATE," ");

   if($SUBJECT1=="")
      $SUBJECT1=csubstr(strip_tags($CONTENT1),0,50).(strlen($CONTENT1)>50?"...":"");
}

$SEND_TIME=date("Y-m-d H:i:s",time());

//if($COMMENT_ID!="")
//   $query="update DIARY_COMMENT set SEND_TIME='$SEND_TIME',CONTENT='$CONTENT' where COMMENT_ID='$COMMENT_ID'";
//else
   $query="insert into DIARY_COMMENT (DIA_ID,USER_ID,CONTENT,SEND_TIME,ATTACHMENT_ID,ATTACHMENT_NAME) values($DIA_ID,'".$_SESSION["LOGIN_USER_ID"]."','$CONTENT','$SEND_TIME','$ATTACHMENT_ID','$ATTACHMENT_NAME')";
exequery(TD::conn(),$query);

$query="update DIARY set LAST_COMMENT_TIME='$SEND_TIME' where DIA_ID='$DIA_ID'";
exequery(TD::conn(),$query);

$REMIND_URL="diary/show/info/read.php?FROM_TABLE=1&DIA_ID=".$DIA_ID;
$CONTENT=strip_tags($CONTENT);
$MSG = sprintf(_("%s对您 %s 的工作日志“%s”进行了点评，点评内容：%s"), $_SESSION["LOGIN_USER_NAME"],$DIA_DATE,$SUBJECT1,$CONTENT);
$SMS_CONTENT=$MSG;
if($SMS_REMIND=="on")
   send_sms("",$_SESSION["LOGIN_USER_ID"],$DIA_USER,13,$SMS_CONTENT,$REMIND_URL,$DIA_ID);

if($SMS2_REMIND=="on")
   send_mobile_sms_user("",$_SESSION["LOGIN_USER_ID"],$DIA_USER,$SMS_CONTENT,13);
$SUBJECT = urlencode($SUBJECT);
header("location: comment.php?DIA_ID=$DIA_ID&USER_ID=$DIA_USER&FROM=$FROM&BEGIN_DATE=$BEGIN_DATE&END_DATE=$END_DATE&SUBJECT=$SUBJECT&DIA_TYPE=$DIA_TYPE&TO_ID=$TO_ID&TO_ID1=$TO_ID1&PRIV_ID=$PRIV_ID&COPYS_TO_ID=$COPYS_TO_ID&IS_MAIN=1");
?>