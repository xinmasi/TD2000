<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
include_once("inc/header.inc.php");
include_once("get_diary_data.func.php");
ob_end_clean();
$diary_array=get_share($diary_id);
echo retJson($diary_array);
?>