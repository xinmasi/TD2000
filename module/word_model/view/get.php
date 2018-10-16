<?
include_once("inc/auth.inc.php");
include_once("inc/utility_file.php");
ob_end_clean();

$query="select ATTACHMENT from WORD_MODEL where DOC_ID='".intval($DOC_ID)."'";
$cursor=exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
	$ATTACHMENT=$ROW["ATTACHMENT"];
	
   $ATTACHMENT_ARRAY = explode(",", $ATTACHMENT);
   $MODEL_FILE = attach_real_path($ATTACHMENT_ARRAY[0], $ATTACHMENT_ARRAY[1], "model");
   
   if(!file_exists($MODEL_FILE))
   {
      echo _("文件不存在");
      exit;
   }
   
   td_download($MODEL_FILE, '', -1);
}
else
{
   echo _("文件不存在");
}
?>