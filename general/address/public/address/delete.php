<?
include_once("inc/auth.inc.php");
include_once("inc/utility_field.php");

include_once("inc/header.inc.php");
?>




<body class="bodycolor">

<?
if($DELETE_STR!="")
{
	 $TOK=strtok($DELETE_STR,",");
   while($TOK!="")
   {
   	  del_field_data("ADDRESS",$TOK);   	
   	
      $query="delete from ADDRESS where ADD_ID='$TOK'";
      exequery(TD::conn(),$query);     	
      $TOK=strtok(",");
   }
}
else
{
	 del_field_data("ADDRESS",$ADD_ID);
	 	
	 $query="delete from ADDRESS where ADD_ID='$ADD_ID'";
   exequery(TD::conn(),$query);
}
?>

<script>
  location="index.php?GROUP_ID=<?=$GROUP_ID?>&start=<?=$start?>";
</script>

</body>
</html>
