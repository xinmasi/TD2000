<?
include_once("inc/auth.inc.php");

include_once("inc/header.inc.php");
?>




<body class="bodycolor">

<?
if($_SESSION["LOGIN_USER_PRIV"]!="1")
   exit;
$query="delete from VOTE_DATA";
exequery(TD::conn(),$query);

$query="delete from VOTE_ITEM";
exequery(TD::conn(),$query);

$query="delete from VOTE_TITLE";
exequery(TD::conn(),$query);

header("location: index1.php?IS_MAIN=1");
?>

</body>
</html>
