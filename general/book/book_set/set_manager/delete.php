<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("ɾ������Ա");
include_once("inc/header.inc.php");
?>




<body class="bodycolor">
<?
//----------------------------------------------------------
$query="delete from BOOK_MANAGER where AUTO_ID='$AUTO_ID'";
exequery(TD::conn(),$query);

header("location:index.php");
?>
</body>
</html>