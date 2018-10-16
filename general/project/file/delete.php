<?
include_once("inc/auth.inc.php");
include_once("inc/utility_file.php");
include_once("inc/utility_all.php");
ob_start();
$ajax = false;
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')
{
    $ajax = true;
    foreach($_POST as $k =>$v)
    {
        $_POST[$k] = iconv('utf-8',MYOA_CHARSET,$v);
    }
}

$query = "SELECT MANAGE_USER from PROJ_FILE_SORT where SORT_ID='$SORT_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
   $MANAGE_USER=$ROW["MANAGE_USER"];
   $MANAGE_PRIV= find_id($MANAGE_USER,$_SESSION["LOGIN_USER_ID"]);
}

$query = "select UPLOAD_USER FROM PROJ_FILE WHERE FILE_ID='$FILE_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
   $UPLOAD_USER=$ROW["UPLOAD_USER"];

if($UPLOAD_USER!=$_SESSION["LOGIN_USER_ID"] && !$MANAGE_PRIV)
   exit;

$FILE_ARRAY=explode(",",$FILE_ID);
for($J=0;$J< count($FILE_ARRAY);$J++)
{
  if($FILE_ARRAY[$J]=="")
     continue;
  $ATTACHMENT_NAME="";
  $query="select SORT_ID,ATTACHMENT_ID,ATTACHMENT_NAME from PROJ_FILE where FILE_ID='$FILE_ARRAY[$J]'";
  $cursor=exequery(TD::conn(),$query);
  if($ROW=mysql_fetch_array($cursor))
  {
    $SORT_ID1=$ROW["SORT_ID"];
    $ATTACHMENT_ID=$ROW["ATTACHMENT_ID"];
    $ATTACHMENT_NAME=$ROW["ATTACHMENT_NAME"];
    
    if($SORT_ID!=$SORT_ID1)
       exit;
  }
  
  if($ATTACHMENT_NAME!="")
  {
     $ATTACHMENT_ID_ARRAY=explode(",",$ATTACHMENT_ID);
     $ATTACHMENT_NAME_ARRAY=explode("*",$ATTACHMENT_NAME);

     $ARRAY_COUNT=sizeof($ATTACHMENT_ID_ARRAY);
     for($I=0;$I<$ARRAY_COUNT;$I++)
     {
        if($ATTACHMENT_ID_ARRAY[$I]!="")
           delete_attach($ATTACHMENT_ID_ARRAY[$I],$ATTACHMENT_NAME_ARRAY[$I]);
     }
  }

}
$query="delete from PROJ_FILE where find_in_set(FILE_ID,'$FILE_ID')";
exequery(TD::conn(),$query);
if($ajax == true)
{
    $p = "false";
    if(mysql_affected_rows())
    $p = "true";
    
    echo $p;
    exit;
}
else
{
    header("location: folder.php?PROJ_ID=$PROJ_ID&SORT_ID=$SORT_ID&start=$start");
}
?>
