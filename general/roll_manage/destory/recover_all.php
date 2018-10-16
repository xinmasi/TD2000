<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

include_once("inc/header.inc.php");
?>




<body class="bodycolor">

<?
$TOK=strtok($DELETE_STR,",");
while($TOK!="")
{  
  $query="update RMS_FILE set DEL_USER='' where FILE_ID='$TOK'";
  exequery(TD::conn(),$query);
   
  $TOK=strtok(",");
}

	header("location: index1.php?connstatus=1");
?>

</body>
</html>
