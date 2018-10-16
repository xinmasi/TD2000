<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

$query="SELECT VM_REQUEST_DATE FROM vehicle_maintenance WHERE V_ID = ".$_GET['VU_ID'];
$cursor = exequery(TD::conn(),$query);

while($ROW = mysql_fetch_array($cursor))
{ 
	$VM_DATE[] = $ROW["VM_REQUEST_DATE"];
}

//查询维护日期是否在申请区间内
if(count($VM_DATE)>0)
{
	$count=count($VM_DATE);
	for($i=0;$i<$count;$i++)
	{
		if(($VM_DATE[$i] == $_GET['VU_START'] || $VM_DATE[$i] == $_GET['VU_END']) || ($_GET['VU_START']<$VM_DATE[$i] && $_GET['VU_END']>$VM_DATE[$i]))
		{
			echo "error";
			exit;
		}
	}	
}
?>