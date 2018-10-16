<?
include_once("details.inc.php");

if(!project_apply_priv($PROJ_ID)){
    Message(_("错误"),_("无权申请资源!"));
    Button_Back();
    exit();    
}

$source_name = $_POST["source_name"]; 
$source_type = $_POST["source_type"]; 
$source_start_time = $_POST["source_start_time"]; 
$source_end_time = $_POST["source_end_time"]; 
$source_count = $_POST["source_count"]; 
$source_price = $_POST["source_price"]; 
$source_money = $source_count * $source_price;
$proj_id = $_POST["proj_id"]; 
if($proj_id)
{
    $query = "INSERT INTO proj_source (id,source_name,source_type,source_start_time,source_end_time,source_count,source_price,source_money,type_id,proj_id) VALUE ('','$source_name','$source_type','$source_start_time','$source_end_time','$source_count','$source_price','$source_money','','$proj_id')";
    exequery(TD::conn(), $query);
}
header("location: proj_resource.php?VALUE=5&PROJ_ID=$proj_id");   

?>