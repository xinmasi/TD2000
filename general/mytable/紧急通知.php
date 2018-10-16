<?
include_once("inc/utility.php");

$MODULE_FUNC_ID="";
$MODULE_DESC=_("紧急通知");
$MODULE_BODY=$MODULE_OP="";
$MODULE_HEAD_CLASS = 'notify';

if($MODULE_FUNC_ID=="" || find_id($USER_FUNC_ID_STR, $MODULE_FUNC_ID))
{
$MODULE_OP='';

$MODULE_BODY.= "<ul>";

$COUNT=0;
$NOTIFY_FILE=MYOA_ATTACH_PATH."mytable/urgent_notify.txt";
if(file_exists($NOTIFY_FILE))
{
   $COUNT=0;
   $ARRAY=file($NOTIFY_FILE);
   for($I=0;$I<count($ARRAY);$I++)
   {
      if($ARRAY[$I]=="")
         continue;
      
      $COUNT++;
      $MODULE_BODY.='<li style="color:#FF0000;">'.td_htmlspecialchars($ARRAY[$I]).'</li>';
   }
}

if($COUNT==0)
   $MODULE_BODY.= "<li>"._("暂无紧急通知")."</li>";

$MODULE_BODY.= "<ul>";
}
?>