<?
include_once("inc/utility.php");

$MODULE_FUNC_ID="62";
$MODULE_DESC=_("内部讨论区");
$MODULE_BODY=$MODULE_OP="";
$MODULE_HEAD_CLASS = 'bbs';

if($MODULE_FUNC_ID=="" || find_id($USER_FUNC_ID_STR, $MODULE_FUNC_ID))
{
$MODULE_OP='<a href="#" title="'._("全部").'" class="all_more" onclick="view_more(\'bbs\',\''._("内部讨论区").'\',\'/general/bbs/\');">'._("全部").'&nbsp;</a>';
$MODULE_TYPE .='<a href="javascript:get_bbs(\'0\');">'._("最新帖子").'</a> ';
$MODULE_TYPE .='<a href="javascript:get_bbs(\'1\');">'._("讨论区列表").'</a>';

$MODULE_BODY.= "<ul>";

$COUNT=0;
$query1 = "SELECT BBS_BOARD.BOARD_ID,BBS_BOARD.BOARD_NAME,BBS_COMMENT.COMMENT_ID,
BBS_COMMENT.SUBJECT,BBS_COMMENT.AUTHOR_NAME,BBS_COMMENT.TOP,BBS_COMMENT.JING,BBS_COMMENT.READEDER,
BBS_COMMENT.SUBMIT_TIME,BBS_COMMENT.REPLY_CONT,BBS_COMMENT.PARENT
from BBS_COMMENT inner join BBS_BOARD
on BBS_COMMENT.BOARD_ID = BBS_BOARD.BOARD_ID
where BBS_COMMENT.PARENT='0' and (BBS_BOARD.DEPT_ID='ALL_DEPT'
or find_in_set('".$_SESSION["LOGIN_DEPT_ID"]."',BBS_BOARD.DEPT_ID)
or find_in_set('".$_SESSION["LOGIN_USER_PRIV"]."',BBS_BOARD.PRIV_ID)
or find_in_set('".$_SESSION["LOGIN_USER_ID"]."',BBS_BOARD.USER_ID)
or find_in_set('".$_SESSION["LOGIN_USER_ID"]."',BBS_BOARD.BOARD_HOSTER))
and IS_CHECK!='0' and IS_CHECK!='2' 
order by BBS_COMMENT.SUBMIT_TIME desc,BBS_COMMENT.COMMENT_ID desc
limit 0,$MAX_COUNT";
$cursor1 = exequery(TD::conn(),$query1);
while($ROW1=mysql_fetch_array($cursor1))
{

   $COUNT++;

   $BOARD_ID = $ROW1["BOARD_ID"];
   $BOARD_NAME = $ROW1["BOARD_NAME"];
   $COMMENT_ID = $ROW1["COMMENT_ID"];
   $SUBJECT = $ROW1["SUBJECT"];
   $AUTHOR_NAME = _("作者:").$ROW1["AUTHOR_NAME"];
   $TOP = $ROW1["TOP"];
   $JING = $ROW1["JING"];
   $READEDER = $ROW1["READEDER"];
   $SUBMIT_TIME = $ROW1["SUBMIT_TIME"];
   $REPLY_CONT = $ROW1["REPLY_CONT"];
   $PARENT = $ROW1["PARENT"];

   $query = "SELECT AUTHOR_NAME,OLD_SUBMIT_TIME from BBS_COMMENT where PARENT = '$COMMENT_ID' order by OLD_SUBMIT_TIME desc limit 0,1";
   $cursor = exequery(TD::conn(),$query);
   if($ROW=mysql_fetch_array($cursor))
   {
      $OLD_SUBMIT_TIME = $ROW["OLD_SUBMIT_TIME"];
      $AUTHOR_NAME =  _("回复人:").$ROW["AUTHOR_NAME"];
      $SUBMIT_TIME = $OLD_SUBMIT_TIME;
   }

   $SUBJECT_TITLE="";
   //$SUBJECT_TITLE=td_htmlspecialchars($SUBJECT);
   if(strlen($SUBJECT) > 50)
      $SUBJECT=csubstr($SUBJECT, 0, 50)."...";
      
   //$SUBJECT=td_htmlspecialchars($SUBJECT);

   if($TOP=="1")
      $SUBJECT="<font color=red><b>".$SUBJECT."</b></font>";

   $MODULE_BODY.='<li>'._("【").'<a href="/general/bbs/board.php?BOARD_ID='.$BOARD_ID.'" target="_blank">'.$BOARD_NAME.'</a>'._("】").'<a href="/general/bbs/comment.php?BOARD_ID='.$BOARD_ID.'&COMMENT_ID='.$COMMENT_ID.'" title="'.$SUBJECT_TITLE.'" target="_blank">'.$SUBJECT.'</a>';

   if(!find_id($READEDER,$_SESSION["LOGIN_USER_ID"]))
      $MODULE_BODY.= "<img src='".MYOA_STATIC_SERVER."/static/images/new.gif' align='absmiddle' /> ";

   if($JING==1)
      $MODULE_BODY.= "<img src='".MYOA_STATIC_SERVER."/static/images/jing.gif' align='absmiddle' />";
   $MODULE_BODY.='('.$AUTHOR_NAME.' '.substr($SUBMIT_TIME,5,-3).')</li>';
}

if($COUNT==0)
   $MODULE_BODY.="<li>"._("暂无帖子")."</li>";

$MODULE_BODY.= "<ul>";

$MODULE_BODY.='<script>
function get_bbs(req)
{
   var obj = $("module_'.$MODULE_ID.'_ul");
   if(!obj) return;

   if(typeof(req) != "object")
   {
      obj.innerHTML = \'<img src="'.MYOA_STATIC_SERVER.'/static/images/loading.gif" align="absMiddle"> '._("加载中，请稍候……").'\';
      _get("bbs.php", "MAX_COUNT='.$MAX_COUNT.'&TYPE_ID="+req+"&MODULE_SCROLL='.$MODULE_SCROLL.'&MODULE_ID='.$MODULE_ID.'", arguments.callee);
   }
   else
   {
      obj.innerHTML = req.status==200 ? req.responseText : ("'._("获取内容错误，代码：").'"+req.status);
   }
}
</script>';
}
?>