<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
include_once("inc/utility_cache.php");

if(strstr($MYTABLE,":"))
{
  $POS_ARRAY=explode(":",$MYTABLE);
  $MYTABLE_LEFT=$POS_ARRAY[0];
  $MYTABLE_RIGHT=$POS_ARRAY[1];
}
else
{
  $MYTABLE_LEFT="";
  $MYTABLE_RIGHT=$MYTABLE;
}

if($MYTABLE!="")
{
   $query="update USER set MYTABLE_LEFT='$MYTABLE_LEFT',MYTABLE_RIGHT='$MYTABLE_RIGHT' where UID='".$_SESSION["LOGIN_UID"]."'";
   exequery(TD::conn(), $query);
}
if($MY_RSS!="")
{
   $query="update USER set MY_RSS='$MY_RSS' where UID='".$_SESSION["LOGIN_UID"]."'";
   exequery(TD::conn(), $query);
}

updateUserCache($_SESSION["LOGIN_UID"]);

echo "OK";
?>
