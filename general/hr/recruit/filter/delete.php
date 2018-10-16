<?
include_once("inc/auth.inc.php");
include_once("inc/utility_file.php");

include_once("inc/header.inc.php");
?>



<body class="bodycolor">
<?
if(substr($FILTER_ID,-1)==",")
  $FILTER_ID = substr($FILTER_ID,0,-1);

$query="delete from HR_RECRUIT_FILTER where FILTER_ID in ($FILTER_ID)";
exequery(TD::conn(),$query);

header("location: index1.php?start=$start&connstatus=1");
?>

</body>
</html>
