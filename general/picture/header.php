<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");
ob_end_clean();
if($SUB_DIR!="")
{
    $SUB_DIR=urldecode($SUB_DIR);
}
if($FILE_NAME!="")
{
    $FILE_NAME=urldecode($FILE_NAME);
}
if(strstr($SUB_DIR,".") && strlen($SUB_DIR)<=3)
{
    exit;
}
$FB_STR1=$FILE_NAME;
if(strstr($FB_STR1,"/")||strstr($FB_STR1,"\\"))
{
    exit;
}
ob_end_clean();
$PIC_ID =intval($PIC_ID);
$query  = "SELECT * from PICTURE where PIC_ID='$PIC_ID'";
$cursor = exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
    $PIC_PATH=$ROW["PIC_PATH"];
}
else
{
    exit;
}

if($FILE_NAME!="")
{
   $FILE_NAME=substr($FILE_NAME,strrpos($FILE_NAME,"/"));
   
   if(isset($open_type) && $open_type==1)
   {
	   $FILE_NAME = iconv('utf-8', MYOA_CHARSET, $FILE_NAME);
   }

   if($SUB_DIR!=""&&substr($PIC_PATH,strlen($PIC_PATH)-1,1)=="/")
   {
       $FILE_PATH = $PIC_PATH.$SUB_DIR;
   }
   else
   {
       $FILE_PATH = $PIC_PATH."/".$SUB_DIR;
   }
   if ($Is_Thumb==1)
   {
       $FILE_PATH .="/tdoa_cache/".$FILE_NAME;
   }
   else
   {
       $FILE_PATH .="/".$FILE_NAME;
   }

   if(!file_exists(iconv2os($FILE_PATH)))
   {
       $FILE_PATH = $PIC_PATH."/".$SUB_DIR."/tdoa_cache/medium_".$FILE_NAME;
       if(!file_exists(iconv2os($FILE_PATH)))
       {
           echo _("找不到文件：").$FILE_PATH;
           exit;
       }

   }
   clearstatcache();
   $fp = td_fopen(iconv2os($FILE_PATH),"rb");

   $FILE_TYPE=substr(strrchr($FILE_NAME, "."), 1);
   $FILE_TYPE=strtolower($FILE_TYPE);

   if($FILE_TYPE=="swf" || $FILE_TYPE=="swc")
   {
       Header("Content-type: application/x-shockwave-flash");
   }
   else if($FILE_TYPE=="jpc" || $FILE_TYPE=="jpx" || $FILE_TYPE=="jb2")
   {
       Header("Content-type: application/octet-stream");
   }
   else if($FILE_TYPE=="wbmp")
   {
       Header("Content-type: image/vnd.wap.wbmp");
   }
   else
   {
       Header("Content-type: image/".$FILE_TYPE);
   }

   Header("Accept-Ranges: bytes");
   Header("Accept-Length: ".sprintf("%u", filesize(iconv2os($FILE_PATH))));
   Header("Content-Disposition: attachment; ".get_attachment_filename($FILE_NAME));

   while (!feof ($fp))
   {
      echo fread($fp,50000);
   }

   fclose($fp);
}
?>