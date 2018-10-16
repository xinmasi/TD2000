<?
include_once("inc/auth.inc.php");

$HTML_PAGE_TITLE = _("É¾³ý´úÂë");
include_once("inc/header.inc.php");
?>




<body class="bodycolor">

<?
 $query = "SELECT * from HR_CODE where CODE_ID='$CODE_ID'";
 $cursor= exequery(TD::conn(),$query);
 if($ROW=mysql_fetch_array($cursor))
    $PARENT_NO=$ROW["PARENT_NO"];
    
$query="delete from HR_CODE where CODE_ID='$CODE_ID'";
exequery(TD::conn(),$query);

$query = "SELECT * from HR_CODE where CODE_NO='$PARENT_NO' and PARENT_NO=''";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
   $CODE_ID=$ROW["CODE_ID"];
   
header("location:index.php?CODE_ID=$CODE_ID");
?>

</body>
</html>
