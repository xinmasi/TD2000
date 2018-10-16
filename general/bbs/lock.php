<?
include_once("inc/auth.inc.php");

include_once("inc/header.inc.php");
?>




<body class="bodycolor">
<?
$query = "update BBS_COMMENT set LOCK_YN='$LOCK_FLAG' where COMMENT_ID='$COMMENT_ID'";
exequery(TD::conn(), $query);

$query = "update BBS_COMMENT set LOCK_YN='$LOCK_FLAG' where PARENT='$COMMENT_ID'";
exequery(TD::conn(), $query);

header("location: comment.php?BOARD_ID=$BOARD_ID&COMMENT_ID=$COMMENT_ID&PAGE_START=$PAGE_START&IS_MAIN=1");
?>

</body>
</html>
