<?
include_once("inc/auth.inc.php");
include_once("get_ann_func.php");
$ANNUAL_LEAVE_LEFT=get_ann($TO_ID);
ob_end_clean();
echo $ANNUAL_LEAVE_LEFT;