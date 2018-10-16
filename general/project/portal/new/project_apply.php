<?php
include_once("inc/auth.inc.php");
include_once("inc/utility_sms1.php");

$proj_id = isset($PROJ_ID)?$PROJ_ID:0;
$proj_manager=$_GET['PROJ_MANAGER'];
$query = "update proj_project set proj_status = '1',PROJ_MANAGER='".$proj_manager."' where PROJ_ID = '$proj_id' and PROJ_OWNER = '" . $_SESSION['LOGIN_USER_ID'] . "' and PROJ_MANAGER != ''";
exequery(TD::conn(),$query);

$query = "select PROJ_MANAGER,PROJ_NAME,PROJ_NUM from proj_project where PROJ_ID ='$proj_id'";
$cur = exequery(TD::conn(),$query);
$data = array();
while($row = mysql_fetch_array($cur))
{
    $data['PROJ_MANAGER'] = $row['PROJ_MANAGER'];
    $data['PROJ_NAME'] = $row['PROJ_NAME'];
    $data['PROJ_NUM'] = $row['PROJ_NUM'];
}


$REMIND_URL = "1:project/approve/index1.php?PROJ_ID=" . $proj_id;
send_sms("", $_SESSION["LOGIN_USER_ID"], $data['PROJ_MANAGER'], 42, $_SESSION['LOGIN_USER_NAME'] ." 创建了项目：" .$data['PROJ_NUM']." ". $data['PROJ_NAME'] ."  请您审批！" , $REMIND_URL,$proj_id);

header("location:../details/proj_progression.php?VALUE=2&PROJ_ID=$proj_id ");

?>