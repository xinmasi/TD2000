<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");
ob_end_clean();

$POSTFIX = _("，");
if($TYPE_ID == "0")
{
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
         $AUTHOR_NAME = _("回复人:").$ROW["AUTHOR_NAME"];
         $SUBMIT_TIME = $OLD_SUBMIT_TIME;
      }
   
      $SUBJECT_TITLE="";
      if(strlen($SUBJECT) > 50)
      {
         $SUBJECT_TITLE=td_htmlspecialchars($SUBJECT);
         $SUBJECT=csubstr($SUBJECT, 0, 50)."...";
      }
      $SUBJECT=td_htmlspecialchars($SUBJECT);
         
      if($TOP=="1")
         $SUBJECT="<font color=red><b>".$SUBJECT."</b></font>";
   
      $MODULE_BODY.='<li>'._("【").'<a href="/general/bbs/board.php?BOARD_ID='.$BOARD_ID.'" target="_blank">'.$BOARD_NAME.'</a>'._("】").'<a href="/general/bbs/comment.php?BOARD_ID='.$BOARD_ID.'&COMMENT_ID='.$COMMENT_ID.'" title=\"'.$SUBJECT_TITLE.'\" target="_blank">'.$SUBJECT.'</a>';
   
      if(!find_id($READEDER,$_SESSION["LOGIN_USER_ID"]))
         $MODULE_BODY.= "<img src='".MYOA_STATIC_SERVER."/static/images/new.gif' align='absmiddle' /> ";
   
      if($JING==1)
         $MODULE_BODY.= "<img src='".MYOA_STATIC_SERVER."/static/images/jing.gif' align='absmiddle' />";
      $MODULE_BODY.='('.$AUTHOR_NAME.' '.substr($SUBMIT_TIME,5,-3).')</li>';
   }
   
   if($COUNT==0)
      $MODULE_BODY.="<li>"._("暂无帖子")."</li>";
}
else if($TYPE_ID == "1")
{
   $COUNT=0;
   $query1 = "SELECT BOARD_ID,BOARD_HOSTER,BOARD_NAME,ANONYMITY_YN from  BBS_BOARD  where (DEPT_ID='ALL_DEPT' or find_in_set('".$_SESSION["LOGIN_DEPT_ID"]."',DEPT_ID) or find_in_set('".$_SESSION["LOGIN_USER_PRIV"]."',PRIV_ID) or find_in_set('".$_SESSION["LOGIN_USER_ID"]."',USER_ID) or find_in_set('".$_SESSION["LOGIN_USER_ID"]."',BOARD_HOSTER)) order by BOARD_NO";
   $cursor1 = exequery(TD::conn(),$query1);
   while($ROW1=mysql_fetch_array($cursor1))
   {
      $COUNT++;
      if($COUNT > $MAX_COUNT)
         break;
      $BOARD_ID = $ROW1["BOARD_ID"];
      $BOARD_HOSTER = $ROW1["BOARD_HOSTER"];   
      $BOARD_NAME = $ROW1["BOARD_NAME"];
      $ANONYMITY_YN = $ROW1["ANONYMITY_YN"];
   
      $USER_NAME_HOSTER = str_replace(',', $POSTFIX, td_trim(GetUserNameById($BOARD_HOSTER)));
   
      if($ANONYMITY_YN=="0")
         $ANONYMITY_YN=_("禁止");
      else
         $ANONYMITY_YN=_("允许");
         
      if($COUNT==1)
      {   	
        $MODULE_BODY.="<table border='0' width='100%'>"; 	
      }
      $MODULE_BODY.="
        <tr>
          <td width='40%' align=left>"._("讨论区：")."<a href='/general/bbs/board.php?BOARD_ID=".$BOARD_ID."'>$BOARD_NAME</a></td>
          <td width='20%' align=center>"._("匿名：")."$ANONYMITY_YN</td>
          <td width='40%' align=left>"._("版主：")."$USER_NAME_HOSTER</td>
        </tr>   
      ";
   }
   if($COUNT > 0)
      $MODULE_BODY.="</table>";
   if($COUNT==0)
      $MODULE_BODY.="";
}
$MODULE_BODY = "<ul>".$MODULE_BODY."</ul>";

if($MODULE_SCROLL=="true" && stristr($MODULE_BODY, "href"))
{
   $MODULE_BODY='<marquee id="module_'.$MODULE_ID.'_marquee" height="100%" direction="up" behavior=scroll scrollamount=2 scrolldelay=100 onmouseover="this.stop()" onmouseout="this.start()" border=0>'.$MODULE_BODY.'</marquee>';
}
echo $MODULE_BODY;
?>