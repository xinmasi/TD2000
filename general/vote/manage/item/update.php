<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");


if($ITEM_ID=="")
   exit;
$VOTE_ID=intval($VOTE_ID);
$query = "SELECT * from VOTE_TITLE where VOTE_ID='$VOTE_ID'";
if($_SESSION["LOGIN_USER_PRIV"]!="1")
   $query.=" and FROM_ID='".$_SESSION["LOGIN_USER_ID"]."'";
$cursor= exequery(TD::conn(),$query);
if(mysql_num_rows($cursor) == 0)
   exit;

$sql = "SELECT ITEM_ID FROM vote_item WHERE VOTE_ID = '$VOTE_ID' AND ITEM_NAME = '$ITEM_NAME' AND ITEM_ID!='$ITEM_ID'";
$arr = exequery(TD::conn(),$sql);
if(mysql_affected_rows()>0)
{
	Message(_("错误"),_("投票项目选项重复"));
	Button_Back();
	exit;
}


$query = "update VOTE_ITEM set VOTE_ID='$VOTE_ID',ITEM_NAME='$ITEM_NAME' where ITEM_ID='$ITEM_ID'";
exequery(TD::conn(),$query);

header("location: index.php?VOTE_ID=$VOTE_ID&IS_MAIN=1");
?>