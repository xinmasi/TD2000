<?
include_once("inc/auth.inc.php");
include_once("inc/check_type.php");

$HTML_PAGE_TITLE = _("�½�������");
include_once("inc/header.inc.php");
?>




<body class="bodycolor">

<?
//------------------- �½������� -----------------------
$query="insert into CHATROOM(SUBJECT,STOP) values ('$SUBJECT','0')";
exequery(TD::conn(),$query);

header("location: ../");
?>

</body>
</html>
