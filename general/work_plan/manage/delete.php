<?
include_once("inc/auth.inc.php");
include_once("inc/utility_file.php");

include_once("inc/header.inc.php");
?>




<body class="bodycolor">

<?  
$PLAN_ID=intval($PLAN_ID);
$query="select * from WORK_PLAN where PLAN_ID='$PLAN_ID'";
$cursor=exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
   $ATTACHMENT_ID=$ROW["ATTACHMENT_ID"];
   $ATTACHMENT_NAME=$ROW["ATTACHMENT_NAME"];
}
if($ATTACHMENT_NAME!=""&&$ATTACHMENT_ID!="")
    delete_attach($ATTACHMENT_ID,$ATTACHMENT_NAME);

$query="delete from WORK_PLAN where PLAN_ID='$PLAN_ID'";
exequery(TD::conn(),$query);
//删除该计划的进度日志和批注
$query="delete from WORK_DETAIL where PLAN_ID='$PLAN_ID'";
exequery(TD::conn(),$query);

$sql="delete from TASK where  WORK_PLAN_ID='$PLAN_ID'";
exequery(TD::conn(),$sql);

header("location: index1.php");
?>

</body>
</html>
