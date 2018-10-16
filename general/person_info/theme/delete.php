<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
include_once("inc/utility_file.php");
include_once("inc/utility_cache.php");

if($ITEM == "SOUND")
{
   delete_attach_old("swf", $_SESSION["LOGIN_UID"].".swf");
   
   $query = "update USER set CALL_SOUND='1' where UID='".$_SESSION["LOGIN_UID"]."'";
   exequery(TD::conn(),$query);
}
else if($ITEM == "BKGROUND")
{
   delete_attach_old("background/users", $_SESSION["LOGIN_UID"].".".$EXT_NAME);
   
   $query = "update USER set BKGROUND='' where UID='".$_SESSION["LOGIN_UID"]."'";
   exequery(TD::conn(),$query);
}

updateUserCache($_SESSION["LOGIN_UID"]);

header("location:index.php");
?>