<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

include_once("inc/header.inc.php");
?>




<body class="bodycolor">

<?
if($_SESSION["LOGIN_USER_PRIV"]=="1")
   $query="select * from RMS_ROLL_ROOM";
else
   $query="select * from RMS_ROLL_ROOM where ADD_USER='".$_SESSION["LOGIN_USER_ID"]."' or MANAGE_USER='".$_SESSION["LOGIN_USER_ID"]."'";

$cursor=exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
  $ROOM_ID=$ROW["ROOM_ID"];
	$ROOM_ID=intval($ROOM_ID);
  $query1="delete from RMS_ROLL_ROOM where ROOM_ID='$ROOM_ID'";
  exequery(TD::conn(),$query1);
}

header("location: index1.php?connstatus=1");
?>

</body>
</html>
