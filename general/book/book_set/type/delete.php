<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("ɾ��ͼ�����");
include_once("inc/header.inc.php");
?>




<body class="bodycolor">

<?
$TYPE_ID=intval($TYPE_ID);
$query = "SELECT TYPE_NAME from BOOK_TYPE where TYPE_ID='$TYPE_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
	$TYPE_NAME=$ROW["TYPE_NAME"];


$query = "SELECT * from BOOK_INFO where TYPE_ID='$TYPE_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
	$MSG = sprintf(_("���%s����������ͼ�飬����ɾ��"), $TYPE_NAME);
  Message(_("����"),$MSG);
  Button_Back();
  exit;
}

$query="delete from BOOK_TYPE where TYPE_ID='$TYPE_ID'";
exequery(TD::conn(),$query);

header("location: index.php");
?>
</body>
</html>
