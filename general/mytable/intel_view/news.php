<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
ob_end_clean();

$TYPE_ARRAY = get_code_array("NEWS");
$TYPE_NEW_ID=$TYPE_ID;
$NEWS_MODULE_BODY.= "<ul>";

$code_arr = array();
$query = "SELECT CODE_NO,CODE_NAME,CODE_EXT from SYS_CODE where PARENT_NO='NEWS'";
$cursor= exequery(TD::conn(),$query);
while($row = mysql_fetch_array($cursor))
{
    $CODE_NO        = $row["CODE_NO"];
    $CODE_NAME      = $row["CODE_NAME"];
    $CODE_EXT       = unserialize($row["CODE_EXT"]);
    if(is_array($CODE_EXT) && $CODE_EXT[MYOA_LANG_COOKIE] != "")
        $CODE_NAME = $CODE_EXT[MYOA_LANG_COOKIE];

    $code_arr[$CODE_NO] = $CODE_NAME;
}

if($TYPE_ID=="")
   $query = "SELECT NEWS_ID,SUBJECT,NEWS_TIME,CLICK_COUNT,FORMAT,TYPE_ID,ANONYMITY_YN,READERS,TOP,SUBJECT_COLOR from NEWS where PUBLISH='1' and (TO_ID='ALL_DEPT' or find_in_set('".$_SESSION["LOGIN_DEPT_ID"]."',TO_ID)".dept_other_sql("TO_ID")." or find_in_set('".$_SESSION["LOGIN_USER_PRIV"]."',PRIV_ID)".priv_other_sql("PRIV_ID")." or find_in_set('".$_SESSION["LOGIN_USER_ID"]."',USER_ID)) order by TOP desc,NEWS_TIME desc limit 0,$MAX_COUNT";
else
{
   if($TYPE_ID!="no_read0")
      $query = "SELECT NEWS_ID,SUBJECT,NEWS_TIME,CLICK_COUNT,FORMAT,TYPE_ID,ANONYMITY_YN,READERS,TOP,SUBJECT_COLOR from NEWS where PUBLISH='1' and TYPE_ID='$TYPE_ID' and (TO_ID='ALL_DEPT' or find_in_set('".$_SESSION["LOGIN_DEPT_ID"]."',TO_ID)".dept_other_sql("TO_ID")." or find_in_set('".$_SESSION["LOGIN_USER_PRIV"]."',PRIV_ID)".priv_other_sql("PRIV_ID")." or find_in_set('".$_SESSION["LOGIN_USER_ID"]."',USER_ID)) order by TOP desc,NEWS_TIME desc limit 0,$MAX_COUNT";
   else
      $query = "SELECT NEWS_ID,SUBJECT,NEWS_TIME,CLICK_COUNT,FORMAT,TYPE_ID,ANONYMITY_YN,READERS,TOP,SUBJECT_COLOR from NEWS where PUBLISH='1' and not find_in_set('".$_SESSION["LOGIN_USER_ID"]."',READERS) and (TO_ID='ALL_DEPT' or find_in_set('".$_SESSION["LOGIN_DEPT_ID"]."',TO_ID)".dept_other_sql("TO_ID")." or find_in_set('".$_SESSION["LOGIN_USER_PRIV"]."',PRIV_ID)".priv_other_sql("PRIV_ID")." or find_in_set('".$_SESSION["LOGIN_USER_ID"]."',USER_ID)) order by TOP desc,NEWS_TIME desc limit 0,$MAX_COUNT";
}
$cursor= exequery(TD::conn(),$query);
$COUNT=0;
while($ROW=mysql_fetch_array($cursor))
{
   $COUNT++;
   $NEWS_ID=$ROW["NEWS_ID"];
   $SUBJECT=$ROW["SUBJECT"];
   $NEWS_TIME=$ROW["NEWS_TIME"];
   $CLICK_COUNT=$ROW["CLICK_COUNT"];
   $FORMAT=$ROW["FORMAT"];
   $TYPE_ID=$ROW["TYPE_ID"];
   $NEWS_TIME=strtok($NEWS_TIME," ");
   $ANONYMITY_YN=$ROW["ANONYMITY_YN"];
   $READERS=$ROW["READERS"];
   $TOP=$ROW["TOP"];
   $SUBJECT_COLOR=$ROW["SUBJECT_COLOR"];
   
   $query = "SELECT count(*) from NEWS_COMMENT where NEWS_ID='$NEWS_ID'";
   $cursor1= exequery(TD::conn(),$query);
   if($ROW=mysql_fetch_array($cursor1))
      $COMMENT_COUNT=$ROW[0];

   $SUBJECT_TITLE="";
   if(strlen($SUBJECT) > 50)
   {
      $SUBJECT_TITLE=$SUBJECT;
      $SUBJECT=csubstr($SUBJECT, 0, 50)."...";
   }
   $SUBJECT=td_htmlspecialchars($SUBJECT);
   if($TOP=="1")
      $SUBJECT="<font color=red><b>".$SUBJECT."</b></font>";
   else
      $SUBJECT="<font color='".$SUBJECT_COLOR."'>".$SUBJECT."</font>";
   $SUBJECT_TITLE=td_htmlspecialchars($SUBJECT_TITLE);

   $TYPE_NAME=$code_arr[$TYPE_ID];
   if($TYPE_NAME!=""&&$TYPE_NEW_ID=="")
      $TSUBJECT='<a href="/general/news/show/news.php?TYPE='.$TYPE_ID.'">'._("【").$TYPE_NAME._("】").'</a>';
   else
      $TSUBJECT='';
   $NEWS_MODULE_BODY.='<li>'.$TSUBJECT.'<a href="javascript:open_news('.$NEWS_ID.','.$FORMAT.');" title="'.$SUBJECT_TITLE.'">'.$SUBJECT.'</a><label title=_("点击次数：'.$CLICK_COUNT.'")>('.$CLICK_COUNT.')</label>&nbsp;';

   if($ANONYMITY_YN!="2")
      $NEWS_MODULE_BODY.='<a href="javascript:re_news('.$NEWS_ID.');" style="text-decoration:underline">'._("评论").'</a>('.$COMMENT_COUNT.')&nbsp;';

   $NEWS_MODULE_BODY.= "(".$NEWS_TIME.")";
   if(!find_id($READERS,$_SESSION["LOGIN_USER_ID"]))
      $NEWS_MODULE_BODY.='<img src="'.MYOA_STATIC_SERVER.'/static/images/email_new.gif" align="absMiddle">';

   $NEWS_MODULE_BODY.='</li>';
}

if($COUNT==0)
{
   if($TYPE_NEW_ID=="")
      $NEWS_MODULE_BODY.= "<li>"._("暂无新闻")."</li>";
   else
   {  
      if($TYPE_ID!="no_read0")
         $NEWS_MODULE_BODY.= "<li>"._("暂无此类别的新闻")."</li>";
      else
         $NEWS_MODULE_BODY.= "<li>"._("暂无未读的新闻")."</li>";
   }   
}

$NEWS_MODULE_BODY.= "</ul>";

if($MODULE_SCROLL=="true" && stristr($NEWS_MODULE_BODY, "href"))
{
   $NEWS_MODULE_BODY='<marquee id="module_'.$MODULE_ID.'_marquee" height="100%" direction="up" behavior=scroll scrollamount=2 scrolldelay=100 onmouseover="this.stop()" onmouseout="this.start()" border=0>'.$NEWS_MODULE_BODY.'</marquee>';
}
echo $NEWS_MODULE_BODY;
?>