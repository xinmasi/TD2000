<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

include_once("inc/header.inc.php");
?>




<body class="bodycolor">

<?
$GROUP_ID=intval($GROUP_ID);
  $query="delete from SCORE_ITEM where GROUP_ID='$GROUP_ID'";
  exequery(TD::conn(),$query);
  $query="delete from SCORE_GROUP where GROUP_ID='$GROUP_ID'";
  exequery(TD::conn(),$query);

 header("location: index.php?CUR_PAGE=$CUR_PAGE&connstatus=1");
?>

</body>
</html>
