<?
include_once("inc/utility.php");

$MODULE_FUNC_ID="147";
$MODULE_DESC=_("新闻");
$MODULE_BODY=$MODULE_OP="";
$MODULE_HEAD_CLASS = 'news';

if($MODULE_FUNC_ID=="" || find_id($USER_FUNC_ID_STR, $MODULE_FUNC_ID))
{
    $MODULE_TYPE = '<a href="javascript:get_news(\'\');">'._("全部新闻").'</a> <a href="javascript:get_news(\'no_read0\');">'._("未读新闻").'</a> ';
$MODULE_OP='<a href="#" title="'._("全部").'" class="all_more" onclick="view_more(\'news\',\''._("新闻").'\',\'/general/news/show/\');">'._("全部").'&nbsp;</a>';
	
$TYPE_ARRAY = get_code_array("NEWS");
while(list($TYPE_NO, $TYPE_NAME) = each($TYPE_ARRAY))
{
   $MODULE_TYPE .= '<a href="javascript:get_news(\''.$TYPE_NO.'\');">'.$TYPE_NAME.'</a> ';
}

$MODULE_BODY.= "<ul>";

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

$query = "SELECT NEWS_ID,SUBJECT,NEWS_TIME,CLICK_COUNT,FORMAT,TYPE_ID,ANONYMITY_YN,READERS,TOP,SUBJECT_COLOR from NEWS where PUBLISH='1' and (TO_ID='ALL_DEPT' or find_in_set('".$_SESSION["LOGIN_DEPT_ID"]."',TO_ID)".dept_other_sql("TO_ID")." or find_in_set('".$_SESSION["LOGIN_USER_PRIV"]."',PRIV_ID)".priv_other_sql("PRIV_ID")." or find_in_set('".$_SESSION["LOGIN_USER_ID"]."',USER_ID)) order by TOP desc,NEWS_TIME desc limit 0,$MAX_COUNT";
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
   if($TYPE_NAME!="")
      $TSUBJECT='<a href="/general/news/show/news.php?TYPE='.$TYPE_ID.'">'._("【").$TYPE_NAME._("】").'</a>';
   else
      $TSUBJECT='';
   $MODULE_BODY.='<li>'.$TSUBJECT.'<a href="javascript:open_news('.$NEWS_ID.','.$FORMAT.');" title="'.$SUBJECT_TITLE.'">'.$SUBJECT.'</a><label title=_("点击次数：'.$CLICK_COUNT.'")>('.$CLICK_COUNT.')</label>&nbsp;';

   if($ANONYMITY_YN!="2")
      $MODULE_BODY.='<a href="javascript:re_news('.$NEWS_ID.');" style="text-decoration:underline">'._("评论").'</a>('.$COMMENT_COUNT.')&nbsp;';

   $MODULE_BODY.= "(".$NEWS_TIME.")";
   if(!find_id($READERS,$_SESSION["LOGIN_USER_ID"]))
      $MODULE_BODY.='<img src="'.MYOA_STATIC_SERVER.'/static/images/email_new.gif" align="absMiddle">';

   $MODULE_BODY.='</li>';
}

if($COUNT==0)
   $MODULE_BODY.= "<li>"._("暂无新闻")."</li>";

$MODULE_BODY.= "<ul>";

$MODULE_BODY.='<script>
function open_news(NEWS_ID,FORMAT)
{
 URL="/general/news/show/read_news.php?NEWS_ID="+NEWS_ID;
 myleft=(screen.availWidth-780)/2;
 mytop=100
 mywidth=780;
 myheight=500;
 if(FORMAT=="1")
 {
    myleft=0;
    mytop=0
    mywidth=screen.availWidth-10;
    myheight=screen.availHeight-40;
 }
 window.open(URL,"read_news"+NEWS_ID,"height="+myheight+",width="+mywidth+",status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,top="+mytop+",left="+myleft+",resizable=yes");
}

function re_news(NEWS_ID)
{
 URL="/general/news/show/re_news.php?NEWS_ID="+NEWS_ID;
 myleft=(screen.availWidth-500)/2;
 window.open(URL,"re_news"+NEWS_ID,"height=400,width=550,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,top=150,left="+myleft+",resizable=yes");
}
function get_news(req)
{
   var obj = $("module_'.$MODULE_ID.'_ul");
   if(!obj) return;
   
   if(typeof(req) != "object")
   {
      obj.innerHTML = \'<img src="'.MYOA_STATIC_SERVER.'/static/images/loading.gif" align="absMiddle"> '._("加载中，请稍候……").'\';
      _get("news.php", "MAX_COUNT='.$MAX_COUNT.'&TYPE_ID="+req+"&MODULE_SCROLL='.$MODULE_SCROLL.'&MODULE_ID='.$MODULE_ID.'", arguments.callee);
   }
   else
   {
      obj.innerHTML = req.status==200 ? req.responseText : ("'._("获取内容错误，代码：").'"+req.status);
   }
}
</script>';
}
?>
