<?
echo '<ul>';
$ONLINE_COUNT = 0;
$query = "SELECT USER_NAME,ONLINE,DEPT_ID from USER where (NOT_LOGIN='0' or NOT_MOBILE_LOGIN='0') and DEPT_ID!=0 order by ONLINE desc limit 0,10";
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
   echo '<li><u title="'.$DEPT_NAME.' '.$ONLINE_DESC.'" style="cursor:hand">'.$USER_NAME.'</u> '.online_level($ONLINE).'</li>';
}
echo '</ul>';
?>
