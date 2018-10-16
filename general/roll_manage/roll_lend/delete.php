<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

include_once("inc/header.inc.php");
?>

<body class="bodycolor">

<?
$LEND_ID=intval($LEND_ID);
//$query="delete from RMS_LEND where LEND_ID='$LEND_ID'";
$query = "update RMS_LEND set DELETE_FLAG = '1' where LEND_ID='$LEND_ID'";
exequery(TD::conn(),$query);

if(isset($type))
{
    header("location: search.php?connstatus=1");
}
else
{
    header("location: confirm.php");
}

?>

</body>
</html>
