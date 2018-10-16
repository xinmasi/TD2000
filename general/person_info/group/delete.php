<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
?>

<body class="bodycolor">

<?
$query="delete from USER_GROUP where GROUP_ID='$GROUP_ID' and USER_ID='".$_SESSION["LOGIN_USER_ID"]."'";
exequery(TD::conn(),$query);

header("location: index.php?IS_MAIN=1");
?>
</body>
</html>
