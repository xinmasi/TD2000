<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("ɾ��ȫ��н����Ŀ");
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
