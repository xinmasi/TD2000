<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
include_once("inc/header.inc.php");
include_once("inc/utility_email.php");
include_once("inc/utility_calendar.php");
ob_end_clean();
$id = intval($_GET["id"]);
//echo $id;exit;
$view = $_GET["view"];
$cal_array = get_cal_detail($id,$view);

//echo json_encode($cal_array);
echo retJson($cal_array);

?>