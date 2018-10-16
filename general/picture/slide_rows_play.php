<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

$CUR_DIR=urldecode($CUR_DIR);
$PIC_FILENAME=urldecode($PIC_FILENAME);
$PIC_FILENAME=urldecode($PIC_FILENAME);
$PIC_FILENAME=td_iconv($PIC_FILENAME, 'utf-8', MYOA_CHARSET);

if(substr($CUR_DIR,-1)=="/")
  $FILE_PATH=$CUR_DIR."tdoa_cache/".$PIC_FILENAME;
else
  $FILE_PATH=$CUR_DIR."/tdoa_cache/".$PIC_FILENAME;
  
ob_end_clean;
$FILE_PATH=str_replace("//","/",$FILE_PATH);
if(file_exists(iconv2os($FILE_PATH)))
{
	echo "true;";
  $IMG_ATTR=@getimagesize(iconv2os($FILE_PATH));
  if($IMG_ATTR[0]>38)
  {
    $IMG_ATTR[0]=38;
    $IMG_ATTR[1]=$IMG_ATTR[1]*38/$IMG_ATTR[0];
  }
  if($IMG_ATTR[1]>40)
  {
    $IMG_ATTR[1]=40;
    $IMG_ATTR[0]=$IMG_ATTR[0]*40/$IMG_ATTR[1];
  }
  if($IMG_ATTR[0]<38 && $IMG_ATTR[1]<40)
  {
  	$IMG_ATTR[0]=38;
  	$IMG_ATTR[1]=40;
  }
  echo $IMG_ATTR[0]."px;";
  echo $IMG_ATTR[1]."px";
}
?>