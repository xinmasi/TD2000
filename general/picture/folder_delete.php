<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
include_once("inc/utility_file.php");

//��ȡ�½�ͼƬĿ¼·��������
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
{
    Message(_("����"),_("�������зǷ��ַ���"));
    Button_Back();
    exit;
}

//��ǰĿ¼·��
if(substr($PIC_PATH,strlen($PIC_PATH)-1,1)=="/")
   $CUR_DIR = $PIC_PATH.$CUR_DIR_RELAT;
else
   $CUR_DIR = $PIC_PATH."/".$CUR_DIR_RELAT;

if(stristr($CUR_DIR,".."))
{
    Message(_("����"),_("�������зǷ��ַ���"));
    Button_Back();
    exit;
}
$REALOAD_ID=dechex(crc32($PIC_ID.realpath($CUR_DIR)));
delete_dir(iconv2os($CUR_DIR), false);
?>
<script>
var xtree=window.parent.PRO_LIST.xtree;
if(xtree)
{
   xtree.redrawNode("<?=$REALOAD_ID?>", "delete","");
   alert("<?=_("ɾ���ɹ�")?>");
   window.location="picture.php?PIC_ID=<?=$PIC_ID?>";

}
</script>
