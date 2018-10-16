<?
include_once("inc/auth.inc.php");
include_once("inc/utility_file.php");

$VIDEO_FILE = '';
$INI_FILE = MYOA_ATTACH_PATH."mytable/video.ini";
if(file_exists($INI_FILE))
{
    $INI_ARRAY = parse_ini_file($INI_FILE);
    if(is_array($INI_ARRAY) && isset($INI_ARRAY['video_file']))
       $VIDEO_FILE = $INI_ARRAY['video_file'];
}

if($VIDEO_FILE == '' || !file_exists($VIDEO_FILE))
{
    echo _("视频文件未设置或路径不正确");
    exit;
}

if(!is_media($VIDEO_FILE))
{
    echo _("禁止访问");
    exit;
}

$file_ext=strtolower(substr($VIDEO_FILE,strpos($VIDEO_FILE,".")));
switch($file_ext)
{
  case ".rm":
  case ".rmvb":
               $COTENT_TYPE_DESC="audio/x-pn-realaudio";
               break;
  default:
               $COTENT_TYPE_DESC="application/octet-stream";
               break;
}

ob_end_clean();
Header("Cache-control: private");
Header("Content-type: $COTENT_TYPE_DESC");
Header("Accept-Ranges: bytes");
Header("Content-Length: ".sprintf("%u", filesize($VIDEO_FILE)));
Header("Content-Disposition: attachment; ".get_attachment_filename(basename($VIDEO_FILE)));
$handle = td_fopen ($VIDEO_FILE,"rb");
$contents = "";
while ($handle && !feof($handle))
  echo $contents = fread($handle, 8192);
fclose($handle);
?>