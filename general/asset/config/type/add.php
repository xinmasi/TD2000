<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("�����ʲ����");
include_once("inc/header.inc.php");
?>




<body class="bodycolor">

<?
$query="select * from CP_ASSET_TYPE where TYPE_NAME='$TYPE_NAME'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
  Message(_("����"),_("���ʲ�����Ѵ���"));
  Button_Back();
  exit;
}

$query="insert into CP_ASSET_TYPE(TYPE_NO,TYPE_NAME) values ($TYPE_NO,'$TYPE_NAME')";
exequery(TD::conn(),$query);

header("location: index.php");
?>
</body>
</html>
