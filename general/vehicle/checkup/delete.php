<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
if($delete_all){
	$VU_ID = substr($VU_ID, 0, -1);
}
$query="delete from VEHICLE_USAGE where VU_ID in ($VU_ID)";
exequery(TD::conn(),$query);
$str='';
foreach($_GET as $k => $v)
{
    if($k!='VU_ID' && $k!='delete_all')
    {
        $str.=$k."=".$v.",";
    }
}
$paras = rtrim($str,',');
$parameter = str_replace(',','&',$paras);
header("location:query.php?".$parameter);
?>
