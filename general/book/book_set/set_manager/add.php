<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("设置管理员");
include_once("inc/header.inc.php");
?>




<body class="bodycolor">
<?
//----------------------------------------------------------
if($AUTO_ID!="")
   $query="UPDATE BOOK_MANAGER set MANAGER_ID='$COPY_TO_ID',MANAGE_DEPT_ID='$TO_ID' where AUTO_ID='$AUTO_ID'";
else
   $query="INSERT into BOOK_MANAGER(MANAGER_ID,MANAGE_DEPT_ID) values ('$COPY_TO_ID','$TO_ID')";
exequery(TD::conn(),$query);

header("location:index.php");
?>
</body>
</html>
