<?
$MODULE_FUNC_ID="";
$MODULE_DESC=_("菜单快捷组");
$MODULE_BODY=$MODULE_OP="";
$MODULE_HEAD_CLASS = 'default';

if($MODULE_FUNC_ID=="" || find_id($USER_FUNC_ID_STR, $MODULE_FUNC_ID))
{
$MODULE_OP='';

$MODULE_BODY.='<div style="float:left;width:49%;">';
$MODULE_BODY.= "<ul>";

$query	= "SELECT SHORTCUT from USER where UID='".$_SESSION["LOGIN_UID"]."'";
$cursor= exequery(TD::conn(),$query);
if($ROW=mysql_fetch_array($cursor))
   $SHORTCUT=$ROW["SHORTCUT"];

$FUNCTION_ARRAY = TD::get_cache('SYS_FUNCTION_ALL_'.bin2hex(MYOA_LANG_COOKIE));
$COUNT=0;
$SHORTCUT_ARRAY=explode(",",$SHORTCUT);
$ARRAY_COUNT=sizeof($SHORTCUT_ARRAY);
for($I=0;$I<$ARRAY_COUNT;$I++)
{
   if($SHORTCUT_ARRAY[$I]=="")
      continue;
   if($COUNT>=$MAX_COUNT*2)
      break;

   $COUNT++;
   $FUNC_ID=$SHORTCUT_ARRAY[$I];
   
   $FUNC_NAME=$FUNCTION_ARRAY[$FUNC_ID]["FUNC_NAME"];
   $FUNC_CODE=$FUNCTION_ARRAY[$FUNC_ID]["FUNC_CODE"];
   $FUNC_IMAGE=$FUNCTION_ARRAY[$FUNC_ID]["IMAGE"];
   $OPEN_WINDOW=$FUNCTION_ARRAY[$FUNC_ID]["OPEN_WINDOW"];
   
   if($FUNC_IMAGE == '')
      $FUNC_IMAGE = 'oa';

   if(stristr($FUNC_CODE,"http://") || stristr($FUNC_CODE,"ftp://"))
      $CLICK_STR="openURL('$FUNC_CODE','$OPEN_WINDOW')";
   elseif(stristr($FUNC_CODE,"file://"))
   {
      $TMP=str_replace("\\","/",str_replace("file://","",$FUNC_CODE));
      $CLICK_STR="winexe('$FUNC_NAME','$TMP')";
   }
   else
   {
      if($FUNC_ID>=10000&&$FUNC_ID<=14999)
         $CLICK_STR="openURL('/fis/$FUNC_CODE')";
      else if($FUNC_ID>=15000&&$FUNC_ID<=15499)
         $CLICK_STR="openURL('/hr/$FUNC_CODE')";
      else if($FUNC_ID>=650&&$FUNC_ID<=1000 || strtolower(substr($FUNC_CODE,-4))==".jsp")
         $CLICK_STR="openURL('/app/$FUNC_CODE')";
      else
         $CLICK_STR="openURL('/general/$FUNC_CODE')";
   }

   $SHOW_ARRAY[$COUNT]['CLICK_STR'] = $CLICK_STR;
   $SHOW_ARRAY[$COUNT]['FUNC_IMAGE'] = $FUNC_IMAGE;      
   $SHOW_ARRAY[$COUNT]['FUNC_NAME'] = $FUNC_NAME; 
   
}

if($COUNT==0)
   $MODULE_BODY.= "<li>"._("暂无快捷方式")."</li>";
else if($COUNT==1)
{
   $MODULE_BODY.='<li><a href="javascript:'.$SHOW_ARRAY['1']['CLICK_STR'].'" id="f'.$FUNC_ID.'"><img src="'.MYOA_STATIC_SERVER.'/static/images/menu/'.$SHOW_ARRAY['1']['FUNC_IMAGE'].'.gif" border="0" WIDTH="16" HEIGHT="16" alt="'.$SHOW_ARRAY['1']['FUNC_NAME'].'" align="absmiddle"> '.$SHOW_ARRAY['1']['FUNC_NAME'].'</a></li>';
}
else
{
   for($I = 1;$I <= floor($COUNT/2);$I++)
   {
      $MODULE_BODY.='<li><a href="javascript:'.$SHOW_ARRAY[$I]['CLICK_STR'].'" id="f'.$FUNC_ID.'"><img src="'.MYOA_STATIC_SERVER.'/static/images/menu/'.$SHOW_ARRAY[$I]['FUNC_IMAGE'].'.gif" border="0" WIDTH="16" HEIGHT="16" alt="'.$SHOW_ARRAY[$I]['FUNC_NAME'].'" align="absmiddle"> '.$SHOW_ARRAY[$I]['FUNC_NAME'].'</a></li>';
   }  
   for($I > floor($COUNT/2);$I <= $COUNT;$I++)
   {
      $MODULE_BODY.='</ul></div><div style="float:right;width:49%;"><ul>';
      $MODULE_BODY.='<li><a href="javascript:'.$SHOW_ARRAY[$I]['CLICK_STR'].'" id="f'.$FUNC_ID.'"><img src="'.MYOA_STATIC_SERVER.'/static/images/menu/'.$SHOW_ARRAY[$I]['FUNC_IMAGE'].'.gif" border="0" WIDTH="16" HEIGHT="16" alt="'.$SHOW_ARRAY[$I]['FUNC_NAME'].'" align="absmiddle"> '.$SHOW_ARRAY[$I]['FUNC_NAME'].'</a></li>';
   }
}

$MODULE_BODY.= "<ul>";
$MODULE_BODY.='</div>';

$MODULE_BODY.='
<script>
function openURL(URL,open_window)
{
  	 if(open_window)
  	    window.open(URL);
  	 else
  	    location=URL;
}

function winexe(NAME,PROG)
{
   URL="/general/winexe/?PROG="+PROG+"&NAME="+NAME;
   window.open(URL,"winexe","height=100,width=350,status=0,toolbar=no,menubar=no,location=no,scrollbars=yes,top=0,left=0,resizable=no");
}
</script>';
}
?>