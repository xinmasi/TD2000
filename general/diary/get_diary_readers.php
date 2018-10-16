<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
include_once("inc/header.inc.php");
include_once("get_diary_data.func.php");
ob_end_clean();
$diary_id = $_GET['DIA_ID'];
$diary_array=get_readers($diary_id);
echo retJson($diary_array);
?>