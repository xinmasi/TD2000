<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
$query = "select PROJ_USER,PROJ_PRIV from PROJ_PROJECT where PROJ_ID='$PROJ_ID'";
$cursor = exequery(TD::conn(), $query);
if($ROW = mysql_fetch_array($cursor))
{
	 $PROJ_USER_OLD = $ROW["PROJ_USER"];
	 $PROJ_PRIV_OLD = $ROW["PROJ_PRIV"];
}
   
$PROJ_USER_ARRAY = td_explode("|",$PROJ_USER_OLD);
$PROJ_PRIV_ARRAY = explode("|",$PROJ_PRIV_OLD);

if(!in_array($PROJ_PRIV,$PROJ_PRIV_ARRAY))
{
    
    $query = "update PROJ_PROJECT set PROJ_PRIV=CONCAT(PROJ_PRIV,'|$PROJ_PRIV'),PROJ_USER=CONCAT(PROJ_USER,'|$USER_ID') where PROJ_ID='$PROJ_ID'";
    exequery(TD::conn(), $query);  
}
else
{
    $PROJ_USER_NEW = $PROJ_PRIV_NEW = "";
    foreach ($PROJ_PRIV_ARRAY as $k => $v)
    {
        if($v=="")
            continue;
        $PROJ_PRIV_NEW.='|'.$v;
        $PROJ_USER_NEW.='|'.$PROJ_USER_ARRAY[$k];
	    if($v==$PROJ_PRIV)
        {            
            $PROJ_USER_NEW.=$USER_ID;
        }
    }
    $query = "update PROJ_PROJECT set PROJ_PRIV='$PROJ_PRIV_NEW',PROJ_USER='$PROJ_USER_NEW' where PROJ_ID='$PROJ_ID'";
    exequery(TD::conn(), $query);    
}
if(isset($FROM)){
   header("location:../task/new.php?PROJ_ID=$PROJ_ID");
    EXIT;
}   
header("location:index.php?PROJ_ID=$PROJ_ID");
?>