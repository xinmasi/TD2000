<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

include_once("inc/header.inc.php");
?>




<body class="bodycolor">

<?
$query="update BOOK_MANAGE set DELETE_FLAG='1' where BORROW_ID='$BORROW_ID'";
exequery(TD::conn(),$query);
header("location: search.php?TO_ID=$TO_ID&BOOK_NO=$BOOK_NO&BOOK_STATUS1=$BOOK_STATUS1&START_B=$START_B&END_B=$END_B");
?>

</body>

</html>
