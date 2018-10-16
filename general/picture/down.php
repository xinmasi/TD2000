<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
ob_end_clean();
if($SUB_DIR!="")
{
    $SUB_DIR = urldecode($SUB_DIR);
}
if($FILE_NAME!=""){
   $FILE_NAME   = urldecode($FILE_NAME);
}
$FB_STR1=$FILE_NAME;
if(strstr($FB_STR1,"/")||strstr($FB_STR1,"\\"))
{
    exit;
}

if(strstr($SUB_DIR,"."))
{
    exit;
}

$query  = "SELECT * from PICTURE where PIC_ID='$PIC_ID'";
$cursor = exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
    $PIC_PATH   = $ROW["PIC_PATH"];
}
else
{
    exit;
}

if($FILE_NAME!="")
{
   $FILE_NAME=substr($FILE_NAME,strrpos($FILE_NAME,"/"));
   if($SUB_DIR!=""&&substr($PIC_PATH,strlen($PIC_PATH)-1,1)=="/")
   {
       $FILE_PATH = $PIC_PATH.$SUB_DIR;
   }
   else
   {
       $FILE_PATH = $PIC_PATH."/".$SUB_DIR;
   }
   if(isset($open_type) && $open_type==1)
   {
	   $FILE_NAME = iconv('utf-8', MYOA_CHARSET, $FILE_NAME);
   }
   $FILE_PATH .="/".$FILE_NAME;
   $FILE_PATH = iconv2os($FILE_PATH);
   if(!file_exists($FILE_PATH))
   {
      echo _("找不到指定文件");
      exit;
   }
   ob_end_clean();
   Header("Cache-control: private");
   Header("Content-type: application/octet-stream");
   Header("Accept-Ranges: bytes");
   Header("Accept-Length: ".sprintf("%u", filesize(iconv2os($FILE_PATH))));
   Header("Content-Disposition: attachment; ".get_attachment_filename(iconv2os($FILE_NAME)));
   readfile($FILE_PATH);
}
?>