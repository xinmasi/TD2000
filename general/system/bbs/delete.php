<?
include_once("inc/auth.inc.php");
include_once("inc/utility_file.php");

include_once("inc/header.inc.php");
?>




<body class="bodycolor">

<?
  $query="select * from BBS_COMMENT where BOARD_ID='$BOARD_ID'";
  $cursor=exequery(TD::conn(),$query);
  while($ROW=mysql_fetch_array($cursor))
  {
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
  }
  
  $query="delete from BBS_COMMENT where BOARD_ID='$BOARD_ID'";
  exequery(TD::conn(), $query);

  $query="delete from BBS_BOARD where BOARD_ID='$BOARD_ID'";
  exequery(TD::conn(), $query);

  header("location: index.php?IS_MAIN=1");  
?>

</body>
</html>
