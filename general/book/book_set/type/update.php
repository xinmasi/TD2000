<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("�޸�ͼ�����");
include_once("inc/header.inc.php");
?>




<body class="bodycolor">

<?
$query="select * from BOOK_TYPE where TYPE_NAME='$TYPE_NAME' and TYPE_ID!='$TYPE_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
  Message(_("����"),sprintf(_("��� %s �Ѵ���"),$TYPE_NAME));
  Button_Back();
  exit;
}

$query="update BOOK_TYPE set TYPE_NAME='$TYPE_NAME' where TYPE_ID='$TYPE_ID'";
exequery(TD::conn(),$query);

header("location: index.php");
?>
</body>
</html>
