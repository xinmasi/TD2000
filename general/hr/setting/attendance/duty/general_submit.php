<?
include_once("inc/auth.inc.php");

include_once("inc/header.inc.php");
?>




<body class="bodycolor">

<?
for($I=0;$I<7;$I++)
{
   $VAR="WEEK".$I;
   if($$VAR=="on")
      $GENERAL.=$I.",";
}
$GENERAL=substr($GENERAL,0,-1);

$query="update ATTEND_CONFIG set GENERAL='$GENERAL' where DUTY_TYPE='$DUTY_TYPE'";
exequery(TD::conn(),$query);

header("location: index.php?connstatus=1");
?>

</body>
</html>