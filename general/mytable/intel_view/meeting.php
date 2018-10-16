<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
ob_end_clean();

if($TYPE_ID == "0")
{
   $CUR_DATE=date("Y-m-d",time());
   $WEEK_BEGIN=date("Y-m-d",(strtotime($CUR_DATE)-(date("w",strtotime($CUR_DATE)))*24*3600))." 00:00:00";
   $WEEK_END=date("Y-m-d",(strtotime($CUR_DATE)+(6-date("w",strtotime($CUR_DATE)))*24*3600))." 23:59:59"; 
   $DATE_STR="((M_START<='$WEEK_END' and M_START>='$WEEK_BEGIN') or (M_END<='$WEEK_END' and M_END>='$WEEK_BEGIN') or (M_START<='$WEEK_BEGIN' and M_END>='$WEEK_END') or (M_START<='$WEEK_BEGIN' and M_END is null))";
   
   $COUNT=0;
   $query = "SELECT * from MEETING where (M_STATUS='1' or M_STATUS='2') and find_in_set('".$_SESSION["LOGIN_USER_ID"]."',M_ATTENDEE) and ".$DATE_STR." order by M_START limit 0,$MAX_COUNT";
   $cursor= exequery(TD::conn(),$query);
   while($ROW=mysql_fetch_array($cursor))
   {
      $COUNT++;
   
      $M_ID=$ROW["M_ID"];
      $M_NAME=$ROW["M_NAME"];
      $M_START=$ROW["M_START"];
      $M_END=$ROW["M_END"];
      
      $TEM_M_START=substr($M_START,5,11);
      $TEM_M_END=substr($M_END,5,11);
   
      $M_NAME_TITLE="";
      if(strlen($M_NAME) > 40)
      {
         $M_NAME_TITLE=td_htmlspecialchars($M_NAME);
         $M_NAME=csubstr($M_NAME, 0, 40)."...";
      }
      $M_NAME=td_htmlspecialchars($M_NAME);
      
      $MODULE_BODY.='<li><a href="javascript:open_meeting('.$M_ID.');" title="'.$M_NAME_TITLE.'">'.$M_NAME.'</a>&nbsp;('.$TEM_M_START.' - '.$TEM_M_END.')</li>';
   }
   
   if($COUNT==0)
      $MODULE_BODY.= "<li>"._("本周暂无会议")."</li>";
}
else if($TYPE_ID == "1")
{
   $CUR_DATE=date("Y-m-d",time());
   $CUR_DATE1=date("Y-m-d",time()+7*24*60*60);
   $WEEK_BEGIN=$CUR_DATE;
   $WEEK_END=$CUR_DATE1;
   $DATE_STR="((M_START<='$WEEK_END' and M_START>='$WEEK_BEGIN') or (M_END<='$WEEK_END' and M_END>='$WEEK_BEGIN') or (M_START<='$WEEK_BEGIN' and M_END>='$WEEK_END') or (M_START<='$WEEK_BEGIN' and M_END is null))";
   
   $COUNT=0;
   $query = "SELECT * from MEETING where (M_STATUS='1' or M_STATUS='2') and find_in_set('".$_SESSION["LOGIN_USER_ID"]."',M_ATTENDEE) and ".$DATE_STR." order by M_START limit 0,$MAX_COUNT";
   $cursor= exequery(TD::conn(),$query);
   while($ROW=mysql_fetch_array($cursor))
   {
      $COUNT++;
   
      $M_ID=$ROW["M_ID"];
      $M_NAME=$ROW["M_NAME"];
      $M_START=$ROW["M_START"];
      $M_END=$ROW["M_END"];
   
      $TEM_M_START=substr($M_START,5,11);
      $TEM_M_END=substr($M_END,5,11);
   
      $M_NAME_TITLE="";
      if(strlen($M_NAME) > 40)
      {
         $M_NAME_TITLE=td_htmlspecialchars($M_NAME);
         $M_NAME=csubstr($M_NAME, 0, 40)."...";
      }
      $M_NAME=td_htmlspecialchars($M_NAME);
      
      $MODULE_BODY.='<li><a href="javascript:open_meeting('.$M_ID.');" title="'.$M_NAME_TITLE.'">'.$M_NAME.'</a>&nbsp;('.$TEM_M_START.' - '.$TEM_M_END.')</li>';
   }
   
   if($COUNT==0)
      $MODULE_BODY.= "<li>"._("未来7天暂无会议")."</li>";
}

$MODULE_BODY = "<ul>".$MODULE_BODY."</ul>";

if($MODULE_SCROLL=="true" && stristr($MODULE_BODY, "href"))
{
   $MODULE_BODY='<marquee id="module_'.$MODULE_ID.'_marquee" height="100%" direction="up" behavior=scroll scrollamount=2 scrolldelay=100 onmouseover="this.stop()" onmouseout="this.start()" border=0>'.$MODULE_BODY.'</marquee>';
}
echo $MODULE_BODY;
?>