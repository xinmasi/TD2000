<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
ob_start();

$HTML_PAGE_TITLE = _("�½�ͼƬĿ¼");
include_once("inc/header.inc.php");
?>




<body class="bodycolor">

<?
//------------------- ���� -----------------------
$PIC_PATH=str_replace("\\\\","/",$PIC_PATH);
if(!file_exists(iconv2os($PIC_PATH)))
{
   Message(_("����"),_("ͼƬĿ¼·������ȷ��"));
   Button_Back();
   exit;
}
$query="insert into PICTURE(PIC_NAME,PIC_PATH,TO_DEPT_ID,TO_PRIV_ID,TO_USER_ID,ROW_PIC_NUM,ROW_PIC) values ('$PIC_NAME','$PIC_PATH','$TO_ID','$PRIV_ID','$COPY_TO_ID','$ROW_PIC_NUM','$ROW_PIC')";
exequery(TD::conn(),$query);

header("location: ../?IS_MAIN=1");
?>

</body>
</html>
