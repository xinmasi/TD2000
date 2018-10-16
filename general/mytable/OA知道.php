<?
include_once("inc/utility.php");

$MODULE_FUNC_ID="185";
$MODULE_DESC=_("OA知道");
$MODULE_BODY=$MODULE_OP="";
$MODULE_HEAD_CLASS = 'zhidao';

if($MODULE_FUNC_ID=="" || find_id($USER_FUNC_ID_STR, $MODULE_FUNC_ID))
{
$MODULE_OP='<a href="#" title="'._("全部").'" class="all_more" onclick="view_more(\'zhidao\',\''._("OA知道").'\',\'/general/zhidao/\');">'._("全部").'&nbsp;</a>';
$MODULE_BODY.= "<ul>";
$COUNT = 0;
$query = "SELECT b.ASK,b.CREATOR,b.CREATE_TIME,b.ASK_ID,a.CATEGORIE_NAME,a.CATEGORIE_ID,b.ASK_STATUS FROM CATEGORIES_TYPE AS a JOIN WIKI_ASK AS b on a.CATEGORIE_ID = b.CATEGORIE_ID order by b.CREATE_TIME desc limit 0,$MAX_COUNT";
$cursor= exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
   $COUNT++;

   $ASK=td_htmlspecialchars($ROW["ASK"]);
   $ASK_ID=$ROW["ASK_ID"];       
   $CATEGORIE_NAME=$ROW["CATEGORIE_NAME"];
   $CATEGORIE_ID=$ROW["CATEGORIE_ID"];
   $CREATOR=$ROW["CREATOR"];
   $CREATE_TIME=$ROW["CREATE_TIME"];
   $ASK_STATUS=$ROW["ASK_STATUS"];  
   if($ASK_STATUS==0)
      $ASK_STATUS_STR = "<font color=red>"._("未解决")."</font>"; 
   else
      $ASK_STATUS_STR = "<font color=green>"._("已解决")."</font>"; 
   
   $USER_NAME="";
   $query1 = "SELECT USER_NAME FROM USER where USER_ID='$CREATOR'";
   $cursor1= exequery(TD::conn(),$query1);
   if($ROW1=mysql_fetch_array($cursor1))
      $USER_NAME=$ROW1["USER_NAME"];
   
   if((strlen($ASK)+strlen($CATEGORIE_NAME)) > 40)
      $ASK=csubstr($ASK,0,40)."...";

   $MODULE_BODY.='<li>[<A href="/general/zhidao/question/show.php?CATEGORIE_ID='.$CATEGORIE_ID.'">'.$CATEGORIE_NAME.'</A>]&nbsp;<a href="/general/zhidao/question/display.php?ASK_ID='.$ASK_ID.'" target="_blank">'.$ASK.'</a>&nbsp;('.$USER_NAME.' '.$CREATE_TIME.') - '.$ASK_STATUS_STR.'</li>';

}

if($COUNT==0)
   $MODULE_BODY.="<li>"._("暂无待解决的问题")."</li>";
$MODULE_BODY.= "<ul>";
}
?>