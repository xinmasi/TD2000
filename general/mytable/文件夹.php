<?
include_once("inc/utility.php");

$SORT_ID="1";
$MODULE_FUNC_ID="15";
$MODULE_DESC=_("测试");
$MODULE_BODY=$MODULE_OP="";
$MODULE_HEAD_CLASS = 'file_folder';

if($MODULE_FUNC_ID=="" || find_id($USER_FUNC_ID_STR, $MODULE_FUNC_ID))
{
$MODULE_OP='<a href="#" title="'._("全部").'" class="all_more" onclick="view_more(\'file_folder\',\''._("文件夹").'\',\'/general/file_folder/index1.php\');">'._("全部").'&nbsp;</a>';
$ACCESS_PRIV=0;
$query = "SELECT SORT_NAME,USER_ID from FILE_SORT where SORT_ID='$SORT_ID'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
{
   $MODULE_DESC=$ROW["SORT_NAME"];
   $USER_ID=$ROW["USER_ID"];

   $ACCESS_USER=explode("|",$USER_ID);
   if($USER_ID==$_SESSION["LOGIN_USER_ID"] || $ACCESS_USER[0]=="ALL_DEPT" || find_id($ACCESS_USER[0],$_SESSION["LOGIN_DEPT_ID"]) || find_id($ACCESS_USER[1],$_SESSION["LOGIN_USER_PRIV"]) || find_id($ACCESS_USER[2],$_SESSION["LOGIN_USER_ID"]))
      $ACCESS_PRIV=1;
}

if($ACCESS_PRIV!=1)
   $MODULE_BODY.= "<ul><li>"._("无该目录访问权限")."</li></ul>";
else
{
   $query = "SELECT CONTENT_ID,SUBJECT from FILE_CONTENT where SORT_ID='$SORT_ID' order by CONTENT_NO,SUBJECT limit 0,$MAX_COUNT";
   $cursor= exequery(TD::conn(),$query);
   while($ROW=mysql_fetch_array($cursor))
   {
      $CONTENT_ID=$ROW["CONTENT_ID"];
      $SUBJECT=$ROW["SUBJECT"];
   
      $SUBJECT_TITLE="";
      if(strlen($SUBJECT) > 50)
      {
         $SUBJECT_TITLE=td_htmlspecialchars($SUBJECT);
         $SUBJECT=csubstr($SUBJECT, 0, 50)."...";
      }
      $SUBJECT=td_htmlspecialchars($SUBJECT);
      
      $MODULE_BODY.= "<li><a href=\"/general/file_folder/read.php?SORT_ID=".$SORT_ID."&CONTENT_ID=".$CONTENT_ID."\" title=\"".$SUBJECT_TITLE."\">".$SUBJECT."</a></li>";
   }//while
   
   if($MODULE_BODY!="")
      $MODULE_BODY="<ul>".$MODULE_BODY."</ul>";
   else
      $MODULE_BODY="<ul><li>"._("该目录下无文件")."</li></ul>";
}
}
?>