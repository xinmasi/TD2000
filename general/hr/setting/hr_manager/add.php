<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("������Դ����Ա����");
include_once("inc/header.inc.php");
?>

<body class="bodycolor">

<?
$query="update HR_MANAGER set DEPT_HR_MANAGER='$COPY_TO_ID',DEPT_HR_SPECIALIST='$HR_SPECIALIST' where DEPT_ID='$DEPT_ID'";
exequery(TD::conn(),$query);
header("location: index1.php?connstatus=1");
?>
</body>
</html>
