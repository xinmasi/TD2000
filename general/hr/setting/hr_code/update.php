<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("�޸Ĵ���");
include_once("inc/header.inc.php");
?>




<body class="bodycolor">

<?
$query = "SELECT * from HR_CODE where CODE_NO='$CODE_NO' and PARENT_NO='' and CODE_ID!='$CODE_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
   Message(_("��ʾ"),_("������").$CODE_NO._("�Ѵ��ڣ�"));
   Button_Back();
   exit;
}

$query="update HR_CODE set CODE_NO='$CODE_NO',CODE_NAME='$CODE_NAME',CODE_ORDER='$CODE_ORDER' where CODE_ID='$CODE_ID'";
exequery(TD::conn(),$query);

Message("",_("�޸ĳɹ���"));

?>
<center>
    <input type="button" value="<?=_("�ر�")?>" class="BigButton" onClick="window.close();" title="<?=_("�رմ���")?>">
</center>

</body>
</html>