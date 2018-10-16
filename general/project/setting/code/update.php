<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("ÐÞ¸Ä´úÂë");
include_once("inc/header.inc.php");
?>
<body class="bodycolor">

    <?
    $query = "SELECT * from SYS_CODE where CODE_NO='$CODE_NO' and PARENT_NO='$PARENT_NO' and CODE_ID!='$CODE_ID'";
    $cursor = exequery(TD::conn(), $query);
    if ($ROW = mysql_fetch_array($cursor))
    {
        $MSG = sprintf(_("´úÂë±àºÅ%sÒÑ´æÔÚ£¡"), $CODE_NO);
        Message(_("´íÎó"), $MSG);
        Button_Back();
        exit;
    }

    $query = "update SYS_CODE set CODE_NO='$CODE_NO',CODE_NAME='$CODE_NAME',CODE_ORDER='$CODE_ORDER' where CODE_ID='$CODE_ID'";
    exequery(TD::conn(), $query);


    header("location:code_list.php?PARENT_NO=$PARENT_NO");
    ?>