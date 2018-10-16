<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
include_once("inc/utility_all.php");

if($SUB_DIR!="")
   $SUB_DIR=urldecode($SUB_DIR);

//读取新建图片目录路径及名称
$query = "SELECT PIC_NAME,PIC_PATH from PICTURE where PIC_ID='$PIC_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
   $PIC_NAME=$ROW["PIC_NAME"];
   $PIC_PATH=$ROW["PIC_PATH"];
}
else
   exit;

if(strstr($SUB_DIR,"..") || strstr($SUB_DIR,"./"))
   exit;

//当前目录路径
if(substr($PIC_PATH,strlen($PIC_PATH)-1,1)=="/")
   $CUR_DIR = $PIC_PATH.$SUB_DIR;
else
   $CUR_DIR = $PIC_PATH."/".$SUB_DIR;
   
$dh = opendir(iconv2os($CUR_DIR));
$FILE_COUNT=0;
while(false !== ($FILE_NAME = readdir($dh)))
{
	 $FILE_NAME = iconv2oa($FILE_NAME);
   if($FILE_NAME=='.' || $FILE_NAME=='..')
	    continue;

	 //遍历文件
	 $FILE_URL = iconv2os($CUR_DIR."/".$FILE_NAME);
   if(is_file($FILE_URL))
   {
     $TEP_TYPE = substr(strrchr($FILE_NAME,"."),1);
	   $IMG_TYPE_STR="gif,jpg,png,swf,swc,tiff,bmp,iff,jp2,jpx,jb2,jpc,xbm,wbmp";
	   if($TEP_TYPE=="db" || !find_id($IMG_TYPE_STR,strtolower($TEP_TYPE)))
	      continue;
	      
	   $FILE_IMG_ATTR=getimagesize(iconv2os($CUR_DIR."/".$FILE_NAME));  
	   $FILE_ATTR_ARRAY[$FILE_COUNT]["TYPE"]=substr(strrchr($FILE_NAME,"."),1);
	   $FILE_ATTR_ARRAY[$FILE_COUNT]["NAME"]=$FILE_NAME;
	   $FILE_ATTR_ARRAY[$FILE_COUNT]["TIME"]=date("Y-m-d H:i:s", filemtime(iconv2os($CUR_DIR."/".$FILE_NAME)));
     $FILE_ATTR_ARRAY[$FILE_COUNT]["SIZE"]=sprintf("%u", filesize(iconv2os($CUR_DIR."/".$FILE_NAME)));
     $FILE_ATTR_ARRAY[$FILE_COUNT]["WIDTH"]=$FILE_IMG_ATTR[0];  
     $FILE_ATTR_ARRAY[$FILE_COUNT]["HEIGHT"]=$FILE_IMG_ATTR[1];         
     $FILE_COUNT++;  //文件计数
   }
}

if($FILE_COUNT!=0)
{
   $SORT_ASC=4;
   $SORT_DESC=3;
   foreach($FILE_ATTR_ARRAY as $RES)
      $SORTAUX[]= strtolower($RES[$VIEW_TYPE])."<br>";
   if($ASC_DESC==4)
      array_multisort($SORTAUX,$SORT_ASC,$FILE_ATTR_ARRAY);
   else
      array_multisort($SORTAUX,$SORT_DESC,$FILE_ATTR_ARRAY);
}
?>
<script language=javascript>
	var FILE_ATTR_ARRAY=new Array(
<?   
for($I=0;$I < $FILE_COUNT;$I++)
{      
	   $FILE_NAME=$FILE_ATTR_ARRAY[$I]["NAME"];
	   $FILE_TIME=$FILE_ATTR_ARRAY[$I]["TIME"];
     $FILE_SIZE=$FILE_ATTR_ARRAY[$I]["SIZE"];
	   $FILE_IMG_ATTR[0]=$FILE_ATTR_ARRAY[$I]["WIDTH"];
	   $FILE_IMG_ATTR[1]=$FILE_ATTR_ARRAY[$I]["HEIGHT"];
	   	   
	   if($I==0)
	      echo "new Array(\"$I\",\"$FILE_NAME\",\"$FILE_TIME\",\"$FILE_SIZE\",\"$FILE_IMG_ATTR[0]\",\"$FILE_IMG_ATTR[1]\")";
	   else
	      echo ",new Array(\"$I\",\"$FILE_NAME\",\"$FILE_TIME\",\"$FILE_SIZE\",\"$FILE_IMG_ATTR[0]\",\"$FILE_IMG_ATTR[1]\")";
} 
?>
);
//alert(FILE_ATTR_ARRAY);
</script>