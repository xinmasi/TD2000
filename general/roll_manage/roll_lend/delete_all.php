<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

include_once("inc/header.inc.php");
?>




<body class="bodycolor">

<?
  $CUR_TIME=date("Y-m-d H:i:s",time());

$TOK=strtok($DELETE_STR,",");
while($TOK!="")
{
	$TOK=intval($TOK);
  $query="select * from RMS_FILE where FILE_ID='$TOK'";
  $cursor=exequery(TD::conn(),$query);
   
  if($ROW=mysql_fetch_array($cursor))
     $ROLL_ID_OLD=$ROW["ROLL_ID"];
	$TOK=intval($TOK);     
  $query="update RMS_FILE set ROLL_ID=0 where ROLL_ID='$TOK'";
  exequery(TD::conn(),$query);
	$TOK=intval($TOK);
  $query="delete from RMS_ROLL where ROLL_ID='$TOK'";
  exequery(TD::conn(),$query);
   
  $TOK=strtok(",");
}

  header("location: index1.php");
?>

</body>
</html>
