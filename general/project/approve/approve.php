<?
include_once("inc/auth.inc.php");
include_once("inc/utility.php");
include_once("inc/utility_sms1.php");
mysql_set_charset(MYOA_DB_CHARSET, TD::conn());
    $USER_ID_STR = "";
    $query = "SELECT PROJ_NAME,PROJ_OWNER,PROJ_VIEWER,PROJ_USER,PROJ_NUM from PROJ_PROJECT where PROJ_ID='$PROJ_ID' AND PROJ_STATUS=1 AND PROJ_MANAGER='" . $_SESSION["LOGIN_USER_ID"] . "' ";
    $cursor = exequery(TD::conn(), $query);
    if ($ROW = mysql_fetch_array($cursor)) {
        $PROJ_NAME = $ROW["PROJ_NAME"];
        $PROJ_OWNER = $ROW["PROJ_OWNER"];
        $PROJ_NUM = $ROW["PROJ_NUM"];
        $PROJ_VIEWER = $ROW["PROJ_VIEWER"];
        $PROJ_USER = $ROW["PROJ_USER"];
        $PROJ_USER = str_replace("|", "", $PROJ_USER);

        $USER_ID_STR = $PROJ_USER . $PROJ_VIEWER;
        if (!find_id($USER_ID_STR, $PROJ_OWNER))
            $USER_ID_STR .= $PROJ_OWNER . ",";
        $PROJ_NAME = gbk_stripslashes($PROJ_NAME);
    }
    
    //以后想没办法解决这里的问题
    $CONTENT = iconv('UTF-8','GB2312',$CONTENT);
    
    $CUR_TIME = date("Y-m-d H:i:s", time());
    if ($PASS == 1) 
    {
        $PROJ_STATUS = 2;
        $MSG = _("项目审批通过!");
        $CONTENT = '<font color="green">' . _("通过") . '</font> <b>by ' . $_SESSION["LOGIN_USER_NAME"] . " " . $CUR_TIME . "</b><br/>" . $CONTENT;

        //--事务提醒--
        $SMS_CONTENT = "您的项目：" . $PROJ_NUM ." ". $PROJ_NAME. " 已通过审批！请您关注！";
        $REMIND_URL = "1:project/portal/details/proj_detail.php?VALUE=1&PROJ_ID=" . $PROJ_ID;
        send_sms($CUR_TIME, $_SESSION["LOGIN_USER_ID"], $USER_ID_STR, 42, $SMS_CONTENT, $REMIND_URL,$PROJ_ID);
    } 
    else 
    {
        //$PROJ_STATUS = 0;
        //9为临时位  当审批未通过所占用 除了<项目审批> 中不取状态9 其他地方均把状态9作为状态1
        $PROJ_STATUS = 9;
        $MSG = _("项目审批未通过!");
        $CONTENT = "<font color=\'red\'>" . _("驳回") . "</font> <b>by " . $_SESSION["LOGIN_USER_NAME"] . " " . $CUR_TIME . "</b><br/>" . $CONTENT;

        $SMS_CONTENT = "您的项目：" . $PROJ_NUM ." ". $PROJ_NAME. " 申请被驳回！";
        $REMIND_URL = "1:project/portal/details/proj_detail.php?VALUE=1&PROJ_ID=".$PROJ_ID;
        send_sms($CUR_TIME, $_SESSION["LOGIN_USER_ID"], $PROJ_OWNER, 42, $SMS_CONTENT, $REMIND_URL,$PROJ_ID);
    }
    $CONTENT.="|*|";
    $query = "update PROJ_PROJECT set PROJ_STATUS='$PROJ_STATUS',APPROVE_LOG=CONCAT(APPROVE_LOG,'$CONTENT') WHERE PROJ_ID='$PROJ_ID' AND PROJ_MANAGER='" . $_SESSION["LOGIN_USER_ID"] . "'";
    $cur = exequery(TD::conn(), $query);
    $p = 1;
    if(!mysql_affected_rows())
        $p = 0;
    ob_clean();
    echo $p;
?>
