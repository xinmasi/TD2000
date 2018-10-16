<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("É¾³ýÏîÄ¿´úÂë");
include_once("inc/header.inc.php");
?>

<body class="bodycolor">
    <?
    $query = "delete from SYS_CODE where CODE_ID='$CODE_ID'";
    exequery(TD::conn(), $query);

    header("location:code_list.php?PARENT_NO=$PARENT_NO");
    ?>
</body>
</html>
