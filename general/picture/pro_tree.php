<?
include_once("inc/auth.inc.php");
include_once("inc/utility.php");
include_once("inc/utility_all.php");
ob_end_clean();
header("Content-type: text/xml");
echo "<?xml version=\"1.0\" encoding=\"".MYOA_CHARSET."\"?>\n";
echo "<TreeNode>\n";
$PRODUCT_IMG=MYOA_STATIC_SERVER."/static/images/endnode.gif";

//��ȡ�ļ���·��
if($CUR_DIR=="")
{
   $query = "SELECT PIC_PATH from PICTURE where PIC_ID='$PIC_ID'";
   $cursor= exequery(TD::conn(),$query);
   if($ROW=mysql_fetch_array($cursor))
   {
      $CUR_DIR=$ROW["PIC_PATH"];
   }
   else
      exit;
}

if(stristr($CUR_DIR,".."))
{
   Message(_("����"),_("�������зǷ��ַ���"));
   exit;
}

//��ǰĿ¼����
$SORT_COUNT=0;  //��ǰĿ¼�ļ�������
$dh = @opendir(iconv2os($CUR_DIR));
while(false !== ($FILE_NAME = @readdir($dh)))
{
	 $FILE_NAME = iconv2oa($FILE_NAME);
   if($FILE_NAME=='.' || $FILE_NAME=='..')
	   continue;
	 $TEMP_FILE_URL = iconv2os($CUR_DIR."/".$FILE_NAME);
   if(!is_file($TEMP_FILE_URL))
   {
     if($FILE_NAME=='tdoa_cache')  //��������ͼĿ¼
		     continue;
     
		  //����Ŀ¼
	   $SORT_ATTR_ARRAY[$SORT_COUNT]["NAME"]=$FILE_NAME;
	   $SORT_ATTR_ARRAY[$SORT_COUNT]["TIME"]=date("Y-m-d H:i:s", filemtime($TEMP_FILE_URL));
     //Ŀ¼����
     $SORT_COUNT++;
   }
}

for($J=0;$J < $SORT_COUNT; $J++)
{
   $SORT_FILE_ARRAY_NAME = $SORT_ATTR_ARRAY[$J]["NAME"];
   $SORT_FILE_ARRAY_TIME = $SORT_ATTR_ARRAY[$J]["TIME"];
   $SORT_FILE_DIR = $CUR_DIR."/".$SORT_ATTR_ARRAY[$J]["NAME"];
   echo "<TreeNode id=\"$SORT_FILE_DIR$J\" text=\"$SORT_FILE_ARRAY_NAME\" img_src=\"$PRODUCT_IMG\" href=\"picture.php?CUR_DIR=$SORT_FILE_DIR\" title=\"$SORT_FILE_ARRAY_NAME\" Xml=\"pro_tree.php?CUR_DIR=$SORT_FILE_DIR\" target=\"list\" />\n";
}
echo "</TreeNode>\n";
?>