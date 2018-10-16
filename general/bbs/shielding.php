<?
include_once("inc/auth.inc.php");

include_once("inc/header.inc.php");
?>




<body class="bodycolor">
<?
$query = "update BBS_COMMENT set SHOW_YN='$SHOW_YN' where COMMENT_ID='$COMMENT_ID'";
exequery(TD::conn(), $query);

$query = "update BBS_COMMENT set SHOW_YN='$SHOW_YN' where PARENT='$COMMENT_ID'";
exequery(TD::conn(), $query);

if($COMMENT_ID_ROOT!=$COMMENT_ID)
   header("location: comment.php?BOARD_ID=$BOARD_ID&COMMENT_ID=$COMMENT_ID_ROOT&PAGE_START=$PAGE_START&IS_MAIN=1");
else
   header("location: board.php?BOARD_ID=$BOARD_ID&IS_MAIN=1");
?>

</body>
</html>
