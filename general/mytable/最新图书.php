<?
include_once("inc/utility.php");

$MODULE_FUNC_ID="54";
$MODULE_DESC=_("����ͼ��");
$MODULE_BODY=$MODULE_OP="";
$MODULE_HEAD_CLASS = 'book';

if($MODULE_FUNC_ID=="" || find_id($USER_FUNC_ID_STR, $MODULE_FUNC_ID))
{
$MODULE_OP='<a href="#" title="'._("ȫ��").'" class="all_more" onclick="view_more(\'book\',\''._("����ͼ��").'\',\'/general/book/query/\');">'._("ȫ��").'&nbsp;</a>';
$COUNT=0;
$USER_FUNC_ID_STR=$_SESSION["LOGIN_FUNC_STR"];
$MODULE_BODY.= "<ul>";

if(find_id($USER_FUNC_ID_STR,"54"))
{
  $query = "SELECT BOOK_NAME,AUTHOR,PUB_HOUSE,BOOK_ID,OPEN from BOOK_INFO  order by BOOK_ID desc limit 0,$MAX_COUNT";
  //where (OPEN='0' and DEPT='$LOGIN_DEPT_ID') or OPEN='' or OPEN='1' or find_in_set('$LOGIN_DEPT_ID',OPEN) or OPEN='ALL_DEPT'
  $cursor= exequery(TD::conn(),$query);
  while($ROW=mysql_fetch_array($cursor))
  {

     $BOOK_ID=$ROW["BOOK_ID"];
     $BOOK_NAME=$ROW["BOOK_NAME"];      
     $AUTHOR=$ROW["AUTHOR"]; 
     $PUB_HOUSE=$ROW["PUB_HOUSE"];
     $OPEN=$ROW["OPEN"];
     $OPEN_ARR=explode(";", $OPEN);       
     if (!find_id($OPEN_ARR[0], $LOGIN_DEPT_ID) && !find_id($OPEN_ARR[1], $LOGIN_USER_ID) && !find_id($OPEN_ARR[2], $LOGIN_USER_PRIV) && $OPEN_ARR[0]!="ALL_DEPT" )
  	     continue;  
  	     
     $COUNT++;
     if($COUNT>$MAX_COUNT)
        break;
     
     $SUBJECT_TITLE="";
     if(strlen($BOOK_NAME) > 50)
     {
        $SUBJECT_TITLE=$BOOK_NAME;
        $BOOK_NAME=csubstr($BOOK_NAME, 0, 50)."...";
     }
     $BOOK_NAME=td_htmlspecialchars($BOOK_NAME);
     $SUBJECT_TITLE=td_htmlspecialchars($SUBJECT_TITLE);
   
     $OPEN_STR="window.open('/general/book/query/detail.php?BOOK_ID=$BOOK_ID','','height=400,width=550,status=1,toolbar=no,menubar=no,location=no,scrollbars=yes,left=220,top=180,resizable=yes');";
     $MODULE_BODY.='<li>'._("ͼ�飺").'<a href="javascript:;" onClick="'.$OPEN_STR.'" title="'.$SUBJECT_TITLE.'">'.$BOOK_NAME.'('._("���ߣ�").$AUTHOR." "._("�����磺").$PUB_HOUSE.')</a></li>';
  }
}

$MODULE_BODY.= "</ul>";
}
?>