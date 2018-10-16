<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");

?>

<body class="bodycolor">

<?
if($delete_all){
	$VM_ID = substr($VM_ID, 0, -1);
}

$query="delete from VEHICLE_MAINTENANCE where VM_ID in ($VM_ID)";
exequery(TD::conn(),$query);
$str='';
foreach($_GET as $k => $v)
{
    if($k!='VM_ID' && $k!='delete_all')
    {
        $str.=$k."=".$v.",";
    }
}
$paras = rtrim($str,',');
$parameter = str_replace(',','&',$paras);

if($from == 'manage')
{
    header("location: ../manage/maintenance.php?V_ID=$V_ID");
}
else
{
    header("location:search.php?".$parameter);
}
?>
</body>
</html>
