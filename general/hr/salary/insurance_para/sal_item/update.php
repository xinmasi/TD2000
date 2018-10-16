<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("ÐÞ¸ÄÐ½³êÏîÄ¿");
include_once("inc/header.inc.php");
?>




<body class="bodycolor">

<?
$ISPRINT=1;
if($ITEM_TYPE=="1")
{
	$ISREPORT=1;
	$ISCOMPUTER=0;
}
else
{
	$ISREPORT=0;
	if($ITEM_TYPE=="2")$ISCOMPUTER=1;else $ISCOMPUTER=0;
}
$ITEM_ID = intval($ITEM_ID);
$query="update SAL_ITEM set ITEM_NAME='$ITEM_NAME',ISPRINT='$ISPRINT',ISCOMPUTER='$ISCOMPUTER',FORMULA='$FORMULA',FORMULANAME='$FORMULANAME',ISREPORT='$ISREPORT' where ITEM_ID='$ITEM_ID'";
exequery(TD::conn(),$query);

header("location: index.php?connstatus=1");
?>
</body>
</html>
