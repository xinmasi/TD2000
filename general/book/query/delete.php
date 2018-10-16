<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");

if($DELETE_FLAG!=1)
{
   $query="delete from BOOK_MANAGE where BORROW_ID='$BORROW_ID'";
   exequery(TD::conn(),$query);
}
else
{
   $query="update BOOK_MANAGE set DELETE_FLAG='1' where BORROW_ID='$BORROW_ID'";
   exequery(TD::conn(),$query);	
   
   $STATUS="1";
}

header("location: query.php?STATUS=$STATUS");
?>