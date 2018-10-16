<?
$MODULE_FUNC_ID="200";
$MODULE_DESC=_("windows快捷组");
$MODULE_BODY=$MODULE_OP="";
$MODULE_HEAD_CLASS = 'default';

if($MODULE_FUNC_ID=="" || find_id($USER_FUNC_ID_STR, $MODULE_FUNC_ID))
{
$MODULE_OP='';

$MODULE_BODY.='<div style="float:left;width:49%;">';
$MODULE_BODY.= "<ul>";

$SHOW_ARRAY=array();
$COUNT=0;
$query = "SELECT * from WINEXE where USER_ID='".$_SESSION["LOGIN_USER_ID"]."' order by WIN_NO";
$cursor= exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
 $COUNT++;
 $WIN_ID=$ROW["WIN_ID"];
 $WIN_NO=$ROW["WIN_NO"];
 $WIN_DESC=$ROW["WIN_DESC"];
 $WIN_PATH=$ROW["WIN_PATH"];
 $WIN_PATH=str_replace("\\","/",$WIN_PATH);

 $FUNC_TITLE="";
 if(strlen($WIN_DESC) > 20)
 {
	$FUNC_TITLE="title=\"".$WIN_DESC."\"";
	$WIN_DESC=csubstr($WIN_DESC, 0, 20);
 }

 $CLICK_STR="winexe('$WIN_DESC','$WIN_PATH');";
 $FUNC_IMAGE="winexe";
 $FUNC_NAME=$WIN_DESC;
 
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