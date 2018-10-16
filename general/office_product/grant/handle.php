<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
$query = "update OFFICE_TRANSHISTORY set GRANTOR='".$_SESSION["LOGIN_USER_ID"]."',GRANT_STATUS='1' where TRANS_ID = '$TRANS_ID'";
exequery(TD::conn(),$query);
$url ="search.php?PAGE_START=$PAGE_START&FROM_DATE=$FROM_DATE&TO_DATE=$TO_DATE&BORROWER=$BORROWER&GRANT_STATUS=$GRANT_STATUS";
header("location: $url");
?>