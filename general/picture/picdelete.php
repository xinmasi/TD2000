<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
include_once("inc/utility_all.php");

//$DelFileNameStr = unescape($DelFileNameStr);

if(stristr($DelFileNameStr,"|")!="")
{
  $loc_and_filename=explode("@~@",$DelFileNameStr);
  for($i=0;$i< count($loc_and_filename);$i++)
  {
  	$temp_loc_and_filename=explode("|",$loc_and_filename[$i]);
  	$SUB_DIR = $temp_loc_and_filename[1];
  	$FILE_NAME = $temp_loc_and_filename[0];
    if(strstr($FILE_NAME,"./"))
       exit;
    if(substr($PIC_PATH,strlen($PIC_PATH)-1,1)=="/")
       $CUR_DIR = $PIC_PATH.$SUB_DIR;
    else
    {
       if($SUB_DIR=="")
         $CUR_DIR = $PIC_PATH."/";
       else
         $CUR_DIR = $PIC_PATH."/".$SUB_DIR."/";
    }

    if($FILE_NAME)
    {
		  $FILE_NAME = iconv('utf-8', MYOA_CHARSET, $FILE_NAME);
       	  $true_file = $CUR_DIR.$FILE_NAME;
       	  @unlink(iconv2os($true_file));
       	  $cache_file = $CUR_DIR."tdoa_cache/".$FILE_NAME;
       	  @unlink(iconv2os($cache_file));
    }
    unset($SUB_DIR,$FILE_NAME);
  }
}
else
{
	if(substr($PIC_PATH,strlen($PIC_PATH)-1,1)=="/")
   $CUR_DIR = $PIC_PATH.$SUB_DIR;
  else
     $CUR_DIR = $PIC_PATH."/".$SUB_DIR;

  $dfile=explode("@~@",$DelFileNameStr);
  for(@reset($dfile);@list($key,$FILE_NAME)=@each($dfile);)
  {
     if($FILE_NAME)
     {
		  $FILE_NAME = iconv('utf-8', MYOA_CHARSET, $FILE_NAME);
     	  $true_file = $CUR_DIR."/".$FILE_NAME;
     	  @unlink(iconv2os($true_file));
     	  $cache_file = $CUR_DIR."/tdoa_cache/".$FILE_NAME;
     	  @unlink(iconv2os($cache_file));
     }
  }
  
  
  unset($dfile);
}

if(stristr($_SERVER['HTTP_REFERER'],"picture.php"))
  header("location: picture.php?PIC_ID=$PIC_ID&SUB_DIR=$SUB_DIR");
else
{
?>
<script>
	alert("<?=_("É¾³ý³É¹¦")?>");
history.back();
window.close();
</script>
<?
}
?>