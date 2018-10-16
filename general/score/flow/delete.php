<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

include_once("inc/header.inc.php");
?>




<body class="bodycolor">

<?
$FLOW_ID=intval($FLOW_ID);
  $query="delete from SCORE_DATE where FLOW_ID='$FLOW_ID'";
  exequery(TD::conn(),$query);

  $query="delete from SCORE_FLOW where FLOW_ID='$FLOW_ID'";
  exequery(TD::conn(),$query);
  
  $query="delete from SCORE_SELF_DATA where FLOW_ID='$FLOW_ID'";
  exequery(TD::conn(),$query);

  header("location: index1.php?CUR_PAGE=$CUR_PAGE&connstatus=1");
?>

</body>
</html>
