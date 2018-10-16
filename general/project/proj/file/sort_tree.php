<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
ob_end_clean();

$DEPOSITORY_ARRAY = array();
$FOLDER_IMG="endnode.gif";

$query = "SELECT PROJ_VIEWER,PROJ_OWNER,PROJ_MANAGER from PROJ_PROJECT where PROJ_ID='$PROJ_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
	$PROJ_VIEWER=$ROW["PROJ_VIEWER"];
	$PROJ_OWNER=$ROW["PROJ_OWNER"];
	$PROJ_MANAGER=$ROW["PROJ_MANAGER"];
}

$query = "select SORT_ID,SORT_NAME,VIEW_USER FROM PROJ_FILE_SORT WHERE PROJ_ID='$PROJ_ID' order by SORT_NO, SORT_NAME asc";
$cursor= exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
    $SORT_ID=$ROW["SORT_ID"];
    $SORT_NAME=$ROW["SORT_NAME"];
    $VIEW_USER=$ROW["VIEW_USER"];
    
    if(find_id($VIEW_USER,$_SESSION["LOGIN_USER_ID"]) || find_id($PROJ_VIEWER,$_SESSION["LOGIN_USER_ID"]) || $PROJ_OWNER==$_SESSION["LOGIN_USER_ID"] || $PROJ_MANAGER==$_SESSION["LOGIN_USER_ID"])
    {   
      $SORT_NAME=td_htmlspecialchars($SORT_NAME);
      
      $url = "/general/project/file/folder.php?PROJ_ID=".$PROJ_ID."&SORT_ID=".$SORT_ID;
      
      $DEPOSITORY_ARRAY[] = array(
           "title" => td_iconv($SORT_NAME, MYOA_CHARSET, 'utf-8'),
           "icon" => $FOLDER_IMG,
           "url" => $url,
           "target" => "file_main"
           );   
    }
}//while

echo json_encode($DEPOSITORY_ARRAY);
?>
