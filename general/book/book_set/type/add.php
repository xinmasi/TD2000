<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("����ͼ�����");
include_once("inc/header.inc.php");
?>




<body class="bodycolor">

<?
$query="select * from BOOK_TYPE where TYPE_NAME='$TYPE_NAME'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
  Message(_("����"),sprintf(_("��� %s �Ѵ���"),$TYPE_NAME));
  Button_Back();
  exit;
}

$query="insert into BOOK_TYPE(TYPE_NAME) values ('$TYPE_NAME')";
exequery(TD::conn(),$query);

header("location: index.php");
?>
</body>
</html>
