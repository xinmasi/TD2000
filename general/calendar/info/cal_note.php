<?
include_once("inc/auth.inc.php");
include_once("inc/utility_all.php");
include_once("inc/utility_org.php");

$CUR_DATE=date("Y-m-d",time());
$CUR_TIME=date("Y-m-d H:i:s",time());
//2013-04-11 主从服务器查询判断
if($IS_MAIN==1)
   $QUERY_MASTER=true;
else
   $QUERY_MASTER="";
$query="select * from CALENDAR where CAL_ID='$CAL_ID'";
$cursor= exequery(TD::conn(),$query,$QUERY_MASTER);
if($ROW=mysql_fetch_array($cursor))
{
   $CAL_TIME=$ROW["CAL_TIME"];
   $CAL_TIME=date("Y-m-d H:i:s",$CAL_TIME);
   $END_TIME=$ROW["END_TIME"];
   $END_TIME=date("Y-m-d H:i:s",$END_TIME);
   $CAL_LEVEL=$ROW["CAL_LEVEL"];
   $CONTENT=$ROW["CONTENT"];
   $OVER_STATUS=$ROW["OVER_STATUS"];
   $CONTENT=td_htmlspecialchars($CONTENT);
   $MANAGER_ID=$ROW["MANAGER_ID"];
   $OWNER=$ROW["OWNER"];
   $TAKER=$ROW["TAKER"];
   $CREATOR=$ROW["USER_ID"];
   $URL = $ROW["URL"];
   if($CAL_LEVEL==0 || $CAL_LEVEL=="")
   {
       $CAL_LEVEL = "";
   }
   $MANAGER_NAME="";
   if($MANAGER_ID!="")
   {
      $query = "SELECT * from USER where USER_ID='$MANAGER_ID'";
      $cursor1= exequery(TD::conn(),$query);
      if($ROW1=mysql_fetch_array($cursor1))
         $MANAGER_NAME=$ROW1["USER_NAME"];
      //$MANAGER_NAME=td_trim(GetUserNameById($MANAGER_ID));   
   }
   if($TAKER!="")
      $TAKER_NAME=td_trim(GetUserNameById($TAKER));
   if($OWNER!="")
      $OWNER_NAME=td_trim(GetUserNameById($OWNER));
   $CREATOR_NAME=td_trim(GetUserNameById($CREATOR));
  
   if($OVER_STATUS=="0")
   {
      if(compare_time($CUR_TIME,$END_TIME)>0)
         $OVER_STATUS1="<font color='#FF0000'><b>"._("已超时")."</b></font>";
      else if(compare_time($CUR_TIME,$CAL_TIME)<0)
         $OVER_STATUS1="<font color='#0000FF'><b>"._("未开始")."</b></font>";
      else
         $OVER_STATUS1="<font color='#0000FF'><b>"._("进行中")."</b></font>";
   }
   else
   {
      $OVER_STATUS1="<font color='#00AA00'><b>"._("已完成")."</b></font>";
   }
             
   $TITLE=csubstr($CONTENT,0,10);
   if(substr($CAL_TIME,0,10) == $CUR_DATE && substr($END_TIME,0,10) == $CUR_DATE)
   {
      $CAL_TIME=substr($CAL_TIME,11,5);
      $END_TIME=substr($END_TIME,11,5);
   }
   else
   {
      $CAL_TIME=substr($CAL_TIME,0,16);
      $END_TIME=substr($END_TIME,0,16);
   }
}
if($URL!="")
{
    $CONTENT = '<a href="'.$URL.'" target="_blank">'.$CONTENT.'</a>';
}
$TITLE = date("Y年m月d日",$ROW["CAL_TIME"])._(" 日程");
$HTML_PAGE_TITLE = $TITLE;
include_once("inc/header.inc.php");
?>

<link rel="stylesheet" type="text/css" href="<?=MYOA_JS_SERVER?>/static/js/bootstrap/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/theme/<?=$_SESSION["LOGIN_THEME"]?>/calendar.css">
<link rel="stylesheet" type="text/css" href="<?=MYOA_STATIC_SERVER?>/static/modules/calendar/css/calendar_note.css">


<body bgcolor="#FFFFCC" topmargin="5">
<div id="main">
    <div class="calendar-note-border-color<?=$CAL_LEVEL?>" style="height:4px;"></div>
    <div class="calendar-note-head">
    <h1><?=$TITLE?><b style="font-size: 12px; margin-left: 20px; color: #FFFFFF;padding: 4px; border-radius: 4px;background:<?=$backcolor?>;"><?=$OVER_STATUS1?></b></h1> 
    </div>
	<div class="calendar-note-content">
		
		<div class="calendar-note"><label class="calendar-note-font"><?=_("开始时间：")?></label><span style="color: #427297;font-family: arial; "><?=$CAL_TIME?></span></div>
		<div class="calendar-note"><label class="calendar-note-font"><?=_("结束时间：")?></label><span style="color: #427297;font-family: arial; "><?=$END_TIME?></span></div>
		<!--<div class="calendar-note"><label class="calendar-note-font"><?=_("下次开始时间：")?></label><?=$M_TOPIC?></div>-->
		<?if($TAKER_NAME!="" || $OWNER_NAME!=""){?>
            <div class="calendar-note"><label class="calendar-note-font"><?=_("创建者：")?></label><?=$CREATOR_NAME?></div>
            <div class="calendar-note-desc"></div>
        <?}?>		
        <?if($MANAGER_NAME!=""){?>
            <div class="calendar-note"><label class="calendar-note-font"><?=_("安排人：")?></label><?=$MANAGER_NAME?></div>
        <?}?>		
        <?if($TAKER_NAME!=""){?>
            <div class="calendar-note"><label class="calendar-note-font"><?=_("执行者：")?></label><?=$TAKER_NAME?></div>
            <div class="calendar-note-desc"></div>
        <?}?>		
        <? if($OWNER_NAME!=""){?>
            <div class="calendar-note"><label class="calendar-note-font"><?=_("所属者：")?></label><?=$OWNER_NAME?></div>
            <div class="calendar-note-desc"></div>
        <?}?>
 <div class="calendar-note" style="min-height:150px;"><label class="calendar-note-font"><?=_("日程内容：")?></label><div><?=$CONTENT?></div></div>
	</div>
</div>
<!--<div class="small" style="height:250px; overflow:auto">
<?=$CAL_TIME?> - <?=$END_TIME?>
<?=$MANAGER_NAME?> 
<?
if($CAL_LEVEL!="")
{
?>
<span class="CalLevel<?=$CAL_LEVEL?>"><?=cal_level_desc($CAL_LEVEL)?></span>
<?
}
?>
<?=$OVER_STATUS1?>

<p style="word-break:break-all"><?=$CONTENT?></p>
</div>-->
</body>
</html>
