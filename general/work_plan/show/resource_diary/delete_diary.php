<?
include_once("inc/auth.inc.php");
include_once("inc/utility_file.php");

include_once("inc/header.inc.php");
?>


<body class="bodycolor">
<?

	$query="delete from WRESOURCE_DETAIL WHERE AUTO_DETAIL='$DETAIL_ID'";
	exequery(TD::conn(), $query);
	if ($FLAG==1)
	  header("location:resource_detail.php?RPERSON_ID=$PLAN_ID");
	else  
	  header("location:add_resource.php?AUTO_PERSON=$PLAN_ID");

?>

</body>
</html>
