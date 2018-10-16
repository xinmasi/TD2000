<?
include_once("inc/auth.inc.php");
include_once("inc/utility_file.php");

include_once("inc/header.inc.php");
?>




<body class="bodycolor">

<?
  if($_SESSION["LOGIN_USER_PRIV"]=="1")
     $query="select NEWS_ID,ATTACHMENT_ID,ATTACHMENT_NAME from NEWS";
  else
     $query="select NEWS_ID,ATTACHMENT_ID,ATTACHMENT_NAME from NEWS where PROVIDER='".$_SESSION["LOGIN_USER_ID"]."'";

  $cursor=exequery(TD::conn(),$query);
  while($ROW=mysql_fetch_array($cursor))
  {
    $NEWS_ID=$ROW["NEWS_ID"];
    $ATTACHMENT_ID=$ROW["ATTACHMENT_ID"];
    $ATTACHMENT_NAME=$ROW["ATTACHMENT_NAME"];

    if($ATTACHMENT_NAME!="")
    {
       $ATTACHMENT_ID_ARRAY=explode(",",$ATTACHMENT_ID);
       $ATTACHMENT_NAME_ARRAY=explode("*",$ATTACHMENT_NAME);

       $ARRAY_COUNT=sizeof($ATTACHMENT_ID_ARRAY);
       for($I=0;$I<$ARRAY_COUNT;$I++)
       {
          if($ATTACHMENT_ID_ARRAY[$I]=="")
             break;
          if($ATTACHMENT_NAME_ARRAY[$I]!="")
             delete_attach($ATTACHMENT_ID_ARRAY[$I],$ATTACHMENT_NAME_ARRAY[$I]);
       }
    }

    $query1="delete from NEWS where NEWS_ID='$NEWS_ID'";
    exequery(TD::conn(),$query1);

    $query1="delete from NEWS_COMMENT where NEWS_ID='$NEWS_ID'";
    exequery(TD::conn(),$query1);
  }

  header("location: index1.php?IS_MAIN=1");
?>

</body>
</html>
