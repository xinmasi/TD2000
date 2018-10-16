<?
$MODULE_FUNC_ID="";
$MODULE_DESC=_("在线时长排行榜");
$MODULE_BODY=$MODULE_OP="";
$MODULE_HEAD_CLASS = 'info';

if($MODULE_FUNC_ID=="" || find_id($USER_FUNC_ID_STR, $MODULE_FUNC_ID))
{
$MODULE_OP='<a href="#" title="'._("全部").'" class="all_more" onclick="view_more(\'user_all\',\''._("在线时长排行榜").'\',\'/general/info/user/user_all.php\');">'._("全部").'&nbsp;</a>';
$ONLINE_COUNT = 0;
$query = "SELECT USER_NAME,ONLINE,DEPT_ID from USER where (NOT_LOGIN='0' or NOT_MOBILE_LOGIN='0') and DEPT_ID!=0 order by ONLINE desc limit 0,20";
$cursor= exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
   $ONLINE_COUNT++;
   $USER_NAME=$ROW["USER_NAME"];
   $ONLINE=$ROW["ONLINE"];
   $DEPT_ID=$ROW["DEPT_ID"];
   $DEPT_NAME=dept_long_name($DEPT_ID);

   $OATIME = time()-strtotime("2004-05-01");
   if($ONLINE > $OATIME)
      $ONLINE = $OATIME;

   $MIN=floor($ONLINE/60);
   $HOUR=floor($MIN/60);
   $MIN=$MIN%60;
   
   $MSG = sprintf(_("在线时长：%d小时%d分"), $HOUR,$MIN);
   $ONLINE_DESC=$MSG;
   $MODULE_BODY.='<li><u title="'.$DEPT_NAME.' '.$ONLINE_DESC.'" style="cursor:hand">'.$USER_NAME.'</u> '.online_level($ONLINE).'</li>';
}
}
?>
