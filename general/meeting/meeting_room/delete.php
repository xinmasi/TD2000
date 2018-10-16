<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

include_once("inc/header.inc.php");
?>




<body class="bodycolor">
<?
$query="delete from MEETING_ROOM where MR_ID='$MR_ID'";
exequery(TD::conn(),$query);

header("location: index.php");
?>
</body>

</html>
