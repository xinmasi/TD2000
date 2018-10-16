<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
ob_end_clean();

$DEPOSITORY_ARRAY = array();
$FOLDER_IMG="menu/project.gif";

//列出我参与的项目
$query = "select PROJ_ID,PROJ_NAME,PROJ_USER,PROJ_VIEWER,PROJ_MANAGER,PROJ_OWNER FROM PROJ_PROJECT WHERE PROJ_STATUS='$PROJ_STATUS'";
$cursor= exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
    $PROJ_ID=$ROW["PROJ_ID"];
    $PROJ_NAME=$ROW["PROJ_NAME"];
    $PROJ_USER=$ROW["PROJ_USER"];
    $PROJ_VIEWER=$ROW["PROJ_VIEWER"];
    $PROJ_MANAGER=$ROW["PROJ_MANAGER"];
    $PROJ_OWNER=$ROW["PROJ_OWNER"];
    
    //项目权限检测
    $PROJ_USER=str_replace("|","",$PROJ_USER);
    if(!find_id($PROJ_USER,$_SESSION["LOGIN_USER_ID"]) && $PROJ_MANAGER!=$_SESSION["LOGIN_USER_ID"] && !find_id($PROJ_VIEWER,$_SESSION["LOGIN_USER_ID"]) && $PROJ_OWNER!=$_SESSION["LOGIN_USER_ID"])
       continue;
   
    //判断项目成员的目录权限
    if(!find_id($PROJ_VIEWER,$_SESSION["LOGIN_USER_ID"]) && $PROJ_MANAGER!=$_SESSION["LOGIN_USER_ID"] && $PROJ_OWNER!=$_SESSION["LOGIN_USER_ID"])
    {
    	$query1 = "select 1 from PROJ_FILE_SORT WHERE PROJ_ID='$PROJ_ID' and (find_in_set('".$_SESSION["LOGIN_USER_ID"]."',VIEW_USER) || find_in_set('".$_SESSION["LOGIN_USER_ID"]."',MANAGE_USER) || find_in_set('".$_SESSION["LOGIN_USER_ID"]."',NEW_USER) || find_in_set('".$_SESSION["LOGIN_USER_ID"]."',MODIFY_USER) )";
      $cursor1= exequery(TD::conn(),$query1);
      if(!mysql_fetch_array($cursor1))
         continue;
    }
    
    $PROJ_NAME=td_htmlspecialchars($PROJ_NAME);
    $PROJ_NAME=str_replace("\"","&quot;",$PROJ_NAME);
    $url = "../proj/info/?PROJ_ID=".$PROJ_ID;
    $json = "sort_tree.php?PROJ_ID=".$PROJ_ID;
    $DEPOSITORY_ARRAY[] = array(
           "title" => td_iconv($PROJ_NAME, MYOA_CHARSET, 'utf-8'),
           "isFolder" => true,
           "isLazy" => true,
           "icon" => $FOLDER_IMG,
           "url" => $url,
           "json" => $json,
           "target" => "file_main"
           );
}//while

echo json_encode($DEPOSITORY_ARRAY);
?>
