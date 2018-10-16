<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

$query="select * from HR_INTEGRAL_ITEM where TYPE_ID='$TYPE_ID' and USED=1";
$cursor=exequery(TD::conn(),$query);
$result_arr=array();
$count=0;
while($ROW=mysql_fetch_array($cursor))
{
	$count++;
	$result_arr["items"][$count]["ID"]=$ROW["ITEM_ID"];
	$result_arr["items"][$count]["NAME"]=$ROW["ITEM_NAME"];
	$result_arr["items"][$count]["ITEM_VALUE"]=$ROW["ITEM_VALUE"];
}
$result_arr['count']=$count;
ob_end_clean();
$str=array_to_json($result_arr);
echo $str;
?>