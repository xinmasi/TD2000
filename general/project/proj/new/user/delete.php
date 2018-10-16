<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
$query = "select PROJ_USER,PROJ_PRIV from PROJ_PROJECT where PROJ_ID='$PROJ_ID'";
$cursor = exequery(TD::conn(), $query);
if($ROW = mysql_fetch_array($cursor))
{
	 $PROJ_USER = $ROW["PROJ_USER"];
	 $PROJ_PRIV = $ROW["PROJ_PRIV"];
}

$PROJ_USER_ARRAY = td_explode("|",$PROJ_USER);
$PROJ_PRIV_ARRAY = explode("|",$PROJ_PRIV);

$PROJ_USER='';
$PROJ_PRIV='';
for($I=0;$I<COUNT($PROJ_PRIV_ARRAY);$I++)
{
	if($PROJ_PRIV_ARRAY[$I]!=$PROJ_PRIV_DEL && $PROJ_PRIV_ARRAY[$I]!="")
    {
        $PROJ_PRIV.='|'.$PROJ_PRIV_ARRAY[$I];
        $PROJ_USER.='|'.$PROJ_USER_ARRAY[$I];
    }
}

$query = "update PROJ_PROJECT set PROJ_PRIV='$PROJ_PRIV',PROJ_USER='$PROJ_USER' where PROJ_ID='$PROJ_ID'";
exequery(TD::conn(), $query);

header("location:index.php?PROJ_ID=$PROJ_ID");
?>