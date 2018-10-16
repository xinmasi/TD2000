<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("启用工资上报流程");
include_once("inc/header.inc.php");
?>




<body class="bodycolor">

<?
$CUR_DATE=date("Y-m-d",time());
if(isset($FLOW_ID))
{
  $query="update SAL_FLOW set ISSEND=0, END_DATE='' where FLOW_ID='$FLOW_ID'";
  exequery(TD::conn(),$query);
}
header("location: index.php?PAGE_START=$PAGE_START&connstatus=1");
?>
</body>
</html>
