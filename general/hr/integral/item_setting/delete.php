<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("É¾³ý»ý·ÖÏî");
include_once("inc/header.inc.php");
?>




<body class="bodycolor">

<?
$query="delete from hr_integral_item_type where TYPE_ID='$TYPE_ID'";
exequery(TD::conn(),$query);

$query="delete from hr_integral_item where TYPE_ID='$TYPE_ID'";
exequery(TD::conn(),$query);

?>

<script>
location="index.php";
</script>

</body>
</html>
