<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");

if($DELETE_STR=="")
   $DELETE_STR=0;
else
   $DELETE_STR=substr($DELETE_STR,0,-1);

$query="delete from SMS2 where SMS_ID in ($DELETE_STR) and SEND_FLAG!='1'";
exequery(TD::conn(),$query);
//header("location: index.php");
?>
<script>
history.back();
</script>