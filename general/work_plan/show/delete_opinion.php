<?
include_once("inc/auth.inc.php");
include_once("inc/utility_file.php");

include_once("inc/header.inc.php");
?>




<body class="bodycolor">
<?
$query="delete from WORK_DETAIL where DETAIL_ID='$DETAIL_ID'";
exequery(TD::conn(),$query);

if($ATTACHMENT_NAME!="")
{
   delete_attach($ATTACHMENT_ID,$ATTACHMENT_NAME);
}

header("location: ../manage/add_opinion.php?PLAN_ID=$PLAN_ID");
?>

</body>
</html>
