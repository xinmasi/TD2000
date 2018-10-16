<?
include_once("inc/auth.inc.php");


include_once("inc/header.inc.php");
?>




<body class="bodycolor">

<?
$query="delete from MEETING_EQUIPMENT where EQUIPMENT_ID = '$EQUIPMENT_ID'";
exequery(TD::conn(),$query);

if($THEFROM==1)
   header("location: query.php");
else
   header("location: index1.php?start=$start");
?>	
</body>
</html>