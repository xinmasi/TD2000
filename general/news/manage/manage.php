<?
include_once("inc/auth.inc.php");
if($IS_MAIN==1)
   $QUERY_MASTER=true;
else
   $QUERY_MASTER="";


include_once("inc/header.inc.php");
?>



<body class="bodycolor">
<?
if($ISEND==1)
   $query="update NEWS set PUBLISH='2' where NEWS_ID='$NEWS_ID'";
else 
   $query="update NEWS set PUBLISH='1' where NEWS_ID='$NEWS_ID'";
  
//echo $query;
//exit;   
exequery(TD::conn(),$query,$QUERY_MASTER);
header("location: index1.php?start=$start&IS_MAIN=1");
?>

</body>
</html>
