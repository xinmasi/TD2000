<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("新建目录");
include_once("inc/header.inc.php");
?>




<body class="bodycolor">

<?
//------------------- 保存 -----------------------

$query="insert into PROJ_FILE_SORT(PROJ_ID,SORT_NO,SORT_PARENT,SORT_NAME) values ('$PROJ_ID','$SORT_NO',0,'$SORT_NAME')";
exequery(TD::conn(),$query);

header("location: ../?PROJ_ID=$PROJ_ID");
?>

</body>
</html>
