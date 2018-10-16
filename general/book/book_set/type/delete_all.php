<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("删除全部类别");
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
	Message(_("错误"),_("数据库中尚有图书，不能将类别全部删除"));
  Button_Back();
  exit;
}

$query="delete from BOOK_TYPE";
exequery(TD::conn(),$query);

header("location: index.php");
?>
</body>
</html>
