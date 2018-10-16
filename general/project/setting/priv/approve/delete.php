<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("É¾³ýÉóÅú¹æÔò");
include_once("inc/header.inc.php");
?>

<body class="bodycolor">
    <?
//----------------------------------------------------------
    $query = "delete from PROJ_PRIV where PRIV_ID='$PRIV_ID'";
    exequery(TD::conn(), $query);

    header("location:index.php");
    ?>
</body>
</html>