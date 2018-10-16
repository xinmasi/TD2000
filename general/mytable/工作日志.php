<?
include_once("inc/utility.php");

$MODULE_FUNC_ID="9";
$MODULE_FUNC_ID2="81";
$MODULE_DESC=_("工作日志");
$MODULE_BODY=$MODULE_OP="";
$MODULE_HEAD_CLASS = 'diary';

if($MODULE_FUNC_ID=="" || find_id($USER_FUNC_ID_STR, $MODULE_FUNC_ID) || find_id($USER_FUNC_ID_STR, $MODULE_FUNC_ID2))
{
if(find_id($USER_FUNC_ID_STR, $MODULE_FUNC_ID)){
	$MODULE_OP.='<a href="#" title="'._("新建工作日志").'" class="email_edit" onclick="view_more(\'diary_new\',\''._("新建日志").'\',\'/general/diary/new/\');">&nbsp;</a>';
    $MODULE_OP.='<a href="#" title="'._("全部").'" class="all_more" onclick="view_more(\'diary\',\''._("工作日志").'\',\'/general/diary/\');">'._("全部").'&nbsp;</a>';
}

$MODULE_BODY.= "<ul>";

$HTML_ARRAY = array();
$TIME_ARRAY = array();
if(find_id($USER_FUNC_ID_STR, $MODULE_FUNC_ID))
{
   $MODULE_ID_DAIRY = $MODULE_ID;
   $PRIV_NO_FLAG=2;

   $MODULE_ID = $MODULE_ID_DAIRY;
   $query = "SELECT DIA_ID,DIA_TYPE,SUBJECT,DIA_DATE,DIA_TIME,USER_ID,READERS,TO_ID from DIARY where (USER_ID='".$_SESSION["LOGIN_USER_ID"]."' or find_in_set('".$_SESSION["LOGIN_USER_ID"]."',TO_ID) and DIA_TYPE!='2') order by DIA_TIME desc limit 0, $MAX_COUNT";
   $cursor= exequery(TD::conn(),$query);
   while($ROW=mysql_fetch_array($cursor))
   {
      $USER_ID=$ROW["USER_ID"];
      $DIA_ID=$ROW["DIA_ID"];
      $DIA_TYPE=$ROW["DIA_TYPE"];
      $TO_ID=$ROW["TO_ID"];
      $READERS=$ROW["READERS"];
      $SUBJECT=$ROW["SUBJECT"];
      $DIA_DATE=$ROW["DIA_DATE"];
      $DIA_TIME=$ROW["DIA_TIME"];
      $USER_NAME=GetUserNameById($USER_ID);
      if($SUBJECT=="")
         $SUBJECT=substr($DIA_DATE,0,10)._("日志");

      if(substr($USER_NAME,-1)==",")
         $USER_NAME=substr($USER_NAME,0,-1);

      $SUBJECT_TITLE="";
      if(strlen($SUBJECT) > 50)
      {
         $SUBJECT_TITLE=$SUBJECT;
         $SUBJECT=csubstr($SUBJECT, 0, 50)."...";
      }
      $SUBJECT=td_htmlspecialchars($SUBJECT);
      $SUBJECT_TITLE=td_htmlspecialchars($SUBJECT_TITLE);

      $HTML='<li>';
      if($USER_ID == $_SESSION["LOGIN_USER_ID"])
         $HTML.='<a href="/general/diary/show_diary.php?dia_id='.$DIA_ID.'&USER_NAME='.$USER_NAME.'&FROM_FLAG=enterprise" target="_blank">'.$SUBJECT.'</a>';
      else
         $HTML.='<a href="/general/diary/show_diary.php?dia_id='.$DIA_ID.'&USER_NAME='.$USER_NAME.'&FROM_FLAG=enterprise&SHARE=1" target="_blank">'.$SUBJECT.'</a>';

      if(!find_id($READERS,$_SESSION["LOGIN_USER_ID"])&&$USER_ID!=$_SESSION["LOGIN_USER_ID"])
         $HTML.='<img src="'.MYOA_STATIC_SERVER.'/static/images/email_new.gif" alt=_("未读") align="absmiddle">';

      $HTML.=' ('.$USER_NAME.' '.substr($DIA_DATE,0,10).')';
      $HTML.='</li>';

      $HTML_ARRAY[] = $HTML;
      $TIME_ARRAY[] = $DIA_TIME;
   }
}

if(find_id($USER_FUNC_ID_STR, $MODULE_FUNC_ID2))
{
   $MODULE_ID_DAIRY = $MODULE_ID;
   $PRIV_NO_FLAG=2;
   $MODULE_ID = "4";
   include("inc/my_priv.php");
   $MODULE_ID = $MODULE_ID_DAIRY;

   if(count($TIME_ARRAY) >= $MAX_COUNT)
      $query = "SELECT DIA_ID,DIA_TYPE,SUBJECT,DIA_DATE,DIA_TIME,USER_ID,READERS,TO_ID from DIARY where USER_ID!='".$_SESSION["LOGIN_USER_ID"]."' and not find_in_set('".$_SESSION["LOGIN_USER_ID"]."',TO_ID) and DIA_TYPE!='2' and DIA_TIME > '".$TIME_ARRAY[count($TIME_ARRAY)-1]."' order by DIA_TIME desc";
   else
      $query = "SELECT DIA_ID,DIA_TYPE,SUBJECT,DIA_DATE,DIA_TIME,USER_ID,READERS,TO_ID from DIARY where USER_ID!='".$_SESSION["LOGIN_USER_ID"]."' and not find_in_set('".$_SESSION["LOGIN_USER_ID"]."',TO_ID) and DIA_TYPE!='2' order by DIA_TIME desc";
   $COUNT=0;
   $cursor= exequery(TD::conn(),$query);
   while($ROW=mysql_fetch_array($cursor))
   {
      $USER_ID=$ROW["USER_ID"];
      $DIA_ID=$ROW["DIA_ID"];
      $DIA_TYPE=$ROW["DIA_TYPE"];
      $TO_ID=$ROW["TO_ID"];

      if($MY_PRIV["DEPT_PRIV"]==4)
         break;

      if(!is_user_priv($USER_ID, $MY_PRIV))
           continue;
      $COUNT++;
      if($COUNT>$MAX_COUNT)
         break;

      $READERS=$ROW["READERS"];
      $SUBJECT=$ROW["SUBJECT"];
      $DIA_DATE=$ROW["DIA_DATE"];
      $DIA_TIME=$ROW["DIA_TIME"];
      $USER_NAME=GetUserNameById($USER_ID);
      if($SUBJECT=="")
         $SUBJECT=substr($DIA_DATE,0,10)._("日志");

      if(substr($USER_NAME,-1)==",")
         $USER_NAME=substr($USER_NAME,0,-1);

      $SUBJECT_TITLE="";
      if(strlen($SUBJECT) > 50)
      {
         $SUBJECT_TITLE=$SUBJECT;
         $SUBJECT=csubstr($SUBJECT, 0, 50)."...";
      }
      $SUBJECT=td_htmlspecialchars($SUBJECT);
      $SUBJECT_TITLE=td_htmlspecialchars($SUBJECT_TITLE);

      $HTML='<li><a href="/general/diary/show_diary.php?FROM_TABLE=1&dia_id='.$DIA_ID.'&USER_NAME='.$USER_NAME.'&USER_ID='.$USER_ID.'&SHARE=1" target="_blank">'.$SUBJECT.'</a>';

      if(!find_id($READERS,$_SESSION["LOGIN_USER_ID"])&&$USER_ID!=$_SESSION["LOGIN_USER_ID"])
         $HTML.='<img src="'.MYOA_STATIC_SERVER.'/static/images/email_new.gif" alt=_("未读") align="absmiddle">';

      $HTML.=' ('.$USER_NAME.' '.substr($DIA_DATE,0,10).')';
      $HTML.='</li>';

      $HTML_ARRAY[] = $HTML;
      $TIME_ARRAY[] = $DIA_TIME;
   }
}

$COUNT = 0;
arsort($TIME_ARRAY);
while(list($I, $DIA_TIME) = each($TIME_ARRAY))
{
   $COUNT++;
   if($COUNT > $MAX_COUNT)
      break;

   $MODULE_BODY.=$HTML_ARRAY[$I];
}

if(count($HTML_ARRAY) == 0)
   $MODULE_BODY.="<li>"._("暂无工作日志")."</li>";

$MODULE_BODY.= "<ul>";
}
?>