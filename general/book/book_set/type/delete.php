<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("删除图书类别");
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
	$MSG = sprintf(_("类别“%s”下面尚有图书，不能删除"), $TYPE_NAME);
  Message(_("错误"),$MSG);
  Button_Back();
  exit;
}

$query="delete from BOOK_TYPE where TYPE_ID='$TYPE_ID'";
exequery(TD::conn(),$query);

header("location: index.php");
?>
</body>
</html>
