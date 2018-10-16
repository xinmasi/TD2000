<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");
include_once("inc/check_type.php");
include_once("inc/utility_org.php");
include_once("inc/utility_cache.php");
ob_end_clean();

$CUR_TIME=$SEND_TIME?$SEND_TIME:date("Y-m-d H:i:s",time());
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
if($TOP=="on")
{
   $TOP='1';
}
else
{
   $TOP='0';
}

$ATTACHMENT_ID=$ATTACHMENTS["ID"];   
$ATTACHMENT_NAME=$ATTACHMENTS["NAME"];

   $CONTENT=strip_unsafe_tags($CONTENT);
   $CONTENT = stripslashes($CONTENT);
   $COMPRESS_CONTENT = bin2hex(gzcompress($CONTENT));
   $CONTENT = addslashes(strip_tags($CONTENT));
   
if ($NEWS_ID=="")
{   
    $query="insert into NEWS(SUBJECT,SUMMARY,CONTENT,PROVIDER,NEWS_TIME,ATTACHMENT_ID,ATTACHMENT_NAME,ANONYMITY_YN,FORMAT,TYPE_ID,PUBLISH,TO_ID,PRIV_ID,USER_ID,COMPRESS_CONTENT,TOP,SUBJECT_COLOR,KEYWORD) values ('$SUBJECT','$SUMMARY','$CONTENT','".$_SESSION["LOGIN_USER_ID"]."','$CUR_TIME','$ATTACHMENT_ID','$ATTACHMENT_NAME','$ANONYMITY_YN','$FORMAT','$TYPE_ID','$PUBLISH','$TO_ID','$PRIV_ID','$COPY_TO_ID',0x$COMPRESS_CONTENT,'$TOP','$SUBJECT_COLOR','$KEYWORD')";
    exequery(TD::conn(), $query);
    $NEWS_ID=mysql_insert_id();
}
else {
	$query="update NEWS set SUBJECT='$SUBJECT',SUMMARY='$SUMMARY',KEYWORD='$KEYWORD',CONTENT='$CONTENT',TOP='$TOP',SUBJECT_COLOR='$SUBJECT_COLOR',COMPRESS_CONTENT=0x$COMPRESS_CONTENT,TO_ID='$TO_ID',PRIV_ID='$PRIV_ID',USER_ID='$COPY_TO_ID',
	ATTACHMENT_ID=CONCAT(ATTACHMENT_ID, '$ATTACHMENT_ID'),NEWS_TIME='$CUR_TIME',ATTACHMENT_NAME=CONCAT(ATTACHMENT_NAME, '$ATTACHMENT_NAME'),ANONYMITY_YN='$ANONYMITY_YN',FORMAT='$FORMAT',TYPE_ID='$TYPE_ID' where NEWS_ID='$NEWS_ID'"; 
	exequery(TD::conn(),$query);
}
   
   $returnStr="NEWS_ID:".$NEWS_ID;
   echo $returnStr;


?>