<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");

include_once("inc/header.inc.php");
?>



<body class="bodycolor">
<?
if($DELETE_STR=="")
   $DELETE_STR=0;
else if(substr($DELETE_STR,-1,1)==",")
   $DELETE_STR=substr($DELETE_STR,0,-1);

if($ISEND==1)
   $query="update NEWS set PUBLISH='2' where NEWS_ID in ($DELETE_STR)";
else
   $query="update NEWS set PUBLISH='1' where NEWS_ID in ($DELETE_STR)";
exequery(TD::conn(),$query);

header("location: index1.php?start=$start&TYPE=$TYPE&IS_MAIN=1");
?>
</body>
</html>
