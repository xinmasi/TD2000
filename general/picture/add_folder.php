<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("�½��ļ���");
include_once("inc/header.inc.php");
?>



<body class="bodycolor">
<?

$CUR_DIR_RELAT=urldecode($CUR_DIR_RELAT);
$SUB_DIR=urldecode($SUB_DIR);

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
   
//��ǰĿ¼·��
if(substr($PIC_PATH,strlen($PIC_PATH)-1,1)=="/")
   $CUR_DIR = $PIC_PATH.$CUR_DIR_RELAT;
else
   $CUR_DIR = $PIC_PATH."/".$CUR_DIR_RELAT;

if(stristr($CUR_DIR,"..")||stristr($FILE_NAME," ")||strstr($FILE_NAME,"/") || strstr($FILE_NAME,"\\") || strstr($FILE_NAME,"&")|| strstr($FILE_NAME,"*") || strstr($FILE_NAME,"+") || strstr($FILE_NAME,"="))
{
    Message(_("����"),_("�������зǷ��ַ�"));
    Button_Back();
    exit;
}   
if($FILE_NAME==null)
{
    Message(_("����"),_("����Ϊ��"));
    Button_Back();
    exit;
}  


if(strstr($FILE_NAME,"/") || strstr($FILE_NAME,"\\") || strstr($URL,".."))
{
    Message(_("����"),_("�������зǷ��ַ���"));
    Button_Back();
    exit;
}

$NEW_DIR = $CUR_DIR."/".$FILE_NAME;
if(!file_exists(iconv2os($NEW_DIR)))
{
   mkdir(iconv2os($NEW_DIR));
   Message("",sprintf(_("�½��ļ��� %s �ɹ�"),$FILE_NAME));  
}
else
{
   Message(_("����"),sprintf(_("�ļ��� %s �Ѵ���"),$FILE_NAME));
   Button_Back();
   exit;
}
$PIC_PATH=$PIC_PATH."/";
$RELOAD_PATH=$CUR_DIR."/";
$RELOAD_PATH=substr($RELOAD_PATH, 0, strrpos($RELOAD_PATH,"/"));
$RELOAD_PATH=realpath($RELOAD_PATH);
if(strlen($RELOAD_PATH)<strlen($PIC_PATH))
   $REALOAD_ID=dechex(crc32($PIC_ID.realpath($PIC_PATH)));
else
   $REALOAD_ID=dechex(crc32($PIC_ID.$RELOAD_PATH));
if($CUR_DIR_RELAT != "")   
   $NEW_SUB = $CUR_DIR_RELAT."/".$FILE_NAME;
else
   $NEW_SUB = $FILE_NAME;
$URL="picture.php?PIC_ID=".$PIC_ID."&SUB_DIR=".urlencode($NEW_SUB);
?>
<br>

<div align="center"><input type="button" class="BigButton" value="<?=_("�����ļ���")?>" onClick="window.location='<?=$URL?>';" title="<?=_("�����ļ���")?>"></div>
<script>
	var xtree=window.parent.PRO_LIST.xtree;
if(xtree)
{
   xtree.redrawNode("<?=$REALOAD_ID?>", "add","");
}
</script>
