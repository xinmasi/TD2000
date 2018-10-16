<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");

$COST_MONEY="";
foreach($_POST as $k=>$v)
{
	if($k!="PROJ_ID")
	   $COST_MONEY .=$v.",";
}

$query="update PROJ_PROJECT set COST_MONEY='$COST_MONEY' where PROJ_ID='$PROJ_ID'";
exequery(TD::conn(),$query);
Message("",_("±£´æ³É¹¦£¡"));
button_back();
//header("location: index.php?PROJ_ID=$PROJ_ID");
?>