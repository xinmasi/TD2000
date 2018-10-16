<?
include_once("inc/auth.inc.php");
include_once("inc/utility_file.php");

include_once("inc/header.inc.php");
?>




<body class="bodycolor">
<?
$query="delete from WORK_PERSON where AUTO_PERSON='$AUTO_PERSON'";
exequery(TD::conn(),$query);

if($ATTACHMENT_NAME!="")
{
   delete_attach($ATTACHMENT_ID,$ATTACHMENT_NAME);
}

header("location: plan_resource.php?PLAN_ID=$PLAN_ID&USER_ID=$USER_ID&USER_NAME=$USER_NAME&NAME=$NAME&URL_BEGIN_DATE=$URL_BEGIN_DATE&URL_END_DATE=$URL_END_DATE");
?>

</body>
</html>
