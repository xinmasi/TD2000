<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("ɾ��ȫ�����");
include_once("inc/header.inc.php");
?>




<body class="bodycolor">

<?
$query = "SELECT COUNT(*) from BOOK_INFO";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
	$BOOK_COUNT=$ROW[0];

if($BOOK_COUNT>0)
{
	Message(_("����"),_("���ݿ�������ͼ�飬���ܽ����ȫ��ɾ��"));
  Button_Back();
  exit;
}

$query="delete from BOOK_TYPE";
exequery(TD::conn(),$query);

header("location: index.php");
?>
</body>
</html>
