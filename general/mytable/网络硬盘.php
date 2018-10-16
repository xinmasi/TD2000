<?
include_once("inc/utility.php");

$MODULE_FUNC_ID="76";
$MODULE_DESC=_("ÍøÂçÓ²ÅÌ");
$MODULE_BODY=$MODULE_OP="";
$MODULE_HEAD_CLASS = 'netdisk';

if($MODULE_FUNC_ID=="" || find_id($USER_FUNC_ID_STR, $MODULE_FUNC_ID))
{
$MODULE_OP='<a href="#" title="'._("È«²¿").'" class="all_more" onclick="view_more(\'netdisk\',\''._("ÍøÂçÓ²ÅÌ").'\',\'/general/netdisk/\');">'._("È«²¿").'&nbsp;</a>';
$MODULE_BODY.='<div style="float:left;width:49%;"><ul>';

$COUNT=0;
$query = "SELECT DISK_ID,DISK_NAME,USER_ID from NETDISK order by DISK_NO,DISK_NAME desc";
$cursor= exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
   $DISK_ID=$ROW["DISK_ID"];
   $DISK_NAME=$ROW["DISK_NAME"];

   if(!check_priv($ROW["USER_ID"]))
      continue;
   
   $SHOW_ARRAY[$COUNT]['DISK_ID'] = $DISK_ID;
   $SHOW_ARRAY[$COUNT]['DISK_NAME'] = $DISK_NAME;   
   
   $COUNT++;
   if($COUNT>$MAX_COUNT*2)
       break;

}//while

if($COUNT==0)
   $MODULE_BODY.= "<li>"._("ÔÝÎÞÍøÂçÓ²ÅÌ")."</li>";
else if($COUNT==1)
{
	 $SHOW_ARRAY['0']['DISK_NAME']=td_htmlspecialchars($SHOW_ARRAY['0']['DISK_NAME']);
   $MODULE_BODY.='<li><a href="/general/netdisk/?DISK_ID='.$SHOW_ARRAY['0']['DISK_ID'].'" target="_blank">'.$SHOW_ARRAY['0']['DISK_NAME'].'</a></li>';
}
else
{
   for($I = 0;$I < floor($COUNT/2);$I++)
   {
      $SHOW_ARRAY[$I]['DISK_NAME']=td_htmlspecialchars($SHOW_ARRAY[$I]['DISK_NAME']);
      $MODULE_BODY.='<li><a href="/general/netdisk/?DISK_ID='.$SHOW_ARRAY[$I]['DISK_ID'].'" target="_blank">'.$SHOW_ARRAY[$I]['DISK_NAME'].'</a></li>';
   }
   
   for($I = floor($COUNT/2);$I < $COUNT;$I++)
   {
      $MODULE_BODY.='</ul></div><div style="float:right;width:49%;"><ul>';
      $SHOW_ARRAY[$I]['DISK_NAME']=td_htmlspecialchars($SHOW_ARRAY[$I]['DISK_NAME']);
      $MODULE_BODY.='<li><a href="/general/netdisk/?DISK_ID='.$SHOW_ARRAY[$I]['DISK_ID'].'" target="_blank">'.$SHOW_ARRAY[$I]['DISK_NAME'].'</a></li>';
   }	
}

$MODULE_BODY.='</ul></div>';
}
?>