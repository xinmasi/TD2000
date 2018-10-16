<?
include_once("inc/auth.inc.php");
include_once("inc/utility_file.php");

include_once("inc/header.inc.php");
?>




<body class="bodycolor">

<?
  $CUR_TIME=date("Y-m-d H:i:s",time());
	$FILE_ID=intval($FILE_ID);
  $query="select * from RMS_FILE where FILE_ID='$FILE_ID'";
  $cursor=exequery(TD::conn(),$query);

  if($ROW=mysql_fetch_array($cursor))
  {
    $ATTACHMENT_ID=$ROW["ATTACHMENT_ID"];
    $ATTACHMENT_NAME=$ROW["ATTACHMENT_NAME"];
  }

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
	$FILE_ID=intval($FILE_ID);
  $query="delete from RMS_FILE where FILE_ID='$FILE_ID'";
  exequery(TD::conn(),$query);

  header("location: index1.php?CUR_PAGE=$CUR_PAGE&connstatus=1");
?>

</body>
</html>