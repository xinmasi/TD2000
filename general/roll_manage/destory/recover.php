<?
include_once("inc/auth.inc.php");

include_once("inc/header.inc.php");
?>




<body class="bodycolor">

<?
  $CUR_TIME=date("Y-m-d H:i:s",time());
	$FILE_ID=intval($FILE_ID);
  $query="update RMS_FILE set DEL_USER='' where FILE_ID='$FILE_ID'";
  exequery(TD::conn(),$query);

  header("location: index1.php?CUR_PAGE=$CUR_PAGE&connstatus=1");
?>

</body>
</html>