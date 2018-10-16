<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("设置审批规则");
include_once("inc/header.inc.php");
?>

<body class="bodycolor">
    <?
//----------------------------------------------------------
    $PRIV_USER = $USER_ID . "|" . $DEPT_ID;
    $query = "UPDATE PROJ_PRIV set PRIV_USER='$PRIV_USER' where PRIV_ID='$PRIV_ID'";
    exequery(TD::conn(), $query);

    header("location:index.php");
    ?>
</body>
</html>
