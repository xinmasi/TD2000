<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");

include_once("inc/header.inc.php");
?>




<body class="bodycolor">

<?
$CUR_TIME=date("Y-m-d H:i:s",time());
$TOK=strtok($DELETE_STR,",");
while($TOK!="")
{    
	$TOK=intval($TOK);
  	$query="update RMS_FILE set DEL_USER='".$_SESSION["LOGIN_USER_ID"]."', DEL_TIME='$CUR_TIME' where FILE_ID='$TOK'";
  	exequery(TD::conn(),$query);
  	$query_select = "SELECT attachment_id, attachment_name FROM RMS_FILE WHERE file_id='$TOK'";
	$cursor = exequery(TD::conn(), $query_select);
	while($ROW=mysql_fetch_array($cursor, MYSQL_ASSOC)){
	    $ATTACHMENT_ID=$ROW["attachment_id"];
	    $ATTACHMENT_NAME=$ROW["attachment_name"];
	    if($ATTACHMENT_NAME!=""){
	        $ATTACHMENT_ID_ARRAY=explode(",",$ATTACHMENT_ID);
	        $ATTACHMENT_NAME_ARRAY=explode("*",$ATTACHMENT_NAME);
	        
	        $ARRAY_COUNT1=sizeof($ATTACHMENT_ID_ARRAY);
	        for($J=0;$J<$ARRAY_COUNT1;$J++){
	           if($ATTACHMENT_ID_ARRAY[$J]!="")
	              delete_attach($ATTACHMENT_ID_ARRAY[$J],$ATTACHMENT_NAME_ARRAY[$J]);
	        }
	    }
	}
   
  $TOK=strtok(",");
}
  if($OP==1)
	header("location: ../roll_file.php?CUR_PAGE=$CUR_PAGE&ROLL_ID=$ROLL_ID0&connstatus=1");
  else
	header("location: index1.php?connstatus=1");
?>

</body>
</html>
