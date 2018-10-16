<?
include_once("inc/auth.inc.php");

include_once("inc/header.inc.php");
?>




<body class="bodycolor">
<?
$query="update PROJ_COMMENT set CONTENT='$CONTENT',WRITE_TIME='$WRITE_TIME' where COMMENT_ID='$COMMENT_ID' AND WRITER='".$_SESSION["LOGIN_USER_ID"]."'";
exequery(TD::conn(),$query);

header("location: index.php?PROJ_ID=$PROJ_ID");
?>
</body>
</html>
