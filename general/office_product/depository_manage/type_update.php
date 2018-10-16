<?
include_once ("inc/auth.inc.php");
include_once ("inc/header.inc.php");
$str='';
if ($ID != "") {
	$query="UPDATE office_type SET type_name='$TYPE_NAME',type_order='$TYPE_ORDER' WHERE id='$ID'";
}else{
	$query="INSERT INTO office_type(type_name,type_order,type_depository,type_parent_id) values('$TYPE_NAME','$TYPE_ORDER','$depository_id','0')";
}
$cursor = exequery ( TD::conn (), $query );
$query="SELECT id FROM office_type where type_depository={$depository_id}";
$cursor = exequery ( TD::conn (), $query );
while ( $ROW = mysql_fetch_array ( $cursor ) ){
	$str.=$ROW['id'].',';
}
$str=substr($str,0,-1);
$query="UPDATE OFFICE_DEPOSITORY SET office_type_id='{$str}' where id={$depository_id}";
$cursor = exequery ( TD::conn (), $query );
header ( "Location:type_manage.php?id={$_POST['depository_id']}" );