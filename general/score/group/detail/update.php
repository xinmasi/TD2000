<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
if($ITEM_ID=="")
   exit;

if (!is_numeric($MIN)||!is_numeric($MAX)||$MAX<0||$MIN>$MAX)
{
	Message("",_("分值错误，请重新输入"));
	Button_Back();
	exit;
}
$query = "update SCORE_ITEM set ITEM_NAME='$ITEM_NAME',MAX='$MAX',MIN='$MIN',ITEM_EXPLAIN='$ITEM_EXPLAIN' where ITEM_ID='$ITEM_ID'";
exequery(TD::conn(),$query);

header("location: index.php?GROUP_ID=".$GROUP_ID."& CUR_PAGE=".$CUR_PAGE."&connstatus=1");
?>