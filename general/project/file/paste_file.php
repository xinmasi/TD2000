<?
include_once("inc/auth.inc.php");
include_once("inc/header.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_file.php");

$SORT_ID=intval($SORT_ID);
$SORT_ID_OLD=intval($_COOKIE["proj_sort_id"]);

$VIEW_PRIV=$NEW_PRIV=$PASTE_PRIV=0;
if($_COOKIE["proj_sort_id"]=="" || $_COOKIE["proj_file_action"]=="" || $_COOKIE["proj_id"]=="" || $_COOKIE["proj_file_id"]=="")
{
   Message(_("错误"),_("参数不正确"));
   exit;
}
$PROJ_ID_OLD=$_COOKIE["proj_id"];

if($SORT_ID_OLD!=0)
{
  $query = "SELECT VIEW_USER from PROJ_FILE_SORT where SORT_ID='$SORT_ID_OLD'";
  $cursor= exequery(TD::conn(),$query);
  if($ROW=mysql_fetch_array($cursor))
  {
     $VIEW_USER=$ROW["VIEW_USER"]; 
     $VIEW_PRIV= find_id($VIEW_USER,$_SESSION["LOGIN_USER_ID"])?"1":"0";
  }
  $query = "SELECT PROJ_OWNER,PROJ_VIEWER,PROJ_MANAGER FROM PROJ_PROJECT WHERE PROJ_ID='$PROJ_ID_OLD'";
  $cursor= exequery(TD::conn(),$query);
  if($ROW=mysql_fetch_array($cursor))
  {
  	$PROJ_OWNER=$ROW["PROJ_OWNER"];
  	$PROJ_VIEWER=$ROW["PROJ_VIEWER"];
  	$PROJ_MANAGER=$ROW["PROJ_MANAGER"];
  	if($PROJ_OWNER==$_SESSION["LOGIN_USER_ID"] || find_id($PROJ_VIEWER,$_SESSION["LOGIN_USER_ID"]) || $PROJ_MANAGER == $_SESSION["LOGIN_USER_ID"])
  	   $VIEW_PRIV=1;  	
  }  
}

if($SORT_ID!=0)
{
  $query = "SELECT NEW_USER from PROJ_FILE_SORT where SORT_ID='$SORT_ID'";
  $cursor= exequery(TD::conn(),$query);
  if($ROW=mysql_fetch_array($cursor))
  {
     $NEW_USER=$ROW["NEW_USER"]; 
     $NEW_PRIV= find_id($NEW_USER,$_SESSION["LOGIN_USER_ID"])?"1":"0";
  }
  $query = "SELECT PROJ_OWNER,PROJ_MANAGER FROM PROJ_PROJECT WHERE PROJ_ID='$PROJ_ID'";
  $cursor= exequery(TD::conn(),$query);
  if($ROW=mysql_fetch_array($cursor))
  {
  	$PROJ_OWNER=$ROW["PROJ_OWNER"];
  	$PROJ_MANAGER=$ROW["PROJ_MANAGER"];
  	if($PROJ_OWNER==$_SESSION["LOGIN_USER_ID"] || $PROJ_MANAGER == $_SESSION["LOGIN_USER_ID"])
  	   $NEW_PRIV=1;  	
  } 
}
$PASTE_PRIV = $VIEW_PRIV && $NEW_PRIV;
if(!$PASTE_PRIV)
{
   Message(_("错误"),_("没有粘贴权限"));
   exit;
}

$FILE_STR=$_COOKIE["proj_file_id"];
if($_COOKIE["proj_file_action"]=="copy")
{
  $query = "SELECT * from PROJ_FILE where find_in_set(FILE_ID,'$FILE_STR')";
  $cursor = exequery(TD::conn(), $query);
  while($ROW=mysql_fetch_array($cursor))
  {
    $SUBJECT = $ROW["SUBJECT"];
    $FILE_DESC = $ROW["FILE_DESC"];
    $ATTACHMENT_ID = $ROW["ATTACHMENT_ID"];
    $ATTACHMENT_NAME = $ROW["ATTACHMENT_NAME"];
    $UPLOAD_USER = $ROW["USER_ID"];
    $UPLOAD_TIME=date("Y-m-d H:i:s",time());
      
    if($ATTACHMENT_ID!="" && $ATTACHMENT_NAME!="")
       $ATTACHMENT_ID=copy_attach($ATTACHMENT_ID,$ATTACHMENT_NAME,"","",true).",";

    $query="insert into PROJ_FILE(PROJ_ID,SORT_ID,SUBJECT,FILE_DESC,UPDATE_TIME,ATTACHMENT_ID,ATTACHMENT_NAME,UPLOAD_USER) values ('$PROJ_ID_OLD','$SORT_ID','$SUBJECT','$FILE_DESC','$UPLOAD_TIME','$ATTACHMENT_ID','$ATTACHMENT_NAME','".$_SESSION["LOGIN_USER_ID"]."')";
    exequery(TD::conn(),$query);
  }
}
else if($_COOKIE["proj_file_action"]=="cut")
{
   $PROJ_ID_OLD = intval($PROJ_ID_OLD);
   $SORT_ID = intval($SORT_ID);
   $query="update PROJ_FILE set PROJ_ID=".$PROJ_ID_OLD.",SORT_ID=".$SORT_ID.",UPLOAD_USER='".$_SESSION["LOGIN_USER_ID"]."' where find_in_set(FILE_ID,'$FILE_STR')";
   if($FILE_SORT==2 && $SORT_ID_OLD==0)
      $query.=" and USER_ID='".$_SESSION["LOGIN_USER_ID"]."'";
   exequery(TD::conn(),$query);
      setcookie("proj_sort_id", "");
      setcookie("proj_file_id", "");
      setcookie("proj_file_action", "");
      setcookie("proj_id", "");
}

header("location: folder.php?SORT_ID=$SORT_ID&PROJ_ID=$PROJ_ID&ORDER_BY=$ORDER_BY&ASC_DESC=$ASC_DESC&start=$start");
?>