<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");

if(substr($PIC_PATH,strlen($PIC_PATH)-1,1)=="/")
   $CUR_DIR = $PIC_PATH.$SUB_DIR;
else
   $CUR_DIR = $PIC_PATH."/".$SUB_DIR;

if(substr($CUR_DIR,strlen($CUR_DIR)-1,1)!="/")
	 $true_file = $CUR_DIR."/".$NEW_NAME;
else
	 $true_file = $CUR_DIR.$NEW_NAME;

ob_end_clean();
if(file_exists($true_file))
   echo "+OK";
?>