<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
include_once("inc/utility_file.php");
$query="select ATTACH from HR_CARD_MODULE where MODULE_ID='$MODULE_ID'";
$cursor=exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
 {
 	 $ATTACH = $ROW["ATTACH"];
 	 $ATTACH_ARRAY = explode(",",$ATTACH);
   $ATTACH_ID =$ATTACH_ARRAY[0];
   $ATTACH_NAME =$ATTACH_ARRAY[1];	
 }
if($ATTACH_ID!="" || $ATTACH_NAME!="")/////É¾³ý¸½¼þ
{
	 delete_attach($ATTACH_ID,$ATTACH_NAME);
}

$query="delete from HR_CARD_MODULE where MODULE_ID='$MODULE_ID'";
exequery(TD::conn(),$query);

header("location:card_module_list.php");
?>
