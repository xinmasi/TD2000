<?
include_once("inc/auth.inc.php");
include_once("inc/utility_file.php");
include_once("inc/utility_all.php");

$CUR_DATE=date("Y-m-d",time());

include_once("inc/header.inc.php");
$PLAN_ID = $_GET['PLAN_ID'];

?>




<body class="bodycolor">
<?
if($QUERY_DELETE != 1)
{
    $PLAN_ID=td_trim($DELETE_STR);
}
$query="select * from WORK_PLAN where PLAN_ID in($PLAN_ID)";
$cursor=exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
	$PLAN_ID_STR.=$ROW["PLAN_ID"].",";
	$ATTACHMENT_ID=$ROW["ATTACHMENT_ID"];
	$ATTACHMENT_NAME=$ROW["ATTACHMENT_NAME"];
	if($ATTACHMENT_NAME!=""&&$ATTACHMENT_ID!="")
	delete_attach($ATTACHMENT_ID,$ATTACHMENT_NAME);
}

$query="delete from WORK_PLAN where PLAN_ID in($PLAN_ID)";
exequery(TD::conn(),$query);

//删除计划的进度日志和批注
$PLAN_ARRAY=explode(",",$DELETE_STR);
$PLAN_ARRAY_NUM=sizeof($PLAN_ARRAY);
for($I=0;$I<$PLAN_ARRAY_NUM;$I++)
{
	$query="delete from WORK_DETAIL where PLAN_ID='$PLAN_ARRAY[$I]'";
	exequery(TD::conn(),$query);

	$sql="delete from TASK where  WORK_PLAN_ID='$PLAN_ARRAY[$I]'";
	exequery(TD::conn(),$sql);
}
if($QUERY_DELETE != 1)
{
    header("location: index1.php");
}else
{
    header("location: search.php");
}

?>

</body>
</html>
