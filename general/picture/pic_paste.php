<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");

$COOK_PIC_ID=iconv2oa($_COOKIE["pic_id"]);
$COOK_PIC_DIR=unescape($_COOKIE["pic_dir"]);
$COOK_SUB_DIR=unescape($_COOKIE["pic_sub_dir"]);
$COOK_PIC_PATH=unescape($_COOKIE["pic_path"]);
$COOK_FILE_NAMES=unescape($_COOKIE["pic_filename"]);
$ACTION=iconv2oa($_COOKIE["pic_action"]);

$TEMP_FILE_NAME=explode("@~@",urldecode($COOK_FILE_NAMES));

$FILE_NAME=array();
$FILE_NAME_DIR=array();
for($i=0;$i< count($TEMP_FILE_NAME)-1;$i++)
{
   $TEMP=explode("|",$TEMP_FILE_NAME[$i]);
   array_push($FILE_NAME,$TEMP[0]);
   array_push($FILE_NAME_DIR,$TEMP[1]);
}

for($I=0;$I<count($FILE_NAME);$I++)
{
   if($FILE_NAME[$I]=="")
      continue;
   if($FILE_NAME_DIR[$I]=="")
   {
      $FILE_PATH = $COOK_PIC_DIR."/".$FILE_NAME[$I];
      $FILE_CACHE_PATH = $COOK_PIC_DIR."/tdoa_cache/".$FILE_NAME[$I];
      $FILE_CACHE_PATH_MEDIUM = $COOK_PIC_DIR."/tdoa_cache/medium_".$FILE_NAME[$I];
   }
   else
   {
      $FILE_PATH = $COOK_PIC_PATH."/".$FILE_NAME_DIR[$I]."/".$FILE_NAME[$I];
      $FILE_CACHE_PATH = $COOK_PIC_PATH."/".$FILE_NAME_DIR[$I]."/tdoa_cache/".$FILE_NAME[$I];
      $FILE_CACHE_PATH_MEDIUM = $COOK_PIC_PATH."/".$FILE_NAME_DIR[$I]."/tdoa_cache/medium_".$FILE_NAME[$I];
   }

   $FILE_PATH = str_replace("\\","/",$FILE_PATH);
   $FILE_PATH = str_replace("//","/",$FILE_PATH);

   if(!file_exists(iconv2os($FILE_PATH)))
      continue;
   if(substr($PIC_DIR,-1)!="/")
      $PIC_DIR=$PIC_DIR."/";
   $FILE_NEW = $PIC_DIR.$FILE_NAME[$I];
   if(!file_exists(iconv2os($FILE_NEW)))
   {
      if($ACTION=="cut")
      {
         td_copy(iconv2os($FILE_PATH),iconv2os($FILE_NEW));
         unlink(iconv2os($FILE_PATH));
         unlink(iconv2os($FILE_CACHE_PATH));
         unlink(iconv2os($FILE_CACHE_PATH_MEDIUM));
      }
      else
      {  
         td_copy(iconv2os($FILE_PATH),iconv2os($FILE_NEW));
      }
   }
   else
   {
         make_copy($FILE_PATH,$FILE_CACHE_PATH,$FILE_CACHE_PATH_MEDIUM, $PIC_DIR,$FILE_NAME[$I],$ACTION);      
   }
}

if($ACTION=="cut")
{
   setcookie("pic_id","");
   setcookie("pic_dir","");
   setcookie("pic_sub_dir","");
   setcookie("pic_path","");
   setcookie("pic_filename","");
   setcookie("pic_action","");
}

echo "<script>history.go(-1)</script>";
function make_copy($FROM,$FROM_CACHE,$FROM_CACHE_MEDIUM,$TO,$FILE_NAME,$ACTION)
{

  if($FROM == $TO.$FILE_NAME && $ACTION == "cut")
	 	 echo "<script>history.go(-1)</script>";
	else
	{
      if(strstr($FILE_NAME,"."))
         $FILE_NAME_NEW=substr($FILE_NAME,0,strrpos($FILE_NAME,"."))._("-¸´¼þ").substr($FILE_NAME,strrpos($FILE_NAME,"."));
      else
         $FILE_NAME_NEW=$FILE_NAME._("-¸´¼þ");
      
      $FILE_NEW = $TO.$FILE_NAME_NEW;

      if(!file_exists(iconv2os($FILE_NEW)))
      {
         if($ACTION=="cut")
         {
            td_copy(iconv2os($FROM),iconv2os($FILE_NEW));
            unlink(iconv2os($FROM));
            unlink(iconv2os($FROM_CACHE));
            unlink(iconv2os($FROM_CACHE_MEDIUM));
         }
         else
         {
            td_copy(iconv2os($FROM),iconv2os($FILE_NEW));
         }
      }
      else
         make_copy($FROM,$FROM_CACHE,$FROM_CACHE_MEDIUM,$TO,$FILE_NAME_NEW,$ACTION);
  }
}
?>