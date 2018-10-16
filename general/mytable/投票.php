<?
include_once("inc/utility.php");

$MODULE_FUNC_ID="148";
$MODULE_DESC=_("投票");
$MODULE_BODY=$MODULE_OP="";
$MODULE_HEAD_CLASS = 'vote';

if($MODULE_FUNC_ID=="" || find_id($USER_FUNC_ID_STR, $MODULE_FUNC_ID))
{
$MODULE_OP='<a href="#" title="'._("全部").'" class="all_more" onclick="view_more(\'vote\',\''._("投票").'\',\'/general/vote/show/\');">'._("全部").'&nbsp;</a>';
$MODULE_BODY.= "<ul>";

$COUNT=0;
$CUR_DATE=date("Y-m-d",time());
$query = "SELECT VOTE_ID,SUBJECT,TOP,VIEW_PRIV,READERS from VOTE_TITLE where PUBLISH='1' and PARENT_ID=0 and (TO_ID='ALL_DEPT' or find_in_set('".$_SESSION["LOGIN_DEPT_ID"]."',TO_ID)".dept_other_sql("TO_ID")." or find_in_set('".$_SESSION["LOGIN_USER_ID"]."',USER_ID) or find_in_set('".$_SESSION["LOGIN_USER_PRIV"]."',PRIV_ID)".priv_other_sql("PRIV_ID").") and BEGIN_DATE<='$CUR_DATE' and (END_DATE>='$CUR_DATE' or END_DATE is null) order by TOP desc,BEGIN_DATE desc limit 0,$MAX_COUNT";
$cursor= exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
   $COUNT++;
   $VOTE_ID=$ROW["VOTE_ID"];
   $SUBJECT=$ROW["SUBJECT"];
   $TOP=$ROW["TOP"];
   $VIEW_PRIV=$ROW["VIEW_PRIV"];
   $READERS=$ROW["READERS"];
   $SUBJECT_TITLE="";
   if(strlen($SUBJECT) > 50)
   {
      $SUBJECT_TITLE=$SUBJECT;
      $SUBJECT=csubstr($SUBJECT, 0, 50)."...";
   }
   $SUBJECT=td_htmlspecialchars($SUBJECT);
   $SUBJECT_TITLE=td_htmlspecialchars($SUBJECT_TITLE);
   if (find_id($READERS, $_SESSION["LOGIN_USER_ID"])&&$VIEW_PRIV!=2){
	$URL="/general/vote/show/show_reader.php?VOTE_ID=$VOTE_ID";
}
else 
   $URL="/general/vote/show/read_vote.php?VOTE_ID=$VOTE_ID";
   
   if($TOP=="1")
      $SUBJECT="<font color=red><b>".$SUBJECT."</b></font>";
      $MODULE_BODY.='<li><a href="'.$URL.'" target="_blank"">'.$SUBJECT.'</a></li>';
   //$MODULE_BODY.='<li><a href="javascript:open_vote('.$VOTE_ID.');" title="'.$SUBJECT_TITLE.'">'.$SUBJECT.'</a></li>';
}

if($COUNT==0)
   $MODULE_BODY.= "<li>"._("暂无投票")."</li>";

$MODULE_BODY.= "<ul>";

$MODULE_BODY.='<script>
function open_vote(VOTE_ID)
{
 URL="/general/vote/show/read_vote.php?VOTE_ID="+VOTE_ID;
 myleft=(screen.availWidth-780)/2;
 window.open(URL,"read_vote"+VOTE_ID,"height=500,width=780,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,top=150,left="+myleft+",resizable=yes");
}
</script>';
}
?>