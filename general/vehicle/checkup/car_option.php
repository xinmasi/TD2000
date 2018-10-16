<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
$query = "select VU_DRIVER from vehicle_usage where VU_ID = '$vu_id'";
$cursor= exequery(TD::conn(),$query);
if($row = mysql_fetch_array($cursor)){
	$driverstr = $row["VU_DRIVER"];
}
$driverarr = explode(',',$driverstr);
foreach($driverarr as $value){
	if(!empty($value)){
		$data .= '<option>'.$value.'</option>';
	}
}
ob_clean();
echo $data;
?>