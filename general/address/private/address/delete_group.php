<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("É¾³ý·Ö×é");
include_once("inc/header.inc.php");
?>

<body class="bodycolor">
<?
$GROUP_ID=intval($GROUP_ID);
$query="update ADDRESS set GROUP_ID=0 where GROUP_ID='$GROUP_ID'";
exequery(TD::conn(),$query);

$query="delete from ADDRESS_GROUP where GROUP_ID='$GROUP_ID'";
exequery(TD::conn(),$query);

header("location: group_manage.php?IS_MAIN=1");
?>
</body>
</html>
