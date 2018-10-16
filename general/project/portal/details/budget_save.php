<?
include_once("details.inc.php");
include_once(MYOA_ROOT_PATH . "/inc/flow_hook.php");

if(!project_apply_priv($PROJ_ID)){
    Message(_("错误"),_("无权申请资金!"));
    Button_Back();
    exit();    
}

$date = time();
$real_amount = $_POST['budget_amount'];
$proj_id = $_POST['PROJ_ID'];
$type_code = $_POST['type_code'];
$type_style_code = $_POST['type_style_code'];
$record =  $_POST['budget_record'];

$proj_hook = project_hook("project_money_x1");

if($proj_hook == 1){

    $query = "insert into proj_budget_real(type_code,real_id,real_amount,proj_id,date,record) values ('$type_code','".$_SESSION["LOGIN_USER_ID"]."','$real_amount','$proj_id','$date','$record')";
    exequery(TD::conn(),$query);
    $ROW_ID=mysql_insert_id();
	
    $query = "select PROJ_NAME,type_name from proj_project,proj_budget_type where PROJ_ID = '$proj_id' and proj_budget_type.id = '$type_code'";
    $cur = exequery(TD::conn(),$query);
    if($row = mysql_fetch_array($cur)){
        $proj_name = $row['PROJ_NAME'];
        $type_name = $row['type_name'];
    }

    $data_array=array(
						"KEY"=>$ROW_ID,
						"field"=>"project_money_id",
						"PROJ_NAME"=>"$proj_name",
						"REAL_AMOUNT"=>$real_amount,
						"TYPE_NAME"=>$type_name,
						"DATE"=>date("Y-m-d"),
						"RECORD"=>$record,
						"REAL_USER_NAME"=>$_SESSION["LOGIN_USER_NAME"]
					);
    $module = array("module"=>"project_money_x1");
    $status = 0;
    run_hook($data_array,$module);     
  
}else{
   $query = "insert into proj_budget_real(type_code,real_id,real_amount,proj_id,date,record,status) values ('$type_code','".$_SESSION["LOGIN_USER_ID"]."','$real_amount','$proj_id','$date','$record','1,1,1,')";
   exequery(TD::conn(),$query);
   Message(_(""),"记录成功!");
   Button_Back();
}





?>