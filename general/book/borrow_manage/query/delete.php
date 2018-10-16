<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

include_once("inc/header.inc.php");
?>




<body class="bodycolor">

<?
$query="delete from BOOK_MANAGE where BORROW_ID='$BORROW_ID'";
exequery(TD::conn(),$query);

header("location: search.php?TO_ID=$TO_ID&BOOK_NO=$BOOK_NO&BOOK_STATUS1=$BOOK_STATUS1");
?>

</body>

</html>
