<?
$MODULE_FUNC_ID="";
$MODULE_DESC=_("¼ÆÊ±ÅÆ");
$MODULE_BODY=$MODULE_OP="";
$MODULE_HEAD_CLASS = 'info';

$CUR_DATE=date("Y-m-d",time());
$query = "SELECT * from COUNTDOWN where END_TIME>='$CUR_DATE' and (TO_DEPT='ALL_DEPT' || find_in_set('".$_SESSION["LOGIN_DEPT_ID"]."', TO_DEPT) || find_in_set('".$_SESSION["LOGIN_USER_PRIV"]."', TO_PRIV) || find_in_set('".$_SESSION["LOGIN_USER_ID"]."', TO_USER)) order by ORDER_NO asc, ROW_ID desc";
$cursor= exequery(TD::conn(),$query);
while($ROW=mysql_fetch_array($cursor))
{
   $ROW_ID=$ROW["ROW_ID"];
   $CONTENT=$ROW["CONTENT"];
   $END_TIME=$ROW["END_TIME"];
   $COUNT_TYPE=$ROW["COUNT_TYPE"];
   $BEGIN_TIME=$ROW["BEGIN_TIME"];


   if($END_TIME >= $CUR_DATE)
   {
   		$DAYS_UP = (strtotime($CUR_DATE) - strtotime($BEGIN_TIME)) / 86400;
      $DAYS_DOWN = (strtotime($END_TIME) - strtotime($CUR_DATE)) / 86400;
      $DAYS_SUM = (strtotime($END_TIME) - strtotime($BEGIN_TIME)) / 86400;
      $CONTENT = str_replace("{N}", $DAYS_DOWN, $CONTENT);
      $CONTENT = str_replace("{M}", $DAYS_UP, $CONTENT);
      $CONTENT = str_replace("{B}", $BEGIN_TIME, $CONTENT);
      $CONTENT = str_replace("{E}", $END_TIME, $CONTENT);
      $CONTENT = str_replace("{S}", $DAYS_SUM, $CONTENT);
   }
   $MODULE_BODY.= $CONTENT."<br />";
}
?>