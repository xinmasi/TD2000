<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

include_once("inc/header.inc.php");
?>




<body class="bodycolor">

<?
$TOK=strtok($DELETE_STR,",");
while($TOK!="")
{    
	$TOK=intval($TOK);
  $query="update RMS_FILE set DEL_USER='".$_SESSION["LOGIN_USER_ID"]."' and DEL_TIME='$CUR_TIME' where FILE_ID='$TOK'";
  exequery(TD::conn(),$query);
   
  $TOK=strtok(",");
}

header("location: roll_file.php?CUR_PAGE=$CUR_PAGE&ROLL_ID=$ROLL_ID0&connstatus=1");
?>

</body>
</html>
