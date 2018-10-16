<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("É¾³ý´úÂë");
include_once("inc/header.inc.php");
?>




<body class="bodycolor">

<?
$query="delete from HR_CODE where CODE_ID='$CODE_ID'";
exequery(TD::conn(),$query);

header("location:no_link.php");
?>

</body>
</html>
