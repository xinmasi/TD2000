<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");

$HTML_PAGE_TITLE = _("重命名文件夹");
include_once("inc/header.inc.php");
?>



<body class="bodycolor">
<?
//读取新建图片目录路径及名称
$SUB_DIR=urldecode($SUB_DIR);
$CUR_DIR_RELAT=urldecode($CUR_DIR_RELAT);
$query = "SELECT PIC_NAME,PIC_PATH from PICTURE where PIC_ID='$PIC_ID'";
$cursor= exequery(TD::conn(),$query);
$NEW_FOLDER_NAME1= $NEW_FOLDER_NAME;
if($ROW=mysql_fetch_array($cursor))
{
   $PIC_NAME=$ROW["PIC_NAME"];
   $PIC_PATH=$ROW["PIC_PATH"];
}
else
{
   Message(_("错误"),_("参数含有非法字符。"));
   Button_Back();
   exit;
}  
if(strstr($SUB_DIR,"..") || strstr($SUB_DIR,"./") || strstr($SUB_DIR,"+"))
{
	 Message(_("错误"),_("参数含有非法字符。"));
   Button_Back();
   exit;
}   
//当前目录路径
if(substr($PIC_PATH,strlen($PIC_PATH)-1,1)=="/")
   $CUR_DIR = $PIC_PATH.$CUR_DIR_RELAT;
else
   $CUR_DIR = $PIC_PATH."/".$CUR_DIR_RELAT;

if(stristr($CUR_DIR,".."))
{
   Message(_("错误"),_("参数含有非法字符。"));
   Button_Back();
   exit;
}
$OLD_FOLDER_NAME = $CUR_DIR;
$REALOAD_ID=dechex(crc32($PIC_ID.realpath($OLD_FOLDER_NAME)));
if(!file_exists(iconv2os($OLD_FOLDER_NAME)))
{
   Message(_("错误"),_("参数含有非法字符。"));
   Button_Back();
   exit;
}
if(substr($OLD_FOLDER_NAME,-1,1)=="/")
   $OLD_FOLDER_NAME = substr($OLD_FOLDER_NAME,0,-1);
if($NEW_FOLDER_NAME=="" || strstr($NEW_FOLDER_NAME,"*") || strstr($NEW_FOLDER_NAME,"+") || strstr($NEW_FOLDER_NAME,"&"))
{
   message(_("提示"),_("新文件夹名不能为空或特殊字符"));
   Button_Back();
   exit;
}
else
{
	 $mat=explode(" ",$NEW_FOLDER_NAME);   
   if(count($mat)!=1)
   {
      message(_("提示"),_("名称中不能有空格"));
      Button_Back();
      exit;
   }
   $NEW_FOLDER_NAME = substr($OLD_FOLDER_NAME,0,strrpos($OLD_FOLDER_NAME, "/"))."/".$NEW_FOLDER_NAME1;
   if(file_exists(iconv2os($NEW_FOLDER_NAME)))
   {
      message(_("提示"),_("文件名已存在"));
      Button_Back();
      exit;
   }
   else
   {
       td_rename(iconv2os($OLD_FOLDER_NAME."\\"),iconv2os($NEW_FOLDER_NAME."\\"));
       Message(_("提示"),_("操作成功"));
   }
}

$NEW_DIR=substr($SUB_DIR,0,strrpos($SUB_DIR,"/"));
if($NEW_DIR!="")
{
   $SUB_DIR_NEW=$NEW_DIR."/".$NEW_FOLDER_NAME1;
}
else
{
	 $SUB_DIR_NEW=$NEW_FOLDER_NAME1;
}
$REALOAD_ID_NEW=dechex(crc32($PIC_ID.realpath($NEW_FOLDER_NAME)));

if($SUB_DIR != "")   
   $NEW_SUB = $SUB_DIR."/".$NEW_FOLDER_NAME1;
else
   $NEW_SUB = $NEW_FOLDER_NAME1;

$URL="picture.php?PIC_ID=".$PIC_ID."&SUB_DIR=".urlencode($NEW_SUB);
?>

<div align="center"><input type="button" class="BigButton" value="<?=_("返回文件夹")?>" onClick="window.location='<?=$URL?>';" title="<?=_("返回文件夹")?>"></div>	

<script>
var xtree=window.parent.PRO_LIST.xtree;
if(xtree)
{
   xtree.redrawNode("<?=$REALOAD_ID?>", "rename","<?=td_htmlspecialchars($NEW_FOLDER_NAME1)?>", "<?=$REALOAD_ID_NEW?>");
}
</script>
