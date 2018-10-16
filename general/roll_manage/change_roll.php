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
  $query="select * from RMS_FILE where FILE_ID='$TOK'";
  $cursor=exequery(TD::conn(),$query);
   
  if($ROW=mysql_fetch_array($cursor))
     $ROLL_ID_OLD=$ROW["ROLL_ID"];
 	$TOK=intval($TOK);
  $query="update RMS_FILE set ROLL_ID='$ROLL_ID' where FILE_ID='$TOK'";
  exequery(TD::conn(),$query);
   
  $TOK=strtok(",");
}

  if($OP==1)
	header("location: roll_file.php?CUR_PAGE=$CUR_PAGE&ROLL_ID=$ROLL_ID_OLD&connstatus=1");
  else
	header("location: index1.php?PAGE_START=$PAGE_START&ROLL_ID=$ROLL_ID_OLD&connstatus=1");
?>

</body>
</html>
