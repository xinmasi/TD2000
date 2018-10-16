<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");
include_once("inc/check_type.php");
include_once("inc/utility_org.php");
include_once("inc/utility_cache.php");
include_once("sql_inc.php");
ob_end_clean();

function HandleError($message)
{
   echo "-ERR ".iconv(MYOA_CHARSET,"utf-8",$message);
   exit;
}

while (list($key, $value) = each($_GET))
   $$key=iconv("utf-8", MYOA_CHARSET, $value);
while (list($key, $value) = each($_POST))
   $$key=iconv("utf-8", MYOA_CHARSET, $value);


if(!isset($_FILES["Filedata"]) || !is_uploaded_file($_FILES["Filedata"]["tmp_name"]) || $_FILES["Filedata"]["error"] != 0)
{	
	HandleError(_("上传出现错误"));
}

while (list($key, $value) = each($_FILES["Filedata"])){
	$_FILES["Filedata"][$key] = iconv("utf-8",MYOA_CHARSET,$value);
}
$FILE_NAME=$_FILES["Filedata"]["name"];
if(strstr($FILE_NAME,"/") || strstr($FILE_NAME,"\\"))
{
	HandleError(_("文件名无效"));
}

if(!is_uploadable($FILE_NAME))
{
	HandleError(_("禁止上传该类文件"));
}

if(!file_exists($_FILES["Filedata"]["tmp_name"]))
{
	HandleError(_("文件上传失败"));
}

$ATTACH_NAME=$FILE_NAME;
//$SUBJECT=substr($FILE_NAME,0,strrpos($FILE_NAME,"."));
$SEND_TIME=date("Y-m-d H:i:s",time());
//变更上传程序，调用系统公用函数 edit by ljc 2012-05-03
$ATTACHMENTS = upload("Filedata","",FALSE);
if(!is_array($ATTACHMENTS))
{
	HandleError($ATTACHMENTS);
}

if($FORMAT=="1")
   $CONTENT="";
if($PRINT=="on")
   $PRINT='1';
else
   $PRINT='0';
if($DOWNLOAD=="on")
   $DOWNLOAD='1';
else
   $DOWNLOAD='0'; 
if($TOP=="on")
{
   $TOP='1';
}
else
{
   $TOP='0';
   $TOP_DAYS="";
}

if($END_DATE=="")
  $END_DATE="0000-00-00";

$BEGIN_DATE=strtotime($BEGIN_DATE) ;
$END_DATE=strtotime($END_DATE); 

$ATTACHMENT_ID=$ATTACHMENTS["ID"];   
$ATTACHMENT_NAME=$ATTACHMENTS["NAME"];

   $CONTENT=strip_unsafe_tags($CONTENT);
   $CONTENT = stripslashes($CONTENT);
   $COMPRESS_CONTENT = bin2hex(gzcompress($CONTENT));
   $CONTENT = addslashes(strip_tags($CONTENT));
   
if ($NOTIFY_ID=="")
{   
   $DATA="FROM_DEPT,FROM_ID,TO_ID,SUBJECT,SUMMARY,CONTENT,SEND_TIME,BEGIN_DATE,END_DATE,ATTACHMENT_ID,ATTACHMENT_NAME,PRINT,FORMAT,TOP,TOP_DAYS,PRIV_ID,USER_ID,TYPE_ID,PUBLISH,AUDITER,COMPRESS_CONTENT,DOWNLOAD,SUBJECT_COLOR,KEYWORD";
   $DATA_VALUE="'".$_SESSION["LOGIN_DEPT_ID"]."','".$_SESSION["LOGIN_USER_ID"]."','$TO_ID','$SUBJECT','$SUMMARY','$CONTENT','$SEND_TIME','$BEGIN_DATE','$END_DATE','$ATTACHMENT_ID','$ATTACHMENT_NAME','$PRINT','$FORMAT','$TOP','$TOP_DAYS','$PRIV_ID','$COPY_TO_ID','$TYPE_ID','$PUBLISH','$AUDITER',0x$COMPRESS_CONTENT,'$DOWNLOAD','$SUBJECT_COLOR','$KEYWORD'";
   $NOTIFY_ID=insert_notify($DATA,$DATA_VALUE);
}
else {
	
	$UPDATE_DATA.="SEND_TIME='$SEND_TIME',TO_ID='$TO_ID',SUBJECT='$SUBJECT',SUMMARY='$SUMMARY',CONTENT='$CONTENT',BEGIN_DATE='$BEGIN_DATE',END_DATE='$END_DATE',ATTACHMENT_ID=CONCAT(ATTACHMENT_ID, '$ATTACHMENT_ID'),ATTACHMENT_NAME=CONCAT(ATTACHMENT_NAME, '$ATTACHMENT_NAME'),PRINT='$PRINT',DOWNLOAD='$DOWNLOAD',FORMAT='$FORMAT',TOP='$TOP',TOP_DAYS='$TOP_DAYS',PRIV_ID='$PRIV_ID',USER_ID='$COPY_TO_ID',TYPE_ID='$TYPE_ID',PUBLISH='$PUBLISH',AUDITER='$AUDITER',READERS='',SUBJECT_COLOR='$SUBJECT_COLOR',KEYWORD='$KEYWORD'";
$WHERE="where NOTIFY_ID='$NOTIFY_ID'";
if($_SESSION["LOGIN_USER_PRIV"]!="1"&&$POST_PRIV!="1"&&$IS_AUDITING_EDIT!="1")
   $WHERE.=" and FROM_ID='".$_SESSION["LOGIN_USER_ID"]."'";
update_notify($UPDATE_DATA,$WHERE);

}
   
   $returnStr="NOTIFY_ID:".$NOTIFY_ID;
   echo $returnStr;


?>