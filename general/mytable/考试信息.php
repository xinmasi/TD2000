<?
include_once("inc/utility.php");

$MODULE_FUNC_ID="4";
$MODULE_DESC=_("考试信息");
$MODULE_BODY=$MODULE_OP="";
$MODULE_HEAD_CLASS = 'hr';

if($MODULE_FUNC_ID=="" || find_id($USER_FUNC_ID_STR, $MODULE_FUNC_ID))
{
$MODULE_OP='<a href="#" title="'._("全部").'" class="all_more" onclick="view_more(\'exam_manage\',\''._("考试信息").'\',\'/general/exam_manage/exam_online/\');">'._("全部").'&nbsp;</a>';
$MODULE_BODY.= "<ul>";

$CUR_DATE=date("Y-m-d H:i:s",time());
$COUNT=0;
$query = "SELECT * from EXAM_FLOW where find_in_set('".$_SESSION["LOGIN_USER_ID"]."',PARTICIPANT) and begin_date<='$CUR_DATE' and (end_date>='$CUR_DATE' or end_date is null) order by BEGIN_DATE desc,SEND_TIME desc limit 0,$MAX_COUNT";

$cursor= exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
   $COUNT++;
   $FLOW_ID=$ROW["FLOW_ID"];
   $FLOW_TITLE=$ROW["FLOW_TITLE"];

   $BEGIN_DATE=$ROW["BEGIN_DATE"];
   $BEGIN_DATE=strtok($BEGIN_DATE," ");
   
   $SUBJECT_TITLE="";
   if(strlen($FLOW_TITLE) > 50)
   {
      $SUBJECT_TITLE=td_htmlspecialchars($FLOW_TITLE);
      $FLOW_TITLE=csubstr($FLOW_TITLE, 0, 50)."...";
   }
   $FLOW_TITLE=td_htmlspecialchars($FLOW_TITLE);
   
   $MODULE_BODY.='<li><a href="/general/exam_manage/exam_online/" title="'.$SUBJECT_TITLE.'">'.$FLOW_TITLE.'</a>&nbsp;('.$BEGIN_DATE.')';

   $MODULE_BODY.= "</li>";
}

if($COUNT==0)
   $MODULE_BODY.= "<li>"._("暂无考试信息")."</li>";

$MODULE_BODY.= "<ul>";

//$MODULE_BODY.=';
}
?>