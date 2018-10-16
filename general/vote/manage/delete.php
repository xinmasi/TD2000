<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
?>

<body class="bodycolor">

<?
if($DELETE_STR=="")
   $DELETE_STR=_("无");
else if(substr($DELETE_STR,-1)==",")
   $DELETE_STR=substr($DELETE_STR,0,-1);

$query="select VOTE_ID from VOTE_TITLE where VOTE_ID in ($DELETE_STR)";
if($_SESSION["LOGIN_USER_PRIV"]!="1")
   $query.=" and FROM_ID='".$_SESSION["LOGIN_USER_ID"]."'";
$DELETE_STR="";
$cursor=exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
   $DELETE_STR.=$ROW["VOTE_ID"].",";

if($DELETE_STR=="")
   $DELETE_STR=_("无");
else if(substr($DELETE_STR,-1)==",")
   $DELETE_STR=substr($DELETE_STR,0,-1);
   
$query="select VOTE_ID from VOTE_TITLE where PARENT_ID in ($DELETE_STR)";
$cursor=exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
   $VOTE_ID1=$ROW["VOTE_ID"];
   
   $query="select ITEM_ID from VOTE_ITEM where VOTE_ID='$VOTE_ID1'";
   $cursor1=exequery(TD::conn(),$query);
   while($ROW1=mysql_fetch_array($cursor1))
   {
      $ITEM_ID=$ROW1["ITEM_ID"];
      
      $query="delete from VOTE_DATA where ITEM_ID='$ITEM_ID' and FIELD_NAME!='0'";
      exequery(TD::conn(),$query);
   }
   
   $query="delete from VOTE_DATA where ITEM_ID='$VOTE_ID1' and FIELD_NAME='0'";
   exequery(TD::conn(),$query);
   
   $query="delete from VOTE_ITEM where VOTE_ID='$VOTE_ID1'";
   exequery(TD::conn(),$query);
}

$query="delete from VOTE_TITLE where PARENT_ID in ($DELETE_STR)";
exequery(TD::conn(),$query);

$query="delete from VOTE_DATA where ITEM_ID in ($DELETE_STR) and FIELD_NAME='0'";
exequery(TD::conn(),$query);
   
$query="delete from VOTE_ITEM where VOTE_ID in ($DELETE_STR)";
exequery(TD::conn(),$query);

$query="delete from VOTE_TITLE where VOTE_ID in ($DELETE_STR)";
exequery(TD::conn(),$query);

$query_title   = "SELECT * FROM vote_title WHERE parent_id='$parent_id'";
$result_titele = exequery(TD::conn(),$query_title);

$query_item    = "SELECT * FROM vote_item WHERE vote_id='$parent_id'";
$result_item   = exequery(TD::conn(),$query_item);

if(mysql_num_rows($result_item)==0 && mysql_num_rows($result_titele)==0)
{
	$query="UPDATE vote_title SET publish=0 WHERE vote_id='$parent_id'";
	exequery(TD::conn(),$query);
}

Message('提示', '操作成功');
Button_Back();
//header("location: index1.php");
?>

</body>
</html>
