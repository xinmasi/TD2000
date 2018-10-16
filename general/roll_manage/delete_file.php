<?
include_once("inc/auth.inc.php");

include_once("inc/header.inc.php");
?>




<body class="bodycolor">

<?
  $CUR_TIME=date("Y-m-d H:i:s",time());
	$FILE_ID=intval($FILE_ID);
  $query="update RMS_FILE set DEL_USER='".$_SESSION["LOGIN_USER_ID"]."',DEL_TIME='$CUR_TIME' where FILE_ID='$FILE_ID'";
  exequery(TD::conn(),$query);

  header("location: roll_file.php?CUR_PAGE=$CUR_PAGE&ROLL_ID=$ROLL_ID0&connstatus=1");
?>

</body>
</html>