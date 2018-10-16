<?
$MODULE_FUNC_ID="235";
$MODULE_DESC=_("最新词条");
$MODULE_BODY=$MODULE_OP="";
$MODULE_HEAD_CLASS = 'wiki';

if($MODULE_FUNC_ID=="" || find_id($USER_FUNC_ID_STR, $MODULE_FUNC_ID))
{
if(find_id($USER_FUNC_ID_STR, $MODULE_FUNC_ID))
	$MODULE_OP='<a href="#" title="'._("维基百科").'" class="email_edit" onclick="view_more(\'wiki\',\''._("维基百科").'\',\'/general/wiki/index1.php\');">&nbsp;</a>';

$MODULE_BODY.= "<ul>";

$HTML_ARRAY = array();
$TIME_ARRAY = array();
if(find_id($USER_FUNC_ID_STR, $MODULE_FUNC_ID))
{
   $MODULE_ID_DAIRY = $MODULE_ID;
   $PRIV_NO_FLAG=2;
   $MODULE_ID = "4";
   include_once("inc/my_priv.php");
   $MODULE_ID = $MODULE_ID_DAIRY;

   $query = "SELECT TERM_ID,TERM_CREATEDBY,TERM_NAME,TERM_CREATEDTIME,TERM_COUNT FROM GWIKI_TERM ORDER BY TERM_ID DESC LIMIT 0,$MAX_COUNT";
   $cursor= exequery(TD::conn(),$query);
   while($ROW=mysql_fetch_array($cursor))
   {
      $TERM_CREATEDBY = $ROW["TERM_CREATEDBY"];
      $TERM_CREATEDBY_NAME=GetUserNameById($TERM_CREATEDBY);
			$TERM_ID = $ROW["TERM_ID"];
			$TERM_NAME = $ROW["TERM_NAME"];	
			$TERM_CREATEDTIME = $ROW["TERM_CREATEDTIME"];
			$TERM_COUNT = $ROW["TERM_COUNT"];
            
      if(substr($TERM_CREATEDBY_NAME,-1)==",")
         $TERM_CREATEDBY_NAME=substr($TERM_CREATEDBY_NAME,0,-1);
               
      $HTML='<li>';      
      $HTML.='<a href="/general/wiki/ctinfo.php?TERM_ID='.$TERM_ID.'" target="_self">'.$TERM_NAME.'</a> ('.$TERM_CREATEDBY_NAME.' '.date("Y-m-d",$TERM_CREATEDTIME).')';
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
   $MODULE_BODY.="<li>"._("暂无词条")."</li>";

$MODULE_BODY.= "<ul>";
}
?>