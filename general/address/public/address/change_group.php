<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
include_once("inc/utility_all.php");

$DELETE_STR = td_trim($DELETE_STR);
if($DELETE_STR != "")
{
   $query="update ADDRESS set GROUP_ID='$GROUP_ID' where GROUP_ID='$GROUP_ID_OLD' and ADD_ID in ($DELETE_STR)";
   exequery(TD::conn(),$query);
}

header("location:index.php?start=$start&GROUP_ID=$GROUP_ID_OLD&FIELD=$FIELD&ASC_DESC=$ASC_DESC");
?>

