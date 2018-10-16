<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_sms1.php");
include_once("inc/utility_sms2.php");

include_once("inc/header.inc.php");
?>




<body class="bodycolor">
<?
if($PAIBAN_ID!="")
{    
   $query="delete from ZBAP_PAIBAN where PAIBAN_ID='$PAIBAN_ID'";
   exequery(TD::conn(),$query);
      
   header("location: pbgl.php?PAIBAN_TYPE=$PAIBAN_TYPE&YEAR=$YEAR&MONTH=$MONTH&DAY=$DAY&DEPT_ID=$DEPT_ID&connstatus=1");
}
?>
</body>
</html>
