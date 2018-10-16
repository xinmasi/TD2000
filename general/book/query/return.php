<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
include_once("inc/utility_sms1.php");

$query="update BOOK_MANAGE set BOOK_STATUS='1',STATUS='0',REG_FLAG='0' where BORROW_ID='$BORROW_ID'";
exequery(TD::conn(),$query);

$query = "select BOOK_NO,RUSER_ID from book_manage where BORROW_ID = '$BORROW_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW = mysql_fetch_array($cursor))
{
   $BOOK_NO = $ROW['BOOK_NO'];
   $RUSER_ID = $ROW['RUSER_ID'];
}
$MSG = sprintf(_("%s归还了所借的图书(编号：%s)。"), $_SESSION["LOGIN_USER_NAME"],$BOOK_NO);
send_sms("",$_SESSION["LOGIN_USER_ID"],$RUSER_ID,73,$MSG,"book/borrow_manage/borrow/");
header("location: query.php?STATUS=$STATUS");
?>