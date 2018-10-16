<?
include_once("inc/auth.inc.php");
ob_end_clean();

//----zfc-----
$status = isset($_GET['TYPE']) ? intval($TYPE) : 1 ;

$query = "update PROJ_BUG SET STATUS='".$status."' ";
if($RESULT!="")
{
	$CUR_TIME=date("Y-m-d H:i:s",time());
  $RESULT = $_SESSION["LOGIN_USER_NAME"]."(".$CUR_TIME.") <font color=red>"._("ÍË»Ø")."</font> : ".$RESULT."|*|";
  $query .=" ,RESULT=CONCAT(RESULT,'$RESULT')";
}
$query .=" where BUG_ID='$BUG_ID'";
exequery(TD::conn(),$query);

header("location:index.php?PROJ_ID=$PROJ_ID&TASK_ID=$TASK_ID");
?>