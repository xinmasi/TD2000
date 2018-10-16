<?
include_once("inc/auth.inc.php");

include_once("inc/header.inc.php");
?>




<body class="bodycolor">
<?
$query="delete from PROJ_COMMENT where COMMENT_ID='$COMMENT_ID'";
exequery(TD::conn(),$query);

header("location: index.php?PROJ_ID=$PROJ_ID");
?>

</body>
</html>
