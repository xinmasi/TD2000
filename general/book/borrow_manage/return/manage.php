<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("还书");
include_once("inc/header.inc.php");
?>

<body class="bodycolor">
<?
$CUR_DATE=date("Y-m-d",time());

$query="update BOOK_MANAGE set BOOK_STATUS='1',STATUS='1',REAL_RETURN_TIME='$CUR_DATE' where BORROW_ID='$BORROW_ID'";
exequery(TD::conn(),$query);

//改变图书状态
$query="update BOOK_INFO set LEND='0' where BOOK_NO='$BOOK_NO1'";
exequery(TD::conn(),$query);

//删除提醒短信
$MSG = sprintf(_("编号：%s"), $BOOK_NO1);
$query="DELETE a,b FROM sms as a ,sms_body as b WHERE a.BODY_ID = b.BODY_ID AND b.CONTENT like '%".$MSG."%'";
exequery(TD::conn(),$query);

header("location: search.php?TO_ID=$TO_ID&BOOK_NO=$BOOK_NO&BOOK_STATUS1=$BOOK_STATUS1&START_B=$START_B&END_B=$END_B");
?>
</body>
</html>
