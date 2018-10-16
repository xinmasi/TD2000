<?
include_once("inc/auth.inc.php");

include_once("inc/header.inc.php");
?>




<body class="bodycolor">

<?

  $CUR_DATE=date("Y-m-d",time());
  $END_DATE=date("Y-m-d",time()-24*60*60);

  if($OPERATION==1)
     $query="update SCORE_FLOW set BEGIN_DATE='$CUR_DATE' where FLOW_ID='$FLOW_ID'";
  else if($OPERATION==2)
     $query="update SCORE_FLOW set END_DATE='$END_DATE' where FLOW_ID='$FLOW_ID'";
  else
     $query="update SCORE_FLOW set END_DATE='0000-00-00' where FLOW_ID='$FLOW_ID'";

  exequery(TD::conn(),$query);

  header("location: index1.php?CUR_PAGE=$CUR_PAGE");
?>

</body>
</html>
