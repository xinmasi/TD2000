<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

include_once("inc/header.inc.php");
?>




<body class="bodycolor">

<?
$ROOM_ID=intval($ROOM_ID);
$query="delete from RMS_ROLL_ROOM where ROOM_ID='$ROOM_ID'";
exequery(TD::conn(),$query);

header("location: index1.php?CUR_PAGE=$CUR_PAGE&connstatus=1");
?>

</body>
</html>