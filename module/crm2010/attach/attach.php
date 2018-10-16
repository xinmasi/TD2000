<?
include_once("inc/auth.inc.php");
include_once("inc/utility_file.php");

if(stristr($MODULE,"/") || stristr($MODULE,"\\") || stristr($YM,"/") || stristr($YM,"\\")
   || stristr($ATTACHMENT_ID,"/") || stristr($ATTACHMENT_ID,"\\") || stristr($ATTACHMENT_NAME,"/") || stristr($ATTACHMENT_NAME,"\\"))
{
    Message(_("����"),_("�������зǷ��ַ���"));
    exit;
}

$ATTACHMENT_ID_OLD=$ATTACHMENT_ID;
$ATTACHMENT_ID=attach_id_decode($ATTACHMENT_ID,$ATTACHMENT_NAME);
$MYOA_ATTACHMENT_NAME=$ATTACHMENT_NAME;

if($MODULE!="" && $YM!="")
   $URL=MYOA_ATTACH_PATH."crm/".$MODULE."/".$YM."/".$ATTACHMENT_ID.".".$ATTACHMENT_NAME;
else
   $URL=MYOA_ATTACH_PATH."crm/".$ATTACHMENT_ID."/".$ATTACHMENT_NAME;

if(!file_exists($URL))
{
   if($MODULE=="" && $YM=="")//����3.1��֮ǰ�汾���桢�ļ����������Ƕ���ͼƬ
   {
      $ATTACHMENT_ID=($ATTACHMENT_ID_OLD-2)/3;
      $URL=MYOA_ATTACH_PATH."crm/".$ATTACHMENT_ID."/".$ATTACHMENT_NAME;
      if(!file_exists($URL))
      {
         echo sprintf(_("�ļ�����%s%s��Ǹ���������ʵ��ļ������ڣ������Ѿ���ɾ����ת�ƣ�����ϵOA����Ա��%s"), $MYOA_ATTACHMENT_NAME, "<br>", "<br>");
         Button_Back();
         exit;
      }
   }
   else
   {
         echo sprintf(_("�ļ�����%s%s��Ǹ���������ʵ��ļ������ڣ������Ѿ���ɾ����ת�ƣ�����ϵOA����Ա��%s"), $MYOA_ATTACHMENT_NAME, "<br>", "<br>");
      Button_Back();
      exit;
   }
}

$file_ext=strtolower(substr($MYOA_ATTACHMENT_NAME,strpos($MYOA_ATTACHMENT_NAME,".")));

if(is_ntko_office($ATTACHMENT_NAME))
	oc_log(trim($YM."_".$ATTACHMENT_ID, "_"), $ATTACHMENT_NAME, 3);

if($DIRECT_VIEW)
{
   switch($file_ext)
   {
     case ".jpg":
     case ".jpeg":
                  $COTENT_TYPE=0;
                  $COTENT_TYPE_DESC="image/jpeg";
                  break;
     case ".bmp":
                  $COTENT_TYPE=0;
                  $COTENT_TYPE_DESC="image/bmp";
                  break;
     case ".gif":
                  $COTENT_TYPE=0;
                  $COTENT_TYPE_DESC="image/gif";
                  break;
     case ".png":
                  $COTENT_TYPE=0;
                  $COTENT_TYPE_DESC="image/png";
                  break;
     case ".html":
     case ".htm":
                  $COTENT_TYPE=0;
                  $COTENT_TYPE_DESC="text/html";
                  break;
     case ".wmv":
     case ".wav":
     case ".mid":
     case ".mht":
                  $COTENT_TYPE=0;
                  $COTENT_TYPE_DESC="application/octet-stream";
                  break;
     case ".pdf":
                  $COTENT_TYPE=0;
                  $COTENT_TYPE_DESC="application/pdf";
                  break;
     case ".swf":
                  $COTENT_TYPE=0;
                  $COTENT_TYPE_DESC="application/x-shockwave-flash";
                  break;
     default:
                  $COTENT_TYPE=1;
                  $COTENT_TYPE_DESC="application/octet-stream";
                  break;
   }
}
else
{
   switch($file_ext)
   {
     case ".doc":
     case ".dot":
                  $COTENT_TYPE=MYOA_ATTACH_OFFICE_OPEN_IN_IE ? 0 : 1;
                  $COTENT_TYPE_DESC=MYOA_ATTACH_OFFICE_OPEN_IN_IE ? "application/msword" : "application/octet-stream";
                  break;
     case ".xls":
     case ".xlc":
     case ".xll":
     case ".xlm":
     case ".xlw":
     case ".csv":
                  $COTENT_TYPE=MYOA_ATTACH_OFFICE_OPEN_IN_IE ? 0 : 1;
                  $COTENT_TYPE_DESC=MYOA_ATTACH_OFFICE_OPEN_IN_IE ? "application/msexcel" : "application/octet-stream";
                  break;
     case ".ppt":
     case ".pot":
     case ".pps":
     case ".ppz":
                  $COTENT_TYPE=MYOA_ATTACH_OFFICE_OPEN_IN_IE ? 0 : 1;
                  $COTENT_TYPE_DESC=MYOA_ATTACH_OFFICE_OPEN_IN_IE ? "application/mspowerpoint" : "application/octet-stream";
                  break;
     case ".docx":
     case ".dotx":
     case ".xlsx":
     case ".xltx":
     case ".pptx":
     case ".potx":
     case ".ppsx":
                  $COTENT_TYPE=MYOA_ATTACH_OFFICE_OPEN_IN_IE ? 0 : 1;
                  $COTENT_TYPE_DESC=MYOA_ATTACH_OFFICE_OPEN_IN_IE ? "application/vnd.openxmlformats" : "application/octet-stream";
                  break;
     case ".rm":
     case ".rmvb":
                  $COTENT_TYPE=1;
                  $COTENT_TYPE_DESC="audio/x-pn-realaudio";
                  break;
     default:
                  $COTENT_TYPE=1;
                  $COTENT_TYPE_DESC="application/octet-stream";
                  break;
   }
}
ob_end_clean();

Header("Cache-control: private");
Header("Content-type: $COTENT_TYPE_DESC");
Header("Accept-Ranges: bytes");
Header("Content-Length: ".sprintf("%u", filesize($URL)));

//if($HTTP_ACCEPT_LANGUAGE!="" && stristr($HTTP_ACCEPT_LANGUAGE,"zh-cn") === FALSE)// && stristr($HTTP_USER_AGENT, "MSIE")

if(preg_match("/msie|trident/i", $_SERVER['HTTP_USER_AGENT']) && $ATTACH_UTF8)
   $MYOA_ATTACHMENT_NAME = urlencode(iconv(MYOA_CHARSET,"utf-8",$MYOA_ATTACHMENT_NAME));

if($COTENT_TYPE==1)
   Header("Content-Disposition: attachment; ".get_attachment_filename($MYOA_ATTACHMENT_NAME));
else
   Header("Content-Disposition: ".get_attachment_filename($MYOA_ATTACHMENT_NAME));
//readfile($URL);exit;
$handle = td_fopen ($URL,"rb");
$contents = "";
while (!feof($handle))
   echo $contents = fread($handle, 8192);
fclose($handle);
?>