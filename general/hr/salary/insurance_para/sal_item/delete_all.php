<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("删除全部薪酬项目");
include_once("inc/header.inc.php");
?>




<body class="bodycolor">

<?
$query="delete from SAL_ITEM";
exequery(TD::conn(),$query);

header("location: index.php?connstatus=1");
?>
</body>
</html>
