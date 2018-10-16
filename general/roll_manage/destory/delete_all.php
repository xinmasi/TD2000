<?
include_once("inc/auth.inc.php");
include_once("inc/utility_file.php");

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
   
	$ATTACHMENT_ID=$ROW["ATTACHMENT_ID"];
	$ATTACHMENT_NAME=$ROW["ATTACHMENT_NAME"];
	if($ATTACHMENT_NAME!="")
	{
	 $ATTACHMENT_ID_ARRAY=explode(",",$ATTACHMENT_ID);
	 $ATTACHMENT_NAME_ARRAY=explode("*",$ATTACHMENT_NAME);

	 $ARRAY_COUNT=sizeof($ATTACHMENT_ID_ARRAY);
	 for($I=0;$I<$ARRAY_COUNT;$I++)
	 {
		if($ATTACHMENT_ID_ARRAY[$I]!="")
		   delete_attach($ATTACHMENT_ID_ARRAY[$I],$ATTACHMENT_NAME_ARRAY[$I]);
	 }
	}
	$TOK=intval($TOK);     
  $query="delete from RMS_FILE where FILE_ID='$TOK'";
  exequery(TD::conn(),$query);
   
  $TOK=strtok(",");
}

	header("location: index1.php?connstatus=1");
?>

</body>
</html>
