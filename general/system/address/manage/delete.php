<?
include_once("inc/auth.inc.php");

include_once("inc/header.inc.php");
?>




<body class="bodycolor">

<? 
$query="update ADDRESS set GROUP_ID=0 where GROUP_ID='$GROUP_ID'";
exequery(TD::conn(),$query);

$query="delete from ADDRESS_GROUP where GROUP_ID='$GROUP_ID'";
exequery(TD::conn(),$query);
?>

<script>
location="index.php?GROUP_ID=<?=$GROUP_ID?>";
</script>

</body>
</html>
