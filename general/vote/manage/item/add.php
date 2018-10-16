<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
$VOTE_ID=intval($VOTE_ID);
if($VOTE_ID=="")
   exit;


$sql = "SELECT ITEM_ID FROM vote_item WHERE VOTE_ID = '$VOTE_ID' AND ITEM_NAME = '$ITEM_NAME'";
$arr = exequery(TD::conn(),$sql);
if(mysql_affected_rows()>0)
{
	Message(_("错误"),_("投票项目选项重复"));
	Button_Back();
	exit;
}

$query = "insert into VOTE_ITEM (VOTE_ID,ITEM_NAME) values ($VOTE_ID,'$ITEM_NAME')";
$cursor=exequery(TD::conn(),$query);
header("location: index.php?VOTE_ID=$VOTE_ID&IS_MAIN=1");
?>