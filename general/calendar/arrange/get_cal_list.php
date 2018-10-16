<?
include_once("inc/session.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
include_once("inc/header.inc.php");
include_once("inc/utility_email.php");
include_once("inc/utility_calendar.php");

session_start();
ob_end_clean();
$begin_date=$_GET["starttime"];
$end_date = $_GET["endtime"];
$view = $_GET["view"];

$end_date = strtotime(date("Y-m-d",$end_date)." 23:59:59");
//echo date("Y-m-d H:i:s",$begin_date)."-".date("Y-m-d H:i:s",$end_date);

//exit;


//$view = "agendaWeek";
$cal_array = get_list_data($view,$begin_date,$end_date);

//echo json_encode($cal_array);
echo retJson($cal_array);

?>