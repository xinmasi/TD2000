<?
include_once("inc/auth.inc.php");

if(strstr($PHOTO_NAME,"/")||strstr($PHOTO_NAME,"\\")||strstr($PHOTO_NAME,".."))
   exit;

$URL=MYOA_ATTACH_PATH."recruit_pic/$PHOTO_NAME";
if (!file_exists($URL))
{
 echo _("找不到文件，位于服务器：").$URL;
 exit;
}

$file_ext=strtolower(substr($PHOTO_NAME,strrpos($PHOTO_NAME,".")));
switch($file_ext)
{
  case ".jpg":
  case ".bmp":
  case ".gif":
  case ".png":
  case ".wmv":
  case ".html":
  case ".htm":
  case ".wav":
  case ".mid":
               $COTENT_TYPE=0;
               $COTENT_TYPE_DESC="application/octet-stream";
               break;
  default:
               $COTENT_TYPE=1;
               $COTENT_TYPE_DESC="application/octet-stream";
}

ob_end_clean();
Header("Cache-control: private");
Header("Content-type: $COTENT_TYPE_DESC");
Header("Accept-Ranges: bytes");
Header("Accept-Length: ".filesize($URL));

if($COTENT_TYPE==1)
   Header("Content-Disposition: attachment; ".get_attachment_filename($PHOTO_NAME));
else
   Header("Content-Disposition: ".get_attachment_filename($PHOTO_NAME));

readfile($URL);
?>