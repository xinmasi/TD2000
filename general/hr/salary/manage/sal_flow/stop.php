<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("终止工资上报流程");
include_once("inc/header.inc.php");
?>




<body class="bodycolor">

<?
$CUR_DATE=date("Y-m-d",time());
$FLOW_ID = intval($FLOW_ID);
$query="update SAL_FLOW set ISSEND=1,END_DATE=CURDATE() where FLOW_ID='$FLOW_ID'";
//var_dump($query);
exequery(TD::conn(),$query);
header("location: index.php?PAGE_START=$PAGE_START&connstatus=1");
?>
</body>
</html>
