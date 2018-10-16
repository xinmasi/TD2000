<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");

if(substr($PIC_PATH,-1,1)=="/")
   $CUR_DIR = $PIC_PATH.$SUB_DIR;
else
   $CUR_DIR = $PIC_PATH."/".$SUB_DIR;
 
if(stristr($FILE_NAME,"/") || stristr($FILE_NAME,"\\")|| stristr($FILE_NAME,"?")|| stristr($FILE_NAME,"*")|| stristr($FILE_NAME,"\"")|| stristr($FILE_NAME,"<")|| stristr($FILE_NAME,":")|| stristr($FILE_NAME,">")|| stristr($FILE_NAME,"|"))
{
	 Message(_("错误"),_("参数含有非法字符。"));
   Button_Back();
   exit;
}

$CACHE_DIR_NAME_OLD=$CUR_DIR."/tdoa_cache/".$PIC_NAME;
$CACHE_DIR_NAME_MEDIUM_OLD=$CUR_DIR."/tdoa_cache/medium_".$PIC_NAME;
$PIC_PATH_OLD = $CUR_DIR."/".$PIC_NAME;
$FILE_TYPE = substr($PIC_NAME,strrpos($PIC_NAME,"."));

$PIC_PATH = $CUR_DIR."/".$NEW_NAME.$FILE_TYPE;
$CACHE_PIC_PATH = $CUR_DIR."/tdoa_cache/".$NEW_NAME.$FILE_TYPE;
$CACHE_PIC_PATH_MEDIUM = $CUR_DIR."/tdoa_cache/medium_".$NEW_NAME.$FILE_TYPE;

if(file_exists(iconv2os($PIC_PATH_OLD)))
{
    td_rename(iconv2os($PIC_PATH_OLD),iconv2os($PIC_PATH));
    td_rename(iconv2os($CACHE_DIR_NAME_OLD),iconv2os($CACHE_PIC_PATH));
    td_rename(iconv2os($CACHE_DIR_NAME_MEDIUM_OLD),iconv2os($CACHE_PIC_PATH_MEDIUM));
   // if(file_exists(iconv2os($CACHE_DIR_NAME)))
   // 	  unlink(iconv2os($CACHE_DIR_NAME));
    
   //  if(file_exists(iconv2os($CACHE_DIR_NAME_MEDIUM)))
   //    unlink(iconv2os($CACHE_DIR_NAME_MEDIUM));
}
?>
<script>
opener.location.reload();
window.close();
</script>