<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
include_once("inc/header.inc.php");
include_once("get_diary_data.func.php");
ob_end_clean();
$DIA_ID = intval($DIA_ID);
$para_array=get_sys_para("LOCK_TIME,LOCK_SHARE,IS_COMMENTS");
$diary_array=get_diary_detaildata($DIA_ID,$para_array,$IS_MAIN,$DIARY_COPY_TIME);
echo retJson($diary_array);

?>