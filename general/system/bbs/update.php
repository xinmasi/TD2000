<?
include_once("inc/auth.inc.php");

include_once("inc/header.inc.php");
?>



<body class="bodycolor">
<?
$query="update BBS_BOARD set BOARD_NAME='$BOARD_NAME',DEPT_ID='$TO_ID',ANONYMITY_YN='$ANONYMITY_YN',WELCOME_TEXT='$WELCOME_TEXT',BOARD_HOSTER='$COPY_TO_ID',BOARD_NO='$BOARD_NO',PRIV_ID='$PRIV_ID',USER_ID='$TO_ID3',CATEGORY='$CATEGORY',LOCK_DAYS_BEFORE='$LOCK_DAYS_BEFORE',NEED_CHECK='$NEED_CHECK' where BOARD_ID='$BOARD_ID'";
exequery(TD::conn(), $query);

header("location: index.php?IS_MAIN=1");
?>
</body>
</html>