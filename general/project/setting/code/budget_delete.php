<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("É¾³ýÏîÄ¿´úÂë");
include_once("inc/header.inc.php");
?>

<body class="bodycolor">

    <?
    $i_type_no = $_GET["type_no"];
    $query = "delete from proj_budget_type where type_no like '$i_type_no%' ";
    $a = exequery(TD::conn(), $query);

    header("location:budget_list.php");
    ?>

</body>
</html>