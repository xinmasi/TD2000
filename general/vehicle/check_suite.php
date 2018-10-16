<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");


$VU_SUITE_ID = iconv('UTF-8','gbk',urldecode($_GET['VU_SUITE_ID']));

$query="SELECT V_SEATING FROM vehicle WHERE V_ID = ".$_GET['VU_ID'];
$cursor = exequery(TD::conn(),$query);

if($ROW = mysql_fetch_array($cursor))
{ 
	$V_SEATING = $ROW["V_SEATING"];
}
$VU_SUITE_ID  = td_trim($VU_SUITE_ID);
$vu_sum = count(explode(",",$VU_SUITE_ID));

if($vu_sum > $V_SEATING)
{
	echo "error";
	exit;
}else
{
	echo "ok";
	exit;
}

?>