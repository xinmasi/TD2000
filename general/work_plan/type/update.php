<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

$HTML_PAGE_TITLE = _("修改计划类型");
include_once("inc/header.inc.php");
?>




<body class="bodycolor">

<?
$TYPE_NO=intval($TYPE_NO);
$query1 = "select * from PLAN_TYPE where TYPE_ID!='$TYPE_ID' and TYPE_NAME='$TYPE_NAME'";
$cursor1= exequery(TD::conn(),$query1);
if($ROW=mysql_fetch_array($cursor1))
{
  Message(_("错误"),_("该计划类型已存在"));
  Button_Back();
  exit;
}
$query="update PLAN_TYPE set TYPE_NAME='$TYPE_NAME',TYPE_NO='$TYPE_NO' where TYPE_ID='$TYPE_ID'";
exequery(TD::conn(),$query);

header("location: index.php");
?>
</body>
</html>
