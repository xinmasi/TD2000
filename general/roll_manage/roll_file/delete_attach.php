<?
include_once("inc/auth.inc.php");
include_once("inc/utility_file.php");

include_once("inc/header.inc.php");
?>




<body class="bodycolor">

<?
  $query="select * from RMS_FILE where FILE_ID='$FILE_ID'";
  $cursor=exequery(TD::conn(),$query);

  if($ROW=mysql_fetch_array($cursor))
  {
    $SUBJECT=$ROW["SUBJECT"];
    $ATTACHMENT_ID_OLD=$ROW["ATTACHMENT_ID"];
    $ATTACHMENT_NAME_OLD=$ROW["ATTACHMENT_NAME"];
  }

  if($ATTACHMENT_NAME!="")
  {
     delete_attach($ATTACHMENT_ID,$ATTACHMENT_NAME);
     $ATTACHMENT_ID_ARRAY=explode(",",$ATTACHMENT_ID_OLD);
     $ATTACHMENT_NAME_ARRAY=explode("*",$ATTACHMENT_NAME_OLD);
         
     $ARRAY_COUNT=sizeof($ATTACHMENT_ID_ARRAY);
     for($I=0;$I<$ARRAY_COUNT;$I++)
     {
         if($ATTACHMENT_ID_ARRAY[$I]==$ATTACHMENT_ID||$ATTACHMENT_ID_ARRAY[$I]=="")
            continue;
         $ATTACHMENT_ID1.=$ATTACHMENT_ID_ARRAY[$I].",";
         $ATTACHMENT_NAME1.=$ATTACHMENT_NAME_ARRAY[$I]."*";
     }
     $ATTACHMENT_ID_NEW=$ATTACHMENT_ID1;
     $ATTACHMENT_NAME_NEW=$ATTACHMENT_NAME1;
   
		 $FILE_ID=intval($FILE_ID);
     $query="update RMS_FILE set ATTACHMENT_ID='$ATTACHMENT_ID_NEW',ATTACHMENT_NAME='$ATTACHMENT_NAME_NEW' where FILE_ID='$FILE_ID'";
     exequery(TD::conn(),$query);
  }
  
  header("location: modify.php?FILE_ID=$FILE_ID&start=$start&connstatus=1");
?>

</body>
</html>