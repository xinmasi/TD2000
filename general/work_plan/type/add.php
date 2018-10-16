<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("增加计划类型");
include_once("inc/header.inc.php");
?>




<body class="bodycolor">

<?
$query="select * from PLAN_TYPE where TYPE_NAME='$TYPE_NAME'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
  Message(_("错误"),_("该计划类型已存在"));
  Button_Back();
  exit;
}

$query="insert into PLAN_TYPE(TYPE_NO,TYPE_NAME) values ($TYPE_NO,'$TYPE_NAME')";
exequery(TD::conn(),$query);

header("location: index.php");
?>
</body>
</html>
