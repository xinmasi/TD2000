<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");
include_once("inc/utility_sms1.php");
include_once("inc/utility_sms2.php");

$HTML_PAGE_TITLE = _("新建文件");
include_once("inc/header.inc.php");
?>
<body class="bodycolor">

    <?
//--- 安全性 ---
    $query = "SELECT PROJ_NAME,PROJ_USER,PROJ_OWNER,PROJ_MANAGER,PROJ_VIEWER from PROJ_PROJECT where PROJ_ID='$PROJ_ID'";
    $cursor = exequery(TD::conn(), $query);
    if ($ROW = mysql_fetch_array($cursor))
    {
        $PROJ_NAME = $ROW["PROJ_NAME"];
        $PROJ_USER = str_replace("|", "", $ROW["PROJ_USER"]);
        $PROJ_OWNER = $ROW["PROJ_OWNER"];
        $PROJ_MANAGER = $ROW["PROJ_MANAGER"];
        $PROJ_VIEWER = $ROW["PROJ_VIEWER"];
    }

    $query = "SELECT SORT_NAME,NEW_USER,VIEW_USER from PROJ_FILE_SORT where SORT_ID='$SORT_ID'";
    $cursor = exequery(TD::conn(), $query);
    if ($ROW = mysql_fetch_array($cursor))
    {
        $SORT_NAME = $ROW["SORT_NAME"];
        $VIEW_USER = $ROW["VIEW_USER"];
        $NEW_USER = $ROW["NEW_USER"];
        if (!find_id($NEW_USER, $_SESSION["LOGIN_USER_ID"]) && $PROJ_OWNER != $_SESSION["LOGIN_USER_ID"] && $PROJ_MANAGER != $_SESSION["LOGIN_USER_ID"])
            exit;
    }

    if (count($_FILES) > 1)
    {
        $ATTACHMENTS = upload();
        $ATTACHMENT_ID = $ATTACHMENT_ID_OLD . $ATTACHMENTS["ID"];
        $ATTACHMENT_NAME = $ATTACHMENT_NAME_OLD . $ATTACHMENTS["NAME"];
    }
    else
    {
        $ATTACHMENT_ID = $ATTACHMENT_ID_OLD;
        $ATTACHMENT_NAME = $ATTACHMENT_NAME_OLD;
    }

    $ATTACHMENT_ID.=copy_sel_attach($ATTACH_NAME, $ATTACH_DIR, $DISK_ID);
    $ATTACHMENT_NAME.=$ATTACH_NAME;

    if ($NEW_TYPE != "" && $NEW_NAME != "")
    {
        $ATTACHMENT_ID_OFFICE = office_attach($NEW_TYPE, $NEW_NAME);
        $ATTACHMENT_NAME_OFFICE = $NEW_NAME . "." . $NEW_TYPE;
        $ATTACHMENT_ID.=$ATTACHMENT_ID_OFFICE . ",";
        $ATTACHMENT_NAME.=$ATTACHMENT_NAME_OFFICE . "*";
    }

//------------------- 保存 -----------------------
    $SEND_TIME = date("Y-m-d H:i:s", time());

    if ($FILE_ID == "")
    {
        if ($SORT_ID == 0)
            $USER_ID = $_SESSION["LOGIN_USER_ID"];

        $query = "insert into PROJ_FILE(PROJ_ID,SORT_ID,SUBJECT,FILE_DESC,UPDATE_TIME,ATTACHMENT_ID,ATTACHMENT_NAME,UPLOAD_USER) values ('$PROJ_ID','$SORT_ID','$SUBJECT','$FILE_DESC','$SEND_TIME','$ATTACHMENT_ID','$ATTACHMENT_NAME','" . $_SESSION["LOGIN_USER_ID"] . "')";
        exequery(TD::conn(), $query);
        $FILE_ID = mysql_insert_id();
        $ACTION = 1;
    }
    else
    {
        if ($ATTACHMENT_ID != "")
            $query = "update PROJ_FILE set SUBJECT='$SUBJECT',FILE_DESC='$FILE_DESC',UPDATE_TIME='$SEND_TIME',ATTACHMENT_ID='$ATTACHMENT_ID',ATTACHMENT_NAME='$ATTACHMENT_NAME' where FILE_ID='$FILE_ID'";
        else
            $query = "update PROJ_FILE set SUBJECT='$SUBJECT',FILE_DESC='$FILE_DESC',UPDATE_TIME='$SEND_TIME' where FILE_ID='$FILE_ID'";
        exequery(TD::conn(), $query);
        $ACTION = 2;
    }

//记录
    $query = "insert into PROJ_FILE_LOG (FILE_ID,ACTION,USER_ID,ACTION_TIME) VALUES ('$FILE_ID','$ACTION','" . $_SESSION["LOGIN_USER_ID"] . "','$SEND_TIME') ";
    exequery(TD::conn(), $query);

//事务提醒
    if ($OP != 1)
    {
        //提醒对象：项目创建者、审批者、查看者、项目成员并且有此目录权限

        $query = "SELECT USER_ID from USER where (FIND_IN_SET(USER_ID,'$PROJ_USER') AND FIND_IN_SET(USER_ID,'$VIEW_USER')) OR FIND_IN_SET(USER_ID,'$PROJ_VIEWER')";
        $cursor = exequery(TD::conn(), $query);
        while ($ROW = mysql_fetch_array($cursor))
            $TO_ID_STR.=$ROW["USER_ID"] . ",";

        if (!find_id($TO_ID_STR, $PROJ_OWNER))
            $TO_ID_STR.=$PROJ_OWNER . ",";
        if (!find_id($TO_ID_STR, $PROJ_MANAGER))
            $TO_ID_STR.=$PROJ_MANAGER . ",";

        if ($SMS_REMIND == "on" || $SMS2_REMIND == "on")
            $SMS_FILE_DESC = sprintf(_("%s在项目文档-%s-%s 下建添加文件：%s"), $_SESSION["LOGIN_USER_NAME"], $PROJ_NAME, $SORT_NAME, $SUBJECT);

        $REMIND_URL = "1:project/file/read.php?PROJ_ID=" . $PROJ_ID . "&SORT_ID=" . $SORT_ID . "&FILE_ID=" . $FILE_ID;
        if ($SMS_REMIND == "on")
            send_sms("", $_SESSION["LOGIN_USER_ID"], $TO_ID_STR, 42, $SMS_FILE_DESC, $REMIND_URL,$PROJ_ID);
        if ($SMS2_REMIND == "on")
            send_mobile_sms_user("", $_SESSION["LOGIN_USER_ID"], $TO_ID_STR, $SMS_FILE_DESC, 42);
    }

    $YM = substr($ATTACHMENT_ID_OFFICE, 0, strpos($ATTACHMENT_ID_OFFICE, "_"));
    if ($YM)
        $ATTACHMENT_ID_OFFICE = substr($ATTACHMENT_ID_OFFICE, strpos($ATTACHMENT_ID_OFFICE, "_") + 1);
    $ATTACHMENT_ID_OFFICE = attach_id_encode($ATTACHMENT_ID_OFFICE, $ATTACHMENT_NAME_OFFICE);

    if ($OP == "1")
        header("location: index.php?PROJ_ID=$PROJ_ID&SORT_ID=$SORT_ID&FILE_ID=$FILE_ID&MODULE=" . attach_sub_dir() . "&YM=$YM&ATTACHMENT_ID_OFFICE=$ATTACHMENT_ID_OFFICE&ATTACHMENT_NAME_OFFICE=" . urlencode($ATTACHMENT_NAME_OFFICE));
    else
        header("location: ../folder.php?PROJ_ID=$PROJ_ID&SORT_ID=$SORT_ID&start=$start");
    ?>

</body>
</html>
