<?PHP
//-------------------------zfc-----------------
/*
 * 
 * HANG
 *    1:异步审批
 * 
 * TIME : 2014-1-3
 *  */

include_once("inc/auth.inc.php");
include_once("inc/utility.php");
include_once("inc/utility_sms1.php");
mysql_set_charset(MYOA_DB_CHARSET, TD::conn());
$PROJ_ID = intval($PROJ_ID);
$CONTENT = urldecode($CONTENT);

$USER_ID_STR = "";
$query = "SELECT PROJ_NAME,PROJ_OWNER,PROJ_VIEWER,PROJ_USER from PROJ_PROJECT where PROJ_ID='$PROJ_ID' AND PROJ_STATUS=1 AND PROJ_MANAGER='" . $_SESSION["LOGIN_USER_ID"] . "' ";
$cursor = exequery(TD::conn(), $query);
if ($ROW = mysql_fetch_array($cursor)) {
    $PROJ_NAME = $ROW["PROJ_NAME"];
    $PROJ_OWNER = $ROW["PROJ_OWNER"];
    $PROJ_VIEWER = $ROW["PROJ_VIEWER"];
    $PROJ_USER = $ROW["PROJ_USER"];
    $PROJ_USER = str_replace("|", "", $PROJ_USER);

    $USER_ID_STR = $PROJ_USER . $PROJ_VIEWER;
    if (!find_id($USER_ID_STR, $PROJ_OWNER))
        $USER_ID_STR .= $PROJ_OWNER . ",";
    $PROJ_NAME = gbk_stripslashes($PROJ_NAME);
}
$CUR_TIME = date("Y-m-d H:i:s", time());
if ($PASS == 1) 
{
    $PROJ_STATUS = 2;
    $MSG = _("项目审批通过!");
    $CONTENT = '<font color="green">' . _("通过") . '</font> <b>by ' . $_SESSION["LOGIN_USER_NAME"] . " " . $CUR_TIME . "</b><br/>" . $CONTENT;

    //--事务提醒--
    $SMS_CONTENT = sprintf(_("项目：[%s]已立项！请关注！"), $PROJ_NAME);
    $REMIND_URL = "1:project/portal/details/proj_detail.php?VALUE=1&PROJ_ID=" . $PROJ_ID;
    send_sms($CUR_TIME, $_SESSION["LOGIN_USER_ID"], $USER_ID_STR, 42, $SMS_CONTENT, $REMIND_URL,$PROJ_ID);
} 
else 
{
    $PROJ_STATUS = 0;
    $MSG = _("项目审批未通过!");
    $CONTENT = "<font color=\'red\'>" . _("驳回") . "</font> <b>by " . $_SESSION["LOGIN_USER_NAME"] . " " . $CUR_TIME . "</b><br/>" . $CONTENT;

    $SMS_CONTENT = sprintf(_("项目：[%s]申请被驳回！"), $PROJ_NAME);
    $REMIND_URL = "1:project/portal/details/proj_detail.php?VALUE=1&PROJ_ID=" . $PROJ_ID;
    send_sms($CUR_TIME, $_SESSION["LOGIN_USER_ID"], $PROJ_OWNER, 42, $SMS_CONTENT, $REMIND_URL,$PROJ_ID);
}
$CONTENT.="|*|";
$query = "update PROJ_PROJECT set PROJ_STATUS='$PROJ_STATUS',APPROVE_LOG=CONCAT(APPROVE_LOG,'$CONTENT') WHERE PROJ_ID='$PROJ_ID' AND PROJ_MANAGER='" . $_SESSION["LOGIN_USER_ID"] . "'";
exequery(TD::conn(), $query);
// if(!mysql_affected_rows()){
    // echo "0";
// }else{
    // echo "1";
// }

echo $CONTENT;

?>